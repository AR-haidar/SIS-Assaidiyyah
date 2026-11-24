<?php

namespace Tests\Feature\L4;

use App\Enums\JenisAkun;
use App\Enums\JenisKelamin;
use App\Enums\StatusDataSiswa;
use App\Enums\StatusVerifikasi;
use App\Enums\SumberPembayaran;
use App\Models\JenisTagihan;
use App\Models\Pembayaran;
use App\Models\Pengguna;
use App\Models\Peran;
use App\Models\Referensi;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KeuanganTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{0: Pengguna, 1: Pengguna, 2: Siswa, 3: TahunAjaran, 4: Referensi}
     */
    private function siapkanDataKeuangan(): array
    {
        $peranKeuangan = Peran::query()->firstOrCreate(['kode' => 'keuangan'], ['nama' => 'Keuangan']);
        $peranSiswa = Peran::query()->firstOrCreate(['kode' => 'siswa'], ['nama' => 'Siswa']);

        $stafKeuangan = Pengguna::query()->create([
            'username' => 'staf_keuangan_l4',
            'password_hash' => Hash::make('password123'),
            'jenis_akun' => JenisAkun::Staf,
            'nama_lengkap' => 'Staf Keuangan L4',
            'aktif' => true,
        ]);
        $stafKeuangan->peran()->syncWithoutDetaching([$peranKeuangan->getKey()]);

        $akunSiswa = Pengguna::query()->create([
            'username' => '5111111111',
            'password_hash' => Hash::make('123456'),
            'jenis_akun' => JenisAkun::Siswa,
            'nama_lengkap' => 'Siswa Uji Keuangan',
            'aktif' => true,
        ]);
        $akunSiswa->peran()->syncWithoutDetaching([$peranSiswa->getKey()]);

        $siswa = Siswa::query()->create([
            'pengguna_id' => $akunSiswa->getKey(),
            'nama_lengkap' => 'Siswa Uji Keuangan',
            'nisn' => '5111111111',
            'nik' => '3203015111111111',
            'jenis_kelamin' => JenisKelamin::L,
            'status_data' => StatusDataSiswa::Terverifikasi,
        ]);

        $ta = TahunAjaran::query()->create([
            'kode' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
            'aktif' => true,
        ]);

        $metodeTransfer = Referensi::query()->firstOrCreate(
            ['kelompok' => 'metode_pembayaran', 'kode' => 'transfer_bank'],
            ['label' => 'Transfer Bank', 'urutan' => 1, 'aktif' => true],
        );

        return [$stafKeuangan, $akunSiswa, $siswa, $ta, $metodeTransfer];
    }

    public function test_pembuatan_tagihan_massal_tanpa_duplikasi_dan_pengajuan_serta_verifikasi_pembayaran(): void
    {
        Storage::fake('local');

        [$stafKeuangan, $akunSiswa, $siswa, $ta, $metodeTransfer] = $this->siapkanDataKeuangan();

        $this->actingAs($stafKeuangan)
            ->withSession(['peran_aktif' => 'keuangan'])
            ->post(route('keuangan.jenis-tagihan.store'), [
                'nama' => 'SPP Bulanan',
                'bulanan' => true,
                'aktif' => true,
            ])
            ->assertRedirect()
            ->assertSessionHas('status');

        $jenisSpp = JenisTagihan::query()->where('nama', 'SPP Bulanan')->firstOrFail();

        $payloadTagihanNov = [
            'jenis_tagihan_id' => $jenisSpp->getKey(),
            'tahun_ajaran_id' => $ta->getKey(),
            'siswa_ids' => [$siswa->getKey()],
            'periode_bulan' => '2026-11-15',
            'nominal' => 250000,
            'jatuh_tempo' => '2026-11-25',
        ];

        $this->actingAs($stafKeuangan)
            ->withSession(['peran_aktif' => 'keuangan'])
            ->post(route('keuangan.tagihan.massal'), $payloadTagihanNov)
            ->assertRedirect();

        // Pemanggilan ulang pada periode bulan yang sama tidak menggandakan tagihan
        $this->actingAs($stafKeuangan)
            ->withSession(['peran_aktif' => 'keuangan'])
            ->post(route('keuangan.tagihan.massal'), $payloadTagihanNov)
            ->assertRedirect();

        $this->assertDatabaseCount('tagihan', 1);

        $tagihanNov = Tagihan::query()->firstOrFail();
        $this->assertSame('2026-11-01', $tagihanNov->periode_bulan?->format('Y-m-d'));

        // Siswa mengajukan pembayaran dengan bukti transfer
        $this->actingAs($akunSiswa)
            ->withSession(['peran_aktif' => 'siswa'])
            ->post(route('siswa.tagihan.ajukan-pembayaran', $tagihanNov), [
                'nominal' => 250000,
                'metode_pembayaran_id' => $metodeTransfer->getKey(),
                'bukti_berkas' => UploadedFile::fake()->create('bukti-spp.pdf', 120, 'application/pdf'),
            ])
            ->assertRedirect()
            ->assertSessionHas('status');

        $pembayaran = Pembayaran::query()->where('tagihan_id', $tagihanNov->getKey())->firstOrFail();
        $this->assertSame(SumberPembayaran::PengajuanWali, $pembayaran->sumber);
        $this->assertSame(StatusVerifikasi::Menunggu, $pembayaran->status_verifikasi);
        $this->assertNotNull($pembayaran->bukti_berkas_id);

        // Staf keuangan menyetujui pembayaran
        $this->actingAs($stafKeuangan)
            ->withSession(['peran_aktif' => 'keuangan'])
            ->post(route('keuangan.pembayaran.verifikasi', $pembayaran), [
                'status_verifikasi' => StatusVerifikasi::Disetujui->value,
                'tanggal_pembayaran' => '2026-11-23',
            ])
            ->assertRedirect()
            ->assertSessionHas('status');

        $pembayaran->refresh();
        $this->assertSame(StatusVerifikasi::Disetujui, $pembayaran->status_verifikasi);
        $this->assertSame($stafKeuangan->getKey(), $pembayaran->diverifikasi_oleh);

        // Pembayaran kedua pada tagihan yang sudah disetujui harus ditolak validasi
        $this->actingAs($stafKeuangan)
            ->withSession(['peran_aktif' => 'keuangan'])
            ->post(route('keuangan.tagihan.pembayaran.store', $tagihanNov), [
                'nominal' => 10000,
                'metode_pembayaran_id' => $metodeTransfer->getKey(),
                'langsung_setujui' => true,
            ])
            ->assertSessionHasErrors('nominal');

        // Buat tagihan periode berikutnya dan uji input langsung oleh petugas
        $this->actingAs($stafKeuangan)
            ->withSession(['peran_aktif' => 'keuangan'])
            ->post(route('keuangan.tagihan.massal'), [
                'jenis_tagihan_id' => $jenisSpp->getKey(),
                'tahun_ajaran_id' => $ta->getKey(),
                'siswa_ids' => [$siswa->getKey()],
                'periode_bulan' => '2026-12-01',
                'nominal' => 250000,
                'jatuh_tempo' => '2026-12-20',
            ])
            ->assertRedirect();

        $tagihanDes = Tagihan::query()->whereDate('periode_bulan', '2026-12-01')->firstOrFail();

        $this->actingAs($stafKeuangan)
            ->withSession(['peran_aktif' => 'keuangan'])
            ->post(route('keuangan.tagihan.pembayaran.store', $tagihanDes), [
                'nominal' => 250000,
                'metode_pembayaran_id' => $metodeTransfer->getKey(),
                'langsung_setujui' => true,
            ])
            ->assertRedirect()
            ->assertSessionHas('status');

        $pembayaranDes = Pembayaran::query()->where('tagihan_id', $tagihanDes->getKey())->firstOrFail();
        $this->assertSame(SumberPembayaran::InputPetugas, $pembayaranDes->sumber);
        $this->assertSame(StatusVerifikasi::Disetujui, $pembayaranDes->status_verifikasi);
    }
}
