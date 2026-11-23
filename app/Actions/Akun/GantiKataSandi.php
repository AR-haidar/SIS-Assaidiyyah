<?php

namespace App\Actions\Akun;

use App\Models\Pengguna;
use App\Support\LogAktivitas;
use Illuminate\Support\Facades\Hash;

class GantiKataSandi
{
    public function handle(Pengguna $pengguna, string $kataSandiBaru): Pengguna
    {
        $pengguna->forceFill([
            'password_hash' => Hash::make($kataSandiBaru),
        ])->save();

        LogAktivitas::catat(
            modul: 'akun',
            aksi: 'ganti_sandi',
            objek: $pengguna,
            ringkasan: "Mengubah kata sandi/PIN akun {$pengguna->username}."
        );

        return $pengguna;
    }
}
