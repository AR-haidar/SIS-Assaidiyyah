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

            $this->get(route('pustakawan.kategori-buku.index'))
                ->assertOk()
                ->assertViewHas('kategoriBuku', fn ($daftar) => $daftar->contains($kategori))
                ->assertSee('<td>'.$nama.'</td>', false)
                ->assertSee('Kategori pengujian')
                ->assertSee('Kategori buku berhasil ditambahkan.');
        }
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
