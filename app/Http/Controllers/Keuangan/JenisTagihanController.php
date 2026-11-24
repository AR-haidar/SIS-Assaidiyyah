<?php

namespace App\Http\Controllers\Keuangan;

use App\Actions\Keuangan\SimpanJenisTagihan;
use App\Http\Controllers\Controller;
use App\Http\Requests\Keuangan\SimpanJenisTagihanRequest;
use App\Models\JenisTagihan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JenisTagihanController extends Controller
{
    public function index(): View
    {
        $daftarJenis = JenisTagihan::query()
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('keuangan.jenis-tagihan.index', [
            'daftarJenis' => $daftarJenis,
        ]);
    }

    public function store(
        SimpanJenisTagihanRequest $request,
        SimpanJenisTagihan $simpanJenisTagihan,
    ): RedirectResponse {
        $simpanJenisTagihan->handle($request->validated());

        return back()->with('status', 'Jenis tagihan berhasil ditambahkan.');
    }

    public function update(
        SimpanJenisTagihanRequest $request,
        JenisTagihan $jenisTagihan,
        SimpanJenisTagihan $simpanJenisTagihan,
    ): RedirectResponse {
        $simpanJenisTagihan->handle($request->validated(), $jenisTagihan);

        return back()->with('status', 'Jenis tagihan berhasil diperbarui.');
    }

    public function destroy(JenisTagihan $jenisTagihan): RedirectResponse
    {
        $jenisTagihan->delete();

        return back()->with('status', 'Jenis tagihan berhasil dihapus.');
    }
}
