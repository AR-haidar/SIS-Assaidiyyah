<?php

namespace App\Enums;

enum StatusPermohonan: string
{
    case Diajukan = 'diajukan';
    case Diproses = 'diproses';
    case Selesai = 'selesai';
    case Ditolak = 'ditolak';

    public function label(): string
    {
        return match ($this) {
            self::Diajukan => 'Diajukan',
            self::Diproses => 'Diproses',
            self::Selesai => 'Selesai',
            self::Ditolak => 'Ditolak',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function opsi(): array
    {
        return [
            self::Diajukan->value => self::Diajukan->label(),
            self::Diproses->value => self::Diproses->label(),
            self::Selesai->value => self::Selesai->label(),
            self::Ditolak->value => self::Ditolak->label(),
        ];
    }
}
