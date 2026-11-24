<?php

namespace Tests\Feature\Pustakawan;

use App\Enums\StatusPublikasiBuku;
use App\Models\Berkas;
use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Pengguna;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\TestWith;
use RuntimeException;
use Tests\TestCase;
use ZipArchive;

class BukuTest extends TestCase
{
    use DatabaseTransactions;

    public function test_buku_tersimpan_dan_muncul_di_daftar_serta_form_edit(): void
    {
        $pengguna = $this->masukSebagaiPustakawan();
        $this->get(route('pustakawan.data-buku.create'))->assertOk()
            ->assertSee('data-cover-image alt="Pratinjau sampul buku" hidden', false)
            ->assertSee('Belum ada gambar');
        $kategori = $this->kategori();
        $judul = Str::random(Buku::MAKS_JUDUL_KARAKTER);

        $this->post(route('pustakawan.data-buku.store'), [
            ...$this->isian($kategori),
            'judul' => $judul,
            'sampul' => $this->sampul(),
            'naskah' => $this->naskah('naskah-awal.pdf'),
        ])->assertRedirectToRoute('pustakawan.data-buku.index')->assertSessionHas('status', 'Buku berhasil ditambahkan.');

        $buku = Buku::with('kategori', 'sampul', 'naskah')->where('judul', $judul)->firstOrFail();
        $this->assertSame($pengguna->id, $buku->dibuat_oleh);
        $this->assertSame(StatusPublikasiBuku::Draft, $buku->status_publikasi);
        $this->assertEquals([$kategori->id], $buku->kategori->modelKeys());
        Storage::disk('local')->assertExists([$buku->sampul->path, $buku->naskah->path]);

        $this->get(route('pustakawan.data-buku.index', ['kategori' => $kategori->id, 'status' => 'draft', 'q' => $judul]))
            ->assertOk()->assertSee($judul)->assertSee(route('pustakawan.data-buku.edit', $buku))
            ->assertSee('data-cover-image src="'.route('pustakawan.data-buku.sampul', $buku).'"', false);
        $this->get(route('pustakawan.data-buku.edit', $buku))
            ->assertOk()->assertSee($judul)->assertSee('naskah-awal.pdf')->assertSee(route('pustakawan.data-buku.sampul', $buku));
        $this->get(route('pustakawan.data-buku.sampul', $buku))
            ->assertOk()->assertHeader('Content-Type', 'image/png');
    }

    public function test_perubahan_mempertahankan_berkas_lama_sampai_pengganti_diunggah(): void
    {
        $this->masukSebagaiPustakawan();
        $kategori = $this->kategori();
        $kategoriBaru = $this->kategori();
        $data = $this->isian($kategori);

        $this->post(route('pustakawan.data-buku.store'), [
            ...$data,
            'sampul' => $this->sampul(),
            'naskah' => $this->naskah('naskah-awal.pdf'),
        ])->assertRedirectToRoute('pustakawan.data-buku.index');

        $buku = Buku::with('sampul', 'naskah')->where('judul', $data['judul'])->firstOrFail();
        $sampulId = $buku->sampul_berkas_id;
        $naskahId = $buku->naskah_berkas_id;
        $pathSampulLama = $buku->sampul->path;
        $pathNaskahLama = $buku->naskah->path;

        $this->post(route('pustakawan.data-buku.update', $buku), [
            ...$data,
            '_method' => 'PUT',
            'judul' => 'Judul hasil revisi',
            'status_publikasi' => 'aktif',
            'kategori_buku_id' => $kategoriBaru->id,
        ])->assertRedirectToRoute('pustakawan.data-buku.index')->assertSessionHas('status', 'Perubahan buku berhasil disimpan.');

        $buku->refresh();
        $this->assertSame('Judul hasil revisi', $buku->judul);
        $this->assertSame(StatusPublikasiBuku::Aktif, $buku->status_publikasi);
        $this->assertEquals([$kategoriBaru->id], $buku->kategori->modelKeys());
        $this->assertSame($sampulId, $buku->sampul_berkas_id);
        $this->assertSame($naskahId, $buku->naskah_berkas_id);

        $buku->kategori()->attach($this->kategori()->id);

        $this->post(route('pustakawan.data-buku.update', $buku), [
            ...$this->isian($kategoriBaru),
            '_method' => 'PUT',
            'sampul' => $this->sampul('sampul-baru.png'),
            'naskah' => $this->naskah('naskah-baru.pdf'),
        ])->assertRedirectToRoute('pustakawan.data-buku.index');

        $buku->refresh();
        $this->assertEquals([$kategoriBaru->id], $buku->kategori->modelKeys());
        $this->assertNotSame($sampulId, $buku->sampul_berkas_id);
        $this->assertNotSame($naskahId, $buku->naskah_berkas_id);
        $this->assertSame('sampul-baru.png', $buku->sampul->nama_asli);
        $this->assertSame('naskah-baru.pdf', $buku->naskah->nama_asli);
        Storage::disk('local')->assertExists([
            $buku->sampul->path,
            $buku->naskah->path,
            $pathSampulLama,
            $pathNaskahLama,
        ]);
        $this->get(route('pustakawan.data-buku.index', ['q' => $buku->judul]))
            ->assertOk()->assertSee('data-cover-image src="'.route('pustakawan.data-buku.sampul', $buku).'"', false);

        $this->delete(route('pustakawan.data-buku.destroy', $buku))->assertRedirectToRoute('pustakawan.data-buku.index');
        $this->assertSoftDeleted($buku);
        $this->get(route('pustakawan.data-buku.edit', $buku))->assertNotFound();
    }

    public function test_naskah_epub_dapat_disimpan_melalui_endpoint_buku(): void
    {
        $this->masukSebagaiPustakawan();
        $data = $this->isian($this->kategori());
        $path = tempnam(sys_get_temp_dir(), 'sis-epub-');
        $this->assertNotFalse($path);

        try {
            $epub = new ZipArchive;
            $this->assertTrue($epub->open($path, ZipArchive::OVERWRITE));
            $epub->addFromString('mimetype', 'application/epub+zip');
            $epub->setCompressionName('mimetype', ZipArchive::CM_STORE);
            $epub->addFromString('META-INF/container.xml', '<container xmlns="urn:oasis:names:tc:opendocument:xmlns:container" version="1.0"><rootfiles><rootfile full-path="book.opf" media-type="application/oebps-package+xml"/></rootfiles></container>');
            $epub->addFromString('book.opf', '<package xmlns="http://www.idpf.org/2007/opf" version="3.0" unique-identifier="id"><metadata xmlns:dc="http://purl.org/dc/elements/1.1/"><dc:identifier id="id">urn:example:buku-uji</dc:identifier><dc:title>Buku Uji</dc:title><dc:language>id</dc:language></metadata><manifest><item id="page" href="page.xhtml" media-type="application/xhtml+xml"/></manifest><spine><itemref idref="page"/></spine></package>');
            $epub->addFromString('page.xhtml', '<html xmlns="http://www.w3.org/1999/xhtml"><head><title>Buku Uji</title></head><body><p>Isi buku uji.</p></body></html>');
            $this->assertTrue($epub->close());

            $this->post(route('pustakawan.data-buku.store'), [
                ...$data,
                'naskah' => new UploadedFile($path, 'naskah.epub', null, null, true),
            ])->assertRedirectToRoute('pustakawan.data-buku.index')->assertSessionHasNoErrors();

            $naskah = Buku::where('judul', $data['judul'])->firstOrFail()->naskah;
            $this->get(route('pustakawan.data-buku.index', ['q' => $data['judul']]))
                ->assertOk()->assertSee('aria-label="Belum ada sampul"', false)->assertDontSee('data-cover-image src=', false);
            $this->assertSame('naskah.epub', $naskah->nama_asli);
            $this->assertSame('application/epub+zip', $naskah->mime);
            Storage::disk('local')->assertExists($naskah->path);
        } finally {
            unlink($path);
        }
    }

    #[TestWith([false])]
    #[TestWith([true])]
    public function test_penghapusan_yang_gagal_tidak_mengirim_notifikasi_sukses(bool $gagalDatabase): void
    {
        $pengguna = $this->masukSebagaiPustakawan();
        $buku = Buku::create([
            'judul' => 'Buku uji gagal hapus',
            'penulis' => 'Penulis Uji',
            'dibuat_oleh' => $pengguna->id,
        ]);
        Buku::deleting(function () use ($gagalDatabase): bool {
            if ($gagalDatabase) {
                throw new QueryException('pgsql', 'update buku', [], new RuntimeException('Kegagalan database uji'));
            }

            return false;
        });

        $this->delete(route('pustakawan.data-buku.destroy', $buku))
            ->assertRedirectToRoute('pustakawan.data-buku.edit', $buku)
            ->assertSessionHasErrors(['simpan' => 'Buku gagal dihapus. Silakan coba kembali.'])
            ->assertSessionMissing('status');

        $this->assertNotSoftDeleted($buku);
    }

    public function test_isian_tidak_valid_ditolak_dan_unggahan_yang_gagal_dibersihkan(): void
    {
        $this->masukSebagaiPustakawan();
        $kategori = $this->kategori();
        $kategoriNonaktif = $this->kategori(false);
        $jumlahBerkas = Berkas::count();
        $jumlahBuku = Buku::count();
        $asal = route('pustakawan.data-buku.create');
        $tujuan = route('pustakawan.data-buku.store');

        $this->from($asal)->post($tujuan, [
            ...$this->isian($kategori),
            'judul' => str_repeat('A', Buku::MAKS_JUDUL_KARAKTER + 1),
            'penulis' => '   ',
            'sampul' => UploadedFile::fake()->create('sampul.png', (Buku::MAKS_SAMPUL_MB * 1024) + 1, 'image/png'),
        ])->assertRedirect($asal)->assertSessionHasErrors(['judul', 'penulis', 'sampul', 'naskah']);

        $this->from($asal)->post($tujuan, [
            ...$this->isian($kategoriNonaktif),
            'naskah' => $this->naskah('uji.pdf'),
        ])->assertRedirect($asal)->assertSessionHasErrors(['kategori_buku_id' => 'Pilih kategori buku yang masih aktif.']);

        $this->from($asal)->post($tujuan, [
            ...$this->isian($kategori),
            'sampul' => UploadedFile::fake()->create('bukan-sampul.png', 1, 'text/plain'),
            'naskah' => $this->naskah('uji.pdf'),
        ])->assertRedirect($asal)->assertSessionHasErrors('sampul');

        $this->assertSame($jumlahBerkas, Berkas::count());
        $this->assertSame($jumlahBuku, Buku::count());
        $this->assertSame([], Storage::disk('local')->allFiles('perpustakaan'));
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
        $this->withoutVite();
        $this->actingAs($akunUji)->withSession(['peran_aktif' => 'pustakawan']);
        Storage::fake('local');

        return $pengguna;
    }

    private function kategori(bool $aktif = true): KategoriBuku
    {
        return KategoriBuku::create(['nama' => (string) Str::uuid(), 'aktif' => $aktif]);
    }

    private function isian(KategoriBuku $kategori): array
    {
        return [
            'judul' => 'Buku '.(string) Str::uuid(),
            'penulis' => 'Penulis Uji',
            'kategori_buku_id' => $kategori->id,
            'sinopsis' => 'Ringkasan buku untuk pengujian.',
            'status_publikasi' => 'draft',
        ];
    }

    private function sampul(string $nama = 'sampul.png'): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($nama, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIHWP4z8DwHwAFgAI/ScLbtAAAAABJRU5ErkJggg=='
        ));
    }

    private function naskah(string $nama): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($nama, "%PDF-1.4\n%%EOF\n");
    }
}
