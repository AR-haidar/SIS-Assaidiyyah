<?php

namespace Tests\Feature\Pustakawan;

use App\Models\KategoriBuku;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class KategoriBukuTest extends TestCase
{
    use DatabaseTransactions;

    public function test_pustakawan_dapat_menambah_dan_menerima_data_kategori(): void
    {
        $this->masukSebagaiPustakawan();

        $this->get(route('pustakawan.kategori-buku.create'))
            ->assertOk()
            ->assertSee('action="'.route('pustakawan.kategori-buku.store').'" method="POST"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('name="nama"', false)
            ->assertSee('name="deskripsi"', false)
            ->assertSee('name="category_status"', false)
            ->assertSee('type="submit"', false);

        foreach (['active' => true, 'inactive' => false] as $status => $aktif) {
            $nama = (string) Str::uuid();

            $this->post(route('pustakawan.kategori-buku.store'), [
                'nama' => $nama,
                'deskripsi' => 'Kategori pengujian',
                'category_status' => $status,
            ])->assertRedirectToRoute('pustakawan.kategori-buku.index')->assertSessionHas('status');

            $kategori = KategoriBuku::where('nama', $nama)->firstOrFail();
            $this->assertSame($aktif, $kategori->aktif);

            $this->get(route('pustakawan.kategori-buku.index', ['q' => $nama]))
                ->assertOk()
                ->assertViewHas('kategoriBuku', fn ($daftar) => $daftar->contains($kategori))
                ->assertSee('<td>'.$nama.'</td>', false)
                ->assertSee('Kategori pengujian')
                ->assertSee('Kategori buku berhasil ditambahkan.');
        }
    }

    public function test_pencarian_kategori_mencocokkan_nama_dan_mempertahankan_kata_kunci(): void
    {
        $this->masukSebagaiPustakawan();
        $index = route('pustakawan.kategori-buku.index');
        $prefix = (string) Str::uuid();
        $nama = $prefix.' Cerita Islami';
        $lain = $prefix.' Kamus';
        KategoriBuku::create(['nama' => $nama]);
        KategoriBuku::create(['nama' => $lain]);
        $dihapus = KategoriBuku::create(['nama' => $prefix.' Cerita Dihapus']);
        $dihapus->delete();

        foreach ([$nama, $prefix.' cer', strtoupper($prefix.' cerita'), $prefix.' CERITA ISLAMI'] as $kata) {
            $this->get(route('pustakawan.kategori-buku.index', ['q' => $kata]))
                ->assertOk()
                ->assertSee('<td>'.$nama.'</td>', false)
                ->assertDontSee('<td>'.$lain.'</td>', false)
                ->assertDontSee('<td>'.$dihapus->nama.'</td>', false)
                ->assertSee('action="'.$index.'" method="GET"', false)
                ->assertSee('name="q" value="'.$kata.'"', false)
                ->assertSee('<header class="perpustakaan-list-toolbar perpustakaan-category-toolbar">', false)
                ->assertSee('type="submit" form="pencarian-kategori"', false)
                ->assertSee('<div class="perpustakaan-list-scroll perpustakaan-category-table-scroll"', false);
        }

        foreach (['ceritaa', 'certia'] as $kata) {
            $this->get(route('pustakawan.kategori-buku.index', ['q' => $kata]))
                ->assertOk()
                ->assertSee('<td>'.$nama.'</td>', false)
                ->assertDontSee('<td>'.$lain.'</td>', false);
        }

        $this->get(route('pustakawan.kategori-buku.index', ['q' => $prefix.' tidak ada']))
            ->assertOk()->assertSee('Kategori Buku Tidak Ditemukan');

        $this->get(route('pustakawan.kategori-buku.index', ['q' => '']))
            ->assertOk()
            ->assertSee('name="q" value=""', false)
            ->assertViewHas('kategoriBuku', fn ($daftar) => $daftar->total() === KategoriBuku::count());
    }

    public function test_filter_status_kategori_bekerja_bersama_pencarian(): void
    {
        $this->masukSebagaiPustakawan();
        $kata = (string) Str::uuid();
        $aktif = KategoriBuku::create(['nama' => $kata.' Satu', 'aktif' => true]);
        $nonaktif = KategoriBuku::create(['nama' => $kata.' Dua', 'aktif' => false]);
        $dihapus = KategoriBuku::create(['nama' => $kata.' Tiga', 'aktif' => true]);
        $dihapus->delete();
        $sel = fn (KategoriBuku $kategori) => '<td>'.$kategori->nama.'</td>';

        $this->get(route('pustakawan.kategori-buku.index', ['q' => $kata, 'status' => '']))
            ->assertOk()->assertSee($sel($aktif), false)->assertSee($sel($nonaktif), false)
            ->assertDontSee($sel($dihapus), false)
            ->assertSee('aria-label="Filter kategori"', false)
            ->assertDontSee('name="status"', false);

        foreach (['active' => [$aktif, $nonaktif], 'inactive' => [$nonaktif, $aktif]] as $status => [$cocok, $lain]) {
            $tanpaKata = route('pustakawan.kategori-buku.index', ['status' => $status]);
            $this->get(route('pustakawan.kategori-buku.index', ['q' => $kata, 'status' => $status]))
                ->assertOk()->assertSee($sel($cocok), false)->assertDontSee($sel($lain), false)
                ->assertDontSee($sel($dihapus), false)
                ->assertViewHas('filter', ['q' => $kata, 'status' => $status]);

            $this->get($tanpaKata)->assertOk()->assertSee('name="q" value=""', false)
                ->assertViewHas('kategoriBuku', fn ($daftar) => $daftar->isNotEmpty()
                    && $daftar->every(fn ($kategori) => $kategori->aktif === ($status === 'active')));
        }
    }

    public function test_pagination_kategori_mempertahankan_pencarian_dan_status(): void
    {
        $this->masukSebagaiPustakawan();
        $kata = (string) Str::uuid();

        foreach (range(1, 13) as $nomor) {
            KategoriBuku::create(['nama' => sprintf('%s %02d', $kata, $nomor)]);
        }

        KategoriBuku::create(['nama' => $kata.' Nonaktif', 'aktif' => false]);

        $pertama = $this->get(route('pustakawan.kategori-buku.index', ['q' => $kata, 'status' => 'active']))
            ->assertOk()
            ->assertViewHas('kategoriBuku', fn ($daftar) => $daftar->count() === 12 && $daftar->total() === 13);
        $berikutnya = $pertama->viewData('kategoriBuku')->nextPageUrl();
        $pertama->assertSee('href="'.e($berikutnya).'"', false);

        $this->get($berikutnya)
            ->assertOk()
            ->assertSee('name="q" value="'.$kata.'"', false)
            ->assertViewHas('filter', ['q' => $kata, 'status' => 'active'])
            ->assertSee('<td>'.$kata.' 13</td>', false);
    }

    public function test_input_tidak_valid_ditolak_termasuk_nama_kategori_yang_dihapus(): void
    {
        $this->masukSebagaiPustakawan();
        $kategori = KategoriBuku::create(['nama' => (string) Str::uuid()]);
        $kategori->delete();

        $this->from(route('pustakawan.kategori-buku.create'))
            ->post(route('pustakawan.kategori-buku.store'), [
                'nama' => $kategori->nama,
                'category_status' => 'lainnya',
            ])
            ->assertRedirectToRoute('pustakawan.kategori-buku.create')
            ->assertSessionHasErrors(['nama', 'deskripsi', 'category_status']);

        $this->get(route('pustakawan.kategori-buku.create'))
            ->assertOk()
            ->assertSee('Nama kategori buku sudah digunakan.')
            ->assertSee('Deskripsi kategori buku wajib diisi.')
            ->assertSee('Status kategori buku harus aktif atau nonaktif.')
            ->assertSee('value="'.$kategori->nama.'"', false);
    }

    public function test_penghapusan_lunak_mempertahankan_kategori_yang_terkait_buku(): void
    {
        $this->masukSebagaiPustakawan();
        $kategori = KategoriBuku::create(['nama' => (string) Str::uuid()]);
        $kategoriTerpakai = KategoriBuku::create(['nama' => (string) Str::uuid()]);
        $penggunaId = DB::table('pengguna')->insertGetId([
            'username' => (string) Str::uuid(),
            'password_hash' => Str::random(),
            'jenis_akun' => 'staf',
        ]);
        $bukuId = DB::table('buku')->insertGetId([
            'judul' => 'Buku pengujian',
            'penulis' => 'Penulis pengujian',
            'dibuat_oleh' => $penggunaId,
        ]);
        $pivot = ['buku_id' => $bukuId, 'kategori_buku_id' => $kategoriTerpakai->id];
        DB::table('buku_kategori')->insert($pivot);

        $this->delete(route('pustakawan.kategori-buku.destroy', $kategori))
            ->assertRedirectToRoute('pustakawan.kategori-buku.index')->assertSessionHas('status');
        $this->assertSoftDeleted($kategori);

        $this->delete(route('pustakawan.kategori-buku.destroy', $kategoriTerpakai))
            ->assertRedirectToRoute('pustakawan.kategori-buku.index')->assertSessionHasErrors('kategori_buku');
        $this->assertNotSoftDeleted($kategoriTerpakai);
        $this->assertDatabaseHas('buku_kategori', $pivot);
        $this->assertDatabaseHas('buku', ['id' => $bukuId]);
    }

    public function test_pengunjung_dan_pengguna_tanpa_peran_pustakawan_ditolak(): void
    {
        $index = route('pustakawan.kategori-buku.index');
        $this->get($index)->assertRedirectToRoute('login');

        $this->actingAs(new Pengguna)->withSession(['peran_aktif' => 'siswa']);
        $this->get($index)->assertForbidden();
        $this->post(route('pustakawan.kategori-buku.store'))->assertForbidden();
        $this->delete(route('pustakawan.kategori-buku.destroy', 1))->assertForbidden();
    }

    private function masukSebagaiPustakawan(): void
    {
        $pengguna = new class(['username' => 'pustakawan-uji']) extends Pengguna
        {
            public function punyaPeran(string ...$kode): bool
            {
                return $kode === ['pustakawan'];
            }
        };

        $this->withoutVite();
        $this->actingAs($pengguna)->withSession(['peran_aktif' => 'pustakawan']);
    }
}
