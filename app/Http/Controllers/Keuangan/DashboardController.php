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
        // Blade mengirim tombol filter dengan value = id jenis tagihan.
        $jenisId = $request->integer('jenis') ?: null;

        // Satu query GROUP BY untuk tiga kartu status.
        $hitung = Pembayaran::query()
            ->selectRaw('status_verifikasi, COUNT(*) AS total')
            ->groupBy('status_verifikasi')
            ->pluck('total', 'status_verifikasi');

        $statistik = [
            'menunggu'  => (int) ($hitung[StatusVerifikasi::Menunggu->value] ?? 0),
            'disetujui' => (int) ($hitung[StatusVerifikasi::Disetujui->value] ?? 0),
            'ditolak'   => (int) ($hitung[StatusVerifikasi::Ditolak->value] ?? 0),
        ];

        // 10 pengajuan tertua yang menunggu; daftar lengkap ada di halaman Pembayaran Siswa.
        $pembayaranMenunggu = Pembayaran::query()
            ->menunggu()
            ->with([
                'tagihan.jenisTagihan',
                'tagihan.siswa.rombel',
            ])
            ->when($jenisId, fn ($q) => $q->whereHas(
                'tagihan',
                fn ($t) => $t->where('jenis_tagihan_id', $jenisId),
            ))
            ->orderBy('tanggal_pengajuan')
            ->orderBy('id')
            ->limit(10)
            ->get();

        // Blade membaca atribut kelas_nama dan tanggal_bayar. Isi di sini supaya
        // Blade tidak jatuh ke lazy-load (N+1) dan tanggalnya tidak kosong.
        $pembayaranMenunggu->each(function (Pembayaran $pembayaran): void {
            $tagihan = $pembayaran->tagihan;

            $pembayaran->setAttribute(
                'kelas_nama',
                $tagihan?->siswa?->rombel
                    ->firstWhere('tahun_ajaran_id', $tagihan->tahun_ajaran_id)?->nama ?? '-',
            );

            $pembayaran->setAttribute('tanggal_bayar', $pembayaran->tanggal_pengajuan);
        });

        return view('keuangan.dashboard', [
            'statistik'          => $statistik,
            'pembayaranMenunggu' => $pembayaranMenunggu,
            'daftarJenisTagihan' => JenisTagihan::where('aktif', true)->orderBy('nama')->get(),
        ]);
    }
}