<?php

namespace Database\Seeders;

use App\Models\Peran;
use Illuminate\Database\Seeder;

class PeranSeeder extends Seeder
{
    public function run(): void
    {
        $daftarPeran = [
            ['kode' => 'operator', 'nama' => 'Operator'],
            ['kode' => 'guru', 'nama' => 'Guru'],
            ['kode' => 'wali_kelas', 'nama' => 'Wali Kelas'],
            ['kode' => 'kepala', 'nama' => 'Kepala Sekolah'],
            ['kode' => 'keuangan', 'nama' => 'Keuangan'],
            ['kode' => 'pustakawan', 'nama' => 'Pustakawan'],
            ['kode' => 'humas', 'nama' => 'Humas'],
            ['kode' => 'siswa', 'nama' => 'Siswa'],
        ];

        foreach ($daftarPeran as $item) {
            Peran::query()->updateOrCreate(
                ['kode' => $item['kode']],
                ['nama' => $item['nama']]
            );
        }
    }
}
