<?php

namespace App\Models;

use App\Enums\StatusPublikasiBuku;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buku extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diubah_pada';

    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'buku';

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'sinopsis',
        'sampul_berkas_id',
        'naskah_berkas_id',
        'status_publikasi',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tahun_terbit' => 'integer',
        'status_publikasi' => StatusPublikasiBuku::class,
    ];

    public function sampul(): BelongsTo
    {
        return $this->belongsTo(Berkas::class, 'sampul_berkas_id');
    }

    public function naskah(): BelongsTo
    {
        return $this->belongsTo(Berkas::class, 'naskah_berkas_id');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function kategori(): BelongsToMany
    {
        return $this->belongsToMany(KategoriBuku::class, 'buku_kategori', 'buku_id', 'kategori_buku_id')
            ->withTimestamps('dibuat_pada', 'diubah_pada');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status_publikasi', StatusPublikasiBuku::Aktif);
    }
}
