<?php

namespace App\Http\Middleware;

use App\Models\Pengguna;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PastikanPeran
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string ...$peranDiizinkan): Response
    {
        $user = $request->user();

        if (! $user instanceof Pengguna) {
            abort(403);
        }

        $peranAktif = $request->session()->get('peran_aktif');

        if (! is_string($peranAktif) || $peranAktif === '') {
            return redirect()->route('peran.pilih');
        }

        if ($peranDiizinkan !== [] && ! in_array($peranAktif, $peranDiizinkan, true)) {
            abort(403);
        }

        if (! $user->punyaPeran($peranAktif)) {
            $request->session()->forget('peran_aktif');
            abort(403);
        }

        return $next($request);
    }
}
