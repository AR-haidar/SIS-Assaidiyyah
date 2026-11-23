<?php

namespace App\Http\Requests\Keuangan;

use App\Models\JenisTagihan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SimpanJenisTagihanRequest extends FormRequest
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
        $routeJenis = $this->route('jenisTagihan');
        $jenisId = $routeJenis instanceof JenisTagihan
            ? $routeJenis->getKey()
            : (is_numeric($routeJenis) ? (int) $routeJenis : null);

        return [
            'nama' => [
                'required',
                'string',
                'max:60',
                Rule::unique('jenis_tagihan', 'nama')
                    ->ignore($jenisId)
                    ->whereNull('dihapus_pada'),
            ],
            'bulanan' => ['required', 'boolean'],
            'aktif' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nama' => 'nama jenis tagihan',
            'bulanan' => 'jenis bulanan',
            'aktif' => 'status aktif',
        ];
    }
}
