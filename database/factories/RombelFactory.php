<?php

namespace Database\Factories;

use App\Models\Rombel;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rombel>
 */
class RombelFactory extends Factory
{
    protected $model = Rombel::class;

    public function definition(): array
    {
        $tingkat = $this->faker->numberBetween(1, 6);
        $huruf = strtoupper($this->faker->unique()->bothify('?##'));

        return [
            'tahun_ajaran_id' => TahunAjaran::factory(),
            'tingkat' => $tingkat,
            'nama' => "{$tingkat}{$huruf}",
            'wali_kelas_id' => null,
        ];
    }
}
