<?php

namespace App\Http\Requests\Akun;

use App\Enums\JenisAkun;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class GantiKataSandiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $pengguna = $this->user();
        $isSiswa = $pengguna?->jenis_akun === JenisAkun::Siswa;

        $aturanBaru = $isSiswa
            ? ['required', 'string', 'digits:6', 'confirmed']
            : ['required', 'string', Password::min(8), 'confirmed'];

        return [
            'kata_sandi_saat_ini' => ['required', 'string', 'current_password'],
            'kata_sandi_baru' => $aturanBaru,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        $isSiswa = $this->user()?->jenis_akun === JenisAkun::Siswa;

        return [
            'kata_sandi_saat_ini' => $isSiswa ? 'PIN saat ini' : 'kata sandi saat ini',
            'kata_sandi_baru' => $isSiswa ? 'PIN baru' : 'kata sandi baru',
        ];
    }
}
