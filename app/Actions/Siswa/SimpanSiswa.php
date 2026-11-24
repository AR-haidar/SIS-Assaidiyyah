<?php

namespace App\Actions\Siswa;

use App\Enums\JenisBerkasSiswa;
use App\Enums\StatusDataSiswa;
use App\Models\Siswa;
use App\Services\BerkasService;
use App\Support\LogAktivitas;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class SimpanSiswa
{
    public function __construct(
        private readonly BerkasService $berkasService,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, UploadedFile|null>  $berkas
     */
    public function handle(array $data, ?Siswa $siswa = null, array $berkas = []): Siswa
    {
        return DB::transaction(function () use ($data, $siswa, $berkas): Siswa {
            $adalahBaru = ! ($siswa instanceof Siswa);
            $target = $siswa ?? new Siswa;

            $kolomDiizinkan = [
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
                'nama_ayah',
                'nik_ayah',
                'pekerjaan_ayah_id',
                'nama_ibu',
                'nik_ibu',
                'no_whatsapp_orang_tua',
            ];

            foreach ($kolomDiizinkan as $kolom) {
                if (array_key_exists($kolom, $data)) {
                    $target->{$kolom} = $data[$kolom];
                }
            }

            if ($adalahBaru && ! $target->status_data) {
                $target->status_data = StatusDataSiswa::Draft;
            }

            $target->save();

            $petaBerkas = [
                'pasfoto' => JenisBerkasSiswa::Pasfoto,
                'kartu_keluarga' => JenisBerkasSiswa::KartuKeluarga,
                'akta_lahir' => JenisBerkasSiswa::AktaLahir,
            ];

            $target->loadMissing('berkas');

            foreach ($petaBerkas as $inputKey => $jenisEnum) {
                $file = $berkas[$inputKey] ?? null;

                if (! ($file instanceof UploadedFile)) {
                    continue;
                }

                $berkasLama = $target->berkas
                    ->first(fn ($item) => $item->pivot?->jenis === $jenisEnum->value);

                $disk = $jenisEnum === JenisBerkasSiswa::Pasfoto
                    ? BerkasService::DISK_PUBLIC
                    : BerkasService::DISK_PRIVATE;

                $folder = 'siswa/'.$target->getKey().'/'.$jenisEnum->value;

                $berkasBaru = $this->berkasService->ganti($berkasLama, $file, $folder, $disk);
                $target->setBerkas($jenisEnum, $berkasBaru->getKey());
            }

            $target->load('berkas');

            LogAktivitas::catat(
                modul: 'siswa',
                aksi: $adalahBaru ? 'simpan_draf' : 'perbarui_siswa',
                objek: $target,
                ringkasan: ($adalahBaru ? 'Menyimpan draf siswa ' : 'Memperbarui data siswa ')
                    .($target->nama_lengkap ?? ('#'.$target->getKey())),
            );

            return $target;
        });
    }
}
