<?php

namespace App\Enums;

enum StatusPresensiSiswa: string
{
    case Hadir = 'hadir';
    case Izin = 'izin';
    case Sakit = 'sakit';
    case Alpa = 'alpa';

    public function label(): string
    {
        return match ($this) {
            self::Hadir => 'Hadir',
            self::Izin => 'Izin',
            self::Sakit => 'Sakit',
            self::Alpa => 'Alpa',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function opsi(): array
    {
        return [
            self::Hadir->value => self::Hadir->label(),
            self::Izin->value => self::Izin->label(),
            self::Sakit->value => self::Sakit->label(),
            self::Alpa->value => self::Alpa->label(),
        ];
    }
}
