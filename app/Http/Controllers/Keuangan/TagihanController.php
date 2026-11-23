<?php

namespace App\Http\Controllers\Keuangan;

use App\Enums\StatusDataSiswa;
use App\Http\Controllers\Controller;
use App\Http\Requests\Keuangan\StoreTagihanRequest;
use App\Models\JenisTagihan;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Services\Keuangan\TagihanGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TagihanController extends Controller
{
    public function create(): View
    {
        $tahunAjaran = TahunAjaran::aktif()->first();

        // Hanya siswa aktif dan terverifikasi yang punya rombel di tahun ajaran aktif.
        $siswa = $tahunAjaran
            ? Siswa::query()
                ->where('status_data', StatusDataSiswa::Terverifikasi)
                ->whereHas('statusKesiswaan', fn ($q) => $q
                    ->where('kelompok', 'status_kesiswaan')->where('kode', 'aktif'))
                ->whereHas('rombel', fn ($q) => $q->where('rombel.tahun_ajaran_id', $tahunAjaran->id))
                ->with(['rombel' => fn ($q) => $q->where('rombel.tahun_ajaran_id', $tahunAjaran->id)])
                ->orderBy('nama_lengkap')
                ->get()
            : collect();

        return view('keuangan.tambah-tagihan', [
            'tahunAjaran'  => $tahunAjaran,
            'siswa'        => $siswa,
            'jenisTagihan' => JenisTagihan::where('aktif', true)->orderBy('nama')->get(),
        ]);
    }

    public function store(StoreTagihanRequest $request, TagihanGeneratorService $service): RedirectResponse
    {
        $tahunAjaran = TahunAjaran::aktif()->first();

        if (! $tahunAjaran) {
            throw ValidationException::withMessages([
                'siswa_id' => 'Belum ada tahun ajaran aktif. Hubungi operator.',
            ]);
        }

        $data = $request->validated();

        $service->buatSatu([
            'siswa_id'         => $data['siswa_id'],
            'jenis_tagihan_id' => $data['jenis_tagihan_id'],
            'tahun_ajaran_id'  => $tahunAjaran->id,
            'nominal'          => $data['nominal'],
            'jatuh_tempo'      => $data['jatuh_tempo'],
            // Dipakai hanya bila jenisnya bulanan; service yang menentukan.
            'periode_bulan'    => $data['tanggal_tagihan'],
        ]);

        return redirect()
            ->route('keuangan.tagihan.create')
            ->with('sukses', 'Tagihan berhasil dibuat.');
    }
}