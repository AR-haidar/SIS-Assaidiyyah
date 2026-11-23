<?php

namespace App\Http\Requests\Keuangan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTagihanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // middleware peran:keuangan
    }

    public function rules(): array
    {
        return [
            'siswa_id' => [
                'required', 'integer',
                Rule::exists('siswa', 'id')->whereNull('dihapus_pada'),
            ],
            'jenis_tagihan_id' => [
                'required', 'integer',
                Rule::exists('jenis_tagihan', 'id')
                    ->where(fn ($q) => $q->where('aktif', true)->whereNull('dihapus_pada')),
            ],
            'nominal'         => ['required', 'numeric', 'gt:0', 'max:999999999999.99'],
            'tanggal_tagihan' => ['required', 'date'],
            'jatuh_tempo'     => ['required', 'date', 'after_or_equal:tanggal_tagihan'],
        ];
    }

    public function attributes(): array
    {
        return [
            'siswa_id'         => 'siswa',
            'jenis_tagihan_id' => 'jenis tagihan',
            'nominal'          => 'nominal tagihan',
            'tanggal_tagihan'  => 'tanggal tagihan',
            'jatuh_tempo'      => 'tanggal jatuh tempo',
        ];
    }
}