<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\PrestasiSekolah;
use App\Models\ProfilGuruPublik;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class BerandaController extends Controller
{
    public function index(): View
    {
        $profilGuruPublik = Schema::hasTable('profil_guru_publik') ? ProfilGuruPublik::query()
            ->with('pegawai')
            ->where('aktif', true)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get() : collect();

        $prestasiSekolah = Schema::hasTable('prestasi_sekolah') ? PrestasiSekolah::query()
            ->where('aktif', true)
            ->orderBy('urutan')
            ->orderByDesc('id')
            ->get() : collect();

        return view('home', [
            'profilGuruPublik' => $profilGuruPublik,
            'prestasiSekolah' => $prestasiSekolah,
        ]);
    }
}
