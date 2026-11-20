<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rombel extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = 'diubah_pada';
    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'rombel';

    protected $fillable = [
        'tahun_ajaran_id',
        'tingkat',
        'nama',
        'wali_kelas_id',
    ];

    protected $casts = [
        'tingkat' => 'integer',
    ];

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'wali_kelas_id');
    }

    public function keanggotaan(): HasMany
    {
        return $this->hasMany(SiswaRombel::class, 'rombel_id');
    }

    public function siswa(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 'siswa_rombel', 'rombel_id', 'siswa_id')
            ->withTimestamps('dibuat_pada', 'diubah_pada');
    }
}
