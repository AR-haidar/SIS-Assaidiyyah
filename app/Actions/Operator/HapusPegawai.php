<?php

namespace App\Actions\Operator;

use App\Models\Pegawai;
use App\Support\LogAktivitas;
use Illuminate\Support\Facades\DB;

class HapusPegawai
{
    public function handle(Pegawai $pegawai): void
    {
        DB::transaction(function () use ($pegawai): void {
            $nama = $pegawai->nama_lengkap ?? "Pegawai #{$pegawai->id}";
            $pegawai->delete();

            LogAktivitas::catat(
                modul: 'pegawai',
                aksi: 'hapus_pegawai',
                objek: $pegawai,
                ringkasan: "Menghapus (soft delete) data pegawai {$nama}."
            );
        });
    }
}
