<?php

namespace App\Models;

use App\Enums\JenisSasaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengumumanSasaran extends Model
{
    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = 'diubah_pada';

    protected $table = 'pengumuman_sasaran';

    protected $fillable = [
        'pengumuman_id',
        'jenis_sasaran',
        'peran_id',
        'rombel_id',
    ];

    protected $casts = [
        'jenis_sasaran' => JenisSasaran::class,
    ];

    public function pengumuman(): BelongsTo
    {
        return $this->belongsTo(Pengumuman::class, 'pengumuman_id');
    }

    public function peran(): BelongsTo
    {
        return $this->belongsTo(Peran::class, 'peran_id');
    }

    public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class, 'rombel_id');
    }
}
