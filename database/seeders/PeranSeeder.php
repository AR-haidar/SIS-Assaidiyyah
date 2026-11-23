<?php

namespace Database\Seeders;

use App\Models\Peran;
use Illuminate\Database\Seeder;

class PeranSeeder extends Seeder
{
    /**
     * Daftar peran adalah asumsi berdasarkan prototype.
     * Tata Usaha dipetakan ke peran operator (sesuai konfirmasi).
     */
    public function run(): void
    {
        $peran = [
            'operator' => 'Operator / Tata Usaha',
            'guru' => 'Guru',
            'wali_kelas' => 'Wali Kelas',
            'kepala' => 'Kepala Madrasah',
            'keuangan' => 'Keuangan',
            'pustakawan' => 'Pustakawan',
            'siswa' => 'Siswa / Orang Tua',
            'humas' => 'Humas',
        ];

        foreach ($peran as $kode => $nama) {
            Peran::updateOrCreate(['kode' => $kode], ['nama' => $nama]);
        }
    }
}
