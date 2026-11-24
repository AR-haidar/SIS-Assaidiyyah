<?php

namespace App\Actions\Operator;

use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\Peran;
use App\Models\Rombel;
use App\Support\LogAktivitas;
use Illuminate\Support\Facades\DB;

class AturWaliKelas
{
    public function handle(Rombel $rombel, ?Pegawai $waliKelasBaru): Rombel
    {
        return DB::transaction(function () use ($rombel, $waliKelasBaru): Rombel {
            $rombel->loadMissing('waliKelas.pengguna');

            /** @var Pegawai|null $waliKelasLama */
            $waliKelasLama = $rombel->waliKelas;
            $waliKelasBaruId = $waliKelasBaru?->id;

            $rombel->update([
                'wali_kelas_id' => $waliKelasBaruId,
            ]);

            $peranWaliKelas = Peran::query()
                ->where('kode', 'wali_kelas')
                ->first();

            if ($peranWaliKelas instanceof Peran) {
                $kandidatCekLepas = [];

                if ($waliKelasLama instanceof Pegawai && $waliKelasLama->id !== $waliKelasBaruId) {
                    $kandidatCekLepas[] = $waliKelasLama;
                }

                if ($waliKelasBaru instanceof Pegawai) {
                    $waliKelasBaru->loadMissing('pengguna');
                    if ($waliKelasBaru->pengguna instanceof Pengguna) {
                        $waliKelasBaru->pengguna->peran()->syncWithoutDetaching([$peranWaliKelas->id]);
                    }
                }

                foreach ($kandidatCekLepas as $pegawaiLama) {
                    $pegawaiLama->loadMissing('pengguna');
                    if (! $pegawaiLama->pengguna instanceof Pengguna) {
                        continue;
                    }

                    $masihMengampuRombel = Rombel::query()
                        ->where('wali_kelas_id', $pegawaiLama->id)
                        ->exists();

                    if (! $masihMengampuRombel) {
                        $pegawaiLama->pengguna->peran()->detach($peranWaliKelas->id);
                    }
                }
            }

            $rombel->load(['waliKelas.pengguna', 'tahunAjaran']);

            $ringkasan = $waliKelasBaru instanceof Pegawai
                ? "Menetapkan {$waliKelasBaru->nama_lengkap} sebagai wali kelas rombel {$rombel->nama}."
                : "Melepas penugasan wali kelas pada rombel {$rombel->nama}.";

            LogAktivitas::catat(
                modul: 'wali_kelas',
                aksi: $waliKelasBaru instanceof Pegawai ? 'tetapkan_wali_kelas' : 'lepas_wali_kelas',
                objek: $rombel,
                ringkasan: $ringkasan
            );

            return $rombel;
        });
    }
}
