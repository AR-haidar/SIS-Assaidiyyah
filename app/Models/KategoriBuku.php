<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriBuku extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diubah_pada';

    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'kategori_buku';

    protected $fillable = [
        'nama',
        'deskripsi',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function buku(): BelongsToMany
    {
        return $this->belongsToMany(Buku::class, 'buku_kategori', 'kategori_buku_id', 'buku_id')
            ->withTimestamps('dibuat_pada', 'diubah_pada');
    }
}
