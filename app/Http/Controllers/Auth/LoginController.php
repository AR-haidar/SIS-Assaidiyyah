<?php

namespace App\Http\Controllers\Auth;

use App\Enums\JenisAkun;
use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('login', [
            'opsiJenisAkun' => JenisAkun::opsi(),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'jenis_akun' => ['required', Rule::enum(JenisAkun::class)],
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ], [
            'jenis_akun.required' => 'Pilih jenis akun terlebih dahulu.',
            'username.required' => 'Nama pengguna / NISN wajib diisi.',
            'password.required' => 'Kata sandi / PIN wajib diisi.',
        ]);

        $this->ensureIsNotRateLimited($request);

        $pengguna = Pengguna::query()
            ->where('jenis_akun', $credentials['jenis_akun'])
            ->where('username', trim((string) $credentials['username']))
            ->where('aktif', true)
            ->first();

        $sandiCocok = $pengguna instanceof Pengguna
            && hash_equals((string) $pengguna->password_hash, (string) $credentials['password']);

        if (! $sandiCocok) {
            RateLimiter::hit($this->throttleKey($request));

            throw ValidationException::withMessages([
                'username' => 'Nama pengguna atau kata sandi salah.',
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));

        Auth::login($pengguna, $request->boolean('remember'));
        $request->session()->regenerate();

        $pengguna->forceFill(['terakhir_login' => now()])->save();

        return redirect()->route('peran.pilih');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * @throws ValidationException
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'username' => "Terlalu banyak percobaan masuk. Coba lagi dalam {$seconds} detik.",
        ]);
    }

    protected function throttleKey(Request $request): string
    {
        $username = Str::transliterate(Str::lower(trim((string) $request->input('username'))));

        return $username . '|' . $request->ip();
    }
}
