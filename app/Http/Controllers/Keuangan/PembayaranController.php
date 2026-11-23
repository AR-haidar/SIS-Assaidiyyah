<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Keuangan\TolakPembayaranRequest;
use App\Models\Pembayaran;
use App\Services\BerkasService;
use App\Services\Keuangan\PembayaranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    public function __construct(
        private PembayaranService $service,
        private BerkasService $berkas,
    ) {}

    public function show(Pembayaran $pembayaran): View
    {
        $pembayaran->load([
            'tagihan.siswa.rombel',
            'tagihan.jenisTagihan',
            'tagihan.tahunAjaran',
            'metodePembayaran',
            'bukti',
        ]);

        $tagihan = $pembayaran->tagihan;

        return view('keuangan.detail-pembayaran', [
            'pembayaran' => $pembayaran,
            'tagihan'    => $tagihan,
            'siswa'      => $tagihan?->siswa,
            // Kelas = rombel siswa pada tahun ajaran yang sama dengan tagihan.
            'kelas'      => $tagihan?->siswa?->rombel
                ->firstWhere('tahun_ajaran_id', $tagihan->tahun_ajaran_id)?->nama,
            'buktiUrl'   => $pembayaran->bukti ? $this->berkas->url($pembayaran->bukti) : null,
        ]);
    }

    public function setujui(Request $request, Pembayaran $pembayaran): RedirectResponse
    {
        $this->service->setujui($pembayaran, $request->user());

        return redirect()
            ->route('keuangan.pembayaran.show', $pembayaran)
            ->with('sukses', 'Pembayaran berhasil disetujui.');
    }

    public function tolak(TolakPembayaranRequest $request, Pembayaran $pembayaran): RedirectResponse
    {
        $this->service->tolak($pembayaran, $request->user(), $request->validated('alasan_penolakan'));

        return redirect()
            ->route('keuangan.pembayaran.show', $pembayaran)
            ->with('sukses', 'Pembayaran ditolak.');
    }
}