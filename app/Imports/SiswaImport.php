<?php

namespace App\Imports;

use App\Models\Referensi;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\SiswaRombel;
use App\Models\TahunAjaran;
use App\Support\PembersihExcel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToCollection, WithHeadingRow
{
    /** @var array<int, string> */
    public array $peringatan = [];

    public int $jumlahBerhasil = 0;

    public int $jumlahDilewati = 0;

    public function collection(Collection $rows): void
    {
        $tahunAktif = TahunAjaran::query()->where('aktif', true)->first();

        $statusAktifId = Referensi::query()
            ->where('kelompok', 'status_kesiswaan')
            ->where('kode', 'aktif')
            ->value('id');

        $statusTidakAktifId = Referensi::query()
            ->where('kelompok', 'status_kesiswaan')
            ->where('kode', 'tidak_aktif')
            ->value('id') ?? $statusAktifId;

        DB::transaction(function () use ($rows, $tahunAktif, $statusAktifId, $statusTidakAktifId): void {
            foreach ($rows as $index => $row) {
                $barisKe = $index + 2;
                $namaLengkap = PembersihExcel::nama($row['nama_lengkap'] ?? null);
                $nisn = PembersihExcel::angkaDigit($row['nisn'] ?? null, 10);

                if ($namaLengkap === null && $nisn === null) {
                    continue;
                }

                if ($namaLengkap === null || $nisn === null) {
                    $this->jumlahDilewati++;
                    $this->peringatan[] = "Baris {$barisKe}: Nama Lengkap atau NISN (10 digit) tidak valid, baris dilewati.";

                    continue;
                }

                $nikRaw = PembersihExcel::teks($row['nik'] ?? null);
                $nik = PembersihExcel::angkaDigit($nikRaw, 16);

                if ($nikRaw !== null && $nik === null) {
                    $this->peringatan[] = "Baris {$barisKe} ({$namaLengkap}): NIK '{$nikRaw}' bukan 16 digit, disimpan kosong untuk dilengkapi sebelum verifikasi.";
                }

                $statusTeks = strtolower((string) PembersihExcel::teks($row['status'] ?? 'aktif'));
                $adalahTidakAktif = str_contains($statusTeks, 'tidak');

                $siswa = Siswa::query()->updateOrCreate(
                    ['nisn' => $nisn],
                    [
                        'nik' => $nik,
                        'nama_lengkap' => $namaLengkap,
                        'jenis_kelamin' => PembersihExcel::jenisKelamin($row['jenis_kelamin'] ?? null),
                        'tempat_lahir' => PembersihExcel::teks($row['tempat_lahir'] ?? null),
                        'tanggal_lahir' => PembersihExcel::tanggal($row['tanggal_lahir'] ?? null),
                        'alamat' => PembersihExcel::teks($row['alamat'] ?? null),
                        'no_whatsapp_orang_tua' => PembersihExcel::nomorTelepon($row['no_telepon'] ?? null),
                        'nama_ayah' => PembersihExcel::nama($row['nama_ayah_kandung'] ?? null),
                        'nama_ibu' => PembersihExcel::nama($row['nama_ibu_kandung'] ?? null),
                        'nama_wali' => PembersihExcel::nama($row['nama_wali'] ?? null),
                        'kebutuhan_khusus' => PembersihExcel::teks($row['kebutuhan_khusus'] ?? null),
                        'disabilitas' => PembersihExcel::teks($row['disabilitas'] ?? null),
                        'nomor_kip_pip' => PembersihExcel::teks($row['nomor_kippip'] ?? $row['nomor_kip_pip'] ?? null),
                        'status_kesiswaan_id' => $adalahTidakAktif ? $statusTidakAktifId : $statusAktifId,
                        'status_data' => 'draft',
                    ]
                );

                $tingkatRombel = PembersihExcel::teks($row['tingkat_rombel'] ?? null);

                if (! $adalahTidakAktif && $tahunAktif !== null && $tingkatRombel !== null) {
                    $this->tempatkanKeRombel($siswa, $tahunAktif, $tingkatRombel);
                }

                $this->jumlahBerhasil++;
            }
        });
    }

    private function tempatkanKeRombel(Siswa $siswa, TahunAjaran $tahunAktif, string $tingkatRombel): void
    {
        if (! preg_match('/(\d+)\s*-\s*([A-Za-z0-9]+)/', $tingkatRombel, $cocok)) {
            return;
        }

        $tingkat = (int) $cocok[1];
        $namaRombel = "{$tingkat} - {$cocok[2]}";

        $rombel = Rombel::query()->firstOrCreate(
            [
                'tahun_ajaran_id' => $tahunAktif->getKey(),
                'nama' => $namaRombel,
            ],
            [
                'tingkat' => $tingkat,
            ]
        );

        SiswaRombel::query()->firstOrCreate([
            'siswa_id' => $siswa->getKey(),
            'rombel_id' => $rombel->getKey(),
        ]);
    }
}
