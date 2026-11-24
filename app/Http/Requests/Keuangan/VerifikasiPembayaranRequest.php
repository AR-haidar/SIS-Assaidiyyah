<?php

namespace App\Http\Requests\Keuangan;

use App\Enums\StatusVerifikasi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifikasiPembayaranRequest extends FormRequest
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
            'status_verifikasi' => [
                'required',
                Rule::in([
                    StatusVerifikasi::Disetujui->value,
                    StatusVerifikasi::Ditolak->value,
                ]),
            ],
            'tanggal_pembayaran' => ['nullable', 'date'],
            'alasan_penolakan' => [
                Rule::requiredIf(fn (): bool => $this->input('status_verifikasi') === StatusVerifikasi::Ditolak->value),
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'status_verifikasi' => 'status verifikasi',
            'tanggal_pembayaran' => 'tanggal pembayaran',
            'alasan_penolakan' => 'alasan penolakan',
        ];
    }
}
