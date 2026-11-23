<?php
// app/Http/Requests/Keuangan/InputPembayaranRequest.php

namespace App\Http\Requests\Keuangan;

use App\Http\Requests\Keuangan\Concerns\ValidasiMetodePembayaran;
use Illuminate\Foundation\Http\FormRequest;

class InputPembayaranRequest extends FormRequest
{
    use ValidasiMetodePembayaran;

    public function authorize(): bool
    {
        return true; // middleware peran:keuangan
    }

    public function rules(): array
    {
        return [
            'metode_pembayaran_id' => $this->aturanMetodePembayaran(),
            'tanggal_pembayaran'   => ['required', 'date', 'before_or_equal:today'],
            'nominal'              => ['required', 'numeric', 'gt:0'],
            'bukti'                => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
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