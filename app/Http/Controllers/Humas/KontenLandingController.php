<?php

namespace App\Http\Controllers\Humas;

use App\Http\Controllers\Controller;
use App\Models\PrestasiSekolah;
use App\Models\ProfilGuruPublik;
use App\Models\Sekolah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KontenLandingController extends Controller
{
    public function prestasiIndex(Request $request): View
    {
        $cari = trim((string) $request->query('q', $request->query('search', '')));
        $kategori = trim((string) $request->query('category', $request->query('kategori', '')));
        $status = trim((string) $request->query('status', ''));

        $daftarPrestasi = PrestasiSekolah::query()
            ->when($cari !== '', function ($q) use ($cari): void {
                $q->where(function ($sub) use ($cari): void {
                    $sub->where('judul', 'like', '%'.$cari.'%')
                        ->orWhere('deskripsi', 'like', '%'.$cari.'%');
                });
            })
            ->when($kategori !== '', fn ($q) => $q->where('kategori', $kategori))
            ->when($status !== '', function ($q) use ($status): void {
                if ($status === 'Published' || $status === '1' || $status === 'aktif') {
                    $q->where('aktif', true);
                } elseif ($status === 'Draft' || $status === '0' || $status === 'nonaktif') {
                    $q->where('aktif', false);
                }
            })
            ->orderBy('urutan')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('humas.prestasi', [
            'daftarPrestasi' => $daftarPrestasi,
            'statistik' => [
                'total' => PrestasiSekolah::query()->count(),
                'aktif' => PrestasiSekolah::query()->where('aktif', true)->count(),
                'keagamaan' => PrestasiSekolah::query()->where('kategori', 'Keagamaan')->count(),
                'akademik' => PrestasiSekolah::query()->where('kategori', 'Akademik')->count(),
                'olahraga_seni' => PrestasiSekolah::query()->where('kategori', 'Olahraga & Seni')->count(),
            ],
        ]);
    }

    public function prestasiStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:150'],
            'kategori' => ['nullable', 'string', 'max:80'],
            'tingkat' => ['nullable', 'string', 'max:80'],
            'tahun' => ['nullable', 'string', 'max:20'],
            'tanggal' => ['nullable', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'urutan' => ['nullable', 'integer', 'min:0'],
            'aktif' => ['nullable'],
        ]);

        PrestasiSekolah::query()->create([
            'judul' => $validated['judul'],
            'kategori' => $validated['kategori'] ?? null,
            'tingkat' => $validated['tingkat'] ?? null,
            'tahun' => $validated['tahun'] ?? date('Y'),
            'tanggal' => $validated['tanggal'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'urutan' => (int) ($validated['urutan'] ?? 0),
            'aktif' => $request->boolean('aktif', true),
        ]);

        return back()->with('status', 'Data prestasi sekolah berhasil disimpan.');
    }

    public function prestasiDestroy(PrestasiSekolah $prestasi): RedirectResponse
    {
        $prestasi->delete();

        return back()->with('status', 'Data prestasi sekolah berhasil dihapus.');
    }

    public function guruIndex(Request $request): View
    {
        $cari = trim((string) $request->query('q', $request->query('search', '')));
        $aktifFilter = trim((string) $request->query('aktif', ''));

        $daftarProfil = ProfilGuruPublik::query()
            ->with('pegawai')
            ->when($cari !== '', function ($q) use ($cari): void {
                $q->where(function ($sub) use ($cari): void {
                    $sub->where('nama_tampil', 'like', '%'.$cari.'%')
                        ->orWhere('jabatan_tampil', 'like', '%'.$cari.'%')
                        ->orWhere('bio_singkat', 'like', '%'.$cari.'%')
                        ->orWhereHas('pegawai', fn ($p) => $p->where('nama_lengkap', 'like', '%'.$cari.'%'));
                });
            })
            ->when($aktifFilter === '1', fn ($q) => $q->where('aktif', true))
            ->when($aktifFilter === '0', fn ($q) => $q->where('aktif', false))
            ->orderBy('urutan')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('humas.guru', [
            'daftarProfil' => $daftarProfil,
            'statistik' => [
                'aktif' => ProfilGuruPublik::query()->where('aktif', true)->count(),
                'nonaktif' => ProfilGuruPublik::query()->where('aktif', false)->count(),
            ],
        ]);
    }

    public function guruCreate(): View
    {
        return view('humas.guru-tambah');
    }

    public function guruStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pegawai_id' => ['nullable', 'integer'],
            'nama_tampil' => ['required', 'string', 'max:120'],
            'jabatan_tampil' => ['required', 'string', 'max:120'],
            'bio_singkat' => ['nullable', 'string', 'max:250'],
            'urutan' => ['nullable', 'integer', 'min:0'],
            'aktif' => ['nullable'],
        ]);

        ProfilGuruPublik::query()->create([
            'pegawai_id' => isset($validated['pegawai_id']) && is_numeric($validated['pegawai_id'])
                ? (int) $validated['pegawai_id']
                : null,
            'nama_tampil' => $validated['nama_tampil'],
            'jabatan_tampil' => $validated['jabatan_tampil'],
            'bio_singkat' => $validated['bio_singkat'] ?? null,
            'urutan' => (int) ($validated['urutan'] ?? 0),
            'aktif' => $request->boolean('aktif', true),
        ]);

        return redirect()
            ->route('humas.guru.index')
            ->with('status', 'Sorotan profil guru berhasil ditambahkan.');
    }

    public function guruDestroy(ProfilGuruPublik $profil): RedirectResponse
    {
        $profil->delete();

        return back()->with('status', 'Sorotan profil guru berhasil dihapus.');
    }

    public function dataSekolah(): View
    {
        return view('humas.data-sekolah', [
            'sekolah' => Sekolah::query()->first(),
        ]);
    }
}
