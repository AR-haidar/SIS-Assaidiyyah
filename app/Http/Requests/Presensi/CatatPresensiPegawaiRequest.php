<?php

namespace App\Http\Requests\Presensi;

use App\Enums\StatusPresensiPegawai;
use App\Enums\SumberPresensi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CatatPresensiPegawaiRequest extends FormRequest
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
            'pegawai_id' => ['nullable', 'integer', Rule::exists('pegawai', 'id')->whereNull('dihapus_pada')],
            'tanggal' => ['required', 'date'],
            'status' => ['required', Rule::enum(StatusPresensiPegawai::class)],
            'sumber' => ['nullable', Rule::enum(SumberPresensi::class)],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'pegawai_id' => 'pegawai',
            'tanggal' => 'tanggal presensi',
            'status' => 'status presensi pegawai',
            'sumber' => 'sumber presensi',
            'keterangan' => 'keterangan',
        ];
    }
}
