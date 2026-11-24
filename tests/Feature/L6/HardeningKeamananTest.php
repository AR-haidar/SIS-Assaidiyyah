<?php

namespace Tests\Feature\L6;

use App\Enums\JenisAkun;
use App\Models\LogAktivitas;
use App\Models\Pengguna;
use App\Models\Peran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HardeningKeamananTest extends TestCase
{
    use RefreshDatabase;

    private function buatPenggunaDenganPeran(string $kodePeran, string $username): Pengguna
    {
        $peran = Peran::query()->firstOrCreate(
            ['kode' => $kodePeran],
            ['nama' => ucfirst(str_replace('_', ' ', $kodePeran))],
        );

        $jenisAkun = $kodePeran === 'siswa' ? JenisAkun::Siswa : JenisAkun::Staf;

        $pengguna = Pengguna::query()->create([
            'username' => $username,
            'password_hash' => Hash::make('password123'),
            'jenis_akun' => $jenisAkun,
            'nama_lengkap' => 'Pengguna '.$username,
            'aktif' => true,
        ]);

        $pengguna->peran()->syncWithoutDetaching([$peran->getKey()]);

        return $pengguna;
    }

    public function test_pengguna_terautentikasi_tanpa_peran_aktif_diarahkan_ke_pilih_peran(): void
    {
        $operator = $this->buatPenggunaDenganPeran('operator', 'operator_tanpa_sesi');

        $this->actingAs($operator)
            ->get('/operator/pengumuman')
            ->assertRedirect(route('peran.pilih'));
    }

    public function test_akses_lintas_portal_ditolak_dengan_403(): void
    {
        $keuangan = $this->buatPenggunaDenganPeran('keuangan', 'keuangan_test');
        $siswa = $this->buatPenggunaDenganPeran('siswa', 'siswa_test');

        $this->actingAs($keuangan)
            ->withSession(['peran_aktif' => 'keuangan'])
            ->get('/operator/pengumuman')
            ->assertForbidden();

        $this->actingAs($siswa)
            ->withSession(['peran_aktif' => 'siswa'])
            ->get('/humas/prestasi')
            ->assertForbidden();
    }

    public function test_validasi_file_impor_excel_menolak_ekstensi_tidak_diizinkan(): void
    {
        $operator = $this->buatPenggunaDenganPeran('operator', 'operator_validasi_file');
        $fileTidakValid = UploadedFile::fake()->create('script.php', 10, 'text/plain');

        $this->actingAs($operator)
            ->withSession(['peran_aktif' => 'operator'])
            ->post('/operator/impor/siswa', [
                'file' => $fileTidakValid,
            ])
            ->assertSessionHasErrors('file');
    }

    public function test_aksi_penerbitan_pengumuman_mencatat_log_aktivitas(): void
    {
        $operator = $this->buatPenggunaDenganPeran('operator', 'operator_audit_log');

        $this->actingAs($operator)
            ->withSession(['peran_aktif' => 'operator'])
            ->post('/operator/pengumuman', [
                'judul' => 'Pengumuman Audit Keamanan',
                'kategori' => 'pengumuman',
                'nomor_surat' => '421.2/099/MI-ASS',
                'tanggal_terbit' => '2026-11-24',
                'sasaran' => 'Semua Guru & Staf',
                'ringkasan' => 'Uji pencatatan log aktivitas saat menerbitkan pengumuman.',
                'status' => 'terbit',
            ])
            ->assertRedirect(route('operator.pengumuman.index'));

        $this->assertDatabaseHas('log_aktivitas', [
            'pengguna_id' => $operator->getKey(),
            'modul' => 'pengumuman',
        ]);

        $this->assertGreaterThan(0, LogAktivitas::query()->count());
    }
}
