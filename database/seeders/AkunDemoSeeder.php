<?php

namespace Database\Seeders;

use App\Enums\JenisAkun;
use App\Enums\JenisKelamin;
use App\Enums\StatusDataSiswa;
use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\Peran;
use App\Models\Siswa;
use Illuminate\Database\Seeder;

class AkunDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local')) {
            return;
        }

        $peranByKode = Peran::query()->pluck('id', 'kode');

        $akunStaf = [
            [
                'username' => 'operator1',
                'password_hash' => 'password',
                'nama_lengkap' => 'Operator Tata Usaha',
                'peran' => 'operator',
                'pegawai' => null,
            ],
            [
                'username' => 'keuangan1',
                'password_hash' => 'password',
                'nama_lengkap' => 'Bendahara / Keuangan',
                'peran' => 'keuangan',
                'pegawai' => null,
            ],
            [
                'username' => 'pustakawan1',
                'password_hash' => 'password',
                'nama_lengkap' => 'Pustakawan Madrasah',
                'peran' => 'pustakawan',
                'pegawai' => null,
            ],
            [
                'username' => 'kepala1',
                'password_hash' => 'password',
                'nama_lengkap' => 'Kepala Madrasah',
                'peran' => 'kepala',
                'pegawai' => null,
            ],
            [
                'username' => 'humas1',
                'password_hash' => 'password',
                'nama_lengkap' => 'Humas Madrasah',
                'peran' => 'humas',
                'pegawai' => null,
            ],
            [
                'username' => 'guru1',
                'password_hash' => 'password',
                'nama_lengkap' => 'Ustadz Ahmad Fauzi',
                'peran' => 'guru',
                'pegawai' => [
                    'nip' => '198501012010011001',
                    'nama_lengkap' => 'Ustadz Ahmad Fauzi',
                    'mata_pelajaran_diampu' => 'Fiqih & Bahasa Arab',
                ],
            ],
            [
                'username' => 'walikelas1',
                'password_hash' => 'password',
                'nama_lengkap' => 'Ustadzah Siti Aminah',
                'peran' => 'wali_kelas',
                'pegawai' => [
                    'nip' => '199002022015022001',
                    'nama_lengkap' => 'Ustadzah Siti Aminah',
                    'mata_pelajaran_diampu' => 'Tematik Kelas',
                ],
            ],
        ];

        foreach ($akunStaf as $item) {
            $pengguna = Pengguna::withTrashed()->updateOrCreate(
                ['username' => $item['username']],
                [
                    'password_hash' => $item['password_hash'],
                    'jenis_akun' => JenisAkun::Staf,
                    'nama_lengkap' => $item['nama_lengkap'],
                    'aktif' => true,
                    'dihapus_pada' => null,
                ]
            );

            $peranId = $peranByKode->get($item['peran']);
            if ($peranId !== null) {
                $pengguna->peran()->sync([$peranId]);
            }

            if (is_array($item['pegawai'])) {
                Pegawai::withTrashed()->updateOrCreate(
                    ['pengguna_id' => $pengguna->id],
                    [
                        'nip' => $item['pegawai']['nip'],
                        'nama_lengkap' => $item['pegawai']['nama_lengkap'],
                        'mata_pelajaran_diampu' => $item['pegawai']['mata_pelajaran_diampu'],
                        'dihapus_pada' => null,
                    ]
                );
            }
        }

        $siswaPengguna = Pengguna::withTrashed()->updateOrCreate(
            ['username' => '0012345678'],
            [
                'password_hash' => '123456',
                'jenis_akun' => JenisAkun::Siswa,
                'nama_lengkap' => null,
                'aktif' => true,
                'dihapus_pada' => null,
            ]
        );

        $peranSiswaId = $peranByKode->get('siswa');
        if ($peranSiswaId !== null) {
            $siswaPengguna->peran()->sync([$peranSiswaId]);
        }

        Siswa::withTrashed()->updateOrCreate(
            ['pengguna_id' => $siswaPengguna->id],
            [
                'nis' => '2026001',
                'nisn' => '0012345678',
                'nama_lengkap' => 'Muhammad Rizky Pratama',
                'jenis_kelamin' => JenisKelamin::L,
                'status_data' => StatusDataSiswa::Terverifikasi,
                'dihapus_pada' => null,
            ]
        );
    }
}