<?php

namespace App\Actions\Operator;

use App\Models\Berkas;
use App\Models\Pegawai;
use App\Services\BerkasService;
use App\Support\LogAktivitas;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class SimpanPegawai
{
    public function __construct(
        private readonly BerkasService $berkasService,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data, ?Pegawai $target = null): Pegawai
    {
        return DB::transaction(function () use ($data, $target): Pegawai {
            /** @var UploadedFile|null $filePasfoto */
            $filePasfoto = ($data['pasfoto'] ?? null) instanceof UploadedFile
                ? $data['pasfoto']
                : null;
            $hapusPasfoto = (bool) ($data['hapus_pasfoto'] ?? false);

            $normalisasiNullable = fn (string $kunci): mixed => array_key_exists($kunci, $data) && $data[$kunci] !== ''
                ? $data[$kunci]
                : null;

            $atribut = [
                'pengguna_id' => $normalisasiNullable('pengguna_id'),
                'nip' => $normalisasiNullable('nip'),
                'nuptk' => $normalisasiNullable('nuptk'),
                'nik' => $normalisasiNullable('nik'),
                'nama_lengkap' => (string) $data['nama_lengkap'],
                'tempat_lahir' => $normalisasiNullable('tempat_lahir'),
                'tanggal_lahir' => $normalisasiNullable('tanggal_lahir'),
                'pendidikan_terakhir_id' => $normalisasiNullable('pendidikan_terakhir_id'),
                'program_studi' => $normalisasiNullable('program_studi'),
                'no_hp_wa' => $normalisasiNullable('no_hp_wa'),
                'email' => $normalisasiNullable('email'),
                'alamat_domisili' => $normalisasiNullable('alamat_domisili'),
                'status_kepegawaian_id' => $normalisasiNullable('status_kepegawaian_id'),
                'jenis_gtk_id' => $normalisasiNullable('jenis_gtk_id'),
                'mata_pelajaran_diampu' => $normalisasiNullable('mata_pelajaran_diampu'),
                'nomor_sk_pengangkatan' => $normalisasiNullable('nomor_sk_pengangkatan'),
            ];

            if ($target instanceof Pegawai) {
                $pegawai = $target;
                $pasfotoLama = $pegawai->pasfoto;

                if ($filePasfoto instanceof UploadedFile) {
                    $berkasBaru = $this->berkasService->ganti($pasfotoLama, $filePasfoto, 'pegawai/pasfoto');
                    $atribut['pasfoto_berkas_id'] = $berkasBaru->id;
                } elseif ($hapusPasfoto) {
                    if ($pasfotoLama instanceof Berkas) {
                        $this->berkasService->hapus($pasfotoLama);
                    }
                    $atribut['pasfoto_berkas_id'] = null;
                }

                $pegawai->update($atribut);
                $aksi = 'ubah_pegawai';
                $ringkasan = "Memperbarui data pegawai {$pegawai->nama_lengkap}.";
            } else {
                $terhapus = Pegawai::withTrashed()
                    ->where(function ($query) use ($atribut): void {
                        foreach (['nip', 'nuptk', 'nik', 'pengguna_id'] as $kolomUnik) {
                            if (! empty($atribut[$kolomUnik])) {
                                $query->orWhere($kolomUnik, $atribut[$kolomUnik]);
                            }
                        }
                    })
                    ->first();

                if ($filePasfoto instanceof UploadedFile) {
                    $berkasBaru = $this->berkasService->simpan($filePasfoto, 'pegawai/pasfoto');
                    $atribut['pasfoto_berkas_id'] = $berkasBaru->id;
                } else {
                    $atribut['pasfoto_berkas_id'] = null;
                }

                if ($terhapus instanceof Pegawai) {
                    if ($terhapus->trashed()) {
                        $terhapus->restore();
                    }
                    $terhapus->update($atribut);
                    $pegawai = $terhapus;
                    $aksi = 'pulihkan_pegawai';
                    $ringkasan = "Memulihkan dan memperbarui data pegawai {$pegawai->nama_lengkap}.";
                } else {
                    $pegawai = Pegawai::query()->create($atribut);
                    $aksi = 'tambah_pegawai';
                    $ringkasan = "Menambahkan pegawai baru {$pegawai->nama_lengkap}.";
                }
            }

            $pegawai->load(['pasfoto', 'pengguna']);

            LogAktivitas::catat(
                modul: 'pegawai',
                aksi: $aksi,
                objek: $pegawai,
                ringkasan: $ringkasan
            );

            return $pegawai;
        });
    }
}
