<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peran extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diubah_pada';

    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'peran';

    protected $fillable = [
        'kode',
        'nama',
    ];

    public function pengguna(): BelongsToMany
    {
        return $this->belongsToMany(Pengguna::class, 'pengguna_peran', 'peran_id', 'pengguna_id')
            ->withTimestamps('dibuat_pada', 'diubah_pada');
    }
}
