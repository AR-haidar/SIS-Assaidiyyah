<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PesanKontak extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dikirim_pada';
    public const UPDATED_AT = null;
    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'pesan_kontak';

    protected $fillable = [
        'nama_pengirim',
        'kontak',
        'isi_pesan',
    ];
}
