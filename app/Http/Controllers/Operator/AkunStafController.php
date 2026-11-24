<?php

namespace App\Http\Controllers\Operator;

use App\Actions\Operator\HapusAkunStaf;
use App\Actions\Operator\ResetSandiAkunStaf;
use App\Actions\Operator\SimpanAkunStaf;
use App\Actions\Operator\UbahStatusAkunStaf;
use App\Enums\JenisAkun;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\SimpanAkunStafRequest;
use App\Models\Pengguna;
use App\Models\Peran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AkunStafController extends Controller
{
    public function index(Request $request): View
    {
        $cari = trim((string) $request->query('cari', ''));
        $peran = trim((string) $request->query('peran', ''));
        $status = $request->query('status');

        $akunList = Pengguna::query()
            ->where('jenis_akun', JenisAkun::Staf->value)
            ->with(['peran', 'pegawai'])
            ->when($cari !== '', function ($query) use ($cari): void {
                $query->where(function ($sub) use ($cari): void {
                    $sub->where('username', 'ilike', "%{$cari}%")
                        ->orWhere('nama_lengkap', 'ilike', "%{$cari}%");
                });
            })
            ->when($peran !== '', function ($query) use ($peran): void {
                $query->whereHas('peran', fn ($q) => $q->where('kode', $peran));
            })
            ->when($status === 'aktif', fn ($query) => $query->where('aktif', true))
            ->when($status === 'nonaktif', fn ($query) => $query->where('aktif', false))
            ->orderBy('username')
            ->paginate(15)
            ->withQueryString();

        $daftarPeran = Peran::query()
            ->where('kode', '!=', 'siswa')
            ->orderBy('nama')
            ->get();

        return view('operator.akun-staf.index', [
            'akunList' => $akunList,
            'daftarPeran' => $daftarPeran,
        ]);
    }

    public function create(): View
    {
        $daftarPeran = Peran::query()
            ->where('kode', '!=', 'siswa')
            ->orderBy('nama')
            ->get();

        return view('operator.akun-staf.form', [
            'akun' => new Pengguna,
            'daftarPeran' => $daftarPeran,
        ]);
    }

    public function store(SimpanAkunStafRequest $request, SimpanAkunStaf $aksi): RedirectResponse
    {
        $akun = $aksi->handle($request->validated());

        return redirect()
            ->route('operator.akun-staf.index')
            ->with('status', "Akun staf {$akun->username} berhasil disimpan.");
    }

    public function edit(Pengguna $pengguna): View
    {
        $pengguna->load('peran');
        $daftarPeran = Peran::query()
            ->where('kode', '!=', 'siswa')
            ->orderBy('nama')
            ->get();

        return view('operator.akun-staf.form', [
            'akun' => $pengguna,
            'daftarPeran' => $daftarPeran,
        ]);
    }

    public function update(SimpanAkunStafRequest $request, Pengguna $pengguna, SimpanAkunStaf $aksi): RedirectResponse
    {
        $diperbarui = $aksi->handle($request->validated(), $pengguna);

        return redirect()
            ->route('operator.akun-staf.index')
            ->with('status', "Akun staf {$diperbarui->username} berhasil diperbarui.");
    }

    public function ubahStatus(Request $request, Pengguna $pengguna, UbahStatusAkunStaf $aksi): RedirectResponse
    {
        $data = $request->validate([
            'aktif' => ['required', 'boolean'],
        ]);

        $aksi->handle($pengguna, (bool) $data['aktif']);

        return back()->with('status', 'Status akun staf berhasil diperbarui.');
    }

    public function resetSandi(Request $request, Pengguna $pengguna, ResetSandiAkunStaf $aksi): RedirectResponse
    {
        $data = $request->validate([
            'password' => ['required', 'string', Password::min(8), 'confirmed'],
        ]);

        $aksi->handle($pengguna, (string) $data['password']);

        return back()->with('status', "Kata sandi akun {$pengguna->username} berhasil direset.");
    }

    public function destroy(Pengguna $pengguna, HapusAkunStaf $aksi): RedirectResponse
    {
        $aksi->handle($pengguna);

        return redirect()
            ->route('operator.akun-staf.index')
            ->with('status', 'Akun staf berhasil dihapus.');
    }
}
