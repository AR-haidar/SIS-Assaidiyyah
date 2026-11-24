<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Pembayaran;
use App\Models\Pengguna;
use App\Models\PresensiSiswa;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function operator(): View
    {
        return view('operator.dashboard', [
            'statistik' => [
                'total_siswa' => Siswa::query()->count(),
                'total_pegawai' => Pegawai::query()->count(),
                'total_rombel' => Rombel::query()->count(),
                'total_pengguna' => Pengguna::query()->count(),
            ],
        ]);
    }

    public function guru(Request $request): View
    {
        /** @var Pengguna|null $pengguna */
        $pengguna = $request->user();
        $pegawai = $pengguna?->pegawai;

        return view('guru.dashboard', [
            'pegawai' => $pegawai,
            'statistik' => [
                'hadir' => 0,
                'izin' => 0,
                'sakit' => 0,
                'persentase' => null,
            ],
        ]);
    }

    public function waliKelas(Request $request): View
    {
        /** @var Pengguna|null $pengguna */
        $pengguna = $request->user();
        $rombel = $pengguna
            ? Rombel::query()
                ->with(['tahunAjaran', 'waliKelas'])
                ->whereHas('waliKelas', fn ($q) => $q->where('pengguna_id', $pengguna->getKey()))
                ->orderByDesc('id')
                ->first()
            : null;

        $jumlahSiswa = $rombel instanceof Rombel
            ? Siswa::query()->whereHas('keanggotaanRombel', fn ($q) => $q->where('rombel_id', $rombel->getKey()))->count()
            : 0;

        return view('wali-kelas.dashboard', [
            'rombel' => $rombel,
            'statistik' => [
                'jumlah_siswa' => $jumlahSiswa,
            ],
        ]);
    }

    public function siswa(Request $request): View
    {
        /** @var Pengguna|null $pengguna */
        $pengguna = $request->user();
        $siswa = $pengguna
            ? Siswa::query()
                ->with(['keanggotaanRombel.rombel'])
                ->where('pengguna_id', $pengguna->getKey())
                ->first()
            : null;

        $totalTagihan = $siswa instanceof Siswa
            ? (float) Tagihan::query()->where('siswa_id', $siswa->getKey())->sum('nominal')
            : 0.0;

        $totalPresensi = $siswa instanceof Siswa
            ? PresensiSiswa::query()->where('siswa_id', $siswa->getKey())->count()
            : 0;

        return view('siswa.dashboard', [
            'siswa' => $siswa,
            'statistik' => [
                'total_tagihan' => $totalTagihan,
                'total_presensi' => $totalPresensi,
            ],
        ]);
    }

    public function kepala(): View
    {
        return view('kepala-sekolah.dashboard', [
            'statistik' => [
                'total_siswa' => Siswa::query()->count(),
                'total_pegawai' => Pegawai::query()->count(),
                'total_pembayaran' => Pembayaran::query()->count(),
            ],
        ]);
    }

    public function keuangan(): View
    {
        return view('keuangan.dashboard', [
            'statistik' => [
                'total_tagihan' => (float) Tagihan::query()->sum('nominal'),
                'total_pembayaran' => Pembayaran::query()->count(),
            ],
        ]);
    }

    public function humas(): View
    {
        return view('humas.dashboard');
    }
}
