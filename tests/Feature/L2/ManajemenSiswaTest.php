<?php

namespace Tests\Feature\L2;

use App\Enums\JenisAkun;
use App\Enums\JenisBerkasSiswa;
use App\Enums\JenisKelamin;
use App\Enums\StatusDataSiswa;
use App\Models\Berkas;
use App\Models\Pengguna;
use App\Models\Peran;
use App\Models\Referensi;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\SiswaRombel;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ManajemenSiswaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{0: Pengguna, 1: Referensi, 2: Referensi, 3: Referensi}
     */
    private function siapkanOperatorDanReferensiSiswa(): array
    {
        $peranOperator = Peran::query()->firstOrCreate(
            ['kode' => 'operator'],
            ['nama' => 'Operator'],
        );
        Peran::query()->firstOrCreate(
            ['kode' => 'siswa'],
            ['nama' => 'Siswa'],
        );

        $operator = Pengguna::query()->create([
            'username' => 'operator_l2',
            'password_hash' => Hash::make('password123'),
            'jenis_akun' => JenisAkun::Staf,
            'nama_lengkap' => 'Operator Uji L2',
            'aktif' => true,
        ]);
        $operator->peran()->syncWithoutDetaching([$peranOperator->getKey()]);

        $statusMasuk = Referensi::query()->firstOrCreate(
            ['kelompok' => 'status_masuk', 'kode' => 'baru'],
            ['label' => 'Siswa Baru', 'urutan' => 1, 'aktif' => true],
        );

        $statusKesiswaan = Referensi::query()->firstOrCreate(
            ['kelompok' => 'status_kesiswaan', 'kode' => 'aktif'],
            ['label' => 'Aktif', 'urutan' => 1, 'aktif' => true],
        );

        $pekerjaan = Referensi::query()->firstOrCreate(
            ['kelompok' => 'pekerjaan', 'kode' => 'wiraswasta'],
            ['label' => 'Wiraswasta', 'urutan' => 1, 'aktif' => true],
        );

        return [$operator, $statusMasuk, $statusKesiswaan, $pekerjaan];
    }

    public function test_menyimpan_draf_siswa_bertahap_meskipun_data_wajib_verifikasi_belum_lengkap(): void
    {
        [$operator] = $this->siapkanOperatorDanReferensiSiswa();

        $response = $this->actingAs($operator)
            ->withSession(['peran_aktif' => 'operator'])
            ->post(route('operator.siswa.store'), [
                'nama_lengkap' => 'Ahmad Fauzi Draf',
                'alamat' => 'Kp. Cipanas',
            ]);

        $response->assertRedirect();

        $siswa = Siswa::query()->where('nama_lengkap', 'Ahmad Fauzi Draf')->first();
        $this->assertNotNull($siswa);
        $this->assertSame(StatusDataSiswa::Draft, $siswa->status_data);
        $this->assertNull($siswa->nisn);
        $this->assertNull($siswa->nik);
    }

    public function test_menolak_verifikasi_bila_field_wajib_tidak_lengkap_dan_meloloskan_jika_lengkap(): void
    {
        [$operator, $statusMasuk, $statusKesiswaan, $pekerjaan] = $this->siapkanOperatorDanReferensiSiswa();

        $draf = Siswa::query()->create([
            'nama_lengkap' => 'Siswa Belum Lengkap',
            'status_data' => StatusDataSiswa::Draft,
        ]);

        $this->actingAs($operator)
            ->withSession(['peran_aktif' => 'operator'])
            ->post(route('operator.siswa.verifikasi', $draf), [])
            ->assertSessionHasErrors(['nisn', 'nik', 'jenis_kelamin', 'status_masuk_id']);

        $this->assertSame(StatusDataSiswa::Draft, $draf->fresh()?->status_data);

        $this->actingAs($operator)
            ->withSession(['peran_aktif' => 'operator'])
            ->post(route('operator.siswa.verifikasi', $draf), [
                'nama_lengkap' => 'Ahmad Fauzi Lengkap',
                'nisn' => '1234567890',
                'nik' => '3203011234567890',
                'jenis_kelamin' => JenisKelamin::L->value,
                'status_masuk_id' => $statusMasuk->getKey(),
                'status_kesiswaan_id' => $statusKesiswaan->getKey(),
                'nama_ayah' => 'Bapak Fauzi',
                'pekerjaan_ayah_id' => $pekerjaan->getKey(),
                'nama_ibu' => 'Ibu Fauzi',
            ])
            ->assertRedirect(route('operator.siswa.edit', $draf))
            ->assertSessionHas('status');

        $this->assertSame(StatusDataSiswa::Terverifikasi, $draf->fresh()?->status_data);
        $this->assertSame('1234567890', $draf->fresh()?->nisn);
    }

    public function test_mengunggah_dan_mengganti_berkas_siswa_dengan_penyimpanan_terkelola(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        [$operator, $statusMasuk] = $this->siapkanOperatorDanReferensiSiswa();

        $siswa = Siswa::query()->create([
            'nama_lengkap' => 'Siti Aminah',
            'nisn' => '2234567890',
            'nik' => '3203012234567890',
            'jenis_kelamin' => JenisKelamin::P,
            'status_masuk_id' => $statusMasuk->getKey(),
            'status_data' => StatusDataSiswa::Terverifikasi,
        ]);

        $this->actingAs($operator)
            ->withSession(['peran_aktif' => 'operator'])
            ->put(route('operator.siswa.update', $siswa), [
                'nama_lengkap' => 'Siti Aminah',
                'pasfoto' => UploadedFile::fake()->image('foto1.jpg', 300, 400),
                'kartu_keluarga' => UploadedFile::fake()->create('kk1.pdf', 120, 'application/pdf'),
            ])
            ->assertRedirect(route('operator.siswa.edit', $siswa));

        $siswa->load('berkas');
        $this->assertCount(2, $siswa->berkas);

        $kkPertama = $siswa->berkas->firstWhere('pivot.jenis', JenisBerkasSiswa::KartuKeluarga->value);
        $this->assertInstanceOf(Berkas::class, $kkPertama);
        Storage::disk('local')->assertExists($kkPertama->path);

        $this->actingAs($operator)
            ->withSession(['peran_aktif' => 'operator'])
            ->put(route('operator.siswa.update', $siswa), [
                'nama_lengkap' => 'Siti Aminah',
                'kartu_keluarga' => UploadedFile::fake()->create('kk2.pdf', 150, 'application/pdf'),
            ])
            ->assertRedirect(route('operator.siswa.edit', $siswa));

        $siswa->load('berkas');
        $kkKedua = $siswa->berkas->firstWhere('pivot.jenis', JenisBerkasSiswa::KartuKeluarga->value);
        $this->assertInstanceOf(Berkas::class, $kkKedua);
        $this->assertNotSame($kkPertama->getKey(), $kkKedua->getKey());
        Storage::disk('local')->assertMissing($kkPertama->path);
        Storage::disk('local')->assertExists($kkKedua->path);
    }

    public function test_rombel_tunggal_per_tahun_ajaran_dan_pembuatan_serta_reset_pin_akun_siswa(): void
    {
        [$operator, $statusMasuk] = $this->siapkanOperatorDanReferensiSiswa();

        $taAktif = TahunAjaran::query()->create([
            'kode' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
            'aktif' => true,
        ]);

        $rombelA = Rombel::query()->create([
            'tahun_ajaran_id' => $taAktif->getKey(),
            'tingkat' => 1,
            'nama' => '1A',
        ]);

        $rombelB = Rombel::query()->create([
            'tahun_ajaran_id' => $taAktif->getKey(),
            'tingkat' => 1,
            'nama' => '1B',
        ]);

        $siswa = Siswa::query()->create([
            'nama_lengkap' => 'Rizky Pratama',
            'nisn' => '3334567890',
            'nik' => '3203013334567890',
            'jenis_kelamin' => JenisKelamin::L,
            'status_masuk_id' => $statusMasuk->getKey(),
            'status_data' => StatusDataSiswa::Terverifikasi,
        ]);

        $this->actingAs($operator)
            ->withSession(['peran_aktif' => 'operator'])
            ->post(route('operator.siswa.rombel', $siswa), ['rombel_id' => $rombelA->getKey()])
            ->assertRedirect();

        $this->actingAs($operator)
            ->withSession(['peran_aktif' => 'operator'])
            ->post(route('operator.siswa.rombel', $siswa), ['rombel_id' => $rombelB->getKey()])
            ->assertRedirect();

        $keanggotaanAktif = SiswaRombel::query()
            ->where('siswa_id', $siswa->getKey())
            ->whereHas('rombel', fn ($q) => $q->where('tahun_ajaran_id', $taAktif->getKey()))
            ->get();

        $this->assertCount(1, $keanggotaanAktif);
        $this->assertSame((int) $rombelB->getKey(), (int) $keanggotaanAktif->first()?->rombel_id);

        $this->actingAs($operator)
            ->withSession(['peran_aktif' => 'operator'])
            ->post(route('operator.siswa.akun.buat', $siswa), ['pin' => '123456'])
            ->assertRedirect()
            ->assertSessionHas('pin_baru', '123456');

        $siswa->refresh();
        $this->assertNotNull($siswa->pengguna);
        $this->assertSame('3334567890', $siswa->pengguna->username);
        $this->assertSame(JenisAkun::Siswa, $siswa->pengguna->jenis_akun);
        $this->assertTrue(Hash::check('123456', $siswa->pengguna->password_hash));

        $this->actingAs($operator)
            ->withSession(['peran_aktif' => 'operator'])
            ->post(route('operator.siswa.akun.reset-pin', $siswa), ['pin' => '654321'])
            ->assertRedirect()
            ->assertSessionHas('pin_baru', '654321');

        $this->assertTrue(Hash::check('654321', $siswa->pengguna->fresh()?->password_hash ?? ''));
    }
}
