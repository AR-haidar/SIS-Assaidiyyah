<?php

namespace App\Actions\Operator;

use App\Enums\JenisAkun;
use App\Models\Pengguna;
use App\Models\Peran;
use App\Support\LogAktivitas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SimpanAkunStaf
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data, ?Pengguna $target = null): Pengguna
    {
        return DB::transaction(function () use ($data, $target): Pengguna {
            $kodePeran = array_values(array_unique((array) ($data['peran'] ?? [])));
            $peranIds = Peran::query()
                ->whereIn('kode', $kodePeran)
                ->pluck('id')
                ->all();

            $aktif = array_key_exists('aktif', $data) ? (bool) $data['aktif'] : true;
            $namaLengkap = isset($data['nama_lengkap']) && $data['nama_lengkap'] !== ''
                ? (string) $data['nama_lengkap']
                : null;

            if ($target instanceof Pengguna) {
                $atributUpdate = [
                    'username' => (string) $data['username'],
                    'nama_lengkap' => $namaLengkap,
                    'aktif' => $aktif,
                ];

                if (! empty($data['password'])) {
                    $atributUpdate['password_hash'] = Hash::make((string) $data['password']);
                }

                $target->update($atributUpdate);
                $pengguna = $target;
                $aksi = 'ubah_akun_staf';
                $ringkasan = "Memperbarui akun staf {$pengguna->username}.";
            } else {
                /** @var Pengguna|null $terhapus */
                $terhapus = Pengguna::withTrashed()
                    ->where('username', (string) $data['username'])
                    ->first();

                $atributBaru = [
                    'username' => (string) $data['username'],
                    'password_hash' => Hash::make((string) ($data['password'] ?? 'password')),
                    'jenis_akun' => JenisAkun::Staf,
                    'nama_lengkap' => $namaLengkap,
                    'aktif' => $aktif,
                ];

                if ($terhapus instanceof Pengguna) {
                    if ($terhapus->trashed()) {
                        $terhapus->restore();
                    }
                    $terhapus->update($atributBaru);
                    $pengguna = $terhapus;
                    $aksi = 'pulihkan_akun_staf';
                    $ringkasan = "Memulihkan dan memperbarui akun staf {$pengguna->username}.";
                } else {
                    $pengguna = Pengguna::query()->create($atributBaru);
                    $aksi = 'buat_akun_staf';
                    $ringkasan = "Membuat akun staf baru {$pengguna->username}.";
                }
            }

            $pengguna->peran()->sync($peranIds);
            $pengguna->load('peran');

            LogAktivitas::catat(
                modul: 'akun',
                aksi: $aksi,
                objek: $pengguna,
                ringkasan: $ringkasan
            );

            return $pengguna;
        });
    }
}
