<?php

namespace App\Actions\Siswa;

use App\Enums\StatusDataSiswa;
use App\Models\Siswa;
use App\Support\LogAktivitas;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VerifikasiSiswa
{
    public function __construct(
        private readonly SimpanSiswa $simpanSiswa,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, UploadedFile|null>  $berkas
     */
    public function handle(Siswa $siswa, array $data = [], array $berkas = []): Siswa
    {
        return DB::transaction(function () use ($siswa, $data, $berkas): Siswa {
            $target = ($data !== [] || $berkas !== [])
                ? $this->simpanSiswa->handle($data, $siswa, $berkas)
                : $siswa;

            $kesalahan = [];

            $nama = trim((string) ($target->nama_lengkap ?? ''));
            if ($nama === '') {
                $kesalahan['nama_lengkap'][] = 'Nama lengkap wajib diisi sebelum data siswa diverifikasi.';
            }

            $nisn = (string) ($target->nisn ?? '');
            if (! preg_match('/^\d{10}$/', $nisn)) {
                $kesalahan['nisn'][] = 'NISN wajib 10 digit angka sebelum data siswa diverifikasi.';
            } else {
                $duplikatNisn = Siswa::query()
                    ->where('nisn', $nisn)
                    ->whereKeyNot($target->getKey())
                    ->exists();
                if ($duplikatNisn) {
                    $kesalahan['nisn'][] = 'NISN sudah digunakan oleh siswa lain.';
                }
            }

            $nik = (string) ($target->nik ?? '');
            if (! preg_match('/^\d{16}$/', $nik)) {
                $kesalahan['nik'][] = 'NIK wajib 16 digit angka sebelum data siswa diverifikasi.';
            } else {
                $duplikatNik = Siswa::query()
                    ->where('nik', $nik)
                    ->whereKeyNot($target->getKey())
                    ->exists();
                if ($duplikatNik) {
                    $kesalahan['nik'][] = 'NIK sudah digunakan oleh siswa lain.';
                }
            }

            if (! $target->jenis_kelamin) {
                $kesalahan['jenis_kelamin'][] = 'Jenis kelamin wajib dipilih sebelum data siswa diverifikasi.';
            }

            if (! $target->status_masuk_id) {
                $kesalahan['status_masuk_id'][] = 'Status masuk wajib dipilih sebelum data siswa diverifikasi.';
            }

            if ($kesalahan !== []) {
                throw ValidationException::withMessages($kesalahan);
            }

            $target->status_data = StatusDataSiswa::Terverifikasi;
            $target->save();

            LogAktivitas::catat(
                modul: 'siswa',
                aksi: 'verifikasi_siswa',
                objek: $target,
                ringkasan: 'Memverifikasi data siswa '.($target->nama_lengkap ?? ('#'.$target->getKey())),
            );

            return $target->fresh(['berkas']) ?? $target;
        });
    }
}
