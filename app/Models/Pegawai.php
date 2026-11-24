<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diubah_pada';

    public const DELETED_AT = 'dihapus_pada';

    protected $table = 'pegawai';

    protected $fillable = [
        'pengguna_id',
        'nip',
        'nuptk',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan_terakhir_id',
        'program_studi',
        'no_hp_wa',
        'email',
        'alamat_domisili',
        'status_kepegawaian_id',
        'jenis_gtk_id',
        'mata_pelajaran_diampu',
        'nomor_sk_pengangkatan',
        'pasfoto_berkas_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function pendidikanTerakhir(): BelongsTo
    {
        return $this->belongsTo(Referensi::class, 'pendidikan_terakhir_id');
    }

    public function statusKepegawaian(): BelongsTo
    {
        return $this->belongsTo(Referensi::class, 'status_kepegawaian_id');
    }

    public function jenisGtk(): BelongsTo
    {
        return $this->belongsTo(Referensi::class, 'jenis_gtk_id');
    }

    public function pasfoto(): BelongsTo
    {
        return $this->belongsTo(Berkas::class, 'pasfoto_berkas_id');
    }

    public function rombelWali(): HasMany
    {
        return $this->hasMany(Rombel::class, 'wali_kelas_id');
    }

    public function presensi(): HasMany
    {
        return $this->hasMany(PresensiPegawai::class, 'pegawai_id');
    }
}
