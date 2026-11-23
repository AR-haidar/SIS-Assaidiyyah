<?php

namespace Tests\Feature\L1;

use App\Enums\JenisAkun;
use App\Models\LogAktivitas;
use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\Peran;
use App\Models\Referensi;
use App\Models\Rombel;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AkunPegawaiWaliKelasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['operator', 'guru', 'wali_kelas', 'kepala', 'keuangan', 'pustakawan', 'siswa', 'humas'] as $kode) {
            Peran::query()->firstOrCreate(
                ['kode' => $kode],
                ['nama' => ucwords(str_replace('_', ' ', $kode))]
            );
        }
    }

    /**
     * @param  array<int, string>  $kodePeran
     * @param  array<string, mixed>  $atribut
     */
    private function bikinAkun(array $kodePeran = ['operator'], array $atribut = []): Pengguna
    {
        $pengguna = Pengguna::query()->create(array_merge([
            'username' => 'user_'.uniqid(),
            'password_hash' => Hash::make('password'),
            'jenis_akun' => JenisAkun::Staf->value,
            'nama_lengkap' => 'Pengguna Uji',
            'aktif' => true,
        ], $atribut));

        $peranIds = Peran::query()->whereIn('kode', $kodePeran)->pluck('id')->all();
        $pengguna->peran()->sync($peranIds);

        return $pengguna;
    }

    private function loginSebagai(Pengguna $pengguna, string $peranAktif): self
    {
        $this->actingAs($pengguna)->withSession(['peran_aktif' => $peranAktif]);

        return $this;
    }

    public function test_mengizinkan_pengguna_mengganti_kata_sandi_atau_pin_sendiri(): void
    {
        $staf = $this->bikinAkun(['operator'], [
            'username' => 'staf_uji',
            'password_hash' => Hash::make('sandi-lama-123'),
            'jenis_akun' => JenisAkun::Staf->value,
        ]);

        /** @var TestResponse $resStaf */
        $resStaf = $this->loginSebagai($staf, 'operator')
            ->put(route('sandi.update'), [
                'kata_sandi_saat_ini' => 'sandi-lama-123',
                'kata_sandi_baru' => 'sandi-baru-123',
                'kata_sandi_baru_confirmation' => 'sandi-baru-123',
            ]);
        $resStaf->assertRedirect();

        $staf->refresh();
        $this->assertTrue(Hash::check('sandi-baru-123', $staf->password_hash));

        $siswa = $this->bikinAkun(['siswa'], [
            'username' => '1234567890',
            'password_hash' => Hash::make('111222'),
            'jenis_akun' => JenisAkun::Siswa->value,
        ]);

        /** @var TestResponse $resSiswa */
        $resSiswa = $this->loginSebagai($siswa, 'siswa')
            ->put(route('sandi.update'), [
                'kata_sandi_saat_ini' => '111222',
                'kata_sandi_baru' => '654321',
                'kata_sandi_baru_confirmation' => '654321',
            ]);
        $resSiswa->assertRedirect();

        $siswa->refresh();
        $this->assertTrue(Hash::check('654321', $siswa->password_hash));
    }

    public function test_membuat_akun_staf_baru_dan_memulihkan_akun_staf_terhapus_lembut(): void
    {
        $operator = $this->bikinAkun(['operator']);

        /** @var TestResponse $resBuat */
        $resBuat = $this->loginSebagai($operator, 'operator')
            ->post(route('operator.akun-staf.index'), [
                'username' => 'bendahara_baru',
                'nama_lengkap' => 'Ustadz Ahmad',
                'password' => 'rahasia123',
                'aktif' => true,
                'peran' => ['keuangan'],
            ]);
        $resBuat->assertRedirect(route('operator.akun-staf.index'));

        $akun = Pengguna::query()->where('username', 'bendahara_baru')->firstOrFail();
        $this->assertSame(JenisAkun::Staf, $akun->jenis_akun);
        $this->assertTrue($akun->punyaPeran('keuangan'));

        $akun->delete();
        $this->assertFalse(Pengguna::query()->where('username', 'bendahara_baru')->exists());

        /** @var TestResponse $resPulih */
        $resPulih = $this->loginSebagai($operator, 'operator')
            ->post(route('operator.akun-staf.index'), [
                'username' => 'bendahara_baru',
                'nama_lengkap' => 'Ustadz Ahmad Dipulihkan',
                'password' => 'rahasia456',
                'aktif' => true,
                'peran' => ['operator', 'keuangan'],
            ]);
        $resPulih->assertRedirect(route('operator.akun-staf.index'));

        $dipulihkan = Pengguna::query()->where('username', 'bendahara_baru')->firstOrFail();
        $this->assertSame('Ustadz Ahmad Dipulihkan', $dipulihkan->nama_lengkap);
        $this->assertTrue($dipulihkan->punyaPeran('operator'));
        $this->assertTrue($dipulihkan->punyaPeran('keuangan'));
    }

    public function test_mengelola_status_reset_sandi_dan_log_akun_staf(): void
    {
        $operator = $this->bikinAkun(['operator']);
        $target = $this->bikinAkun(['pustakawan'], ['username' => 'pustakawan_uji', 'aktif' => true]);

        /** @var TestResponse $resStatus */
        $resStatus = $this->loginSebagai($operator, 'operator')
            ->patch(route('operator.akun-staf.status', $target), ['aktif' => false]);
        $resStatus->assertRedirect();

        $this->assertFalse((bool) $target->fresh()->aktif);

        /** @var TestResponse $resReset */
        $resReset = $this->loginSebagai($operator, 'operator')
            ->patch(route('operator.akun-staf.reset-sandi', $target), [
                'password' => 'sandiReset123',
                'password_confirmation' => 'sandiReset123',
            ]);
        $resReset->assertRedirect();

        $this->assertTrue(Hash::check('sandiReset123', $target->fresh()->password_hash));
        $this->assertGreaterThanOrEqual(2, LogAktivitas::query()->where('modul', 'akun')->count());
    }

    public function test_menyimpan_pegawai_baru_dengan_pasfoto_dan_validasi_kelompok_referensi(): void
    {
        Storage::fake('public');

        $operator = $this->bikinAkun(['operator']);
        $akunGuru1 = $this->bikinAkun(['guru'], ['username' => 'guru_aktif_1']);
        $akunGuru2 = $this->bikinAkun(['guru'], ['username' => 'guru_aktif_2']);

        $statusKepegawaian = Referensi::query()->create([
            'kelompok' => 'status_kepegawaian',
            'kode' => 'gty',
            'label' => 'GTY',
            'urutan' => 1,
            'aktif' => true,
        ]);

        $jenisGtk = Referensi::query()->create([
            'kelompok' => 'jenis_gtk',
            'kode' => 'guru_kelas',
            'label' => 'Guru Kelas',
            'urutan' => 1,
            'aktif' => true,
        ]);

        $kelompokSalah = Referensi::query()->create([
            'kelompok' => 'status_masuk',
            'kode' => 'baru',
            'label' => 'Baru',
            'urutan' => 1,
            'aktif' => true,
        ]);

        /** @var TestResponse $resInvalid */
        $resInvalid = $this->loginSebagai($operator, 'operator')
            ->post(route('operator.pegawai.store'), [
                'pengguna_id' => $akunGuru1->id,
                'nip' => '198801012020011001',
                'nama_lengkap' => 'Ustadzah Fatimah',
                'status_kepegawaian_id' => $kelompokSalah->id,
                'jenis_gtk_id' => $jenisGtk->id,
            ]);
        $resInvalid->assertSessionHasErrors('status_kepegawaian_id');

        $pasfoto = UploadedFile::fake()->image('pasfoto.jpg', 300, 400);

        /** @var TestResponse $resValid */
        $resValid = $this->loginSebagai($operator, 'operator')
            ->post(route('operator.pegawai.store'), [
                'pengguna_id' => $akunGuru2->id,
                'nip' => '198801012020011002',
                'nama_lengkap' => 'Ustadzah Fatimah',
                'status_kepegawaian_id' => $statusKepegawaian->id,
                'jenis_gtk_id' => $jenisGtk->id,
                'pasfoto' => $pasfoto,
            ]);
        $resValid->assertRedirect(route('operator.pegawai.index'));
    }

    public function test_menetapkan_dan_melepas_wali_kelas_serta_sinkronisasi_peran(): void
    {
        $operator = $this->bikinAkun(['operator']);
        $akunGuru1 = $this->bikinAkun(['guru'], ['username' => 'guru_wali_1']);
        $akunGuru2 = $this->bikinAkun(['guru'], ['username' => 'guru_wali_2']);

        $statusKep = Referensi::query()->create([
            'kelompok' => 'status_kepegawaian',
            'kode' => 'gty',
            'label' => 'GTY',
            'urutan' => 1,
            'aktif' => true,
        ]);
        $jenisGtk = Referensi::query()->create([
            'kelompok' => 'jenis_gtk',
            'kode' => 'guru_kelas',
            'label' => 'Guru Kelas',
            'urutan' => 1,
            'aktif' => true,
        ]);

        $pegawai1 = Pegawai::query()->create([
            'pengguna_id' => $akunGuru1->id,
            'nama_lengkap' => 'Guru Satu',
            'status_kepegawaian_id' => $statusKep->id,
            'jenis_gtk_id' => $jenisGtk->id,
        ]);
        $pegawai2 = Pegawai::query()->create([
            'pengguna_id' => $akunGuru2->id,
            'nama_lengkap' => 'Guru Dua',
            'status_kepegawaian_id' => $statusKep->id,
            'jenis_gtk_id' => $jenisGtk->id,
        ]);

        $ta = TahunAjaran::query()->create([
            'kode' => '2026/2027',
            'aktif' => true,
        ]);

        $rombel = Rombel::query()->create([
            'tahun_ajaran_id' => $ta->id,
            'tingkat' => 5,
            'nama' => '5A',
        ]);

        /** @var TestResponse $res1 */
        $res1 = $this->loginSebagai($operator, 'operator')
            ->put(route('operator.wali-kelas.update', $rombel), ['wali_kelas_id' => $pegawai1->id]);
        $res1->assertRedirect();

        $this->assertSame($pegawai1->id, $rombel->fresh()->wali_kelas_id);
        $this->assertTrue($akunGuru1->fresh()->punyaPeran('wali_kelas'));

        /** @var TestResponse $res2 */
        $res2 = $this->loginSebagai($operator, 'operator')
            ->put(route('operator.wali-kelas.update', $rombel), ['wali_kelas_id' => $pegawai2->id]);
        $res2->assertRedirect();

        $this->assertSame($pegawai2->id, $rombel->fresh()->wali_kelas_id);
        $this->assertTrue($akunGuru2->fresh()->punyaPeran('wali_kelas'));
        $this->assertFalse($akunGuru1->fresh()->punyaPeran('wali_kelas'));

        /** @var TestResponse $res3 */
        $res3 = $this->loginSebagai($operator, 'operator')
            ->put(route('operator.wali-kelas.update', $rombel), ['wali_kelas_id' => null]);
        $res3->assertRedirect();

        $this->assertNull($rombel->fresh()->wali_kelas_id);
        $this->assertFalse($akunGuru2->fresh()->punyaPeran('wali_kelas'));
    }
}
