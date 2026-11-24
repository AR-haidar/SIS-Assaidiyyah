<?php

namespace App\Support;

use App\Models\LogAktivitas as LogAktivitasModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LogAktivitas
{
    public static function catat(
        string $modul,
        string $aksi,
        ?Model $objek = null,
        ?string $ringkasan = null,
        ?int $penggunaId = null,
    ): void {
        $aktorId = $penggunaId ?? Auth::id();

        LogAktivitasModel::query()->create([
            'pengguna_id' => is_numeric($aktorId) ? (int) $aktorId : null,
            'modul' => $modul,
            'aksi' => $aksi,
            'objek' => $objek instanceof Model ? $objek->getTable() : null,
            'objek_id' => $objek instanceof Model && is_numeric($objek->getKey())
                ? (int) $objek->getKey()
                : null,
            'ringkasan' => $ringkasan,
            'waktu' => now(),
        ]);
    }
}
