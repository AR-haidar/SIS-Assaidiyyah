<?php

use App\Enums\StatusPresensiPegawai;
use App\Models\Berkas;
use App\Models\PresensiPegawai;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

Route::get('/dashboard', function (Request $request) {
    $jumlah = PresensiPegawai::query()
        ->whereHas('pegawai', fn ($query) => $query->where('pengguna_id', $request->user()->getAuthIdentifier()))
        ->selectRaw('status, count(*) as jumlah')
        ->groupBy('status')
        ->toBase()
        ->pluck('jumlah', 'status');
    $hadir = (int) $jumlah->get(StatusPresensiPegawai::Hadir->value, 0);
    $total = $jumlah->sum();

    return view('guru.dashboard', [
        'peran' => 'guru',
        'tingkatKehadiran' => $total > 0 ? round($hadir * 100 / $total) : null,
        'hariHadir' => $hadir,
        'hariIzin' => (int) $jumlah->get(StatusPresensiPegawai::IzinTugasLuar->value, 0),
        'hariSakit' => (int) $jumlah->get(StatusPresensiPegawai::Sakit->value, 0),
    ]);
})->name('dashboard');

$pasfotoTersedia = static fn (?Berkas $berkas): bool => $berkas !== null
    && in_array($berkas->mime, ['image/jpeg', 'image/png', 'image/webp'], true)
    && Storage::disk('local')->fileExists($berkas->path);

Route::get('/profile', function (Request $request) use ($pasfotoTersedia) {
    $pegawai = $request->user()->pegawai()->with(['statusKepegawaian', 'pendidikanTerakhir', 'pasfoto'])->first();

    return view('guru.profile', [
        'pegawai' => $pegawai,
        'nama' => $pegawai?->nama_lengkap ?? $request->user()->nama_lengkap,
        'tempatTanggalLahir' => implode(', ', array_filter([$pegawai?->tempat_lahir, $pegawai?->tanggal_lahir?->format('d-m-Y')])),
        'adaPasfoto' => $pasfotoTersedia($pegawai?->pasfoto),
    ]);
})->name('profil.edit');

Route::get('/profile/pasfoto', function (Request $request) use ($pasfotoTersedia) {
    $pasfoto = $request->user()->pegawai()->with('pasfoto')->first()?->pasfoto;
    abort_unless($pasfotoTersedia($pasfoto), 404);

    return Storage::disk('local')->response($pasfoto->path, null, [
        'Content-Type' => $pasfoto->mime,
        'Cache-Control' => 'private, no-store',
        'X-Content-Type-Options' => 'nosniff',
    ]);
})->name('profil.pasfoto');

Route::get('/rekap-absensi', function (Request $request) {
    $tahunAjaran = TahunAjaran::query()
        ->whereColumn('tanggal_mulai', '<=', 'tanggal_selesai')
        ->orderByDesc('tanggal_mulai')
        ->get(['id', 'kode', 'tanggal_mulai', 'tanggal_selesai']);
    $filter = $request->validate([
        'bulan' => ['nullable', 'integer', 'between:1,12'],
        'tahun_ajaran_id' => ['nullable', 'integer', Rule::in($tahunAjaran->modelKeys())],
        'status' => ['nullable', Rule::enum(StatusPresensiPegawai::class)],
    ]);
    $tahunTerpilih = $tahunAjaran->find($filter['tahun_ajaran_id'] ?? null);
    $periode = PresensiPegawai::query()
        ->whereHas('pegawai', fn ($query) => $query->where('pengguna_id', $request->user()->getAuthIdentifier()))
        ->when(isset($filter['bulan']), fn ($query) => $query->whereMonth('tanggal', $filter['bulan']))
        ->when($tahunTerpilih, fn ($query) => $query->whereBetween('tanggal', [
            $tahunTerpilih->tanggal_mulai->toDateString(),
            $tahunTerpilih->tanggal_selesai->toDateString(),
        ]));

    $jumlah = (clone $periode)->selectRaw('status, count(*) as jumlah')->groupBy('status')->toBase()->pluck('jumlah', 'status');
    $hadir = (int) $jumlah->get(StatusPresensiPegawai::Hadir->value, 0);
    $total = $jumlah->sum();

    return view('guru.rekap-absensi', [
        'filter' => $filter,
        'tahunAjaran' => $tahunAjaran,
        'rataKehadiran' => $total > 0 ? round($hadir * 100 / $total) : null,
        'targetTercapai' => $total > 0 && $hadir * 100 >= $total * 95,
        'hariHadir' => $hadir,
        'hariIzin' => (int) $jumlah->get(StatusPresensiPegawai::IzinTugasLuar->value, 0),
        'hariSakit' => (int) $jumlah->get(StatusPresensiPegawai::Sakit->value, 0),
        'riwayat' => $periode
            ->when(isset($filter['status']), fn ($query) => $query->where('status', $filter['status']))
            ->latest('tanggal')
            ->get(['tanggal', 'status', 'keterangan']),
    ]);
})->name('rekap_absensi.index');
