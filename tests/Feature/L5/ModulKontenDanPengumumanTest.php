<?php

namespace Tests\Feature\L5;

use App\Enums\JenisAkun;
use App\Models\Pengguna;
use App\Models\Pengumuman;
use App\Models\Peran;
use App\Models\PrestasiSekolah;
use App\Models\ProfilGuruPublik;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ModulKontenDanPengumumanTest extends TestCase
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

    public function test_beranda_publik_menampilkan_prestasi_dan_profil_guru_aktif(): void
    {
        PrestasiSekolah::query()->create([
            'judul' => 'Juara 1 Tahfidz Tingkat Kabupaten',
            'kategori' => 'Keagamaan',
            'tingkat' => 'Kabupaten',
            'tahun' => '2026',
            'urutan' => 1,
            'aktif' => true,
        ]);

        ProfilGuruPublik::query()->create([
            'nama_tampil' => 'Ustadz Ahmad, S.Pd.I',
            'jabatan_tampil' => 'Guru Tahfidz',
            'bio_singkat' => 'Membina hafalan santri secara terstruktur.',
            'urutan' => 1,
            'aktif' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Juara 1 Tahfidz Tingkat Kabupaten')
            ->assertSee('Ustadz Ahmad, S.Pd.I');
    }

    public function test_humas_dapat_mengelola_prestasi_dan_profil_guru_serta_diblokir_dari_portal_operator(): void
    {
        $humas = $this->buatPenggunaDenganPeran('humas', 'humas_test');

        $this->actingAs($humas)
            ->withSession(['peran_aktif' => 'humas'])
            ->get('/humas/prestasi')
            ->assertOk();

        $this->actingAs($humas)
            ->withSession(['peran_aktif' => 'humas'])
            ->post('/humas/prestasi', [
                'judul' => 'Juara 2 Olimpiade Sains',
                'kategori' => 'Akademik',
                'tingkat' => 'Provinsi',
                'tahun' => '2026',
                'aktif' => '1',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('prestasi_sekolah', [
            'judul' => 'Juara 2 Olimpiade Sains',
            'aktif' => true,
        ]);

        $this->actingAs($humas)
            ->withSession(['peran_aktif' => 'humas'])
            ->get('/humas/guru/tambah')
            ->assertOk();

        $this->actingAs($humas)
            ->withSession(['peran_aktif' => 'humas'])
            ->post('/humas/guru', [
                'nama_tampil' => 'Ustadzah Fatimah, S.Pd',
                'jabatan_tampil' => 'Guru Bahasa Arab',
                'bio_singkat' => 'Pengajar bahasa Arab komunikatif.',
                'urutan' => 1,
                'aktif' => '1',
            ])
            ->assertRedirect(route('humas.guru.index'));

        $this->assertDatabaseHas('profil_guru_publik', [
            'nama_tampil' => 'Ustadzah Fatimah, S.Pd',
            'aktif' => true,
        ]);

        $this->actingAs($humas)
            ->withSession(['peran_aktif' => 'humas'])
            ->get('/operator/pengumuman')
            ->assertForbidden();
    }

    public function test_pengumuman_dapat_diterbitkan_langsung_tanpa_persetujuan_berjenjang(): void
    {
        $operator = $this->buatPenggunaDenganPeran('operator', 'operator_pengumuman');

        $this->actingAs($operator)
            ->withSession(['peran_aktif' => 'operator'])
            ->post('/operator/pengumuman', [
                'judul' => 'Edaran Libur Semester Ganjil',
                'kategori' => 'pengumuman',
                'nomor_surat' => '421.2/015/MI-ASS',
                'tanggal_terbit' => '2026-11-24',
                'sasaran' => 'Semua Siswa & Wali',
                'ringkasan' => 'Pemberitahuan jadwal libur resmi madrasah.',
                'status' => 'terbit',
            ])
            ->assertRedirect(route('operator.pengumuman.index'));

        $pengumuman = Pengumuman::query()->where('judul', 'Edaran Libur Semester Ganjil')->first();
        $this->assertNotNull($pengumuman);
        $this->assertSame('terbit', (string) ($pengumuman->status->value ?? $pengumuman->status));
    }

    public function test_operator_dapat_mengunduh_template_dan_memanggil_endpoint_impor_excel(): void
    {
        Excel::fake();

        $operator = $this->buatPenggunaDenganPeran('operator', 'operator_impor');

        $this->actingAs($operator)
            ->withSession(['peran_aktif' => 'operator'])
            ->get('/operator/impor/template/siswa')
            ->assertOk();

        Excel::assertDownloaded('template-siswa-emis.xlsx');

        $this->actingAs($operator)
            ->withSession(['peran_aktif' => 'operator'])
            ->get('/operator/impor/template/gtk')
            ->assertOk();

        Excel::assertDownloaded('template-gtk.xlsx');

        $berkasDummy = UploadedFile::fake()->create('siswa.xlsx', 10);

        $this->actingAs($operator)
            ->withSession(['peran_aktif' => 'operator'])
            ->post('/operator/impor/siswa', [
                'file' => $berkasDummy,
            ])
            ->assertRedirect();
    }
}
