<?php

namespace App\Http\Controllers\Keuangan;

use App\Actions\Keuangan\BuatTagihanMassal;
use App\Http\Controllers\Controller;
use App\Http\Requests\Keuangan\BuatTagihanMassalRequest;
use App\Models\JenisTagihan;
use App\Models\Referensi;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagihanController extends Controller
{
    public function index(Request $request): View
    {
        $cari = trim((string) $request->query('q', ''));
        $jenisTagihanId = $request->query('jenis_tagihan_id');
        $tahunAjaranId = $request->query('tahun_ajaran_id');

        $daftarTagihan = Tagihan::query()
            ->with([
                'siswa',
                'jenisTagihan',
                'tahunAjaran',
                'pembayaran.metodePembayaran',
                'pembayaran.verifikator',
            ])
            ->when(is_numeric($jenisTagihanId) && (int) $jenisTagihanId > 0, fn ($q) => $q->where('jenis_tagihan_id', (int) $jenisTagihanId))
            ->when(is_numeric($tahunAjaranId) && (int) $tahunAjaranId > 0, fn ($q) => $q->where('tahun_ajaran_id', (int) $tahunAjaranId))
            ->when($cari !== '', function ($q) use ($cari): void {
                $q->whereHas('siswa', function ($sub) use ($cari): void {
                    $sub->where('nama_lengkap', 'like', '%'.$cari.'%')
                        ->orWhere('nisn', 'like', '%'.$cari.'%');
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('keuangan.tagihan.index', [
            'daftarTagihan' => $daftarTagihan,
            'daftarJenisTagihan' => JenisTagihan::query()->where('aktif', true)->orderBy('nama')->get(),
            'daftarTahunAjaran' => TahunAjaran::query()->orderByDesc('aktif')->orderByDesc('kode')->get(),
            'daftarRombel' => Rombel::query()->with('tahunAjaran')->orderByDesc('id')->get(),
            'daftarSiswa' => Siswa::query()->orderBy('nama_lengkap')->get(),
            'opsiMetodePembayaran' => Referensi::query()->kelompok('metode_pembayaran')->get(),
            'filter' => [
                'q' => $cari,
                'jenis_tagihan_id' => is_numeric($jenisTagihanId) ? (int) $jenisTagihanId : null,
                'tahun_ajaran_id' => is_numeric($tahunAjaranId) ? (int) $tahunAjaranId : null,
            ],
        ]);
    }

    public function buatMassal(
        BuatTagihanMassalRequest $request,
        BuatTagihanMassal $buatTagihanMassal,
    ): RedirectResponse {
        $validated = $request->validated();

        /** @var JenisTagihan $jenisTagihan */
        $jenisTagihan = JenisTagihan::query()->findOrFail((int) $validated['jenis_tagihan_id']);

        /** @var TahunAjaran $tahunAjaran */
        $tahunAjaran = TahunAjaran::query()->findOrFail((int) $validated['tahun_ajaran_id']);

        $siswaIds = $validated['siswa_ids'] ?? [];
        if ($siswaIds === [] && isset($validated['rombel_id']) && is_numeric($validated['rombel_id'])) {
            $siswaIds = Siswa::query()
                ->whereHas('keanggotaanRombel', fn ($q) => $q->where('rombel_id', (int) $validated['rombel_id']))
                ->pluck('id')
                ->all();
        }

        $hasil = $buatTagihanMassal->handle(
            jenisTagihan: $jenisTagihan,
            tahunAjaran: $tahunAjaran,
            siswaIds: $siswaIds,
            nominal: (float) $validated['nominal'],
            jatuhTempo: (string) $validated['jatuh_tempo'],
            periodeBulan: $validated['periode_bulan'] ?? null,
        );

        return back()->with(
            'status',
            "Tagihan berhasil dibuat untuk {$hasil['dibuat']} siswa ({$hasil['dilewati']} dilewati).",
        );
    }
}
