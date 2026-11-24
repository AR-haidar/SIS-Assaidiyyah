<?php

namespace Tests\Feature;

use App\Models\Berkas;
use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\Peran;
use App\Models\Referensi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProfilGuruTest extends TestCase
{
    use DatabaseTransactions;

    public function test_guru_hanya_melihat_profil_dan_pasfoto_milik_akunnya(): void
    {
        Storage::fake('local');
        $guru = $this->akun('guru');
        $rekan = $this->akun('guru');
        $status = Referensi::create([
            'kelompok' => 'status_kepegawaian',
            'kode' => 'uji-'.Str::random(8),
            'label' => 'Guru Tetap Uji',
        ]);
        $pendidikan = Referensi::create([
            'kelompok' => 'pendidikan',
            'kode' => 'uji-'.Str::random(8),
            'label' => 'Sarjana Uji',
        ]);
        $nip = Str::random(18);
        $nipRekan = Str::random(18);
        $fotoGuru = $this->pasfoto($guru, 'foto-guru');
        $fotoRekan = $this->pasfoto($rekan, 'foto-rekan');
        Pegawai::create([
            'pengguna_id' => $guru->id,
            'nama_lengkap' => 'Guru Pemilik Akun',
            'nip' => $nip,
            'status_kepegawaian_id' => $status->id,
            'pendidikan_terakhir_id' => $pendidikan->id,
            'pasfoto_berkas_id' => $fotoGuru->id,
            'tempat_lahir' => 'Cipanas',
            'tanggal_lahir' => '1990-02-03',
        ]);
        Pegawai::create([
            'pengguna_id' => $rekan->id,
            'nama_lengkap' => 'Guru Pemilik Akun Lain',
            'nip' => $nipRekan,
            'pasfoto_berkas_id' => $fotoRekan->id,
        ]);

        $this->actingAs($guru)->withSession(['peran_aktif' => 'guru']);
        $this->get('/guru/profile?pegawai_id='.$rekan->pegawai()->firstOrFail()->id)
            ->assertOk()->assertSee('Guru Pemilik Akun')->assertSee($nip)
            ->assertSee('Guru Tetap Uji')->assertSee('Sarjana Uji')->assertSee('Cipanas, 03-02-1990')
            ->assertSee('src="'.route('guru.profil.pasfoto').'"', false)
            ->assertDontSee('Guru Pemilik Akun Lain')->assertDontSee($nipRekan);

        $foto = $this->get(route('guru.profil.pasfoto', ['pegawai_id' => $rekan->pegawai()->firstOrFail()->id]))
            ->assertOk()->assertHeader('Content-Type', 'image/png');
        $this->assertStringContainsString('no-store', $foto->headers->get('Cache-Control'));
        $this->assertSame('foto-guru', $foto->streamedContent());

        $this->actingAs($rekan);
        $this->assertSame('foto-rekan', $this->get(route('guru.profil.pasfoto'))->assertOk()->streamedContent());
    }

    public function test_profil_tetap_bisa_dibuka_saat_data_pegawai_atau_pasfoto_belum_tersedia(): void
    {
        Storage::fake('local');
        $guru = $this->akun('guru');
        $this->actingAs($guru)->withSession(['peran_aktif' => 'guru']);

        $this->get('/guru/profile')->assertOk()->assertSee('Pasfoto Pendidik')
            ->assertDontSee('src="'.route('guru.profil.pasfoto').'"', false);
        $this->get(route('guru.profil.pasfoto'))->assertNotFound();

        $foto = $this->pasfoto($guru, 'foto-hilang');
        Storage::disk('local')->delete($foto->path);
        Pegawai::create(['pengguna_id' => $guru->id, 'pasfoto_berkas_id' => $foto->id]);
        $this->get('/guru/profile')->assertOk()->assertSee('Pasfoto Pendidik')
            ->assertDontSee('src="'.route('guru.profil.pasfoto').'"', false);
        $this->get(route('guru.profil.pasfoto'))->assertNotFound();
    }

    public function test_profil_dan_pasfoto_memerlukan_login_dan_peran_guru(): void
    {
        $this->get('/guru/profile')->assertRedirectToRoute('login');
        $this->get(route('guru.profil.pasfoto'))->assertRedirectToRoute('login');

        $this->actingAs($this->akun('siswa'))->withSession(['peran_aktif' => 'siswa']);
        $this->get('/guru/profile')->assertForbidden();
        $this->get(route('guru.profil.pasfoto'))->assertForbidden();
    }

    private function akun(string $kodePeran): Pengguna
    {
        $akun = Pengguna::create([
            'username' => (string) Str::uuid(),
            'password_hash' => Str::random(),
            'jenis_akun' => $kodePeran === 'guru' ? 'staf' : 'siswa',
        ]);
        $peran = Peran::firstOrCreate(['kode' => $kodePeran], ['nama' => ucfirst($kodePeran)]);
        $akun->peran()->attach($peran->id);

        return $akun;
    }

    private function pasfoto(Pengguna $akun, string $isi): Berkas
    {
        $path = 'pasfoto/uji/'.Str::uuid().'.png';
        Storage::disk('local')->put($path, $isi);

        return Berkas::create([
            'nama_asli' => 'pasfoto.png',
            'path' => $path,
            'mime' => 'image/png',
            'ukuran_byte' => strlen($isi),
            'diunggah_oleh' => $akun->id,
        ]);
    }
}
