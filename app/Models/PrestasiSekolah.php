<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrestasiSekolah extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diubah_pada';

    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'prestasi_sekolah';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'judul',
        'deskripsi',
        'tingkat',
        'tahun',
        'tanggal',
        'foto_berkas_id',
        'urutan',
        'aktif',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'urutan' => 'integer',
            'aktif' => 'boolean',
            'dibuat_pada' => 'datetime',
            'diubah_pada' => 'datetime',
            'dihapus_pada' => 'datetime',
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
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true)->orderBy('urutan')->orderByDesc('id');
    }
}
