<?php

namespace Database\Factories;

use App\Enums\JenisAkun;
use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Pengguna>
 */
class PenggunaFactory extends Factory
{
    protected $model = Pengguna::class;

    public function definition(): array
    {
        return [
            'username' => 'user_'.$this->faker->unique()->bothify('????_####'),
            'password_hash' => Hash::make('password'),
            'jenis_akun' => JenisAkun::Staf,
            'nama_lengkap' => $this->faker->name(),
            'aktif' => true,
            'terakhir_login' => null,
        ];
    }

    public function siswa(): static
    {
        return $this->state(fn (): array => [
            'jenis_akun' => JenisAkun::Siswa,
            'username' => $this->faker->unique()->numerify('##########'),
            'password_hash' => Hash::make('123456'),
            'nama_lengkap' => null,
        ]);
    }

    public function nonaktif(): static
    {
        return $this->state(fn (): array => [
            'aktif' => false,
        ]);
    }
}
