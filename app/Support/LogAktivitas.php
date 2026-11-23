<?php

namespace App\Support;

use App\Models\LogAktivitas as LogAktivitasModel;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas
{
    public static function catat(
        string $modul,
        string $aksi,
        ?Model $objek = null,
        ?string $ringkasan = null,
        ?int $penggunaId = null,
    ): void {
        $aktorId = $penggunaId ?? auth()->id();

        LogAktivitasModel::create([
            'pengguna_id' => $aktorId !== null ? (int) $aktorId : null,
            'modul' => $modul,
            'aksi' => $aksi,
            'objek' => $objek !== null ? $objek->getTable() : null,
            'objek_id' => $objek !== null ? $objek->getKey() : null,
            'ringkasan' => $ringkasan,
            'waktu' => now(),
        ]);
    }
}
