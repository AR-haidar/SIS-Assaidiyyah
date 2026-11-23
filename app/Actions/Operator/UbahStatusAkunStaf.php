<?php

namespace App\Actions\Operator;

use App\Models\Pengguna;
use App\Support\LogAktivitas;

class UbahStatusAkunStaf
{
    public function handle(Pengguna $pengguna, bool $aktif): Pengguna
    {
        $pengguna->update([
            'aktif' => $aktif,
        ]);

        $statusLabel = $aktif ? 'diaktifkan' : 'dinonaktifkan';

        LogAktivitas::catat(
            modul: 'akun',
            aksi: $aktif ? 'aktifkan_akun_staf' : 'nonaktifkan_akun_staf',
            objek: $pengguna,
            ringkasan: "Akun staf {$pengguna->username} {$statusLabel}."
        );

        return $pengguna;
    }
}
