<?php

namespace App\Http\Requests\Operator;

use App\Enums\JenisAkun;
use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\Referensi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SimpanPegawaiRequest extends FormRequest
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
        /** @var Pegawai|null $target */
        $target = $this->route('pegawai');
        $sedangEdit = $target instanceof Pegawai;

        $uniquePegawai = function (string $kolom) use ($sedangEdit, $target) {
            $rule = Rule::unique('pegawai', $kolom)
                ->where(fn ($query) => $query->whereNull('dihapus_pada'));

            if ($sedangEdit) {
                $rule = $rule->ignore($target->id);
            }

            return $rule;
        };

        $uniquePengguna = Rule::unique('pegawai', 'pengguna_id')
            ->where(fn ($query) => $query->whereNull('dihapus_pada'));
        if ($sedangEdit) {
            $uniquePengguna = $uniquePengguna->ignore($target->id);
        }

        return [
            'pengguna_id' => ['nullable', 'integer', 'exists:pengguna,id', $uniquePengguna],
            'nip' => ['nullable', 'string', 'max:30', $uniquePegawai('nip')],
            'nuptk' => ['nullable', 'string', 'digits:16', $uniquePegawai('nuptk')],
            'nik' => ['nullable', 'string', 'digits:16', $uniquePegawai('nik')],
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'tempat_lahir' => ['nullable', 'string', 'max:80'],
            'tanggal_lahir' => ['nullable', 'date'],
            'pendidikan_terakhir_id' => ['nullable', 'integer', 'exists:referensi,id'],
            'program_studi' => ['nullable', 'string', 'max:100'],
            'no_hp_wa' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'alamat_domisili' => ['nullable', 'string'],
            'status_kepegawaian_id' => ['required', 'integer', 'exists:referensi,id'],
            'jenis_gtk_id' => ['required', 'integer', 'exists:referensi,id'],
            'mata_pelajaran_diampu' => ['nullable', 'string', 'max:255'],
            'nomor_sk_pengangkatan' => ['nullable', 'string', 'max:100'],
            'pasfoto' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'hapus_pasfoto' => ['sometimes', 'boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $cekKelompok = function (?int $id, string $kelompok, string $field) use ($validator): void {
                if ($id === null) {
                    return;
                }

                $valid = Referensi::query()
                    ->where('id', $id)
                    ->where('kelompok', $kelompok)
                    ->exists();

                if (! $valid) {
                    $validator->errors()->add($field, "Pilihan {$field} tidak sesuai kelompok referensi {$kelompok}.");
                }
            };

            $cekKelompok(
                $this->filled('pendidikan_terakhir_id') ? (int) $this->input('pendidikan_terakhir_id') : null,
                'pendidikan_terakhir',
                'pendidikan_terakhir_id'
            );
            $cekKelompok(
                $this->filled('status_kepegawaian_id') ? (int) $this->input('status_kepegawaian_id') : null,
                'status_kepegawaian',
                'status_kepegawaian_id'
            );
            $cekKelompok(
                $this->filled('jenis_gtk_id') ? (int) $this->input('jenis_gtk_id') : null,
                'jenis_gtk',
                'jenis_gtk_id'
            );

            if ($this->filled('pengguna_id')) {
                $penggunaId = (int) $this->input('pengguna_id');
                $akunValid = Pengguna::query()
                    ->where('id', $penggunaId)
                    ->where('jenis_akun', JenisAkun::Staf->value)
                    ->whereHas('peran', fn ($q) => $q->where('kode', 'guru'))
                    ->exists();

                if (! $akunValid) {
                    $validator->errors()->add('pengguna_id', 'Akun yang ditautkan harus berjenis staf dan memiliki peran guru.');
                }
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'pengguna_id' => 'akun pengguna',
            'nip' => 'NIP',
            'nuptk' => 'NUPTK',
            'nik' => 'NIK',
            'nama_lengkap' => 'nama lengkap',
            'tempat_lahir' => 'tempat lahir',
            'tanggal_lahir' => 'tanggal lahir',
            'pendidikan_terakhir_id' => 'pendidikan terakhir',
            'program_studi' => 'program studi',
            'no_hp_wa' => 'nomor HP/WA',
            'email' => 'email',
            'alamat_domisili' => 'alamat domisili',
            'status_kepegawaian_id' => 'status kepegawaian',
            'jenis_gtk_id' => 'jenis GTK',
            'mata_pelajaran_diampu' => 'mata pelajaran diampu',
            'nomor_sk_pengangkatan' => 'nomor SK pengangkatan',
            'pasfoto' => 'pasfoto',
        ];
    }
}
