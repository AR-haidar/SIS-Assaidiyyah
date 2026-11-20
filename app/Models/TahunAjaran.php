<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TahunAjaran extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = 'diubah_pada';
    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'kode',
        'tanggal_mulai',
        'tanggal_selesai',
        'aktif',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'aktif' => 'boolean',
    ];

    public function semester(): HasMany
    {
        return $this->hasMany(Semester::class, 'tahun_ajaran_id');
    }

    public function rombel(): HasMany
    {
        return $this->hasMany(Rombel::class, 'tahun_ajaran_id');
    }

    public function tagihan(): HasMany
    {
        return $this->hasMany(Tagihan::class, 'tahun_ajaran_id');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }
}
