<?php

namespace App\Http\Controllers\Keuangan;

use App\Actions\Keuangan\CatatPembayaran;
use App\Actions\Keuangan\VerifikasiPembayaran;
use App\Enums\StatusVerifikasi;
use App\Enums\SumberPembayaran;
use App\Http\Controllers\Controller;
use App\Http\Requests\Keuangan\AjukanPembayaranRequest;
use App\Http\Requests\Keuangan\VerifikasiPembayaranRequest;
use App\Models\Pembayaran;
use App\Models\Pengguna;
use App\Models\Tagihan;
use App\Services\BerkasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class PembayaranController extends Controller
{
    public function index(Request $request): View
    {
        $statusVerifikasi = trim((string) $request->query('status_verifikasi', ''));

        $daftarPembayaran = Pembayaran::query()
            ->with([
                'tagihan.siswa',
                'tagihan.jenisTagihan',
                'metodePembayaran',
                'buktiBerkas',
                'verifikator',
            ])
            ->when($statusVerifikasi !== '', fn ($q) => $q->where('status_verifikasi', $statusVerifikasi))
            ->orderByDesc('tanggal_pengajuan')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('keuangan.pembayaran-siswa', [
            'daftarPembayaran' => $daftarPembayaran,
            'opsiStatusVerifikasi' => StatusVerifikasi::opsi(),
            'filter' => [
                'status_verifikasi' => $statusVerifikasi,
            ],
        ]);
    }

    public function show(Pembayaran $pembayaran): View
    {
        $pembayaran->loadMissing([
            'tagihan.siswa',
            'tagihan.jenisTagihan',
            'tagihan.tahunAjaran',
            'metodePembayaran',
            'buktiBerkas',
            'verifikator',
        ]);

        return view('keuangan.detail-pembayaran', [
            'pembayaran' => $pembayaran,
            'opsiStatusVerifikasi' => StatusVerifikasi::opsi(),
        ]);
    }

    public function store(
        AjukanPembayaranRequest $request,
        Tagihan $tagihan,
        CatatPembayaran $catatPembayaran,
    ): RedirectResponse {
        /** @var Pengguna $pengguna */
        $pengguna = $request->user();
        $bukti = $request->file('bukti_berkas');

        $catatPembayaran->handle(
            tagihan: $tagihan,
            data: $request->validated(),
            sumber: SumberPembayaran::InputPetugas,
            pelaku: $pengguna,
            buktiBerkas: $bukti instanceof UploadedFile ? $bukti : null,
            langsungSetujui: $request->boolean('langsung_setujui', true),
        );

        return back()->with('status', 'Pembayaran petugas berhasil dicatat.');
    }

    public function verifikasi(
        VerifikasiPembayaranRequest $request,
        Pembayaran $pembayaran,
        VerifikasiPembayaran $verifikasiPembayaran,
    ): RedirectResponse {
        /** @var Pengguna $pengguna */
        $pengguna = $request->user();
        $validated = $request->validated();

        $verifikasiPembayaran->handle(
            pembayaran: $pembayaran,
            statusBaru: StatusVerifikasi::from((string) $validated['status_verifikasi']),
            verifikator: $pengguna,
            tanggalPembayaran: $validated['tanggal_pembayaran'] ?? null,
            alasanPenolakan: $validated['alasan_penolakan'] ?? null,
        );

        return back()->with('status', 'Status verifikasi pembayaran berhasil diperbarui.');
    }

    public function bukti(
        Request $request,
        Pembayaran $pembayaran,
        BerkasService $berkasService,
    ): Response {
        $pembayaran->loadMissing('buktiBerkas');
        abort_if($pembayaran->buktiBerkas === null, 404);

        return $berkasService->respons($pembayaran->buktiBerkas, $request);
    }
}
