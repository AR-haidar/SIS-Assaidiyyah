<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sekolah extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = 'diubah_pada';
    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'sekolah';

    protected $fillable = [
        'nama',
        'deskripsi_singkat',
        'visi',
        'jenjang',
        'status_akreditasi',
        'alamat',
        'telepon',
        'email',
        'foto_gedung_berkas_id',
        'target_kehadiran_pegawai',
    ];

    protected $casts = [
        'target_kehadiran_pegawai' => 'decimal:2',
    ];

    public function fotoGedung(): BelongsTo
    {
        return $this->belongsTo(Berkas::class, 'foto_gedung_berkas_id');
    }
}
