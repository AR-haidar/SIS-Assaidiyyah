<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisTagihan extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diubah_pada';

    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'jenis_tagihan';

    protected $fillable = [
        'nama',
        'bulanan',
        'aktif',
    ];

    protected $casts = [
        'bulanan' => 'boolean',
        'aktif' => 'boolean',
    ];

    public function tagihan(): HasMany
    {
        return $this->hasMany(Tagihan::class, 'jenis_tagihan_id');
    }
}
