<?php

namespace Tests\Feature\L2;

use App\Enums\StatusDataSiswa;
use App\Imports\GtkImport;
use App\Imports\SiswaImport;
use App\Models\Pegawai;
use App\Models\Referensi;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\SiswaRombel;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ImportExcelDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_mengimpor_data_siswa_ke_status_draft_menempatkan_rombel_dan_mencatat_peringatan_nik(): void
    {
        TahunAjaran::query()->create([
            'kode' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
            'aktif' => true,
        ]);

        Referensi::query()->firstOrCreate(
            ['kelompok' => 'status_kesiswaan', 'kode' => 'aktif'],
            ['label' => 'Aktif', 'urutan' => 1, 'aktif' => true],
        );

        $rows = new Collection([
            [
                'nama_lengkap' => 'Siswa Valid',
                'nisn' => "'0081122334",
                'nik' => "'3203010101010001",
                'jenis_kelamin' => 'Laki-laki',
                'tingkat_rombel' => 'Kelas 2 - 01',
                'status' => 'Aktif',
                'no_telepon' => 6281234567890.0,
            ],
            [
                'nama_lengkap' => 'Siswa Nik Pendek',
                'nisn' => '0081122335',
                'nik' => "'320301010101001",
                'jenis_kelamin' => 'Perempuan',
                'tingkat_rombel' => 'Kelas 2 - 01',
                'status' => 'Aktif',
            ],
        ]);

        $import = new SiswaImport;
        $import->collection($rows);

        $this->assertSame(2, $import->jumlahBerhasil);
        $this->assertCount(1, $import->peringatan);

        $siswaValid = Siswa::query()->where('nisn', '0081122334')->first();
        $this->assertNotNull($siswaValid);
        $this->assertSame('3203010101010001', $siswaValid->nik);
        $this->assertSame(StatusDataSiswa::Draft, $siswaValid->status_data);
        $this->assertSame('6281234567890', $siswaValid->no_whatsapp_orang_tua);

        $rombel = Rombel::query()->where('nama', '2 - 01')->first();
        $this->assertNotNull($rombel);
        $this->assertTrue(
            SiswaRombel::query()
                ->where('siswa_id', $siswaValid->getKey())
                ->where('rombel_id', $rombel->getKey())
                ->exists()
        );

        $siswaNikPendek = Siswa::query()->where('nisn', '0081122335')->first();
        $this->assertNotNull($siswaNikPendek);
        $this->assertNull($siswaNikPendek->nik);
    }

    public function test_mengimpor_data_gtk_dan_membersihkan_koma_gelar_serta_prefiks_apostrof(): void
    {
        Referensi::query()->firstOrCreate(
            ['kelompok' => 'status_kepegawaian', 'kode' => 'non_pns'],
            ['label' => 'Non PNS', 'urutan' => 1, 'aktif' => true],
        );
        Referensi::query()->firstOrCreate(
            ['kelompok' => 'jenis_gtk', 'kode' => 'guru_kelas'],
            ['label' => 'Guru Kelas', 'urutan' => 1, 'aktif' => true],
        );

        $rows = new Collection([
            [
                'nama_lengkap' => "'BUDI SANTOSO, S.Pd,",
                'nik' => "'3203019999888877",
                'nuptk' => "'1234567890123456",
                'status_kepegawaian' => 'Non PNS',
                'jenis_kelamin' => 'Laki-laki',
                'nomor_handphone' => "'081234567890",
                'tugas' => 'Guru Kelas',
            ],
        ]);

        $import = new GtkImport;
        $import->collection($rows);

        $this->assertSame(1, $import->jumlahBerhasil);
        $this->assertSame(0, $import->jumlahDilewati);

        $pegawai = Pegawai::query()->where('nik', '3203019999888877')->first();
        $this->assertNotNull($pegawai);
        $this->assertSame('BUDI SANTOSO, S.Pd', $pegawai->nama_lengkap);
        $this->assertSame('081234567890', $pegawai->no_hp_wa);
    }
}
