<?php

namespace App\Http\Controllers\Akun;

use App\Actions\Akun\GantiKataSandi;
use App\Http\Controllers\Controller;
use App\Http\Requests\Akun\GantiKataSandiRequest;
use App\Models\Pengguna;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KataSandiController extends Controller
{
    public function edit(): View
    {
        return view('akun.ganti-sandi', [
            'pengguna' => request()->user(),
        ]);
    }

    public function update(GantiKataSandiRequest $request, GantiKataSandi $aksi): RedirectResponse
    {
        /** @var Pengguna $pengguna */
        $pengguna = $request->user();

        $aksi->handle($pengguna, (string) $request->validated('kata_sandi_baru'));

        return back()->with('status', 'Kata sandi/PIN berhasil diperbarui.');
    }
}
