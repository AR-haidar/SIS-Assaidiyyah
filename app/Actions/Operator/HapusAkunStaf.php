<?php

namespace App\Actions\Operator;

use App\Models\Pengguna;
use App\Support\LogAktivitas;
use Illuminate\Support\Facades\DB;

class HapusAkunStaf
{
    public function handle(Pengguna $pengguna): void
    {
        DB::transaction(function () use ($pengguna): void {
            $username = $pengguna->username;
            $pengguna->delete();

            LogAktivitas::catat(
                modul: 'akun',
                aksi: 'hapus_akun_staf',
                objek: $pengguna,
                ringkasan: "Menghapus (soft delete) akun staf {$username}."
            );
        });
    }
}
