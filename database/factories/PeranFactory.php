<?php

namespace Database\Factories;

use App\Models\Peran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Peran>
 */
class PeranFactory extends Factory
{
    protected $model = Peran::class;

    public function definition(): array
    {
        $kode = 'peran_'.$this->faker->unique()->bothify('????_##');

        return [
            'kode' => $kode,
            'nama' => ucwords(str_replace('_', ' ', $kode)),
        ];
    }
}
