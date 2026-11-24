<?php

namespace Tests\Feature\L3;

use App\Enums\JenisAkun;
use App\Enums\JenisKelamin;
use App\Enums\StatusDataSiswa;
use App\Enums\StatusPresensiPegawai;
use App\Enums\StatusPresensiSiswa;
use App\Enums\StatusValidasiPresensi;
use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\Peran;
use App\Models\PresensiPegawai;
use App\Models\PresensiSiswa;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\SiswaRombel;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PresensiTest extends TestCase
{
    use RefreshDatabase;

    public function test_pencatatan_dan_pembaruan_presensi_siswa_harian(): void
    {
        $peranGuru = Peran::query()->firstOrCreate(['kode' => 'guru'], ['nama' => 'Guru']);
        $guruUser = Pengguna::query()->create([
            'username' => 'guru_l3',
            'password_hash' => Hash::make('password123'),
            'jenis_akun' => JenisAkun::Staf,
            'nama_lengkap' => 'Guru Uji L3',
            'aktif' => true,
        ]);
        $guruUser->peran()->syncWithoutDetaching([$peranGuru->getKey()]);

        $ta = TahunAjaran::query()->create([
            'kode' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
            'aktif' => true,
        ]);

        $rombel = Rombel::query()->create([
            'tahun_ajaran_id' => $ta->getKey(),
            'tingkat' => 2,
            'nama' => '2A',
        ]);

        $siswa1 = Siswa::query()->create([
            'nama_lengkap' => 'Siswa Satu',
            'nisn' => '1111111111',
            'nik' => '3203011111111111',
            'jenis_kelamin' => JenisKelamin::L,
            'status_data' => StatusDataSiswa::Terverifikasi,
        ]);

        $siswa2 = Siswa::query()->create([
            'nama_lengkap' => 'Siswa Dua',
            'nisn' => '2222222222',
            'nik' => '3203012222222222',
            'jenis_kelamin' => JenisKelamin::P,
            'status_data' => StatusDataSiswa::Terverifikasi,
        ]);

        SiswaRombel::query()->create(['siswa_id' => $siswa1->getKey(), 'rombel_id' => $rombel->getKey()]);
        SiswaRombel::query()->create(['siswa_id' => $siswa2->getKey(), 'rombel_id' => $rombel->getKey()]);

        $tanggal = '2026-11-23';

        $this->actingAs($guruUser)
            ->withSession(['peran_aktif' => 'guru'])
            ->post(route('guru.presensi.siswa.store'), [
                'tanggal' => $tanggal,
                'presensi' => [
                    ['siswa_id' => $siswa1->getKey(), 'status' => StatusPresensiSiswa::Hadir->value],
                    ['siswa_id' => $siswa2->getKey(), 'status' => StatusPresensiSiswa::Sakit->value, 'keterangan' => 'Demam'],
                ],
            ])
            ->assertRedirect()
            ->assertSessionHas('status');

        $this->assertDatabaseCount('presensi_siswa', 2);

        $this->actingAs($guruUser)
            ->withSession(['peran_aktif' => 'guru'])
            ->post(route('guru.presensi.siswa.store'), [
                'tanggal' => $tanggal,
                'presensi' => [
                    ['siswa_id' => $siswa2->getKey(), 'status' => StatusPresensiSiswa::Hadir->value, 'keterangan' => null],
                ],
            ])
            ->assertRedirect();

        $recordSiswa2 = PresensiSiswa::query()
            ->where('siswa_id', $siswa2->getKey())
            ->whereDate('tanggal', $tanggal)
            ->first();

        $this->assertNotNull($recordSiswa2);
        $this->assertSame(StatusPresensiSiswa::Hadir, $recordSiswa2->status);
    }

    public function test_pencatatan_dan_validasi_presensi_pegawai(): void
    {
        $peranGuru = Peran::query()->firstOrCreate(['kode' => 'guru'], ['nama' => 'Guru']);
        $peranOperator = Peran::query()->firstOrCreate(['kode' => 'operator'], ['nama' => 'Operator']);

        $guruUser = Pengguna::query()->create([
            'username' => 'guru_absen',
            'password_hash' => Hash::make('password123'),
            'jenis_akun' => JenisAkun::Staf,
            'nama_lengkap' => 'Guru Absen',
            'aktif' => true,
        ]);
        $guruUser->peran()->syncWithoutDetaching([$peranGuru->getKey()]);

        $operatorUser = Pengguna::query()->create([
            'username' => 'operator_validasi',
            'password_hash' => Hash::make('password123'),
            'jenis_akun' => JenisAkun::Staf,
            'nama_lengkap' => 'Operator Validasi',
            'aktif' => true,
        ]);
        $operatorUser->peran()->syncWithoutDetaching([$peranOperator->getKey()]);

        $pegawai = Pegawai::query()->create([
            'pengguna_id' => $guruUser->getKey(),
            'nik' => '3203019999999999',
            'nama_lengkap' => 'Guru Absen',
        ]);

        $this->actingAs($guruUser)
            ->withSession(['peran_aktif' => 'guru'])
            ->post(route('guru.presensi.pegawai.store'), [
                'pegawai_id' => $pegawai->getKey(),
                'tanggal' => '2026-11-23',
                'status' => StatusPresensiPegawai::IzinTugasLuar->value,
                'keterangan' => 'Dinas luar kabupaten',
            ])
            ->assertRedirect()
            ->assertSessionHas('status');

        $presensi = PresensiPegawai::query()->where('pegawai_id', $pegawai->getKey())->first();
        $this->assertNotNull($presensi);
        $this->assertSame(StatusValidasiPresensi::Menunggu, $presensi->status_validasi);

        $this->actingAs($operatorUser)
            ->withSession(['peran_aktif' => 'operator'])
            ->post(route('operator.presensi-pegawai.validasi', $presensi), [
                'status_validasi' => StatusValidasiPresensi::Valid->value,
            ])
            ->assertRedirect()
            ->assertSessionHas('status');

        $presensi->refresh();
        $this->assertSame(StatusValidasiPresensi::Valid, $presensi->status_validasi);
        $this->assertSame($operatorUser->getKey(), $presensi->divalidasi_oleh);
        $this->assertNotNull($presensi->divalidasi_pada);
    }
}
