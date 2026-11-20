<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiswaRombel extends Model
{
    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = 'diubah_pada';

    protected $table = 'siswa_rombel';

    protected $fillable = [
        'siswa_id',
        'rombel_id',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class, 'rombel_id');
    }
}
