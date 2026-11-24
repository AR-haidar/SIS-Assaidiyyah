<?php

namespace Tests\Feature;

use App\Models\Berkas;
use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Pengguna;
use App\Models\Peran;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class PerpustakaanDigitalTest extends TestCase
{
    use DatabaseTransactions;

    public function test_tautan_landing_dan_seluruh_route_perpustakaan_memerlukan_login(): void
    {
        $katalog = route('publik.perpustakaan.index');
        $this->get(route('publik.beranda'))->assertOk()->assertSee('href="'.$katalog.'"', false);
        $this->get($katalog)->assertRedirectToRoute('login');
        foreach (['detail', 'baca', 'sampul', 'naskah'] as $halaman) {
            $this->get(route('publik.perpustakaan.buku.'.$halaman, 1))->assertRedirectToRoute('login');
        }

        $pengguna = $this->buatPengguna();
        $peran = Peran::firstOrCreate(['kode' => 'siswa'], ['nama' => 'Siswa']);
        $pengguna->peran()->attach($peran->id);
        $this->post(route('login.store'), [
            'jenis_akun' => 'siswa',
            'username' => $pengguna->username,
            'password' => $pengguna->password_hash,
        ])->assertRedirectToRoute('peran.pilih');
        $this->get(route('peran.pilih'))->assertRedirectToRoute('siswa.dashboard');
        $this->get($katalog)->assertOk();
    }

    public function test_katalog_menampilkan_buku_yang_tersedia_dan_mendukung_pencarian_dengan_kategori(): void
    {
        $pengguna = $this->buatPengguna();
        $this->actingAs($pengguna);
        Storage::fake('local');
        $this->get(route('publik.perpustakaan.index'))
            ->assertOk()->assertSee('Belum Ada Koleksi Buku Ditambahkan');

        $naskah = $this->buatBerkas($pengguna, 'application/pdf', '%PDF-1.4');
        $sampul = $this->buatBerkas($pengguna, 'image/png', 'sampul');
        $kategori = KategoriBuku::create(['nama' => (string) Str::uuid()]);
        $lain = KategoriBuku::create(['nama' => (string) Str::uuid()]);
        $buku = $this->buatBuku($pengguna, $naskah, ['judul' => 'Cerita Islami', 'sampul_berkas_id' => $sampul->id]);
        $buku->kategori()->attach($kategori->id);
        $bukuLain = $this->buatBuku($pengguna, $naskah, ['judul' => 'Cerita Sekolah']);
        $bukuLain->kategori()->attach($lain->id);
        $draf = $this->buatBuku($pengguna, $naskah, ['status_publikasi' => 'draft']);
        $terhapus = $this->buatBuku($pengguna, $naskah);
        $terhapus->delete();
        $hilang = $this->buatBuku($pengguna, $this->buatBerkas($pengguna, 'application/pdf'));

        $this->get(route('publik.perpustakaan.index'))->assertOk()
            ->assertSee($buku->judul)->assertSee($bukuLain->judul)->assertSee($kategori->nama)
            ->assertSee(route('publik.perpustakaan.buku.sampul', $buku->id))
            ->assertSee(route('publik.perpustakaan.buku.detail', $buku->id))
            ->assertDontSee($draf->judul)->assertDontSee($terhapus->judul)->assertDontSee($hilang->judul);
        $this->get(route('publik.perpustakaan.index', ['q' => 'CER', 'kategori' => $kategori->id]))
            ->assertOk()->assertSee($buku->judul)->assertDontSee($bukuLain->judul)
            ->assertSee('value="'.$kategori->id.'" selected', false);
        $this->get(route('publik.perpustakaan.index', ['q' => 'penulis uji']))
            ->assertOk()->assertSee($buku->judul);
        $this->get(route('publik.perpustakaan.index', ['q' => 'kata tidak ditemukan']))
            ->assertOk()->assertSee('Buku Tidak Ditemukan');
        $this->get(route('publik.perpustakaan.buku.detail', $draf->id))->assertNotFound();
        $this->get(route('publik.perpustakaan.buku.baca', $terhapus->id))->assertNotFound();
        $this->get(route('publik.perpustakaan.buku.naskah', $hilang->id))->assertNotFound();
    }

    public function test_detail_pembaca_dan_berkas_sesuai_buku_yang_dipilih(): void
    {
        $pengguna = $this->buatPengguna();
        $this->actingAs($pengguna);
        Storage::fake('local');
        $pdf = $this->buatBerkas($pengguna, 'application/pdf', "%PDF-1.4\nIsi buku");
        $epub = $this->buatBerkas($pengguna, 'application/epub+zip', 'Naskah EPUB');
        $sampul = $this->buatBerkas($pengguna, 'image/png', 'Sampul');
        $buku = $this->buatBuku($pengguna, $pdf, ['sampul_berkas_id' => $sampul->id]);
        $bukuEpub = $this->buatBuku($pengguna, $epub);

        $naskah = route('publik.perpustakaan.buku.naskah', $buku->id);
        $baca = route('publik.perpustakaan.buku.baca', $buku->id);
        $detail = route('publik.perpustakaan.buku.detail', $buku->id);
        $this->get($detail)->assertOk()->assertSee($buku->judul)->assertSee('Ringkasan naskah uji')->assertSee($baca);
        $this->get($baca)->assertOk()->assertSee('<canvas', false)->assertSee('data-pdf-url="'.$naskah.'"', false)
            ->assertSee('Sebelumnya')->assertSee('Berikutnya')->assertSee('Perbesar halaman')
            ->assertDontSee('<iframe', false)->assertDontSee('Unduh')->assertDontSee('Buka PDF langsung');
        $respons = $this->get($naskah)->assertOk()->assertHeader('Content-Type', 'application/pdf')
            ->assertHeader('Content-Disposition', 'inline; filename=buku-'.$buku->id.'.pdf');
        $this->assertStringContainsString('private', $respons->headers->get('Cache-Control'));
        $this->assertStringContainsString('no-store', $respons->headers->get('Cache-Control'));
        $this->assertSame(Storage::disk('local')->path($pdf->path), $respons->baseResponse->getFile()->getPathname());
        $this->get($naskah, ['Range' => 'bytes=0-3'])->assertStatus(206);
        $this->get(route('publik.perpustakaan.buku.sampul', $buku->id))->assertOk()->assertHeader('Content-Type', 'image/png');
        $this->get(route('publik.perpustakaan.buku.baca', $bukuEpub->id))
            ->assertOk()->assertSee('Pembaca EPUB belum tersedia.')->assertDontSee('Unduh')
            ->assertDontSee(route('publik.perpustakaan.buku.naskah', $bukuEpub->id));
        $this->get(route('publik.perpustakaan.buku.naskah', $bukuEpub->id))
            ->assertNotFound();

        $this->withSession(['peran_aktif' => 'siswa']);
        $this->get(route('pustakawan.data-buku.index'))->assertForbidden();
        $this->get(route('pustakawan.data-buku.edit', $buku))->assertForbidden();
        $this->post(route('pustakawan.data-buku.store'))->assertForbidden();
        $this->put(route('pustakawan.data-buku.update', $buku))->assertForbidden();
        $this->delete(route('pustakawan.data-buku.destroy', $buku))->assertForbidden();
        $this->put($naskah)->assertStatus(405);
        $this->delete($naskah)->assertStatus(405);
        $this->assertNotSoftDeleted($buku);
        $this->assertSame($pdf->id, $buku->refresh()->naskah_berkas_id);
        $this->get('/storage/'.$pdf->path)->assertForbidden();

        Storage::disk('local')->delete($pdf->path);
        $this->get($detail)->assertNotFound();
        $this->get($baca)->assertNotFound();
        $this->get($naskah)->assertNotFound();

        $pengguna->update(['aktif' => false]);
        $this->get(route('publik.perpustakaan.buku.baca', $bukuEpub->id))->assertForbidden();
        $this->get($naskah)->assertForbidden();
    }

    private function buatPengguna(): Pengguna
    {
        return Pengguna::create([
            'username' => (string) Str::uuid(),
            'password_hash' => Str::random(),
            'jenis_akun' => 'siswa',
            'aktif' => true,
        ]);
    }

    private function buatBerkas(Pengguna $pengguna, string $mime, ?string $isi = null): Berkas
    {
        $path = 'perpustakaan/uji/'.Str::uuid();
        if ($isi !== null) {
            Storage::disk('local')->put($path, $isi);
        }

        return Berkas::create([
            'nama_asli' => 'berkas-uji',
            'path' => $path,
            'mime' => $mime,
            'ukuran_byte' => strlen($isi ?? ''),
            'diunggah_oleh' => $pengguna->id,
        ]);
    }

    private function buatBuku(Pengguna $pengguna, Berkas $naskah, array $data = []): Buku
    {
        return Buku::create([
            'judul' => (string) Str::uuid(),
            'penulis' => 'Penulis Uji',
            'sinopsis' => 'Ringkasan naskah uji',
            'status_publikasi' => 'aktif',
            'naskah_berkas_id' => $naskah->id,
            'dibuat_oleh' => $pengguna->id,
            ...$data,
        ]);
    }
}
