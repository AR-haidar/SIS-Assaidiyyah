<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

final class PortalPublik
{
    public static function rute(Request $request): string
    {
        $pengguna = $request->user();

        if (! $pengguna) {
            return 'login';
        }

        $peran = $request->session()->get('peran_aktif');

        if (is_string($peran) && Route::has($peran.'.dashboard') && $pengguna->punyaPeran($peran)) {
            return $peran.'.dashboard';
        }

        return 'peran.pilih';
    }
}
