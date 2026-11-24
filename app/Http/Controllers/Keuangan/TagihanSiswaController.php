<?php

namespace App\Http\Controllers\Keuangan;

use App\Enums\StatusVerifikasi;
use App\Http\Controllers\Controller;
use App\Http\Requests\Keuangan\AjukanPembayaranRequest;
use App\Models\Referensi;
use App\Models\JenisTagihan;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\TahunAjaran;
use App\Services\Keuangan\PembayaranService;
use App\Services\Keuangan\RekapTagihanQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagihanSiswaController extends Controller
{
    public function index(Request $request, RekapTagihanQuery $rekap): View
    {
        $siswa = $this->siswaLogin($request);

        $tahunList = TahunAjaran::orderByDesc('tanggal_mulai')->get();
        $tahun = $tahunList->firstWhere('id', $request->integer('tahun'))
            ?? $tahunList->firstWhere('aktif', true)
            ?? $tahunList->first();
        $tahunId = $tahun?->id;

        $status = $request->query('status');
        $filter = [
            'jenis'  => $request->integer('jenis') ?: null,
            'status' => is_string($status) && array_key_exists($status, RekapTagihanQuery::OPSI_STATUS)
                ? $status
                : null,
        ];

        // Semua query dibatasi ke siswa yang login (bukan hanya lewat policy).
        $milik = fn () => $rekap->dasar($tahunId)->where('siswa_id', $siswa->id);

        $total = (float) $milik()->sum('nominal');
        $terbayar = (float) $milik()
            ->whereHas('pembayaran', fn ($p) => $p->disetujui())
            ->sum('nominal');

        $belumBayar = fn () => $milik()->whereDoesntHave(
            'pembayaran',
            fn ($p) => $p->whereIn('status_verifikasi', ['menunggu', 'disetujui']),
        );
        $jumlahBelumBayar = $belumBayar()->count();
        $daftarBelumBayar = $belumBayar()->with('jenisTagihan')->orderBy('jatuh_tempo')->limit(5)->get();

        $tagihan = $rekap->daftar($tahunId, $filter)
            ->where('siswa_id', $siswa->id)
            ->with(['jenisTagihan', 'pembayaran']) // wajib: status dihitung dari pembayaran
            ->orderBy('jatuh_tempo')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        $tagihan->getCollection()->each(function (Tagihan $t): void {
            $belumDibayar = $t->status_pembayaran === 'belum_dibayar';

            $t->setAttribute('label_status', RekapTagihanQuery::labelStatus($t));
            $t->setAttribute('bisa_ajukan', $belumDibayar);
            $t->setAttribute('alasan_ditolak', $belumDibayar
                ? $t->pembayaran
                    ->where('status_verifikasi', StatusVerifikasi::Ditolak)
                    ->sortByDesc('id')->first()?->alasan_penolakan
                : null);
        });

        return view('siswa.tagihan-spp', [
            'siswa'            => $siswa,
            'kelas'            => $siswa->rombel()->where('rombel.tahun_ajaran_id', $tahunId)->first()?->nama,
            'tahunList'        => $tahunList,
            'tahunId'          => $tahunId,
            'filter'           => $filter,
            'opsiStatus'       => RekapTagihanQuery::OPSI_STATUS,
            'jenisTagihan'     => JenisTagihan::where('aktif', true)->orderBy('nama')->get(),
            'metodePembayaran' => Referensi::where('kelompok', 'metode_pembayaran')
                ->where('aktif', true)->orderBy('urutan')->get(),
            'tagihan'          => $tagihan,
            'total'            => $total,
            'terbayar'         => $terbayar,
            'tunggakan'        => max($total - $terbayar, 0),
            'jumlahBelumBayar' => $jumlahBelumBayar,
            'daftarBelumBayar' => $daftarBelumBayar,
        ]);
    }

    public function ajukan(
        AjukanPembayaranRequest $request,
        PembayaranService $service,
        int $tagihan,
    ): RedirectResponse {
        $siswa = $this->siswaLogin($request);

        // Hanya tagihan milik siswa ini; milik orang lain menghasilkan 404.
        $milik = Tagihan::where('siswa_id', $siswa->id)->findOrFail($tagihan);

        $service->ajukan(
            $milik,
            $request->safe()->except('bukti'),
            $request->file('bukti'),
            $request->user(),
        );

        return redirect()
            ->route('siswa.tagihan.index', ['tahun' => $milik->tahun_ajaran_id])
            ->with('sukses', 'Bukti pembayaran terkirim dan menunggu verifikasi petugas keuangan.');
    }

    private function siswaLogin(Request $request): Siswa
    {
        return Siswa::where('pengguna_id', $request->user()->id)->first()
            ?? abort(403, 'Akun ini belum terhubung dengan data siswa.');
    }
}