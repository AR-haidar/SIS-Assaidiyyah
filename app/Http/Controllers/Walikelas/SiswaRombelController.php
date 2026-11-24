<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\Rombel;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiswaRombelController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Pengguna|null $pengguna */
        $pengguna = $request->user();
        $cari = trim((string) $request->query('q', $request->query('search', '')));

        $rombel = $pengguna
            ? Rombel::query()
                ->with(['tahunAjaran', 'waliKelas'])
                ->whereHas('waliKelas', fn ($q) => $q->where('pengguna_id', $pengguna->getKey()))
                ->orderByDesc('id')
                ->first()
            : null;

        $daftarSiswa = $rombel instanceof Rombel
            ? Siswa::query()
                ->with(['keanggotaanRombel.rombel', 'pengguna'])
                ->whereHas('keanggotaanRombel', fn ($q) => $q->where('rombel_id', $rombel->getKey()))
                ->when($cari !== '', function ($q) use ($cari): void {
                    $q->where(function ($sub) use ($cari): void {
                        $sub->where('nama_lengkap', 'like', '%'.$cari.'%')
                            ->orWhere('nisn', 'like', '%'.$cari.'%')
                            ->orWhere('nik', 'like', '%'.$cari.'%');
                    });
                })
                ->orderBy('nama_lengkap')
                ->paginate(15)
                ->withQueryString()
            : collect();

        return view('wali-kelas.data-siswa', [
            'rombel' => $rombel,
            'daftarSiswa' => $daftarSiswa,
            'filter' => [
                'q' => $cari,
            ],
        ]);
    }
}
