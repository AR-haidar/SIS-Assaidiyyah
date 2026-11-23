<?php

namespace Database\Factories;

use App\Models\Referensi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Referensi>
 */
class ReferensiFactory extends Factory
{
    protected $model = Referensi::class;

    public function definition(): array
    {
        $kode = 'kode_'.$this->faker->unique()->bothify('????_##');

        return [
            'kelompok' => 'status_masuk',
            'kode' => $kode,
            'label' => ucwords(str_replace('_', ' ', $kode)),
            'urutan' => $this->faker->numberBetween(1, 20),
            'aktif' => true,
        ];
    }
}
