<?php

namespace App\Http\Controllers;

use App\Enums\StatusPengumuman;
use App\Models\Pengumuman;
use App\Support\LogAktivitas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    public function index(Request $request): View
    {
        $prefix = $this->prefixPeran($request);
        $cari = trim((string) $request->query('q', $request->query('search', '')));
        $statusFilter = trim((string) $request->query('status', ''));
        $kategoriFilter = trim((string) $request->query('kategori', $request->query('category', '')));

        $daftarPengumuman = Pengumuman::query()
            ->when($statusFilter !== '' && $statusFilter !== 'all' && $statusFilter !== 'published', function ($q) use ($statusFilter): void {
                $q->where('status', $statusFilter);
            })
            ->when($statusFilter === 'published', function ($q): void {
                $q->where('status', StatusPengumuman::Terbit->value);
            })
            ->when($kategoriFilter !== '' && $kategoriFilter !== 'Semua Kategori', function ($q) use ($kategoriFilter): void {
                $q->where('kategori', $kategoriFilter);
            })
            ->when($cari !== '', function ($q) use ($cari): void {
                $q->where(function ($sub) use ($cari): void {
                    $sub->where('judul', 'like', '%'.$cari.'%')
                        ->orWhere('nomor_surat', 'like', '%'.$cari.'%')
                        ->orWhere('ringkasan', 'like', '%'.$cari.'%');
                });
            })
            ->orderByDesc('tanggal_terbit')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $statistik = [
            'total' => Pengumuman::query()->count(),
            'terbit' => Pengumuman::query()->where('status', StatusPengumuman::Terbit->value)->count(),
            'draft' => Pengumuman::query()->where('status', StatusPengumuman::Draft->value)->count(),
            'arsip' => Pengumuman::query()->where('status', StatusPengumuman::Arsip->value)->count(),
        ];

        $viewName = match ($prefix) {
            'keuangan' => 'keuangan.pengumuman',
            'kepala' => 'kepala-sekolah.pengumuman',
            default => 'operator.pengumuman',
        };

        return view($viewName, [
            'daftarPengumuman' => $daftarPengumuman,
            'statistik' => $statistik,
            'daftarKategori' => Pengumuman::query()
                ->whereNotNull('kategori')
                ->where('kategori', '!=', '')
                ->distinct()
                ->orderBy('kategori')
                ->pluck('kategori'),
            'filter' => [
                'q' => $cari,
                'status' => $statusFilter,
                'kategori' => $kategoriFilter,
            ],
        ]);
    }

    public function create(Request $request): View
    {
        $prefix = $this->prefixPeran($request);
        $viewName = $prefix === 'keuangan'
            ? 'keuangan.tambah-pengumuman'
            : 'operator.tambah-pengumuman';

        return view($viewName, [
            'pengumuman' => new Pengumuman([
                'status' => StatusPengumuman::Draft->value,
            ]),
            'opsiStatus' => StatusPengumuman::opsi(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'nomor_surat' => ['nullable', 'string', 'max:100'],
            'tanggal_terbit' => ['nullable', 'date'],
            'sasaran' => ['nullable', 'string', 'max:150'],
            'ringkasan' => ['nullable', 'string'],
            'isi' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,terbit,arsip'],
        ]);

        $pengumuman = Pengumuman::query()->create([
            'judul' => $data['judul'],
            'nomor_surat' => $data['nomor_surat'] ?? null,
            'isi' => $data['isi'] ?? ($data['ringkasan'] ?? null),
            'status' => $data['status'],
            'tanggal_terbit' => $data['status'] === 'terbit'
                ? ($data['tanggal_terbit'] ?? now())
                : ($data['tanggal_terbit'] ?? null),
            'dibuat_oleh' => $request->user()?->getKey(),
        ]);

        LogAktivitas::catat(
            modul: 'pengumuman',
            aksi: 'simpan_pengumuman',
            objek: $pengumuman,
            ringkasan: "Menyimpan pengumuman '{$pengumuman->judul}' dengan status {$data['status']}.",
        );

        return redirect()
            ->route($this->prefixRoute().'pengumuman.index')
            ->with('status', 'Pengumuman berhasil disimpan.');
    }

    private function prefixRoute(?Request $request = null): string
    {
        $request ??= request();
        $routeName = (string) ($request->route()?->getName() ?? '');

        if (str_starts_with($routeName, 'keuangan.')) {
            return 'keuangan.';
        }

        if (str_starts_with($routeName, 'kepala.')) {
            return 'kepala.';
        }

        if (str_starts_with($routeName, 'operator.')) {
            return 'operator.';
        }

        $peranAktif = (string) session('peran_aktif', 'operator');

        return match ($peranAktif) {
            'keuangan' => 'keuangan.',
            'kepala' => 'kepala.',
            default => 'operator.',
        };
    }

    public function edit(Request $request, Pengumuman $pengumuman): View
    {
        $prefix = $this->prefixPeran($request);
        $viewName = $prefix === 'keuangan'
            ? 'keuangan.tambah-pengumuman'
            : 'operator.tambah-pengumuman';

        return view($viewName, [
            'pengumuman' => $pengumuman,
            'opsiStatus' => StatusPengumuman::opsi(),
        ]);
    }

    public function update(Request $request, Pengumuman $pengumuman): RedirectResponse
    {
        $prefix = $this->prefixPeran($request);

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'nomor_surat' => ['nullable', 'string', 'max:120'],
            'tanggal_terbit' => ['nullable', 'date'],
            'sasaran' => ['nullable', 'string', 'max:120'],
            'ringkasan' => ['nullable', 'string', 'max:2000'],
            'isi' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:draft,terbit,arsip'],
        ]);

        $pengumuman->update([
            'judul' => $validated['judul'],
            'kategori' => $validated['kategori'] ?? $pengumuman->kategori,
            'nomor_surat' => $validated['nomor_surat'] ?? $pengumuman->nomor_surat,
            'tanggal_terbit' => $validated['tanggal_terbit'] ?? $pengumuman->tanggal_terbit,
            'sasaran' => $validated['sasaran'] ?? $pengumuman->sasaran,
            'ringkasan' => $validated['ringkasan'] ?? $pengumuman->ringkasan,
            'isi' => $validated['isi'] ?? $pengumuman->isi,
            'status' => $validated['status'] ?? $pengumuman->status,
        ]);

        $routeIndex = "{$prefix}.pengumuman.index";

        return redirect()
            ->route(Route::has($routeIndex) ? $routeIndex : 'operator.pengumuman.index')
            ->with('status', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Request $request, Pengumuman $pengumuman): RedirectResponse
    {
        $prefix = $this->prefixPeran($request);
        $pengumuman->delete();

        $routeIndex = "{$prefix}.pengumuman.index";

        return redirect()
            ->route(Route::has($routeIndex) ? $routeIndex : 'operator.pengumuman.index')
            ->with('status', 'Pengumuman berhasil dihapus.');
    }

    private function prefixPeran(Request $request): string
    {
        $routeName = (string) ($request->route()?->getName() ?? '');

        if (str_starts_with($routeName, 'keuangan.')) {
            return 'keuangan';
        }

        if (str_starts_with($routeName, 'kepala.')) {
            return 'kepala';
        }

        return 'operator';
    }
}
