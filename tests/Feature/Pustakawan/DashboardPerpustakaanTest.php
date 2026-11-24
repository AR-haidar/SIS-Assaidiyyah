<?php

namespace Tests\Feature\Pustakawan;

use App\Models\Berkas;
use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class DashboardPerpustakaanTest extends TestCase
{
    use DatabaseTransactions;

    public function test_dashboard_menampilkan_statistik_dan_katalog_sesuai_database(): void
    {
        $pengguna = $this->masukSebagaiPustakawan();
        $totalBuku = Buku::count();
        $totalKategori = KategoriBuku::count();
        $kode = (string) Str::uuid();
        $kategori = KategoriBuku::create(['nama' => $kode.' Kategori']);
        KategoriBuku::create(['nama' => $kode.' Kategori Nonaktif', 'aktif' => false]);
        KategoriBuku::create(['nama' => $kode.' Kategori Dihapus'])->delete();
        $naskah = Berkas::create([
            'nama_asli' => 'katalog-uji.pdf',
            'path' => 'perpustakaan/naskah/'.$kode.'.pdf',
            'mime' => 'application/pdf',
            'ukuran_byte' => 100,
            'diunggah_oleh' => $pengguna->id,
        ]);
        $atribut = [
            'penulis' => $kode.' Penulis',
            'status_publikasi' => 'aktif',
            'naskah_berkas_id' => $naskah->id,
            'dibuat_oleh' => $pengguna->id,
        ];
        $lama = Buku::create(['judul' => $kode.' Koleksi Lama', ...$atribut]);
        $baru = Buku::create(['judul' => $kode.' Koleksi Baru', ...$atribut]);
        $baru->kategori()->attach($kategori->id);
        $draf = Buku::create(['judul' => $kode.' Draf', ...$atribut, 'status_publikasi' => 'draft']);
        $tanpaNaskah = Buku::create(['judul' => $kode.' Tanpa Naskah', ...$atribut, 'naskah_berkas_id' => null]);
        $dihapus = Buku::create(['judul' => $kode.' Dihapus', ...$atribut]);
        $dihapus->delete();

        $this->get(route('pustakawan.dashboard'))
            ->assertOk()
            ->assertSee('<h2>Total Buku</h2><strong class="perpustakaan-summary-total">'.($totalBuku + 4).'</strong>', false)
            ->assertSee('<h2>Total Kategori</h2><strong class="perpustakaan-summary-total">'.($totalKategori + 2).'</strong>', false)
            ->assertSee('class="dlib-public-book-card"', false)
            ->assertSee('Kategori: '.$kategori->nama)
            ->assertSee('E-Book PDF')
            ->assertSee('href="'.route('publik.perpustakaan.buku.baca', $baru->id).'"', false)
            ->assertSeeInOrder([$baru->judul, $lama->judul])
            ->assertDontSee($draf->judul)
            ->assertDontSee($tanpaNaskah->judul)
            ->assertDontSee($dihapus->judul);

        $this->get(route('pustakawan.dashboard', ['q' => strtoupper($baru->judul)]))
            ->assertOk()->assertSee($baru->judul)->assertDontSee($lama->judul);
        $this->get(route('pustakawan.dashboard', ['q' => $atribut['penulis']]))
            ->assertOk()->assertSee($baru->judul)->assertSee($lama->judul);
        $this->get(route('pustakawan.dashboard', ['q' => $kode.' tidak cocok']))
            ->assertOk()->assertSee('Buku Tidak Ditemukan');
    }

    public function test_dashboard_kosong_dan_tautan_tambah_buku_tetap_tersedia(): void
    {
        $this->masukSebagaiPustakawan();
        DB::statement('CREATE TEMP TABLE buku ON COMMIT DROP AS SELECT * FROM buku WHERE false');
        DB::statement('CREATE TEMP TABLE kategori_buku ON COMMIT DROP AS SELECT * FROM kategori_buku WHERE false');

        $this->get(route('pustakawan.dashboard'))
            ->assertOk()
            ->assertSee('<h2>Total Buku</h2><strong class="perpustakaan-summary-total">0</strong>', false)
            ->assertSee('<h2>Total Kategori</h2><strong class="perpustakaan-summary-total">0</strong>', false)
            ->assertSee('Belum Ada Koleksi Buku Ditambahkan')
            ->assertSee('Tambah Buku Baru')
            ->assertSee('href="'.route('pustakawan.data-buku.create').'"', false);

        $this->get(route('pustakawan.data-buku.create'))->assertOk();
    }

    public function test_navigasi_dashboard_dan_hak_akses_pustakawan(): void
    {
        $dashboard = route('pustakawan.dashboard');
        $this->get($dashboard)->assertRedirectToRoute('login');
        $this->actingAs(new Pengguna)->withSession(['peran_aktif' => 'siswa'])->get($dashboard)->assertForbidden();
        $this->masukSebagaiPustakawan();

        $halaman = $this->get($dashboard)->assertOk();
        foreach (['pustakawan.dashboard', 'pustakawan.data-buku.index', 'pustakawan.data-buku.create',
            'pustakawan.kategori-buku.index', 'publik.beranda'] as $namaRoute) {
            $url = route($namaRoute);
            $halaman->assertSee('href="'.$url.'"', false);
            $this->get($url)->assertOk();
        }

        $halaman->assertSee('action="'.route('logout').'"', false);
        $this->post(route('logout'))->assertRedirectToRoute('login');
        $this->assertGuest();
    }

    private function masukSebagaiPustakawan(): Pengguna
    {
        $pengguna = Pengguna::create([
            'username' => (string) Str::uuid(),
            'password_hash' => Str::random(),
            'jenis_akun' => 'staf',
        ]);
        $akunUji = new class extends Pengguna
        {
            public function punyaPeran(string ...$kode): bool
            {
                return $kode === ['pustakawan'];
            }
        };
        $akunUji->setRawAttributes($pengguna->getAttributes(), true);
        $this->actingAs($akunUji)->withSession(['peran_aktif' => 'pustakawan']);

        return $pengguna;
    }
}
