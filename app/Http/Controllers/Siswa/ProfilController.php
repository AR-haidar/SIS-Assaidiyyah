<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function show(Request $request): View
    {
        /** @var Pengguna|null $pengguna */
        $pengguna = $request->user();

        $siswa = $pengguna
            ? Siswa::query()
                ->with([
                    'keanggotaanRombel.rombel.tahunAjaran',
                    'keanggotaanRombel.rombel.waliKelas',
                    'berkas',
                    'pengguna',
                ])
                ->where('pengguna_id', $pengguna->getKey())
                ->first()
            : null;

        return view('siswa.data-diri', [
            'user' => $pengguna,
            'siswa' => $siswa,
        ]);
    }
}
