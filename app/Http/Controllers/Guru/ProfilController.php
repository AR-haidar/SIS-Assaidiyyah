<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function edit(Request $request): View
    {
        /** @var Pengguna|null $pengguna */
        $pengguna = $request->user();

        $pegawai = $pengguna
            ? Pegawai::query()
                ->with(['pasfoto', 'statusKepegawaian', 'jenisGtk', 'rombelWali', 'pengguna'])
                ->where('pengguna_id', $pengguna->getKey())
                ->first()
            : null;

        return view('guru.profile', [
            'user' => $pengguna,
            'pegawai' => $pegawai,
        ]);
    }
}
