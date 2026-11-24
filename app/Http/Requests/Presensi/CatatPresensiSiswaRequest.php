<?php

namespace App\Http\Requests\Presensi;

use App\Enums\StatusPresensiSiswa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CatatPresensiSiswaRequest extends FormRequest
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
        return [
            'tanggal' => ['required', 'date'],
            'presensi' => ['required', 'array', 'min:1'],
            'presensi.*.siswa_id' => ['required', 'integer', Rule::exists('siswa', 'id')->whereNull('dihapus_pada')],
            'presensi.*.status' => ['required', Rule::enum(StatusPresensiSiswa::class)],
            'presensi.*.keterangan' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'tanggal' => 'tanggal presensi',
            'presensi' => 'daftar presensi siswa',
            'presensi.*.siswa_id' => 'siswa',
            'presensi.*.status' => 'status presensi',
            'presensi.*.keterangan' => 'keterangan',
        ];
    }
}
