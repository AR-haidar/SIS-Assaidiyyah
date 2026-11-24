<?php

namespace Tests\Feature;

use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\Pengumuman;
use App\Models\Peran;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class NavigasiPortalTest extends TestCase
{
    use DatabaseTransactions;

    #[DataProvider('halamanPortal')]
    public function test_menu_portal_memuat_halaman_dengan_peran_yang_sesuai(string $peran, array $halaman): void
    {
        $akun = Pengguna::create([
            'username' => (string) Str::uuid(),
            'password_hash' => Str::random(),
            'jenis_akun' => $peran === 'siswa' ? 'siswa' : 'staf',
        ]);
        $akun->peran()->attach(Peran::firstOrCreate(['kode' => $peran], ['nama' => ucfirst($peran)]));
        $this->actingAs($akun)->withSession(['peran_aktif' => $peran]);

        if ($peran === 'operator' || $peran === 'siswa') {
            $siswa = Siswa::create([
                'nama_lengkap' => 'Siswa navigasi',
                'pengguna_id' => $peran === 'siswa' ? $akun->id : null,
            ]);
        }

        foreach ($halaman as $url) {
            $this->get($url)->assertOk();
        }

        if ($peran === 'operator') {
            $this->get('/operator/data-siswa/'.$siswa->id.'/edit')->assertOk()->assertViewHas('siswa', fn (Siswa $hasil) => $hasil->is($siswa));
        }

        if ($peran === 'keuangan') {
            $pengumuman = Pengumuman::create(['judul' => 'Pengumuman navigasi', 'dibuat_oleh' => $akun->id]);
            $this->get(route('keuangan.pengumuman.edit', $pengumuman))->assertOk();
            $this->get('/keuangan/pembayaran-siswa/detail')->assertRedirectToRoute('keuangan.tagihan.index');
        }

        if ($peran === 'kepala') {
            foreach ($halaman as $url) {
                $this->get(str_replace('/kepala-sekolah', '/kepala', $url))->assertRedirect($url);
            }
        }

        if ($peran === 'wali_kelas') {
            $pegawai = Pegawai::create(['pengguna_id' => $akun->id]);
            $tahun = TahunAjaran::create([
                'kode' => '2026/2027',
                'tanggal_mulai' => '2026-07-01',
                'tanggal_selesai' => '2027-06-30',
            ]);
            $rombel = Rombel::create([
                'tahun_ajaran_id' => $tahun->id,
                'wali_kelas_id' => $pegawai->id,
                'tingkat' => 1,
                'nama' => '1A',
            ]);
            $siswa = Siswa::create(['nama_lengkap' => 'Siswa kelas sendiri']);
            $siswa->keanggotaanRombel()->create(['rombel_id' => $rombel->id]);
            $siswa->presensi()->create([
                'tanggal' => '2026-11-23',
                'status' => 'hadir',
                'dicatat_oleh' => $akun->id,
            ]);
            Siswa::create(['nama_lengkap' => 'Siswa di luar kelas']);

            $this->get('/wali-kelas/presensi-absensi?bulan=11&tahun=2026')->assertOk()
                ->assertViewHas('rekapSiswa', fn ($rekap) => $rekap->pluck('nama_lengkap')->all() === [$siswa->nama_lengkap]
                    && $rekap->first()['hadir'] === 1);
        }

        $this->withSession(['peran_aktif' => $peran === 'siswa' ? 'guru' : 'siswa']);
        $this->get($halaman[0])->assertForbidden();
        if ($peran === 'kepala') {
            $this->get('/kepala')->assertForbidden();
        }
    }

    public static function halamanPortal(): array
    {
        return [
            'operator' => ['operator', [
                '/operator', '/operator/data-siswa', '/operator/data-siswa/tambah',
                '/operator/data-guru', '/operator/data-guru/tambah', '/operator/data-sekolah',
                '/operator/pengumuman', '/operator/pengumuman/tambah',
            ]],
            'keuangan' => ['keuangan', [
                '/keuangan', '/keuangan/pembayaran-siswa', '/keuangan/pembayaran',
                '/keuangan/tagihan/tambah', '/keuangan/rekap-absensi-guru', '/keuangan/pengumuman',
                '/keuangan/pengumuman/tambah',
            ]],
            'kepala' => ['kepala', [
                '/kepala-sekolah', '/kepala-sekolah/rekap-absensi', '/kepala-sekolah/rekap-pembayaran', '/kepala-sekolah/data-sekolah', '/kepala-sekolah/pengumuman',
            ]],
            'humas' => ['humas', [
                '/humas', '/humas/rekap-absensi', '/humas/rekap-pembayaran', '/humas/data-sekolah', '/humas/pengumuman',
            ]],
            'siswa' => ['siswa', ['/siswa', '/siswa/data-diri', '/siswa/absensi', '/siswa/tagihan-spp']],
            'wali_kelas' => ['wali_kelas', [
                '/wali-kelas', '/wali-kelas/data-siswa', '/wali-kelas/presensi-absensi', '/wali-kelas/keuangan-spp',
            ]],
        ];
    }
}
