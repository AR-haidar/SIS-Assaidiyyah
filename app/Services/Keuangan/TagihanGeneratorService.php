<?php

namespace App\Services\Keuangan;

use App\Models\JenisTagihan;
use App\Models\Tagihan;
use App\Support\Aktivitas;
use App\Enums\StatusDataSiswa;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TagihanGeneratorService
{
    private const UKURAN_CHUNK = 500;

    /**
     * @param array{
     *   jenis_tagihan_id:int, tahun_ajaran_id:int, tingkat:int,
     *   nominal:numeric-string|int|float, jatuh_tempo:string, periode_bulan?:?string
     * } $data
     * @return array{dibuat:int, dipulihkan:int, dilewati:int}
     */
    public function generate(array $data): array
    {
        $jenis = JenisTagihan::findOrFail($data['jenis_tagihan_id']);

        if (! $jenis->aktif) {
            throw ValidationException::withMessages([
                'jenis_tagihan_id' => 'Jenis tagihan ini tidak aktif.',
            ]);
        }

        $periode = $this->tentukanPeriode($jenis, $data['periode_bulan'] ?? null);
        $tahunAjaranId = (int) $data['tahun_ajaran_id'];

        $siswaIds = $this->siswaAktifIds($tahunAjaranId, (int) $data['tingkat']);

        if ($siswaIds === []) {
            throw ValidationException::withMessages([
                'tingkat' => 'Tidak ada siswa aktif pada tingkat dan tahun ajaran tersebut.',
            ]);
        }

        try {
            return DB::transaction(function () use ($data, $jenis, $periode, $tahunAjaranId, $siswaIds) {
                // Cegah dua generate bersamaan untuk kombinasi yang sama
                // (penting untuk tagihan tanpa bulan, yang tidak dijaga unique index).
                DB::select('SELECT pg_advisory_xact_lock(?)', [
                    crc32("tagihan:{$jenis->id}:{$tahunAjaranId}"),
                ]);

                $dibuat = $dipulihkan = $dilewati = 0;

                foreach (array_chunk($siswaIds, self::UKURAN_CHUNK) as $chunk) {
                    $ada = Tagihan::withTrashed()
                        ->where('jenis_tagihan_id', $jenis->id)
                        ->where('tahun_ajaran_id', $tahunAjaranId)
                        ->when(
                            $periode === null,
                            fn ($q) => $q->whereNull('periode_bulan'),
                            fn ($q) => $q->where('periode_bulan', $periode),
                        )
                        ->whereIn('siswa_id', $chunk)
                        ->get()
                        ->groupBy('siswa_id');

                    $baru = [];

                    foreach ($chunk as $siswaId) {
                        $baris = $ada->get($siswaId);

                        if ($baris === null) {
                            $baru[] = [
                                'siswa_id'         => $siswaId,
                                'jenis_tagihan_id' => $jenis->id,
                                'tahun_ajaran_id'  => $tahunAjaranId,
                                'periode_bulan'    => $periode,
                                'nominal'          => $data['nominal'],
                                'jatuh_tempo'      => $data['jatuh_tempo'],
                            ];
                            continue;
                        }

                        // Sudah ada yang aktif -> lewati
                        if ($baris->contains(fn (Tagihan $t) => ! $t->trashed())) {
                            $dilewati++;
                            continue;
                        }

                        // Hanya ada yang soft delete -> pulihkan, jangan insert ulang
                        $tagihan = $baris->first();
                        $tagihan->restore();
                        $tagihan->update([
                            'nominal'     => $data['nominal'],
                            'jatuh_tempo' => $data['jatuh_tempo'],
                        ]);
                        $dipulihkan++;
                    }

                    if ($baru !== []) {
                        Tagihan::insert($baru);
                        $dibuat += count($baru);
                    }
                }

                Aktivitas::catat(
                    'keuangan',
                    'generate_tagihan',
                    null,
                    "Generate tagihan {$jenis->nama} tingkat {$data['tingkat']}: "
                    . "{$dibuat} dibuat, {$dipulihkan} dipulihkan, {$dilewati} dilewati",
                );

                return [
                    'dibuat'     => $dibuat,
                    'dipulihkan' => $dipulihkan,
                    'dilewati'   => $dilewati,
                ];
            });
        } catch (QueryException $e) {
            if (($e->errorInfo[0] ?? null) === '23505') {
                throw ValidationException::withMessages([
                    'jenis_tagihan_id' => 'Sebagian tagihan sudah ada. Silakan coba generate ulang.',
                ]);
            }

            throw $e;
        }
    }

    private function tentukanPeriode(JenisTagihan $jenis, ?string $periodeBulan): ?string
    {
        if ($jenis->bulanan) {
            if (blank($periodeBulan)) {
                throw ValidationException::withMessages([
                    'periode_bulan' => 'Bulan wajib diisi untuk jenis tagihan bulanan.',
                ]);
            }

            // Selalu simpan tanggal 1, supaya cek duplikat konsisten.
            return Carbon::parse($periodeBulan)->startOfMonth()->toDateString();
        }

        return null; // jenis tahunan/sekali bayar: tanpa bulan
    }

    /**
     * @return list<int>
     */
    private function siswaAktifIds(int $tahunAjaranId, int $tingkat): array
    {
        return DB::table('siswa_rombel as sr')
            ->join('rombel as r', 'r.id', '=', 'sr.rombel_id')
            ->join('siswa as s', 's.id', '=', 'sr.siswa_id')
            ->leftJoin('referensi as k', 'k.id', '=', 's.status_kesiswaan_id')
            ->where('r.tahun_ajaran_id', $tahunAjaranId)
            ->where('r.tingkat', $tingkat)
            ->whereNull('r.dihapus_pada')
            ->whereNull('s.dihapus_pada')
            ->where('s.status_data', StatusDataSiswa::Terverifikasi->value)
            ->where('k.kelompok', 'status_kesiswaan') 
            ->where('k.kode', 'aktif')                 
            ->distinct()
            ->pluck('sr.siswa_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * Buat satu tagihan untuk satu siswa.
     *
     * @param array{
     *   siswa_id:int, jenis_tagihan_id:int, tahun_ajaran_id:int,
     *   nominal:numeric-string|int|float, jatuh_tempo:string, periode_bulan?:?string
     * } $data
     */
    public function buatSatu(array $data): Tagihan
    {
        $jenis = JenisTagihan::findOrFail($data['jenis_tagihan_id']);

        if (! $jenis->aktif) {
            throw ValidationException::withMessages([
                'jenis_tagihan_id' => 'Jenis tagihan ini tidak aktif.',
            ]);
        }

        $periode = $this->tentukanPeriode($jenis, $data['periode_bulan'] ?? null);
        $tahunAjaranId = (int) $data['tahun_ajaran_id'];

        try {
            return DB::transaction(function () use ($data, $jenis, $periode, $tahunAjaranId) {
                DB::select('SELECT pg_advisory_xact_lock(?)', [
                    crc32("tagihan:{$jenis->id}:{$tahunAjaranId}"),
                ]);

                $ada = Tagihan::withTrashed()
                    ->where('siswa_id', $data['siswa_id'])
                    ->where('jenis_tagihan_id', $jenis->id)
                    ->where('tahun_ajaran_id', $tahunAjaranId)
                    ->when(
                        $periode === null,
                        fn ($q) => $q->whereNull('periode_bulan'),
                        fn ($q) => $q->where('periode_bulan', $periode),
                    )
                    ->get();

                if ($ada->contains(fn (Tagihan $t) => ! $t->trashed())) {
                    throw ValidationException::withMessages([
                        'siswa_id' => 'Siswa ini sudah memiliki tagihan ' . $jenis->nama
                            . ($periode ? ' untuk bulan tersebut.' : ' pada tahun ajaran ini.'),
                    ]);
                }

                if ($ada->isNotEmpty()) {
                    // Hanya ada yang soft delete -> pulihkan, jangan insert ulang.
                    $tagihan = $ada->first();
                    $tagihan->restore();
                    $tagihan->update([
                        'nominal'     => $data['nominal'],
                        'jatuh_tempo' => $data['jatuh_tempo'],
                    ]);
                } else {
                    $tagihan = Tagihan::create([
                        'siswa_id'         => $data['siswa_id'],
                        'jenis_tagihan_id' => $jenis->id,
                        'tahun_ajaran_id'  => $tahunAjaranId,
                        'periode_bulan'    => $periode,
                        'nominal'          => $data['nominal'],
                        'jatuh_tempo'      => $data['jatuh_tempo'],
                    ]);
                }

                Aktivitas::catat('keuangan', 'buat_tagihan', $tagihan,
                    "Tagihan {$jenis->nama} untuk siswa #{$data['siswa_id']}");

                return $tagihan;
            });
        } catch (QueryException $e) {
            if (($e->errorInfo[0] ?? null) === '23505') {
                throw ValidationException::withMessages([
                    'siswa_id' => 'Tagihan ini sudah ada.',
                ]);
            }

            throw $e;
        }
    }
}