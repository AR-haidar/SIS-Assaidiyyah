<?php

namespace Database\Factories;

use App\Enums\NamaSemester;
use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Semester>
 */
class SemesterFactory extends Factory
{
    protected $model = Semester::class;

    public function definition(): array
    {
        return [
            'tahun_ajaran_id' => TahunAjaran::factory(),
            'nama' => $this->faker->randomElement(NamaSemester::cases()),
            'tanggal_mulai' => now()->startOfMonth()->toDateString(),
            'tanggal_selesai' => now()->addMonths(5)->endOfMonth()->toDateString(),
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
