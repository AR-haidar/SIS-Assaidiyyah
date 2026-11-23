<?php

namespace App\Http\Controllers\Keuangan;

use App\Enums\StatusVerifikasi;
use App\Http\Controllers\Controller;
use App\Models\JenisTagihan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $filterJenis = $request->integer('jenis') ?: null;

        // Satu query GROUP BY untuk tiga kartu status.
        $hitung = Pembayaran::query()
            ->selectRaw('status_verifikasi, COUNT(*) AS total')
            ->groupBy('status_verifikasi')
            ->pluck('total', 'status_verifikasi');

        $jumlah = [
            'menunggu'  => (int) ($hitung[StatusVerifikasi::Menunggu->value] ?? 0),
            'disetujui' => (int) ($hitung[StatusVerifikasi::Disetujui->value] ?? 0),
            'ditolak'   => (int) ($hitung[StatusVerifikasi::Ditolak->value] ?? 0),
        ];

        // 10 pengajuan tertua yang menunggu; daftar lengkap ada di halaman Pembayaran Siswa.
        $antrean = Pembayaran::query()
            ->menunggu()
            ->with([
                'tagihan.jenisTagihan',
                'tagihan.siswa.rombel',
            ])
            ->when($filterJenis, fn ($q) => $q->whereHas(
                'tagihan',
                fn ($t) => $t->where('jenis_tagihan_id', $filterJenis),
            ))
            ->orderBy('tanggal_pengajuan')
            ->limit(10)
            ->get();

        // Kelas = rombel siswa pada tahun ajaran yang sama dengan tagihan.
        $antrean->each(function (Pembayaran $pembayaran): void {
            $tagihan = $pembayaran->tagihan;

            $pembayaran->setAttribute(
                'kelas',
                $tagihan?->siswa?->rombel
                    ->firstWhere('tahun_ajaran_id', $tagihan->tahun_ajaran_id)?->nama,
            );
        });

        return view('keuangan.dashboard', [
            'jumlah'       => $jumlah,
            'antrean'      => $antrean,
            'jenisTagihan' => JenisTagihan::where('aktif', true)->orderBy('nama')->get(),
            'filterJenis'  => $filterJenis,
        ]);
    }
}