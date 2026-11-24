<?php

namespace App\Actions\Siswa;

use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\SiswaRombel;
use App\Support\LogAktivitas;
use Illuminate\Support\Facades\DB;

class AturRombelSiswa
{
    public function handle(Siswa $siswa, Rombel $rombel): SiswaRombel
    {
        return DB::transaction(function () use ($siswa, $rombel): SiswaRombel {
            $tahunAjaranId = (int) $rombel->tahun_ajaran_id;

            $keanggotaanSamaTahun = SiswaRombel::query()
                ->where('siswa_id', $siswa->getKey())
                ->whereHas('rombel', fn ($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
                ->get();

            foreach ($keanggotaanSamaTahun as $lama) {
                if ((int) $lama->rombel_id !== (int) $rombel->getKey()) {
                    $lama->delete();
                }
            }

            $keanggotaan = SiswaRombel::query()->firstOrCreate([
                'siswa_id' => $siswa->getKey(),
                'rombel_id' => $rombel->getKey(),
            ]);

            $keanggotaan->loadMissing('rombel');

            LogAktivitas::catat(
                modul: 'siswa',
                aksi: 'atur_rombel',
                objek: $siswa,
                ringkasan: 'Menempatkan siswa '.($siswa->nama_lengkap ?? ('#'.$siswa->getKey()))
                    .' ke rombel '.($keanggotaan->rombel?->nama ?? ('#'.$rombel->getKey())),
            );

            return $keanggotaan;
        });
    }
}
