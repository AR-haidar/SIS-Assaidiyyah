<?php

namespace Tests\Feature\Pustakawan;

use App\Enums\StatusPublikasiBuku;
use App\Models\Berkas;
use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class BukuTest extends TestCase
{
    use DatabaseTransactions;

    public function test_buku_tersimpan_dan_muncul_di_daftar_serta_form_edit(): void
    {
        $pengguna = $this->masukSebagaiPustakawan();
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
            ->assertOk()->assertSee($judul)->assertSee(route('pustakawan.data-buku.edit', $buku));
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

        $buku = Buku::where('judul', $data['judul'])->firstOrFail();
        $sampulId = $buku->sampul_berkas_id;
        $naskahId = $buku->naskah_berkas_id;

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

        $this->post(route('pustakawan.data-buku.update', $buku), [
            ...$this->isian($kategoriBaru),
            '_method' => 'PUT',
            'naskah' => $this->naskah('naskah-baru.pdf'),
        ])->assertRedirectToRoute('pustakawan.data-buku.index');

        $buku->refresh();
        $this->assertNotSame($naskahId, $buku->naskah_berkas_id);
        $this->assertSame('naskah-baru.pdf', $buku->naskah->nama_asli);
        Storage::disk('local')->assertExists($buku->naskah->path);

        $this->delete(route('pustakawan.data-buku.destroy', $buku))->assertRedirectToRoute('pustakawan.data-buku.index');
        $this->assertSoftDeleted($buku);
        $this->get(route('pustakawan.data-buku.edit', $buku))->assertNotFound();
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

    private function sampul(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('sampul.png', base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIHWP4z8DwHwAFgAI/ScLbtAAAAABJRU5ErkJggg=='
        ));
    }

    private function naskah(string $nama): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($nama, "%PDF-1.4\n%%EOF\n");
    }
}
