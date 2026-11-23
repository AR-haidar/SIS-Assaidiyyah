<?php

namespace Database\Factories;

use App\Models\Berkas;
use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Berkas>
 */
class BerkasFactory extends Factory
{
    protected $model = Berkas::class;

    public function definition(): array
    {
        $uuid = Str::uuid()->toString();

        return [
            'nama_asli' => 'dokumen-'.$this->faker->bothify('??##').'.pdf',
            'path' => 'umum/'.$uuid.'.pdf',
            'mime' => 'application/pdf',
            'ukuran_byte' => $this->faker->numberBetween(1024, 512000),
            'diunggah_oleh' => Pengguna::factory(),
            'diunggah_pada' => now(),
        ];
    }
}
