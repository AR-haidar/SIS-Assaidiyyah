<?php

namespace Database\Seeders;

use App\Models\Referensi;
use Illuminate\Database\Seeder;

class ReferensiSeeder extends Seeder
{
    /**
     * Seluruh nilai di bawah ini adalah asumsi awal karena nilainya
     * belum terlihat di prototype. Sesuaikan dengan kebutuhan madrasah.
     */
    public function run(): void
    {
        $data = [
            'status_masuk' => [
                'baru' => 'Siswa Baru',
                'pindahan' => 'Pindahan',
            ],
            'status_kesiswaan' => [
                'aktif' => 'Aktif',
                'lulus' => 'Lulus',
                'pindah' => 'Pindah',
                'keluar' => 'Keluar',
                'tidak_aktif' => 'Tidak Aktif',
            ],
            'status_kepegawaian' => [
                'pns' => 'PNS',
                'pppk' => 'PPPK',
                'gty' => 'Guru Tetap Yayasan',
                'gtt' => 'Guru Tidak Tetap',
                'honorer' => 'Honorer',
                'non_pns' => 'Non PNS',
            ],
            'jenis_gtk' => [
                'guru_kelas' => 'Guru Kelas',
                'guru_mapel' => 'Guru Mata Pelajaran',
                'tenaga_kependidikan' => 'Tenaga Kependidikan',
                'kepala_madrasah' => 'Kepala Madrasah',
                'tata_usaha' => 'Tata Usaha',
                'operator_pendataan' => 'Operator Pendataan',
            ],
            'pendidikan_terakhir' => [
                'sma' => 'SMA/MA/Sederajat',
                'd3' => 'D3',
                's1' => 'S1',
                's2' => 'S2',
                's3' => 'S3',
            ],
            'pekerjaan' => [
                'pns_tni_polri' => 'PNS/TNI/Polri',
                'karyawan_swasta' => 'Karyawan Swasta',
                'wiraswasta' => 'Wiraswasta',
                'petani' => 'Petani',
                'buruh' => 'Buruh',
                'pedagang' => 'Pedagang',
                'lainnya' => 'Lainnya',
            ],
            'kategori_pengumuman' => [
                'pengumuman' => 'Pengumuman',
                'surat_edaran' => 'Surat Edaran',
                'berita' => 'Berita',
                'agenda' => 'Agenda',
            ],
            'metode_pembayaran' => [
                'transfer_bank' => 'Transfer Bank',
                'tunai' => 'Tunai',
                'e_wallet' => 'E-Wallet',
            ],
        ];

        foreach ($data as $kelompok => $items) {
            $urutan = 1;
            foreach ($items as $kode => $label) {
                Referensi::updateOrCreate(
                    ['kelompok' => $kelompok, 'kode' => $kode],
                    ['label' => $label, 'urutan' => $urutan++, 'aktif' => true],
                );
            }
        }
    }
}
