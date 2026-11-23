<?php

namespace App\Http\Requests\Operator;

use App\Models\Pengguna;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SimpanAkunStafRequest extends FormRequest
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
        /** @var Pengguna|null $target */
        $target = $this->route('pengguna');
        $sedangEdit = $target instanceof Pengguna;

        $aturanUsername = ['required', 'string', 'max:100', 'alpha_dash'];
        if (! $sedangEdit) {
            $aturanUsername[] = Rule::unique('pengguna', 'username')
                ->where(fn ($query) => $query->whereNull('dihapus_pada'));
        } else {
            $aturanUsername[] = Rule::unique('pengguna', 'username')
                ->ignore($target->id)
                ->where(fn ($query) => $query->whereNull('dihapus_pada'));
        }

        return [
            'username' => $aturanUsername,
            'nama_lengkap' => ['nullable', 'string', 'max:150'],
            'password' => [
                $sedangEdit ? 'nullable' : 'required',
                'string',
                Password::min(8),
            ],
            'aktif' => ['sometimes', 'boolean'],
            'peran' => ['required', 'array', 'min:1'],
            'peran.*' => [
                'required',
                'string',
                Rule::exists('peran', 'kode')->where(
                    fn ($query) => $query->where('kode', '!=', 'siswa')
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
            'username' => 'nama pengguna',
            'nama_lengkap' => 'nama lengkap',
            'password' => 'kata sandi',
            'aktif' => 'status aktif',
            'peran' => 'peran akun',
        ];
    }
}
