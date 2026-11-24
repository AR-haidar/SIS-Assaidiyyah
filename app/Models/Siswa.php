<?php

namespace App\Models;

use App\Enums\JenisBerkasSiswa;
use App\Enums\JenisKelamin;
use App\Enums\StatusDataSiswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diubah_pada';

    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'siswa';

    protected $fillable = [
        'pengguna_id',
        'nis',
        'nisn',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'tahun_masuk',
        'status_masuk_id',
        'status_kesiswaan_id',
        'status_data',
        'nama_ayah',
        'nik_ayah',
        'pekerjaan_ayah_id',
        'nama_ibu',
        'nik_ibu',
        'no_whatsapp_orang_tua',
        'kebutuhan_khusus',
        'disabilitas',
        'nomor_kip_pip',
        'nama_wali',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'jenis_kelamin' => JenisKelamin::class,
        'tahun_masuk' => 'integer',
        'status_data' => StatusDataSiswa::class,
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function statusMasuk(): BelongsTo
    {
        return $this->belongsTo(Referensi::class, 'status_masuk_id');
    }

    public function statusKesiswaan(): BelongsTo
    {
        return $this->belongsTo(Referensi::class, 'status_kesiswaan_id');
    }

    public function pekerjaanAyah(): BelongsTo
    {
        return $this->belongsTo(Referensi::class, 'pekerjaan_ayah_id');
    }

    public function keanggotaanRombel(): HasMany
    {
        return $this->hasMany(SiswaRombel::class, 'siswa_id');
    }

    public function rombel(): BelongsToMany
    {
        return $this->belongsToMany(Rombel::class, 'siswa_rombel', 'siswa_id', 'rombel_id')
            ->withTimestamps('dibuat_pada', 'diubah_pada');
    }

    public function berkas(): BelongsToMany
    {
        return $this->belongsToMany(Berkas::class, 'berkas_siswa', 'siswa_id', 'berkas_id')
            ->withPivot('jenis')
            ->withTimestamps('dibuat_pada', 'diubah_pada');
    }

    public function presensi(): HasMany
    {
        return $this->hasMany(PresensiSiswa::class, 'siswa_id');
    }

    public function tagihan(): HasMany
    {
        return $this->hasMany(Tagihan::class, 'siswa_id');
    }

    public function permohonan(): HasMany
    {
        return $this->hasMany(PermohonanPerubahanData::class, 'siswa_id');
    }

    /**
     * Mengganti dokumen siswa untuk satu jenis (pasfoto, kartu keluarga, akta lahir).
     * PK berkas_siswa adalah (siswa_id, jenis), sehingga dokumen lama dilepas dulu.
     */
    public function setBerkas(JenisBerkasSiswa $jenis, int $berkasId): void
    {
        $this->berkas()->wherePivot('jenis', $jenis->value)->detach();
        $this->berkas()->attach($berkasId, ['jenis' => $jenis->value]);
    }
}
