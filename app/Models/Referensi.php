<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referensi extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = 'diubah_pada';
    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'referensi';

    protected $fillable = [
        'kelompok',
        'kode',
        'label',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'urutan' => 'integer',
        'aktif' => 'boolean',
    ];

    public function scopeKelompok(Builder $query, string $kelompok): Builder
    {
        return $query->where('kelompok', $kelompok)->where('aktif', true)->orderBy('urutan');
    }
}
