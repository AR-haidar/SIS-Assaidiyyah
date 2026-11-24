<?php

use App\Enums\JenisAkun;
use App\Models\Pengguna;
use App\Models\Peran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/**
 * Membuat akun Pengguna lengkap dengan attach peran (otomatis membuat baris Peran bila belum ada).
 *
 * @param  array<int, string>  $kodePeran
 * @param  array<string, mixed>  $atribut
 */
function buatAkun(array $kodePeran = ['operator'], array $atribut = []): Pengguna
{
    $jenisAkun = $atribut['jenis_akun'] ?? (
        $kodePeran === ['siswa'] ? JenisAkun::Siswa : JenisAkun::Staf
    );

    $kataSandiMentah = $atribut['password'] ?? ($jenisAkun === JenisAkun::Siswa ? '123456' : 'password');
    unset($atribut['password']);

    $pengguna = Pengguna::factory()->create(array_merge([
        'jenis_akun' => $jenisAkun,
        'password_hash' => Hash::make($kataSandiMentah),
        'aktif' => true,
    ], $atribut));

    if ($kodePeran !== []) {
        $peranIds = [];
        foreach ($kodePeran as $kode) {
            $peran = Peran::query()->firstOrCreate(
                ['kode' => $kode],
                ['nama' => ucwords(str_replace('_', ' ', $kode))]
            );
            $peranIds[] = $peran->id;
        }

        $pengguna->peran()->syncWithoutDetaching($peranIds);
        $pengguna->load('peran');
    }

    return $pengguna;
}

/**
 * Mengatur autentikasi sebagai $pengguna dengan peran_aktif di sesi untuk satu request test.
 *
 * @return Illuminate\Testing\TestCase
 */
function sebagai(Pengguna $pengguna, string $peranAktif): mixed
{
    return test()
        ->actingAs($pengguna)
        ->withSession(['peran_aktif' => $peranAktif]);
}
