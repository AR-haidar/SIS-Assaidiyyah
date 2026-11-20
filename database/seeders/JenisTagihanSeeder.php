<?php

namespace Database\Seeders;

use App\Models\JenisTagihan;
use Illuminate\Database\Seeder;

class JenisTagihanSeeder extends Seeder
{
    /**
     * SPP Bulanan dan Daftar Ulang terlihat di prototype.
     * Jenis tagihan lain ditambahkan lewat aplikasi.
     */
    public function run(): void
    {
        JenisTagihan::updateOrCreate(['nama' => 'SPP Bulanan'], ['bulanan' => true, 'aktif' => true]);
        JenisTagihan::updateOrCreate(['nama' => 'Daftar Ulang'], ['bulanan' => false, 'aktif' => true]);
    }
}
