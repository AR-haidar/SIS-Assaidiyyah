<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrestasiSekolah extends Model
{
    use SoftDeletes;

    protected $table = 'prestasi_sekolah';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diubah_pada';

    public const DELETED_AT = 'dihapus_pada';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tingkat',
        'kategori',
        'tahun',
        'tanggal',
        'foto_berkas_id',
        'urutan',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
            'tanggal' => 'date',
            'urutan' => 'integer',
            'aktif' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Berkas, $this>
     */
    public function foto(): BelongsTo
    {
        return $this->belongsTo(Berkas::class, 'foto_berkas_id');
    }

    /**
     * @param  Builder<PrestasiSekolah>  $query
     * @return Builder<PrestasiSekolah>
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true)->orderBy('urutan')->orderByDesc('tahun');
    }
}
