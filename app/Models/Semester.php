<?php

namespace App\Models;

use App\Enums\NamaSemester;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semester extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = 'diubah_pada';
    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'semester';

    protected $fillable = [
        'tahun_ajaran_id',
        'nama',
        'tanggal_mulai',
        'tanggal_selesai',
        'aktif',
    ];

    protected $casts = [
        'nama' => NamaSemester::class,
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'aktif' => 'boolean',
    ];

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }
}
