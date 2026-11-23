<?php

namespace App\Http\Controllers\Siswa;

use App\Actions\Keuangan\CatatPembayaran;
use App\Enums\SumberPembayaran;
use App\Http\Controllers\Controller;
use App\Http\Requests\Keuangan\AjukanPembayaranRequest;
use App\Models\Pengguna;
use App\Models\Referensi;
use App\Models\Siswa;
use App\Models\Tagihan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\View\View;

class TagihanSiswaController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Pengguna $pengguna */
        $pengguna = $request->user();

        $siswa = Siswa::query()
            ->where('pengguna_id', $pengguna->getKey())
            ->firstOrFail();

        $daftarTagihan = Tagihan::query()
            ->with(['jenisTagihan', 'tahunAjaran', 'pembayaran.metodePembayaran'])
            ->where('siswa_id', $siswa->getKey())
            ->orderByDesc('jatuh_tempo')
            ->paginate(15)
            ->withQueryString();

        return view('siswa.tagihan.index', [
            'siswa' => $siswa,
            'daftarTagihan' => $daftarTagihan,
            'opsiMetodePembayaran' => Referensi::query()->kelompok('metode_pembayaran')->get(),
        ]);
    }

    public function ajukanPembayaran(
        AjukanPembayaranRequest $request,
        Tagihan $tagihan,
        CatatPembayaran $catatPembayaran,
    ): RedirectResponse {
        /** @var Pengguna $pengguna */
        $pengguna = $request->user();

        $siswa = Siswa::query()
            ->where('pengguna_id', $pengguna->getKey())
            ->firstOrFail();

        abort_unless((int) $tagihan->siswa_id === (int) $siswa->getKey(), 403);

        $bukti = $request->file('bukti_berkas');

        $catatPembayaran->handle(
            tagihan: $tagihan,
            data: $request->validated(),
            sumber: SumberPembayaran::PengajuanWali,
            pelaku: $pengguna,
            buktiBerkas: $bukti instanceof UploadedFile ? $bukti : null,
            langsungSetujui: false,
        );

        return back()->with('status', 'Pengajuan pembayaran berhasil dikirim dan menunggu verifikasi.');
    }
}
