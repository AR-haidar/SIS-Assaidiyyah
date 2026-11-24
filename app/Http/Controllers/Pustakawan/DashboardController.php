<?php

namespace App\Http\Controllers\Pustakawan;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $filter = $request->validate(['q' => ['nullable', 'string', 'max:'.Buku::MAKS_JUDUL_KARAKTER]]);

        $katalog = Buku::aktif()->whereHas('naskah')
            ->with(['kategori:id,nama', 'sampul:id,mime', 'naskah:id,mime']);

        if (isset($filter['q'])) {
            $pola = '%'.addcslashes($filter['q'], '\\%_').'%';
            $katalog->where(fn ($query) => $query->whereLike('judul', $pola)->orWhereLike('penulis', $pola));
        }

        return view('perpustakaan.dashboard', [
            'peran' => 'pustakawan',
            'totalBuku' => Buku::count(),
            'totalKategori' => KategoriBuku::count(),
            'katalog' => $katalog->latest(Buku::CREATED_AT)->orderByDesc('id')
                ->get(['id', 'judul', 'penulis', 'sampul_berkas_id', 'naskah_berkas_id']),
            'kataKunci' => $filter['q'] ?? '',
        ]);
    }
}
