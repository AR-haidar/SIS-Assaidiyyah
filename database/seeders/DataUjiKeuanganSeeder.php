<?php

namespace Database\Seeders;

use App\Enums\StatusDataSiswa;
use App\Enums\StatusVerifikasi;
use App\Enums\SumberPembayaran;
use App\Models\Berkas;
use App\Models\JenisTagihan;
use App\Models\Pengguna;
use App\Models\Referensi;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Data uji modul Keuangan. Hanya untuk lingkungan lokal.
 * Jalankan: php artisan db:seed --class=DataUjiKeuanganSeeder
 */
class DataUjiKeuanganSeeder extends Seeder
{
    // PNG 1x1 piksel sebagai bukti pembayaran contoh.
    private const PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';

    public function run(): void
    {
        if (! app()->environment('local')) {
            $this->command->warn('Seeder ini hanya untuk lingkungan local.');
            return;
        }

        $petugas = Pengguna::query()->first();
        $aktif = Referensi::where('kelompok', 'status_kesiswaan')->where('kode', 'aktif')->first();
        $transfer = Referensi::where('kelompok', 'metode_pembayaran')->where('kode', 'transfer_bank')->first();
        $spp = JenisTagihan::where('bulanan', true)->first();
        $daftarUlang = JenisTagihan::where('bulanan', false)->first();

        if (! $petugas || ! $aktif || ! $transfer || ! $spp || ! $daftarUlang) {
            $this->command->error('Data dasar belum ada. Jalankan dulu: php artisan db:seed');
            return;
        }

        $tahun = TahunAjaran::aktif()->first()
            ?? TahunAjaran::query()->first()
            ?? TahunAjaran::create([
                'kode'            => '2026/2027',
                'tanggal_mulai'   => '2026-07-13',
                'tanggal_selesai' => '2027-06-30',
                'aktif'           => true,
            ]);

        $rombel = Rombel::firstOrCreate(
            ['tahun_ajaran_id' => $tahun->id, 'nama' => '1A'],
            ['tingkat' => 1],
        );

        $siswa = [];
        foreach ([
            ['9900000001', 'Ahmad Fauzi'],
            ['9900000002', 'Siti Aminah'],
            ['9900000003', 'Muhammad Rizki'],
            ['9900000004', 'Nur Aisyah'],
        ] as [$nisn, $nama]) {
            $s = Siswa::firstOrCreate(
                ['nisn' => $nisn],
                [
                    'nis'                 => 'DEMO-' . substr($nisn, -4),
                    'nama_lengkap'        => $nama,
                    'status_data'         => StatusDataSiswa::Terverifikasi,
                    'status_kesiswaan_id' => $aktif->id,
                ],
            );
            $s->rombel()->syncWithoutDetaching([$rombel->id]);
            $siswa[] = $s;
        }

        // Berkas bukti contoh (file sungguhan di disk public).
        $pathBukti = 'bukti-pembayaran/demo-bukti.png';
        Storage::disk('public')->put($pathBukti, base64_decode(self::PNG));
        $bukti = Berkas::firstOrCreate(
            ['path' => $pathBukti],
            [
                'nama_asli'     => 'demo-bukti.png',
                'mime'          => 'image/png',
                'ukuran_byte'   => Storage::disk('public')->size($pathBukti),
                'diunggah_oleh' => $petugas->id,
            ],
        );

        $bulan = now()->startOfMonth();

        $tagihanSpp = [];
        foreach ($siswa as $i => $s) {
            $tagihanSpp[$i] = Tagihan::firstOrCreate(
                [
                    'siswa_id'         => $s->id,
                    'jenis_tagihan_id' => $spp->id,
                    'tahun_ajaran_id'  => $tahun->id,
                    'periode_bulan'    => $bulan->toDateString(),
                ],
                ['nominal' => 150000, 'jatuh_tempo' => $bulan->copy()->addDays(9)->toDateString()],
            );
        }

        $tagihanDaftarUlang = Tagihan::firstOrCreate(
            [
                'siswa_id'         => $siswa[0]->id,
                'jenis_tagihan_id' => $daftarUlang->id,
                'tahun_ajaran_id'  => $tahun->id,
                'periode_bulan'    => null,
            ],
            ['nominal' => 500000, 'jatuh_tempo' => $bulan->copy()->addMonth()->toDateString()],
        );

        $buat = function (Tagihan $t, StatusVerifikasi $status, SumberPembayaran $sumber, array $ekstra = []) use ($transfer, $bukti): void {
            if ($t->pembayaran()->exists()) {
                return; // idempoten: jangan dobel kalau seeder dijalankan ulang
            }

            $t->pembayaran()->create(array_merge([
                'nominal'              => $t->nominal,
                'metode_pembayaran_id' => $transfer->id,
                'tanggal_pembayaran'   => now()->toDateString(),
                'sumber'               => $sumber,
                'bukti_berkas_id'      => $bukti->id,
                'status_verifikasi'    => $status,
            ], $ekstra));
        };

        // Siswa 1: dua pengajuan menunggu (SPP dan Daftar Ulang) -> tampil di antrean dashboard.
        $buat($tagihanSpp[0], StatusVerifikasi::Menunggu, SumberPembayaran::PengajuanWali);
        $buat($tagihanDaftarUlang, StatusVerifikasi::Menunggu, SumberPembayaran::PengajuanWali);

        // Siswa 2: sudah disetujui (diinput petugas).
        $buat($tagihanSpp[1], StatusVerifikasi::Disetujui, SumberPembayaran::InputPetugas, [
            'diverifikasi_oleh' => $petugas->id,
            'diverifikasi_pada' => now(),
        ]);

        // Siswa 3: ditolak dengan alasan.
        $buat($tagihanSpp[2], StatusVerifikasi::Ditolak, SumberPembayaran::PengajuanWali, [
            'diverifikasi_oleh' => $petugas->id,
            'diverifikasi_pada' => now(),
            'alasan_penolakan'  => 'Bukti transfer tidak terbaca.',
        ]);

        // Siswa 4: belum membayar (tidak ada pembayaran).

        $this->command->info('Data uji keuangan siap: 4 siswa, 5 tagihan, 4 pembayaran.');
    }
}