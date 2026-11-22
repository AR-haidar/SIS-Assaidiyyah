<?php

use App\Http\Middleware\PastikanPeran;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function (): void {
            $daftarPeran = [
                'operator',
                'guru',
                'wali_kelas',
                'kepala',
                'keuangan',
                'pustakawan',
                'siswa',
                'humas',
            ];

            foreach ($daftarPeran as $peran) {
                Route::middleware(['web', 'auth', 'peran:' . $peran])
                    ->prefix(str_replace('_', '-', $peran))
                    ->name($peran . '.')
                    ->group(base_path("routes/{$peran}.php"));
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'peran' => PastikanPeran::class,
        ]);
        $middleware->redirectUsersTo('/pilih-peran');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            return redirect()
                ->route('login')
                ->with('status', 'Sesi Anda telah berakhir. Silakan masuk kembali.');
        });
    })->create();
