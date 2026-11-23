<?php
// app/Http/Requests/Keuangan/AjukanPembayaranRequest.php

namespace App\Http\Requests\Keuangan;

use App\Http\Requests\Keuangan\Concerns\ValidasiMetodePembayaran;
use Illuminate\Foundation\Http\FormRequest;

class AjukanPembayaranRequest extends FormRequest
{
    use ValidasiMetodePembayaran;

    public function authorize(): bool
    {
        return true; // kepemilikan tagihan dicek di query controller, bukan di sini
    }

    public function rules(): array
    {
        return [
            'metode_pembayaran_id' => $this->aturanMetodePembayaran(),
            'tanggal_pembayaran'   => ['nullable', 'date', 'before_or_equal:today'],
            'nominal'              => ['required', 'numeric', 'gt:0'],
            'bukti'                => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // 5 MB
        ];
    }

    public function messages(): array
    {
        return [
            'bukti.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti.mimes'    => 'Bukti harus berupa JPG, PNG, atau PDF.',
            'bukti.max'      => 'Ukuran bukti maksimal 5 MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'metode_pembayaran_id' => 'metode pembayaran',
            'tanggal_pembayaran'   => 'tanggal pembayaran',
            'nominal'              => 'nominal',
            'bukti'                => 'bukti pembayaran',
        ];
    }
}