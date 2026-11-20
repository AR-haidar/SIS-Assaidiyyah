<?php

namespace App\Models;

use App\Enums\StatusPermohonan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermohonanPerubahanData extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'diajukan_pada';
    public const UPDATED_AT = 'diubah_pada';
    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'permohonan_perubahan_data';

    protected $fillable = [
        'siswa_id',
        'diajukan_oleh',
        'keterangan',
        'status',
        'diproses_oleh',
        'diproses_pada',
        'catatan_proses',
    ];

    protected $casts = [
        'status' => StatusPermohonan::class,
        'diproses_pada' => 'datetime',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function pengaju(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'diajukan_oleh');
    }

    public function pemroses(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'diproses_oleh');
    }
}
