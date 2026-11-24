<?php

use App\Http\Middleware\PastikanPeran;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (): void {
            $portalPeran = [
                'operator' => 'operator',
                'guru' => 'guru',
                'wali_kelas' => 'wali-kelas',
                'kepala' => 'kepala-sekolah',
                'keuangan' => 'keuangan',
                'pustakawan' => 'perpustakaan',
                'humas' => 'humas',
                'siswa' => 'siswa',
            ];

            foreach ($portalPeran as $kode => $prefixUrl) {
                Route::middleware(['web', 'auth', 'peran:'.$kode])
                    ->prefix($prefixUrl)
                    ->name($kode.'.')
                    ->group(base_path("routes/{$kode}.php"));
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['peran' => PastikanPeran::class]);
        $middleware->redirectUsersTo('/pilih-peran');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            return redirect()
                ->route('login')
                ->with('status', 'Sesi Anda telah berakhir. Silakan masuk kembali.');
        });
    })->create();
