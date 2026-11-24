<?php

namespace App\Actions\Keuangan;

use App\Models\JenisTagihan;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\TahunAjaran;
use App\Support\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BuatTagihanMassal
{
    /**
     * @param  array<int, int>  $siswaIds
     * @return array{dibuat: int, dilewati: int, tagihan: array<int, Tagihan>}
     */
    public function handle(
        JenisTagihan $jenisTagihan,
        TahunAjaran $tahunAjaran,
        array $siswaIds,
        float $nominal,
        string $jatuhTempo,
        ?string $periodeBulan = null,
    ): array {
        $bersihIds = array_values(array_unique(array_filter(
            array_map(fn ($id): int => (int) $id, $siswaIds),
            fn (int $id): bool => $id > 0,
        )));

        if ($bersihIds === []) {
            throw ValidationException::withMessages([
                'siswa_ids' => ['Pilih minimal satu siswa untuk membuat tagihan.'],
            ]);
        }

        if ($nominal <= 0) {
            throw ValidationException::withMessages([
                'nominal' => ['Nominal tagihan harus lebih besar dari nol.'],
            ]);
        }

        $periodeNormal = null;
        if ($jenisTagihan->bulanan) {
            if ($periodeBulan === null || trim($periodeBulan) === '') {
                throw ValidationException::withMessages([
                    'periode_bulan' => ['Periode bulan wajib diisi untuk tagihan bulanan.'],
                ]);
            }
            $periodeNormal = Carbon::parse($periodeBulan)->startOfMonth()->format('Y-m-d');
        }

        $jatuhTempoNormal = Carbon::parse($jatuhTempo)->format('Y-m-d');

        return DB::transaction(function () use (
            $jenisTagihan,
            $tahunAjaran,
            $bersihIds,
            $nominal,
            $jatuhTempoNormal,
            $periodeNormal,
        ): array {
            $daftarSiswa = Siswa::query()
                ->whereIn('id', $bersihIds)
                ->get();

            $dibuat = 0;
            $dilewati = 0;
            $hasil = [];

            foreach ($daftarSiswa as $siswa) {
                $sudahAda = Tagihan::query()
                    ->where('siswa_id', $siswa->getKey())
                    ->where('jenis_tagihan_id', $jenisTagihan->getKey())
                    ->where('tahun_ajaran_id', $tahunAjaran->getKey())
                    ->when(
                        $periodeNormal !== null,
                        fn ($q) => $q->whereDate('periode_bulan', $periodeNormal),
                        fn ($q) => $q->whereNull('periode_bulan'),
                    )
                    ->exists();

                if ($sudahAda) {
                    $dilewati++;

                    continue;
                }

                $tagihan = Tagihan::query()->create([
                    'siswa_id' => $siswa->getKey(),
                    'jenis_tagihan_id' => $jenisTagihan->getKey(),
                    'tahun_ajaran_id' => $tahunAjaran->getKey(),
                    'periode_bulan' => $periodeNormal,
                    'nominal' => round($nominal, 2),
                    'jatuh_tempo' => $jatuhTempoNormal,
                ]);

                $dibuat++;
                $hasil[] = $tagihan;
            }

            LogAktivitas::catat(
                modul: 'keuangan',
                aksi: 'buat_tagihan_massal',
                objek: $jenisTagihan,
                ringkasan: "Membuat {$dibuat} tagihan ({$dilewati} dilewati) untuk {$jenisTagihan->nama}",
            );

            return [
                'dibuat' => $dibuat,
                'dilewati' => $dilewati,
                'tagihan' => $hasil,
            ];
        });
    }
}
