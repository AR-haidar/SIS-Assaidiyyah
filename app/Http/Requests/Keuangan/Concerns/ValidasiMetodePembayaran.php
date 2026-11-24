<?php
// app/Http/Requests/Keuangan/Concerns/ValidasiMetodePembayaran.php

namespace App\Http\Requests\Keuangan\Concerns;

use Illuminate\Validation\Rules\Exists;

trait ValidasiMetodePembayaran
{
    protected function aturanMetodePembayaran(): array
    {
        return [
            'required',
            'integer',
            Rule::exists('referensi', 'id')
                ->where(fn ($q) => $q
                    ->where('kelompok', 'metode_pembayaran')
                    ->where('aktif', true)
                    ->whereNull('dihapus_pada')),
        ];
    }
}