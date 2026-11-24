<?php

namespace Tests\Feature;

use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\Peran;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class DashboardGuruTest extends TestCase
{
    use DatabaseTransactions;

    public function test_ringkasan_hanya_menghitung_presensi_guru_yang_login(): void
    {
        $guru = $this->akun('guru');
        $pegawai = Pegawai::create(['pengguna_id' => $guru->id]);
        $rekan = Pegawai::create(['pengguna_id' => $this->akun('guru')->id]);

        foreach (['hadir', 'hadir', 'izin_tugas_luar', 'sakit'] as $index => $status) {
            $pegawai->presensi()->create([
                'tanggal' => '2026-11-0'.($index + 1),
                'status' => $status,
                'sumber' => 'manual',
            ]);
        }

        $pegawai->presensi()->create([
            'tanggal' => '2026-11-05', 'status' => 'hadir', 'sumber' => 'manual',
        ])->delete();
        $rekan->presensi()->create([
            'tanggal' => '2026-11-01', 'status' => 'sakit', 'sumber' => 'manual',
            'keterangan' => 'Catatan pribadi guru lain',
        ]);

        $this->actingAs($guru)->withSession(['peran_aktif' => 'guru']);
        $this->get('/guru/dashboard')->assertOk()
            ->assertSee('<strong>50 %</strong>', false)
            ->assertSee('<span>TOTAL HARI HADIR KERJA</span><strong>2 <small>Hari</small></strong>', false)
            ->assertSee('<span>IZIN &amp; DINAS LUAR<br>RESMI</span><strong>1 <small>Hari</small></strong>', false)
            ->assertSee('<span>SAKIT / ALASAN MEDIS</span><strong>1 <small>Hari</small></strong>', false)
            ->assertDontSee('Catatan pribadi guru lain');
    }

    public function test_guru_tetap_dapat_membuka_dashboard_tanpa_presensi(): void
    {
        $guru = $this->akun('guru');
        $this->actingAs($guru)->withSession(['peran_aktif' => 'guru']);
        $this->get('/guru')->assertRedirectToRoute('guru.dashboard');
        $this->get('/guru/dashboard')->assertOk()->assertViewHas('hariHadir', 0);

        Pegawai::create(['pengguna_id' => $guru->id]);

        $this->get('/guru/dashboard')->assertOk()
            ->assertSee('<strong>- %</strong>', false)
            ->assertViewHasAll(['hariHadir' => 0, 'hariIzin' => 0, 'hariSakit' => 0]);
    }

    public function test_dashboard_guru_memerlukan_login_dan_peran_guru(): void
    {
        $this->get('/guru/dashboard')->assertRedirectToRoute('login');

        $this->actingAs($this->akun('siswa'))->withSession(['peran_aktif' => 'siswa']);
        $this->get('/guru/dashboard')->assertForbidden();
        $this->withSession(['peran_aktif' => 'guru'])->get('/guru/dashboard')->assertForbidden();
    }

    private function akun(string $kodePeran): Pengguna
    {
        $pengguna = Pengguna::create([
            'username' => (string) Str::uuid(),
            'password_hash' => Str::random(),
            'jenis_akun' => $kodePeran === 'siswa' ? 'siswa' : 'staf',
        ]);
        $peran = Peran::firstOrCreate(['kode' => $kodePeran], ['nama' => ucfirst($kodePeran)]);
        $pengguna->peran()->attach($peran->id);

        return $pengguna;
    }
}
