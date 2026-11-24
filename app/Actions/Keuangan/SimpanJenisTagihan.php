<?php

namespace App\Actions\Keuangan;

use App\Models\JenisTagihan;
use App\Support\LogAktivitas;
use Illuminate\Support\Facades\DB;

class SimpanJenisTagihan
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data, ?JenisTagihan $jenisTagihan = null): JenisTagihan
    {
        return DB::transaction(function () use ($data, $jenisTagihan): JenisTagihan {
            $adalahBaru = ! ($jenisTagihan instanceof JenisTagihan);
            $target = $jenisTagihan ?? new JenisTagihan;

            $target->nama = trim((string) $data['nama']);
            $target->bulanan = (bool) $data['bulanan'];
            $target->aktif = array_key_exists('aktif', $data) ? (bool) $data['aktif'] : ($adalahBaru ? true : $target->aktif);
            $target->save();

            LogAktivitas::catat(
                modul: 'keuangan',
                aksi: $adalahBaru ? 'tambah_jenis_tagihan' : 'ubah_jenis_tagihan',
                objek: $target,
                ringkasan: ($adalahBaru ? 'Menambah jenis tagihan ' : 'Memperbarui jenis tagihan ').$target->nama,
            );

            return $target;
        });
    }
}
