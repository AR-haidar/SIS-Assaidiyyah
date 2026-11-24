<?php

namespace Database\Factories;

use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TahunAjaran>
 */
class TahunAjaranFactory extends Factory
{
    protected $model = TahunAjaran::class;

    public function definition(): array
    {
        $tahunAwal = $this->faker->unique()->numberBetween(2020, 2035);
        $tahunAkhir = $tahunAwal + 1;

        return [
            'kode' => "{$tahunAwal}/{$tahunAkhir}",
            'tanggal_mulai' => "{$tahunAwal}-07-01",
            'tanggal_selesai' => "{$tahunAkhir}-06-30",
            'aktif' => false,
        ];
    }

    public function aktif(): static
    {
        return $this->state(fn (): array => [
            'aktif' => true,
        ]);
    }
}
