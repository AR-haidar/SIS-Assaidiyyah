<?php

namespace Tests\Feature;

use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\Peran;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class RekapAbsensiGuruTest extends TestCase
{
    use DatabaseTransactions;

    public function test_rekap_menghitung_presensi_sendiri_dan_menyaring_riwayat(): void
    {
        $guru = $this->akun('guru');
        $pegawai = Pegawai::create(['pengguna_id' => $guru->id]);
        $rekan = Pegawai::create(['pengguna_id' => $this->akun('guru')->id]);
        $tahun = TahunAjaran::create([
            'kode' => '2080/2081', 'tanggal_mulai' => '2080-07-01', 'tanggal_selesai' => '2081-06-30',
        ]);

        foreach ([
            ['2080-11-01', 'hadir', 'Hadir masih menunggu'],
            ['2080-11-02', 'hadir', 'Hadir kedua'],
            ['2080-11-03', 'izin_tugas_luar', 'Tugas luar sendiri'],
            ['2080-11-04', 'sakit', 'Sakit sendiri'],
            ['2081-01-01', 'hadir', 'Bulan lain tahun ajaran sama'],
            ['2081-11-01', 'hadir', 'Bulan sama tahun ajaran lain'],
        ] as [$tanggal, $status, $keterangan]) {
            $pegawai->presensi()->create([
                'tanggal' => $tanggal, 'status' => $status, 'keterangan' => $keterangan,
                'sumber' => 'manual', 'status_validasi' => 'menunggu',
            ]);
        }
        $pegawai->presensi()->create([
            'tanggal' => '2080-11-05', 'status' => 'hadir', 'sumber' => 'manual', 'keterangan' => 'Presensi dihapus',
        ])->delete();
        $rekan->presensi()->create([
            'tanggal' => '2080-11-04', 'status' => 'sakit', 'sumber' => 'manual', 'keterangan' => 'Catatan pribadi rekan',
        ]);

        $this->actingAs($guru)->withSession(['peran_aktif' => 'guru']);
        $url = 'guru.rekap_absensi.index';
        $periode = ['bulan' => 11, 'tahun_ajaran_id' => $tahun->id];

        $this->get(route($url))->assertOk()->assertSee('6 data dimuat')->assertSee('<strong>67 %</strong>', false)
            ->assertDontSee('Presensi dihapus')->assertDontSee('Catatan pribadi rekan');
        $this->get(route($url, ['tahun_ajaran_id' => $tahun->id]))
            ->assertOk()->assertSee('5 data dimuat')->assertSee('Bulan lain tahun ajaran sama')
            ->assertDontSee('Bulan sama tahun ajaran lain');
        $this->get(route($url, ['bulan' => 11]))
            ->assertOk()->assertSee('5 data dimuat')->assertSee('Bulan sama tahun ajaran lain')
            ->assertDontSee('Bulan lain tahun ajaran sama');

        $this->get(route($url, $periode))->assertOk()
            ->assertViewHasAll(['rataKehadiran' => 50.0, 'hariHadir' => 2, 'hariIzin' => 1, 'hariSakit' => 1])
            ->assertSee('Target madrasah: 95%')->assertSee('Belum tercapai')
            ->assertSee('4 data dimuat')
            ->assertSeeInOrder(['Sakit sendiri', 'Tugas luar sendiri', 'Hadir kedua', 'Hadir masih menunggu'])
            ->assertDontSee('Catatan pribadi rekan')->assertDontSee('Presensi dihapus');

        foreach (['hadir' => 2, 'izin_tugas_luar' => 1, 'sakit' => 1] as $status => $jumlah) {
            $this->get(route($url, [...$periode, 'status' => $status]))->assertOk()
                ->assertSee($jumlah.' data dimuat')
                ->assertSee('value="'.$status.'" selected', false)
                ->assertSee('value="11" selected', false)
                ->assertSee('value="'.$tahun->id.'" selected', false)
                ->assertViewHas('rataKehadiran', 50.0)
                ->assertViewHas('riwayat', fn ($catatan) => $catatan->every(fn ($presensi) => $presensi->status->value === $status));
        }

        $this->get(route($url, ['bulan' => 1, 'tahun_ajaran_id' => $tahun->id]))
            ->assertOk()->assertSee('<strong>100 %</strong>', false)->assertSee('Tercapai');
    }

    public function test_rekap_kosong_dan_filter_tidak_valid_ditangani(): void
    {
        $this->actingAs($this->akun('guru'))->withSession(['peran_aktif' => 'guru']);
        $url = route('guru.rekap_absensi.index');
        $this->get($url)->assertOk()
            ->assertSee('Belum Ada Catatan Kehadiran untuk Periode Terpilih')
            ->assertSee('<strong>- %</strong>', false)->assertSee('0 data dimuat');
        $this->from($url)->get(route('guru.rekap_absensi.index', ['bulan' => 13, 'status' => 'alpa']))
            ->assertRedirect($url)->assertSessionHasErrors(['bulan', 'status']);
    }

    public function test_rekap_memerlukan_login_dan_peran_guru(): void
    {
        $this->get('/guru/rekap-absensi')->assertRedirectToRoute('login');
        $this->actingAs($this->akun('siswa'))->withSession(['peran_aktif' => 'siswa']);
        $this->get('/guru/rekap-absensi')->assertForbidden();
    }

    private function akun(string $kodePeran): Pengguna
    {
        $akun = Pengguna::create([
            'username' => (string) Str::uuid(),
            'password_hash' => Str::random(),
            'jenis_akun' => $kodePeran === 'guru' ? 'staf' : 'siswa',
        ]);
        $akun->peran()->attach(Peran::firstOrCreate(['kode' => $kodePeran], ['nama' => ucfirst($kodePeran)])->id);

        return $akun;
    }
}
