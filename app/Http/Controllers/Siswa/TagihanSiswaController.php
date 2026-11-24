<?php

namespace App\Http\Controllers\Siswa;

use App\Actions\Keuangan\CatatPembayaran;
use App\Enums\StatusVerifikasi;
use App\Enums\SumberPembayaran;
use App\Http\Controllers\Controller;
use App\Http\Requests\Keuangan\AjukanPembayaranRequest;
use App\Models\JenisTagihan;
use App\Models\Pengguna;
use App\Models\Referensi;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class TagihanSiswaController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Pengguna $pengguna */
        $pengguna = $request->user();

        $siswa = Siswa::query()
            ->with(['keanggotaanRombel.rombel'])
            ->where('pengguna_id', $pengguna->getKey())
            ->firstOrFail();

        $tahunAjaranId = $request->query('tahun_ajaran_id');
        $jenisTagihanId = $request->query('jenis_tagihan_id');
        $statusFilter = trim((string) $request->query('status', ''));

        $semuaTagihanSiswa = Tagihan::query()
            ->with(['jenisTagihan', 'tahunAjaran', 'pembayaran.metodePembayaran'])
            ->where('siswa_id', $siswa->getKey())
            ->get();

        $totalKewajiban = (float) $semuaTagihanSiswa->sum(fn (Tagihan $t): float => (float) $t->nominal);
        $totalTerbayar = (float) $semuaTagihanSiswa->sum(function (Tagihan $t): float {
            $bayar = $t->pembayaran instanceof Collection
                ? $t->pembayaran->sortByDesc('id')->first()
                : $t->pembayaran;

            return ($bayar && $bayar->status_verifikasi === StatusVerifikasi::Disetujui)
                ? (float) $bayar->nominal
                : 0.0;
        });
        $sisaTunggakan = max(0.0, round($totalKewajiban - $totalTerbayar, 2));
        $jumlahBelumLunas = $semuaTagihanSiswa->filter(function (Tagihan $t): bool {
            $bayar = $t->pembayaran instanceof Collection
                ? $t->pembayaran->sortByDesc('id')->first()
                : $t->pembayaran;

            return ! $bayar || $bayar->status_verifikasi !== StatusVerifikasi::Disetujui;
        })->count();

        $daftarTagihan = Tagihan::query()
            ->with(['jenisTagihan', 'tahunAjaran', 'pembayaran.metodePembayaran'])
            ->where('siswa_id', $siswa->getKey())
            ->when(is_numeric($tahunAjaranId) && (int) $tahunAjaranId > 0, fn ($q) => $q->where('tahun_ajaran_id', (int) $tahunAjaranId))
            ->when(is_numeric($jenisTagihanId) && (int) $jenisTagihanId > 0, fn ($q) => $q->where('jenis_tagihan_id', (int) $jenisTagihanId))
            ->when($statusFilter !== '', function ($q) use ($statusFilter): void {
                if ($statusFilter === 'belum') {
                    $q->whereDoesntHave('pembayaran');
                } elseif (in_array($statusFilter, [StatusVerifikasi::Menunggu->value, StatusVerifikasi::Disetujui->value, StatusVerifikasi::Ditolak->value], true)) {
                    $q->whereHas('pembayaran', fn ($sub) => $sub->where('status_verifikasi', $statusFilter));
                }
            })
            ->orderByDesc('jatuh_tempo')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('siswa.tagihan-spp', [
            'siswa' => $siswa,
            'daftarTagihan' => $daftarTagihan,
            'daftarTahunAjaran' => TahunAjaran::query()->orderByDesc('aktif')->orderByDesc('kode')->get(),
            'daftarJenisTagihan' => JenisTagihan::query()->where('aktif', true)->orderBy('nama')->get(),
            'opsiMetodePembayaran' => Referensi::query()->kelompok('metode_pembayaran')->get(),
            'ringkasan' => [
                'total_kewajiban' => $totalKewajiban,
                'terbayar' => $totalTerbayar,
                'sisa_tunggakan' => $sisaTunggakan,
                'jumlah_belum_lunas' => $jumlahBelumLunas,
            ],
            'filter' => [
                'tahun_ajaran_id' => is_numeric($tahunAjaranId) ? (int) $tahunAjaranId : null,
                'jenis_tagihan_id' => is_numeric($jenisTagihanId) ? (int) $jenisTagihanId : null,
                'status' => $statusFilter,
            ],
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
