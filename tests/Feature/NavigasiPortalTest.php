<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class NavigasiPortalTest extends TestCase
{
    #[DataProvider('halamanPortal')]
    public function test_menu_portal_memuat_halaman_dengan_peran_yang_sesuai(string $peran, array $halaman): void
    {
        $akun = new class(['username' => 'portal-uji']) extends Pengguna
        {
            public function punyaPeran(string ...$kode): bool
            {
                return $kode === [$this->getAttribute('peran_uji')];
            }
        };
        $akun->setAttribute('peran_uji', $peran);
        $this->actingAs($akun)->withSession(['peran_aktif' => $peran]);

        foreach ($halaman as $url) {
            $this->get($url)->assertOk();
        }

        $this->withSession(['peran_aktif' => $peran === 'siswa' ? 'guru' : 'siswa']);
        $this->get($halaman[0])->assertForbidden();
    }

    public static function halamanPortal(): array
    {
        return [
            'operator' => ['operator', [
                '/operator', '/operator/data-siswa', '/operator/data-siswa/tambah', '/operator/data-siswa/1/edit',
                '/operator/data-guru', '/operator/data-guru/tambah', '/operator/data-sekolah',
                '/operator/pengumuman', '/operator/pengumuman/tambah',
            ]],
            'keuangan' => ['keuangan', [
                '/keuangan', '/keuangan/pembayaran-siswa', '/keuangan/pembayaran-siswa/detail',
                '/keuangan/tagihan/tambah', '/keuangan/rekap-absensi-guru', '/keuangan/pengumuman',
                '/keuangan/pengumuman/tambah', '/keuangan/pengumuman/1/edit',
            ]],
            'kepala' => ['kepala', [
                '/kepala', '/kepala/rekap-absensi', '/kepala/rekap-pembayaran', '/kepala/data-sekolah', '/kepala/pengumuman',
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
