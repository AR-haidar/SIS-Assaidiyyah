<?php

namespace App\Models;

use App\Enums\StatusPengumuman;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengumuman extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diubah_pada';

    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'isi',
        'nomor_surat',
        'kategori_id',
        'status',
        'disematkan',
        'tanggal_terbit',
        'tayang_selesai',
        'gambar_berkas_id',
        'dibuat_oleh',
    ];

    protected $casts = [
        'status' => StatusPengumuman::class,
        'disematkan' => 'boolean',
        'tanggal_terbit' => 'datetime',
        'tayang_selesai' => 'datetime',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Referensi::class, 'kategori_id');
    }

    public function gambar(): BelongsTo
    {
        return $this->belongsTo(Berkas::class, 'gambar_berkas_id');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function sasaran(): HasMany
    {
        return $this->hasMany(PengumumanSasaran::class, 'pengumuman_id');
    }

    public function lampiran(): BelongsToMany
    {
        return $this->belongsToMany(Berkas::class, 'pengumuman_lampiran', 'pengumuman_id', 'berkas_id')
            ->withTimestamps('dibuat_pada', 'diubah_pada');
    }

    public function scopeTerbit(Builder $query): Builder
    {
        return $query->where('status', StatusPengumuman::Terbit)
            ->where(fn (Builder $q) => $q->whereNull('tanggal_terbit')->orWhere('tanggal_terbit', '<=', now()))
            ->where(fn (Builder $q) => $q->whereNull('tayang_selesai')->orWhere('tayang_selesai', '>=', now()));
    }
}
