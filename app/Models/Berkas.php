<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Berkas extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'diunggah_pada';

    public const UPDATED_AT = 'diubah_pada';

    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'berkas';

    protected $fillable = [
        'nama_asli',
        'path',
        'mime',
        'ukuran_byte',
        'diunggah_oleh',
    ];

    public function pengunggah(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'diunggah_oleh');
    }
}
