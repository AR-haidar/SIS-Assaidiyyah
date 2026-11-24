<?php

namespace App\Models;

use App\Enums\StatusPresensiSiswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PresensiSiswa extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diubah_pada';

    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'presensi_siswa';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'status',
        'keterangan',
        'dicatat_oleh',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status' => StatusPresensiSiswa::class,
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dicatat_oleh');
    }
}
