<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Keuangan\TolakPembayaranRequest;
use App\Models\Pembayaran;
use App\Models\Rombel;
use App\Models\Tagihan;
use App\Models\TahunAjaran;
use App\Models\JenisTagihan;
use App\Services\BerkasService;
use App\Services\Keuangan\PembayaranService;
use App\Services\Keuangan\RekapTagihanQuery;
use App\Enums\StatusVerifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    public function __construct(
        private PembayaranService $service,
        private BerkasService $berkas,
    ) {}

    public function index(Request $request, RekapTagihanQuery $rekap): View
    {
        $tahunAjaran = TahunAjaran::aktif()->first();
        $tahunId = $tahunAjaran?->id;

        $status = $request->query('status');

        $filter = [
            'q'      => trim((string) $request->query('q', '')),
            'jenis'  => $request->integer('jenis') ?: null,
            'rombel' => $request->integer('rombel') ?: null,
            'status' => is_string($status) && array_key_exists($status, RekapTagihanQuery::OPSI_STATUS)
                ? $status
                : null,
        ];

        $tagihan = $rekap->daftar($tahunId, $filter)
            ->with([
                'jenisTagihan',
                'pembayaran', // wajib: status_pembayaran dihitung dari sini (hindari N+1)
                'siswa.rombel' => fn ($q) => $q->where('rombel.tahun_ajaran_id', $tahunId),
            ])
            ->orderBy('jatuh_tempo')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        $tagihan->getCollection()->each(function (Tagihan $t): void {
            $t->setAttribute('label_status', RekapTagihanQuery::labelStatus($t));

            // Pembayaran yang berlaku (menunggu/disetujui), untuk link ke halaman detail.
            $t->setRelation('pembayaranAktif', $t->pembayaran->first(
                fn ($p) => in_array($p->status_verifikasi, [StatusVerifikasi::Menunggu, StatusVerifikasi::Disetujui], true)
            ));
        });

        return view('keuangan.pembayaran-siswa', [
            'tagihan'      => $tagihan,
            'stat'         => $rekap->ringkasan($tahunId),
            'filter'       => $filter,
            'opsiStatus'   => RekapTagihanQuery::OPSI_STATUS,
            'jenisTagihan' => JenisTagihan::where('aktif', true)->orderBy('nama')->get(),
            'rombelList'   => Rombel::where('tahun_ajaran_id', $tahunId)->orderBy('tingkat')->orderBy('nama')->get(),
        ]);
    }

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