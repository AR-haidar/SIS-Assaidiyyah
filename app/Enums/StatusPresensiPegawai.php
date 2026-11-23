<?php

namespace App\Enums;

enum StatusPresensiPegawai: string
{
    case Hadir = 'hadir';
    case IzinTugasLuar = 'izin_tugas_luar';
    case Sakit = 'sakit';

    public function label(): string
    {
        return match ($this) {
            self::Hadir => 'Hadir',
            self::IzinTugasLuar => 'Izin / Tugas Luar',
            self::Sakit => 'Sakit',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function opsi(): array
    {
        return [
            self::Hadir->value => self::Hadir->label(),
            self::IzinTugasLuar->value => self::IzinTugasLuar->label(),
            self::Sakit->value => self::Sakit->label(),
        ];
    }
}
