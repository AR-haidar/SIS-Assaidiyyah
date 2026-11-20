<?php

namespace App\Models;

use App\Enums\StatusVerifikasi;
use App\Enums\SumberPembayaran;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'tanggal_pengajuan';
    public const UPDATED_AT = 'diubah_pada';
    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'pembayaran';

    protected $fillable = [
        'tagihan_id',
        'nominal',
        'metode_pembayaran_id',
        'tanggal_pembayaran',
        'sumber',
        'bukti_berkas_id',
        'status_verifikasi',
        'diverifikasi_oleh',
        'diverifikasi_pada',
        'alasan_penolakan',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_pembayaran' => 'date',
        'sumber' => SumberPembayaran::class,
        'status_verifikasi' => StatusVerifikasi::class,
        'diverifikasi_pada' => 'datetime',
    ];

    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }

    public function metodePembayaran(): BelongsTo
    {
        return $this->belongsTo(Referensi::class, 'metode_pembayaran_id');
    }

    public function bukti(): BelongsTo
    {
        return $this->belongsTo(Berkas::class, 'bukti_berkas_id');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'diverifikasi_oleh');
    }

    public function scopeMenunggu(Builder $query): Builder
    {
        return $query->where('status_verifikasi', StatusVerifikasi::Menunggu);
    }

    public function scopeDisetujui(Builder $query): Builder
    {
        return $query->where('status_verifikasi', StatusVerifikasi::Disetujui);
    }
}
