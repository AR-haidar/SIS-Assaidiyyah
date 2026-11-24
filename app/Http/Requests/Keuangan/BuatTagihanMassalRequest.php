<?php

namespace App\Http\Requests\Keuangan;

use App\Models\JenisTagihan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class BuatTagihanMassalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $periode = $this->input('periode_bulan');
        if (is_string($periode) && preg_match('/^\d{4}-\d{2}$/', trim($periode)) === 1) {
            $this->merge([
                'periode_bulan' => trim($periode).'-01',
            ]);
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'jenis_tagihan_id' => [
                'required',
                'integer',
                Rule::exists('jenis_tagihan', 'id')
                    ->where('aktif', true)
                    ->whereNull('dihapus_pada'),
            ],
            'tahun_ajaran_id' => [
                'required',
                'integer',
                Rule::exists('tahun_ajaran', 'id')->whereNull('dihapus_pada'),
            ],
            'siswa_ids' => ['nullable', 'array'],
            'siswa_ids.*' => ['integer', Rule::exists('siswa', 'id')->whereNull('dihapus_pada')],
            'rombel_id' => ['nullable', 'integer', Rule::exists('rombel', 'id')->whereNull('dihapus_pada')],
            'periode_bulan' => ['nullable', 'date'],
            'nominal' => ['required', 'numeric', 'min:1', 'max:999999999999.99'],
            'jatuh_tempo' => ['required', 'date'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $siswaIds = $this->input('siswa_ids');
            $rombelId = $this->input('rombel_id');

            $punyaSiswa = is_array($siswaIds) && count(array_filter($siswaIds)) > 0;
            $punyaRombel = is_numeric($rombelId) && (int) $rombelId > 0;

            if (! $punyaSiswa && ! $punyaRombel) {
                $validator->errors()->add(
                    'siswa_ids',
                    'Pilih minimal satu siswa atau satu rombel tujuan tagihan.',
                );
            }

            $jenisId = $this->input('jenis_tagihan_id');
            if (is_numeric($jenisId)) {
                $jenis = JenisTagihan::query()->find((int) $jenisId);
                if ($jenis instanceof JenisTagihan && $jenis->bulanan && ! $this->filled('periode_bulan')) {
                    $validator->errors()->add(
                        'periode_bulan',
                        'Periode bulan wajib diisi untuk jenis tagihan bulanan.',
                    );
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
            'jenis_tagihan_id' => 'jenis tagihan',
            'tahun_ajaran_id' => 'tahun ajaran',
            'siswa_ids' => 'daftar siswa',
            'rombel_id' => 'rombel',
            'periode_bulan' => 'periode bulan',
            'nominal' => 'nominal tagihan',
            'jatuh_tempo' => 'tanggal jatuh tempo',
        ];
    }
}
