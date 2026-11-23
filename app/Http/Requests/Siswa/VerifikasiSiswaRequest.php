<?php

namespace App\Http\Requests\Siswa;

use App\Enums\JenisKelamin;
use App\Models\Siswa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifikasiSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $routeSiswa = $this->route('siswa');
        $siswa = $routeSiswa instanceof Siswa ? $routeSiswa : null;
        $siswaId = $siswa instanceof Siswa
            ? $siswa->getKey()
            : (is_numeric($routeSiswa) ? (int) $routeSiswa : null);

        $tahunMaks = (int) now()->format('Y') + 1;

        return [
            'nama_lengkap' => [
                $siswa?->nama_lengkap ? 'sometimes' : 'required',
                'string',
                'max:150',
            ],
            'nisn' => [
                $siswa?->nisn ? 'sometimes' : 'required',
                'string',
                'digits:10',
                Rule::unique('siswa', 'nisn')
                    ->ignore($siswaId)
                    ->whereNull('dihapus_pada'),
            ],
            'nik' => [
                $siswa?->nik ? 'sometimes' : 'required',
                'string',
                'digits:16',
                Rule::unique('siswa', 'nik')
                    ->ignore($siswaId)
                    ->whereNull('dihapus_pada'),
            ],
            'jenis_kelamin' => [
                $siswa?->jenis_kelamin ? 'sometimes' : 'required',
                Rule::enum(JenisKelamin::class),
            ],
            'status_masuk_id' => [
                $siswa?->status_masuk_id ? 'sometimes' : 'required',
                'integer',
                Rule::exists('referensi', 'id')
                    ->where('kelompok', 'status_masuk')
                    ->where('aktif', true)
                    ->whereNull('dihapus_pada'),
            ],

            // Field pendukung yang ikut divalidasi jika disertakan
            'nis' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('siswa', 'nis')
                    ->ignore($siswaId)
                    ->whereNull('dihapus_pada'),
            ],
            'tempat_lahir' => [
                'nullable',
                'string',
                'max:80',
            ],
            'tanggal_lahir' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'alamat' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'tahun_masuk' => [
                'nullable',
                'integer',
                'min:1900',
                'max:'.$tahunMaks,
            ],
            'status_kesiswaan_id' => [
                'nullable',
                'integer',
                Rule::exists('referensi', 'id')
                    ->where('kelompok', 'status_kesiswaan')
                    ->where('aktif', true)
                    ->whereNull('dihapus_pada'),
            ],
            'nama_ayah' => [
                'nullable',
                'string',
                'max:150',
            ],
            'nik_ayah' => [
                'nullable',
                'string',
                'digits:16',
            ],
            'pekerjaan_ayah_id' => [
                'nullable',
                'integer',
                Rule::exists('referensi', 'id')
                    ->where('kelompok', 'pekerjaan')
                    ->where('aktif', true)
                    ->whereNull('dihapus_pada'),
            ],
            'nama_ibu' => [
                'nullable',
                'string',
                'max:150',
            ],
            'nik_ibu' => [
                'nullable',
                'string',
                'digits:16',
            ],
            'no_whatsapp_orang_tua' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9+\-\s()]{6,20}$/',
            ],
            'pasfoto' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:51200',
            ],
            'kartu_keluarga' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:51200',
            ],
            'akta_lahir' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:51200',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return (new SimpanSiswaRequest)->attributes();
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return array_merge((new SimpanSiswaRequest)->messages(), [
            'nama_lengkap.required' => ':attribute wajib diisi sebelum data siswa diverifikasi.',
            'nisn.required' => ':attribute wajib diisi sebelum data siswa diverifikasi.',
            'nik.required' => ':attribute wajib diisi sebelum data siswa diverifikasi.',
            'jenis_kelamin.required' => ':attribute wajib dipilih sebelum data siswa diverifikasi.',
            'status_masuk_id.required' => ':attribute wajib dipilih sebelum data siswa diverifikasi.',
        ]);
    }
}
