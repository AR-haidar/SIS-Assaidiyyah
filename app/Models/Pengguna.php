<?php

namespace App\Models;

use App\Enums\JenisAkun;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = 'diubah_pada';
    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'pengguna';

    protected $fillable = [
        'username',
        'password_hash',
        'jenis_akun',
        'nama_lengkap',
        'aktif',
        'terakhir_login',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'jenis_akun' => JenisAkun::class,
        'aktif' => 'boolean',
        'terakhir_login' => 'datetime',
        'password_hash' => 'hashed',
    ];

    /**
     * Kolom kata sandi bernama password_hash (bukan password).
     * Untuk akun siswa, nilainya adalah hash dari PIN.
     */
    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    public function getAuthPassword(): string
    {
        return (string) $this->password_hash;
    }

    /**
     * Tabel pengguna tidak punya kolom remember_token (sesuai rancangan),
     * sehingga fitur "ingat saya" dinonaktifkan.
     */
    public function getRememberTokenName(): string
    {
        return '';
    }

    public function peran(): BelongsToMany
    {
        return $this->belongsToMany(Peran::class, 'pengguna_peran', 'pengguna_id', 'peran_id')
            ->withTimestamps('dibuat_pada', 'diubah_pada');
    }

    public function pegawai(): HasOne
    {
        return $this->hasOne(Pegawai::class, 'pengguna_id');
    }

    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class, 'pengguna_id');
    }

    public function punyaPeran(string ...$kode): bool
    {
        return $this->peran()->whereIn('kode', $kode)->exists();
    }
}
