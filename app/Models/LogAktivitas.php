<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAktivitas extends Model
{
    public const CREATED_AT = 'waktu';
    public const UPDATED_AT = null;

    protected $table = 'log_aktivitas';

    protected $fillable = [
        'pengguna_id',
        'modul',
        'aksi',
        'objek',
        'objek_id',
        'ringkasan',
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }
}
