<?php

namespace App\Http\Requests\Siswa;

use App\Enums\JenisKelamin;
use App\Models\Siswa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SimpanSiswaRequest extends FormRequest
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
        $siswaId = $routeSiswa instanceof Siswa
            ? $routeSiswa->getKey()
            : (is_numeric($routeSiswa) ? (int) $routeSiswa : null);

        $tahunMaks = (int) now()->format('Y') + 1;

        return [
            // Langkah 1: Akademik
            'nis' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('siswa', 'nis')
                    ->ignore($siswaId)
                    ->whereNull('dihapus_pada'),
            ],
            'nisn' => [
                'nullable',
                'string',
                'digits:10',
                Rule::unique('siswa', 'nisn')
                    ->ignore($siswaId)
                    ->whereNull('dihapus_pada'),
            ],
            'tahun_masuk' => [
                'nullable',
                'integer',
                'min:1900',
                'max:'.$tahunMaks,
            ],
            'status_masuk_id' => [
                'nullable',
                'integer',
                Rule::exists('referensi', 'id')
                    ->where('kelompok', 'status_masuk')
                    ->where('aktif', true)
                    ->whereNull('dihapus_pada'),
            ],
            'status_kesiswaan_id' => [
                'nullable',
                'integer',
                Rule::exists('referensi', 'id')
                    ->where('kelompok', 'status_kesiswaan')
                    ->where('aktif', true)
                    ->whereNull('dihapus_pada'),
            ],

            // Langkah 2: Biodata
            'nik' => [
                'nullable',
                'string',
                'digits:16',
                Rule::unique('siswa', 'nik')
                    ->ignore($siswaId)
                    ->whereNull('dihapus_pada'),
            ],
            'nama_lengkap' => [
                'nullable',
                'string',
                'max:150',
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
            'jenis_kelamin' => [
                'nullable',
                Rule::enum(JenisKelamin::class),
            ],
            'alamat' => [
                'nullable',
                'string',
                'max:2000',
            ],

            // Langkah 3: Orang Tua
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

            // Langkah 4: Berkas Siswa
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
        return [
            'nis' => 'NIS',
            'nisn' => 'NISN',
            'nik' => 'NIK',
            'nama_lengkap' => 'nama lengkap',
            'tempat_lahir' => 'tempat lahir',
            'tanggal_lahir' => 'tanggal lahir',
            'jenis_kelamin' => 'jenis kelamin',
            'alamat' => 'alamat',
            'tahun_masuk' => 'tahun masuk',
            'status_masuk_id' => 'status masuk',
            'status_kesiswaan_id' => 'status kesiswaan',
            'nama_ayah' => 'nama ayah',
            'nik_ayah' => 'NIK ayah',
            'pekerjaan_ayah_id' => 'pekerjaan ayah',
            'nama_ibu' => 'nama ibu',
            'nik_ibu' => 'NIK ibu',
            'no_whatsapp_orang_tua' => 'nomor WhatsApp orang tua',
            'pasfoto' => 'pasfoto',
            'kartu_keluarga' => 'kartu keluarga',
            'akta_lahir' => 'akta lahir',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nisn.digits' => ':attribute harus terdiri dari tepat 10 digit angka.',
            'nisn.unique' => ':attribute sudah digunakan oleh siswa lain.',
            'nis.unique' => ':attribute sudah digunakan oleh siswa lain.',
            'nik.digits' => ':attribute harus terdiri dari tepat 16 digit angka.',
            'nik.unique' => ':attribute sudah digunakan oleh siswa lain.',
            'nik_ayah.digits' => ':attribute harus terdiri dari tepat 16 digit angka.',
            'nik_ibu.digits' => ':attribute harus terdiri dari tepat 16 digit angka.',
            'status_masuk_id.exists' => ':attribute tidak valid atau tidak aktif.',
            'status_kesiswaan_id.exists' => ':attribute tidak valid atau tidak aktif.',
            'pekerjaan_ayah_id.exists' => ':attribute tidak valid atau tidak aktif.',
            'no_whatsapp_orang_tua.regex' => 'Format :attribute tidak valid.',
            'pasfoto.mimes' => ':attribute harus berformat JPG, JPEG, PNG, atau WEBP.',
            'kartu_keluarga.mimes' => ':attribute harus berformat JPG, JPEG, PNG, WEBP, atau PDF.',
            'akta_lahir.mimes' => ':attribute harus berformat JPG, JPEG, PNG, WEBP, atau PDF.',
            '*.max' => 'Ukuran :attribute maksimal 50 MB.',
        ];
    }
}
