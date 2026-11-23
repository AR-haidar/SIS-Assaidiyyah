<?php

namespace App\Support;

use App\Models\LogAktivitas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Aktivitas
{
    /**
     * Catat aksi tulis ke log_aktivitas.
     * Signature mengikuti kontrak fondasi Lead: jangan diubah.
     */
    public static function catat(
        string $modul,
        string $aksi,
        ?Model $objek = null,
        ?string $ringkasan = null,
    ): void {
        LogAktivitas::create([
            'pengguna_id' => Auth::id(),
            'modul'       => $modul,
            'aksi'        => $aksi,
            'objek'       => $objek ? class_basename($objek) : null,
            'objek_id'    => $objek?->getKey(),
            'ringkasan'   => $ringkasan,
        ]);
    }
}