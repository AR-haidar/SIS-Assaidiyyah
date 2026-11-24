<?php

namespace App\Http\Requests\Keuangan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AjukanPembayaranRequest extends FormRequest
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
            'nominal' => ['required', 'numeric', 'min:1', 'max:999999999999.99'],
            'metode_pembayaran_id' => [
                'required',
                'integer',
                Rule::exists('referensi', 'id')
                    ->where('kelompok', 'metode_pembayaran')
                    ->where('aktif', true)
                    ->whereNull('dihapus_pada'),
            ],
            'tanggal_pembayaran' => ['nullable', 'date'],
            'bukti_berkas' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:51200'],
            'langsung_setujui' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nominal' => 'nominal pembayaran',
            'metode_pembayaran_id' => 'metode pembayaran',
            'tanggal_pembayaran' => 'tanggal pembayaran',
            'bukti_berkas' => 'bukti pembayaran',
        ];
    }
}
