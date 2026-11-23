<?php

namespace App\Models;

use App\Enums\StatusPresensiPegawai;
use App\Enums\StatusValidasiPresensi;
use App\Enums\SumberPresensi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PresensiPegawai extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diubah_pada';

    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'presensi_pegawai';

    protected $fillable = [
        'pegawai_id',
        'tanggal',
        'status',
        'keterangan',
        'status_validasi',
        'divalidasi_oleh',
        'divalidasi_pada',
        'sumber',
        'berkas_pendukung_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status' => StatusPresensiPegawai::class,
        'status_validasi' => StatusValidasiPresensi::class,
        'divalidasi_pada' => 'datetime',
        'sumber' => SumberPresensi::class,
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'divalidasi_oleh');
    }

    public function berkasPendukung(): BelongsTo
    {
        return $this->belongsTo(Berkas::class, 'berkas_pendukung_id');
    }
}
