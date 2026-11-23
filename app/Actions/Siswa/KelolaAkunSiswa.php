<?php

namespace App\Actions\Siswa;

use App\Enums\JenisAkun;
use App\Models\Pengguna;
use App\Models\Peran;
use App\Models\Siswa;
use App\Support\LogAktivitas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class KelolaAkunSiswa
{
    /**
     * @return array{pengguna: Pengguna, pin_terang: string}
     */
    public function buatAtauResetPin(Siswa $siswa, ?string $pinBaru = null): array
    {
        return DB::transaction(function () use ($siswa, $pinBaru): array {
            $nisn = trim((string) ($siswa->nisn ?? ''));
            if (! preg_match('/^\d{10}$/', $nisn)) {
                throw ValidationException::withMessages([
                    'nisn' => ['Siswa harus memiliki NISN 10 digit yang valid untuk membuat atau mereset akun.'],
                ]);
            }

            $pin = $pinBaru !== null && $pinBaru !== ''
                ? $pinBaru
                : str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            if (! preg_match('/^\d{6}$/', $pin)) {
                throw ValidationException::withMessages([
                    'pin' => ['PIN akun siswa harus terdiri dari 6 digit angka.'],
                ]);
            }

            $peranSiswa = Peran::query()->where('kode', 'siswa')->first();
            if (! ($peranSiswa instanceof Peran)) {
                throw ValidationException::withMessages([
                    'peran' => ['Peran siswa belum tersedia di basis data.'],
                ]);
            }

            $pengguna = $siswa->pengguna;
            $adalahBaru = false;

            if (! ($pengguna instanceof Pengguna)) {
                $pengguna = Pengguna::withTrashed()
                    ->where('username', $nisn)
                    ->first();

                if ($pengguna instanceof Pengguna && $pengguna->trashed()) {
                    $pengguna->restore();
                }
            }

            if (! ($pengguna instanceof Pengguna)) {
                $adalahBaru = true;
                $pengguna = new Pengguna;
            }

            $pengguna->username = $nisn;
            $pengguna->password_hash = Hash::make($pin);
            $pengguna->jenis_akun = JenisAkun::Siswa;
            $pengguna->nama_lengkap = $siswa->nama_lengkap;
            $pengguna->aktif = true;
            $pengguna->save();

            $pengguna->peran()->syncWithoutDetaching([$peranSiswa->getKey()]);

            if ((int) $siswa->pengguna_id !== (int) $pengguna->getKey()) {
                $siswa->pengguna_id = $pengguna->getKey();
                $siswa->save();
            }

            LogAktivitas::catat(
                modul: 'siswa',
                aksi: $adalahBaru ? 'buat_akun_siswa' : 'reset_pin_siswa',
                objek: $siswa,
                ringkasan: ($adalahBaru ? 'Membuat akun siswa ' : 'Mereset PIN akun siswa ')
                    .$nisn.' ('.($siswa->nama_lengkap ?? 'Siswa').')',
            );

            return [
                'pengguna' => $pengguna,
                'pin_terang' => $pin,
            ];
        });
    }
}
