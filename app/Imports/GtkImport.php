<?php

namespace App\Imports;

use App\Models\Pegawai;
use App\Models\Referensi;
use App\Support\PembersihExcel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GtkImport implements ToCollection, WithHeadingRow
{
    /** @var array<int, string> */
    public array $peringatan = [];

    public int $jumlahBerhasil = 0;

    public int $jumlahDilewati = 0;

    public function collection(Collection $rows): void
    {
        DB::transaction(function () use ($rows): void {
            foreach ($rows as $index => $row) {
                $barisKe = $index + 2;
                $namaLengkap = PembersihExcel::nama($row['nama_lengkap'] ?? null);
                $nik = PembersihExcel::angkaDigit($row['nik'] ?? null, 16);

                if ($namaLengkap === null && $nik === null) {
                    continue;
                }

                if ($namaLengkap === null || $nik === null) {
                    $this->jumlahDilewati++;
                    $this->peringatan[] = "Baris {$barisKe}: Nama Lengkap atau NIK (16 digit) tidak valid, baris dilewati.";

                    continue;
                }

                $nuptk = PembersihExcel::angkaDigit($row['nuptk'] ?? null, 16);
                $nip = PembersihExcel::angkaDigit($row['nip'] ?? null, 18);

                $statusKepegawaianId = $this->petakanStatusKepegawaian(
                    PembersihExcel::teks($row['status_kepegawaian'] ?? null)
                );

                $jenisGtkId = $this->petakanJenisGtk(
                    PembersihExcel::teks($row['tugas'] ?? null)
                );

                Pegawai::query()->updateOrCreate(
                    ['nik' => $nik],
                    [
                        'nama_lengkap' => $namaLengkap,
                        'nuptk' => $nuptk,
                        'nip' => $nip,
                        'jenis_kelamin' => PembersihExcel::jenisKelamin($row['jenis_kelamin'] ?? null),
                        'tempat_lahir' => PembersihExcel::teks($row['tempat_lahir'] ?? null),
                        'tanggal_lahir' => PembersihExcel::tanggal($row['tanggal_lahir'] ?? null),
                        'no_hp' => PembersihExcel::nomorTelepon($row['nomor_handphone'] ?? null),
                        'email' => PembersihExcel::teks($row['email'] ?? null),
                        'status_kepegawaian_id' => $statusKepegawaianId,
                        'jenis_gtk_id' => $jenisGtkId,
                        'mata_pelajaran' => PembersihExcel::teks($row['mata_pelajaran'] ?? null),
                        'penempatan' => PembersihExcel::teks($row['penempatan'] ?? null),
                        'aktif' => true,
                    ]
                );

                $this->jumlahBerhasil++;
            }
        });
    }

    private function petakanStatusKepegawaian(?string $teks): ?int
    {
        if ($teks === null) {
            return null;
        }

        $kode = str_contains(strtolower($teks), 'non') ? 'non_pns' : 'pns';

        return Referensi::query()
            ->where('kelompok', 'status_kepegawaian')
            ->where('kode', $kode)
            ->value('id');
    }

    private function petakanJenisGtk(?string $teks): ?int
    {
        if ($teks === null) {
            return null;
        }

        $normal = strtolower($teks);
        $kode = match (true) {
            str_contains($normal, 'kepala') => 'kepala_madrasah',
            str_contains($normal, 'kelas') => 'guru_kelas',
            str_contains($normal, 'mapel') => 'guru_mapel',
            str_contains($normal, 'tata usaha') => 'tata_usaha',
            str_contains($normal, 'operator') => 'operator_aplikasi',
            default => 'guru_mapel',
        };

        return Referensi::query()
            ->where('kelompok', 'jenis_gtk')
            ->where('kode', $kode)
            ->value('id');
    }
}
