<?php

namespace App\Http\Requests\Operator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AturWaliKelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->punyaPeran('operator') ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'wali_kelas_id' => [
                'nullable',
                'integer',
                Rule::exists('pegawai', 'id')->where(
                    fn ($query) => $query->whereNull('dihapus_pada')
                ),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'wali_kelas_id' => 'wali kelas',
        ];
    }
}
