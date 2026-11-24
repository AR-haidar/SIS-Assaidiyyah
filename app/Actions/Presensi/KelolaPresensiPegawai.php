<?php

namespace App\Actions\Presensi;

use App\Enums\StatusPresensiPegawai;
use App\Enums\StatusValidasiPresensi;
use App\Enums\SumberPresensi;
use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\PresensiPegawai;
use App\Support\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class KelolaPresensiPegawai
{
    public function catat(
        Pegawai $pegawai,
        string $tanggal,
        StatusPresensiPegawai $status,
        SumberPresensi $sumber = SumberPresensi::Manual,
        ?string $keterangan = null,
    ): PresensiPegawai {
        $tanggalNormal = Carbon::parse($tanggal)->format('Y-m-d');

        return DB::transaction(function () use ($pegawai, $tanggalNormal, $status, $sumber, $keterangan): PresensiPegawai {
            $record = PresensiPegawai::withTrashed()
                ->where('pegawai_id', $pegawai->getKey())
                ->whereDate('tanggal', $tanggalNormal)
                ->first();

            $bersihKeterangan = $keterangan !== null && trim($keterangan) !== ''
                ? trim($keterangan)
                : null;

            if ($record instanceof PresensiPegawai) {
                if ($record->trashed()) {
                    $record->restore();
                }
                $record->status = $status;
                $record->keterangan = $bersihKeterangan;
                $record->sumber = $sumber;
                $record->status_validasi = StatusValidasiPresensi::Menunggu;
                $record->divalidasi_oleh = null;
                $record->divalidasi_pada = null;
                $record->save();
            } else {
                $record = PresensiPegawai::query()->create([
                    'pegawai_id' => $pegawai->getKey(),
                    'tanggal' => $tanggalNormal,
                    'status' => $status,
                    'keterangan' => $bersihKeterangan,
                    'sumber' => $sumber,
                    'status_validasi' => StatusValidasiPresensi::Menunggu,
                ]);
            }

            LogAktivitas::catat(
                modul: 'presensi_pegawai',
                aksi: 'catat_presensi',
                objek: $record,
                ringkasan: 'Mencatat presensi pegawai '.($pegawai->nama_lengkap ?? ('#'.$pegawai->getKey()))
                    .' pada '.$tanggalNormal.' ('.$status->value.')',
            );

            return $record;
        });
    }

    public function validasi(
        PresensiPegawai $presensi,
        StatusValidasiPresensi $statusValidasi,
        Pengguna $validator,
        ?string $keterangan = null,
    ): PresensiPegawai {
        if ($statusValidasi === StatusValidasiPresensi::Menunggu) {
            throw ValidationException::withMessages([
                'status_validasi' => ['Pilih status valid atau ditolak saat memproses validasi presensi.'],
            ]);
        }

        return DB::transaction(function () use ($presensi, $statusValidasi, $validator, $keterangan): PresensiPegawai {
            $presensi->status_validasi = $statusValidasi;
            $presensi->divalidasi_oleh = $validator->getKey();
            $presensi->divalidasi_pada = now();

            if ($keterangan !== null && trim($keterangan) !== '') {
                $presensi->keterangan = trim($keterangan);
            }

            $presensi->save();

            LogAktivitas::catat(
                modul: 'presensi_pegawai',
                aksi: 'validasi_presensi',
                objek: $presensi,
                ringkasan: 'Validasi presensi pegawai #'.$presensi->getKey().' menjadi '.$statusValidasi->value,
            );

            return $presensi;
        });
    }
}
