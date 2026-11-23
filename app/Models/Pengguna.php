<?php

namespace App\Models;

use App\Enums\JenisAkun;
use Database\Factories\PenggunaFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    /** @use HasFactory<PenggunaFactory> */
    use HasFactory;

    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diubah_pada';

    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'pengguna';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'password_hash',
        'jenis_akun',
        'nama_lengkap',
        'aktif',
        'terakhir_login',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jenis_akun' => JenisAkun::class,
            'aktif' => 'boolean',
            'terakhir_login' => 'datetime',
        ];
    }

    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    public function getAuthPassword(): string
    {
        return (string) $this->password_hash;
    }

    /**
     * @return BelongsToMany<Peran, $this>
     */
    public function peran(): BelongsToMany
    {
        return $this->belongsToMany(Peran::class, 'pengguna_peran', 'pengguna_id', 'peran_id')
            ->withTimestamps('dibuat_pada', 'diubah_pada');
    }

    /**
     * @return HasOne<Pegawai, $this>
     */
    public function pegawai(): HasOne
    {
        return $this->hasOne(Pegawai::class, 'pengguna_id');
    }

    /**
     * @return HasOne<Siswa, $this>
     */
    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class, 'pengguna_id');
    }

    public function punyaPeran(string ...$kode): bool
    {
        return $this->peran()->whereIn('kode', $kode)->exists();
    }

    /**
     * @return Attribute<string, never>
     */
    protected function namaTampil(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $this->loadMissing(['pegawai', 'siswa']);

                return $this->nama_lengkap
                    ?? $this->pegawai?->nama_lengkap
                    ?? $this->siswa?->nama_lengkap
                    ?? $this->username;
            },
        );
    }
}
