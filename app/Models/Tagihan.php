<?php

namespace App\Models;

use App\Enums\StatusVerifikasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tagihan extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = 'diubah_pada';
    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'tagihan';

    protected $fillable = [
        'siswa_id',
        'jenis_tagihan_id',
        'tahun_ajaran_id',
        'periode_bulan',
        'nominal',
        'jatuh_tempo',
    ];

    protected $casts = [
        'periode_bulan' => 'date',
        'nominal' => 'decimal:2',
        'jatuh_tempo' => 'date',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function jenisTagihan(): BelongsTo
    {
        return $this->belongsTo(JenisTagihan::class, 'jenis_tagihan_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'tagihan_id');
    }

    /**
     * Status tagihan tidak disimpan; dihitung dari pembayaran (tanpa cicilan).
     * Duplikat tagihan jenis tanpa bulan (periode_bulan NULL) dicek di aplikasi.
     */
    public function getStatusPembayaranAttribute(): string
    {
        $pembayaran = $this->pembayaran;

        if ($pembayaran->contains('status_verifikasi', StatusVerifikasi::Disetujui)) {
            return 'lunas';
        }

        if ($pembayaran->contains('status_verifikasi', StatusVerifikasi::Menunggu)) {
            return 'menunggu_verifikasi';
        }

        return 'belum_dibayar';
    }
}
