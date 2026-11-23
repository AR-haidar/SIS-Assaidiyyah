<?php

namespace App\Actions\Presensi;

use App\Enums\StatusPresensiSiswa;
use App\Models\Pengguna;
use App\Models\PresensiSiswa;
use App\Models\Siswa;
use App\Support\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CatatPresensiSiswa
{
    /**
     * @param  array<int, array{siswa_id: int, status: string|StatusPresensiSiswa, keterangan?: ?string}>  $daftarItem
     * @return array<int, PresensiSiswa>
     */
    public function handle(string $tanggal, array $daftarItem, Pengguna $pencatat): array
    {
        $tanggalNormal = Carbon::parse($tanggal)->format('Y-m-d');

        return DB::transaction(function () use ($tanggalNormal, $daftarItem, $pencatat): array {
            $tersimpan = [];

            foreach ($daftarItem as $item) {
                $siswaId = (int) ($item['siswa_id'] ?? 0);
                $siswa = Siswa::query()->find($siswaId);

                if (! ($siswa instanceof Siswa)) {
                    throw ValidationException::withMessages([
                        'presensi' => ["Data siswa dengan ID #{$siswaId} tidak ditemukan."],
                    ]);
                }

                $statusRaw = $item['status'] ?? null;
                $statusEnum = $statusRaw instanceof StatusPresensiSiswa
                    ? $statusRaw
                    : StatusPresensiSiswa::tryFrom((string) $statusRaw);

                if (! ($statusEnum instanceof StatusPresensiSiswa)) {
                    throw ValidationException::withMessages([
                        'presensi' => ['Status presensi siswa tidak valid.'],
                    ]);
                }

                $keterangan = isset($item['keterangan']) && trim((string) $item['keterangan']) !== ''
                    ? trim((string) $item['keterangan'])
                    : null;

                $record = PresensiSiswa::withTrashed()
                    ->where('siswa_id', $siswa->getKey())
                    ->whereDate('tanggal', $tanggalNormal)
                    ->first();

                if ($record instanceof PresensiSiswa) {
                    if ($record->trashed()) {
                        $record->restore();
                    }
                    $record->status = $statusEnum;
                    $record->keterangan = $keterangan;
                    $record->dicatat_oleh = $pencatat->getKey();
                    $record->save();
                } else {
                    $record = PresensiSiswa::query()->create([
                        'siswa_id' => $siswa->getKey(),
                        'tanggal' => $tanggalNormal,
                        'status' => $statusEnum,
                        'keterangan' => $keterangan,
                        'dicatat_oleh' => $pencatat->getKey(),
                    ]);
                }

                $tersimpan[] = $record;
            }

            LogAktivitas::catat(
                modul: 'presensi_siswa',
                aksi: 'catat_presensi',
                objek: null,
                ringkasan: 'Mencatat presensi '.count($tersimpan).' siswa pada tanggal '.$tanggalNormal,
            );

            return $tersimpan;
        });
    }
}
