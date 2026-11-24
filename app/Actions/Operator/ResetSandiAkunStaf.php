<?php

namespace App\Actions\Operator;

use App\Models\Pengguna;
use App\Support\LogAktivitas;
use Illuminate\Support\Facades\Hash;

class ResetSandiAkunStaf
{
    public function handle(Pengguna $pengguna, string $kataSandiBaru): Pengguna
    {
        $pengguna->forceFill([
            'password_hash' => Hash::make($kataSandiBaru),
        ])->save();

        LogAktivitas::catat(
            modul: 'akun',
            aksi: 'reset_sandi_akun_staf',
            objek: $pengguna,
            ringkasan: "Mereset kata sandi akun staf {$pengguna->username}."
        );

        return $pengguna;
    }
}
