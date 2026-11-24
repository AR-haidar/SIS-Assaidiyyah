<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\PrestasiSekolah;
use App\Models\ProfilGuruPublik;
use Illuminate\View\View;

class BerandaController extends Controller
{
    public function index(): View
    {
        $profilGuruPublik = ProfilGuruPublik::query()
            ->with('pegawai')
            ->where('aktif', true)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        $prestasiSekolah = PrestasiSekolah::query()
            ->where('aktif', true)
            ->orderBy('urutan')
            ->orderByDesc('id')
            ->get();

        return view('home', [
            'profilGuruPublik' => $profilGuruPublik,
            'prestasiSekolah' => $prestasiSekolah,
        ]);
    }
}
