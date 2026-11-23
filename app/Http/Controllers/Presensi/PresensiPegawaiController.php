<?php

namespace App\Http\Controllers\Presensi;

use App\Actions\Presensi\KelolaPresensiPegawai;
use App\Enums\StatusPresensiPegawai;
use App\Enums\StatusValidasiPresensi;
use App\Enums\SumberPresensi;
use App\Http\Controllers\Controller;
use App\Http\Requests\Presensi\CatatPresensiPegawaiRequest;
use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\PresensiPegawai;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PresensiPegawaiController extends Controller
{
    public function index(Request $request): View
    {
        $statusValidasi = trim((string) $request->query('status_validasi', ''));
        $tanggal = trim((string) $request->query('tanggal', ''));

        $daftarPresensi = PresensiPegawai::query()
            ->with(['pegawai', 'validator'])
            ->when($statusValidasi !== '', fn ($q) => $q->where('status_validasi', $statusValidasi))
            ->when($tanggal !== '', fn ($q) => $q->whereDate('tanggal', $tanggal))
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $daftarPegawai = Pegawai::query()
            ->orderBy('nama_lengkap')
            ->get();

        return view('presensi.pegawai.index', [
            'daftarPresensi' => $daftarPresensi,
            'daftarPegawai' => $daftarPegawai,
            'opsiStatusPresensi' => StatusPresensiPegawai::opsi(),
            'opsiStatusValidasi' => StatusValidasiPresensi::opsi(),
            'filter' => [
                'status_validasi' => $statusValidasi,
                'tanggal' => $tanggal,
            ],
        ]);
    }

    public function store(
        CatatPresensiPegawaiRequest $request,
        KelolaPresensiPegawai $kelolaPresensiPegawai,
    ): RedirectResponse {
        /** @var Pengguna $pengguna */
        $pengguna = $request->user();
        $validated = $request->validated();

        $pegawai = null;
        if (isset($validated['pegawai_id']) && is_numeric($validated['pegawai_id'])) {
            $pegawai = Pegawai::query()->find((int) $validated['pegawai_id']);
        }

        if (! ($pegawai instanceof Pegawai)) {
            $pegawai = Pegawai::query()->where('pengguna_id', $pengguna->getKey())->first();
        }

        if (! ($pegawai instanceof Pegawai)) {
            return back()->withErrors([
                'pegawai_id' => ['Data pegawai terkait akun belum ditemukan.'],
            ]);
        }

        $status = StatusPresensiPegawai::from((string) $validated['status']);
        $sumber = isset($validated['sumber'])
            ? SumberPresensi::from((string) $validated['sumber'])
            : SumberPresensi::Manual;

        $kelolaPresensiPegawai->catat(
            pegawai: $pegawai,
            tanggal: (string) $validated['tanggal'],
            status: $status,
            sumber: $sumber,
            keterangan: $validated['keterangan'] ?? null,
        );

        return back()->with('status', 'Presensi pegawai berhasil dicatat.');
    }

    public function validasi(
        Request $request,
        PresensiPegawai $presensiPegawai,
        KelolaPresensiPegawai $kelolaPresensiPegawai,
    ): RedirectResponse {
        /** @var Pengguna $pengguna */
        $pengguna = $request->user();

        $validated = $request->validate([
            'status_validasi' => ['required', 'in:valid,ditolak'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $kelolaPresensiPegawai->validasi(
            presensi: $presensiPegawai,
            statusValidasi: StatusValidasiPresensi::from((string) $validated['status_validasi']),
            validator: $pengguna,
            keterangan: $validated['keterangan'] ?? null,
        );

        return back()->with('status', 'Status validasi presensi pegawai berhasil diperbarui.');
    }
}
