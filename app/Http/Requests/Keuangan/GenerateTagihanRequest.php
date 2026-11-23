<?php
// app/Http/Requests/Keuangan/GenerateTagihanRequest.php

namespace App\Http\Requests\Keuangan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GenerateTagihanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // akses dibatasi middleware peran:keuangan di route
    }

    public function rules(): array
    {
        return [
            'jenis_tagihan_id' => [
                'required', 'integer',
                Rule::exists('jenis_tagihan', 'id')
                    ->where(fn ($q) => $q->where('aktif', true)->whereNull('dihapus_pada')),
            ],
            'tahun_ajaran_id' => ['required', 'integer', Rule::exists('tahun_ajaran', 'id')],
            'tingkat'         => ['required', 'integer', 'between:1,6'],
            'nominal'         => ['required', 'numeric', 'gt:0', 'max:999999999999.99'],
            'jatuh_tempo'     => ['required', 'date'],
            // Wajib hanya untuk jenis bulanan; pengecekannya ada di TagihanGeneratorService.
            'periode_bulan'   => ['nullable', 'date_format:Y-m-d,Y-m'],
        ];
    }

    public function attributes(): array
    {
        return [
            'jenis_tagihan_id' => 'jenis tagihan',
            'tahun_ajaran_id'  => 'tahun ajaran',
            'tingkat'          => 'tingkat',
            'nominal'          => 'nominal',
            'jatuh_tempo'      => 'jatuh tempo',
            'periode_bulan'    => 'bulan',
        ];
    }
}