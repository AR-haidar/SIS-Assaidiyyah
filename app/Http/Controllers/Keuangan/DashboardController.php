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

        $antrean = Pembayaran::query()
            ->menunggu()
            ->with([
                'tagihan.siswa',
                'tagihan.jenisTagihan',
            ])
            ->when($filterJenis, fn ($q) => $q->whereHas(
                'tagihan', fn ($t) => $t->where('jenis_tagihan_id', $filterJenis)
            ))
            ->orderBy('tanggal_pengajuan')
            ->paginate(10)
            ->withQueryString();

        return view('keuangan.dashboard', [
            'jumlah'        => $jumlah,
            'antrean'       => $antrean,
            'jenisTagihan'  => JenisTagihan::where('aktif', true)->orderBy('nama')->get(),
            'filterJenis'   => $filterJenis,
        ]);
    }
}