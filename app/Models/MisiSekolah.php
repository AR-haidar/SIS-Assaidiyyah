<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MisiSekolah extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diubah_pada';

    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'misi_sekolah';

    protected $fillable = [
        'urutan',
        'isi',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];
}
