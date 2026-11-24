<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use App\Models\PresensiPegawai;
use App\Models\PresensiSiswa;
use App\Models\Rombel;
use App\Models\Tagihan;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RekapController extends Controller
{
    public function absensi(Request $request): View
    {
        $rombelId = $request->query('rombel_id');

        $daftarRombel = Rombel::query()
            ->with(['tahunAjaran', 'waliKelas'])
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        $matriksRombel = $daftarRombel
            ->when(
                is_numeric($rombelId) && (int) $rombelId > 0,
                fn ($col) => $col->where('id', (int) $rombelId)
            )
            ->values()
            ->map(function (Rombel $rombel): array {
                $logs = PresensiSiswa::query()
                    ->whereHas('siswa.keanggotaanRombel', fn ($q) => $q->where('rombel_id', $rombel->getKey()))
                    ->get();

                $totalLog = $logs->count();
                $hadir = $logs->filter(fn ($p) => ($p->status?->value ?? (string) $p->status) === 'hadir')->count();
                $izin = $logs->filter(fn ($p) => ($p->status?->value ?? (string) $p->status) === 'izin')->count();
                $sakit = $logs->filter(fn ($p) => ($p->status?->value ?? (string) $p->status) === 'sakit')->count();
                $alpa = $logs->filter(fn ($p) => ($p->status?->value ?? (string) $p->status) === 'alpa')->count();

                $totalSiswa = $rombel->keanggotaan()->count();

                return [
                    'kelas' => $rombel->nama,
                    'wali_kelas' => $rombel->waliKelas?->nama_lengkap ?? '—',
                    'total_siswa' => $totalSiswa,
                    'hadir_persen' => $totalLog > 0 ? round(($hadir / $totalLog) * 100, 1) : 0.0,
                    'izin_persen' => $totalLog > 0 ? round(($izin / $totalLog) * 100, 1) : 0.0,
                    'sakit_persen' => $totalLog > 0 ? round(($sakit / $totalLog) * 100, 1) : 0.0,
                    'alpa_persen' => $totalLog > 0 ? round(($alpa / $totalLog) * 100, 1) : 0.0,
                ];
            });

        $totalSiswaLogs = PresensiSiswa::query()->count();
        $hadirSiswaLogs = PresensiSiswa::query()->where('status', 'hadir')->count();
        $presensiSiswaPct = $totalSiswaLogs > 0 ? round(($hadirSiswaLogs / $totalSiswaLogs) * 100, 1) : null;

        $totalPegawaiLogs = PresensiPegawai::query()->count();
        $hadirPegawaiLogs = PresensiPegawai::query()->where('status', 'hadir')->count();
        $presensiPendidikPct = $totalPegawaiLogs > 0 ? round(($hadirPegawaiLogs / $totalPegawaiLogs) * 100, 1) : null;

        return view('kepala-sekolah.rekap-absensi', [
            'daftarPeriode' => collect([
                ['nilai' => 'hari-ini', 'label' => 'Hari Ini'],
                ['nilai' => 'bulan-ini', 'label' => 'Bulan Ini'],
                ['nilai' => 'semester-ini', 'label' => 'Semester Ini'],
            ]),
            'daftarRombel' => $daftarRombel,
            'matriksRombel' => $matriksRombel,
            'ringkasan' => [
                'presensi_siswa' => $presensiSiswaPct,
                'presensi_pendidik' => $presensiPendidikPct,
                'hari_efektif' => null,
            ],
        ]);
    }

    public function pembayaran(Request $request): View
    {
        $tahunAjaranId = $request->query('tahun_ajaran_id');
        $rombelId = $request->query('rombel_id');

        $daftarTahunAjaran = TahunAjaran::query()
            ->orderByDesc('aktif')
            ->orderByDesc('kode')
            ->get();

        $daftarRombel = Rombel::query()
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        $rincianKelas = $daftarRombel
            ->when(
                is_numeric($rombelId) && (int) $rombelId > 0,
                fn ($col) => $col->where('id', (int) $rombelId)
            )
            ->values()
            ->map(function (Rombel $rombel) use ($tahunAjaranId): array {
                $tagihanQuery = Tagihan::query()
                    ->whereHas('siswa.keanggotaanRombel', fn ($q) => $q->where('rombel_id', $rombel->getKey()))
                    ->when(
                        is_numeric($tahunAjaranId) && (int) $tahunAjaranId > 0,
                        fn ($q) => $q->where('tahun_ajaran_id', (int) $tahunAjaranId)
                    );

                $target = (float) (clone $tagihanQuery)->sum('nominal');
                $realisasi = (float) (clone $tagihanQuery)
                    ->whereHas('pembayaran', fn ($q) => $q->where('status_verifikasi', 'disetujui'))
                    ->sum('nominal');
                $tunggakan = max(0.0, $target - $realisasi);
                $rasio = $target > 0 ? round(($realisasi / $target) * 100, 1) : 0.0;
                $jumlahSiswa = $rombel->keanggotaan()->count();

                return [
                    'kelas' => $rombel->nama,
                    'jumlah_siswa' => $jumlahSiswa,
                    'target' => $target,
                    'realisasi' => $realisasi,
                    'tunggakan' => $tunggakan,
                    'rasio' => $rasio,
                    'status' => $tunggakan <= 0 && $target > 0 ? 'Tercapai' : 'Berjalan',
                ];
            });

        $totalTarget = (float) $rincianKelas->sum('target');
        $totalTunggakan = (float) $rincianKelas->sum('tunggakan');

        return view('kepala-sekolah.rekap-pembayaran', [
            'daftarTahunAjaran' => $daftarTahunAjaran,
            'daftarRombel' => $daftarRombel,
            'rincianKelas' => $rincianKelas,
            'ringkasan' => [
                'target' => $totalTarget > 0 ? $totalTarget : null,
                'tunggakan' => $totalTarget > 0 ? $totalTunggakan : null,
                'per_kategori' => [],
            ],
        ]);
    }
}
