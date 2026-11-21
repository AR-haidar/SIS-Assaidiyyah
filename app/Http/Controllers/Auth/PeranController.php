<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\Peran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class PeranController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user instanceof Pengguna) {
            abort(403);
        }

        /** @var Peran|null $peran */
        $peran = $user->peran()->orderBy('id')->first();

        if (! $peran instanceof Peran || ! Route::has($peran->kode.'.dashboard')) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors(['username' => 'Akun belum memiliki peran. Hubungi operator.']);
        }

        $request->session()->put('peran_aktif', $peran->kode);

        return redirect()->route($peran->kode.'.dashboard');
    }
}
