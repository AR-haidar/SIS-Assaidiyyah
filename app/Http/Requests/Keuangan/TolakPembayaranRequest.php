<?php
// app/Http/Requests/Keuangan/TolakPembayaranRequest.php

namespace App\Http\Requests\Keuangan;

use Illuminate\Foundation\Http\FormRequest;

class TolakPembayaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // middleware peran:keuangan
    }

    public function rules(): array
    {
        return [
            'alasan_penolakan' => ['required', 'string', 'min:5', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
            'alasan_penolakan.min'      => 'Alasan penolakan minimal 5 karakter.',
        ];
    }
}