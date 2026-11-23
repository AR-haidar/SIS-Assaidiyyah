<?php

namespace App\Enums;

enum SumberPembayaran: string
{
    case PengajuanWali = 'pengajuan_wali';
    case InputPetugas  = 'input_petugas';

    public function label(): string
    {
        return match ($this) {
            self::PengajuanWali => 'Pengajuan Wali',
            self::InputPetugas  => 'Input Petugas',
        };
    }
}