<?php

namespace App\Enums;

enum SumberPresensi: string
{
    case Biometrik = 'biometrik';
    case Manual = 'manual';

    public function label(): string
    {
        return match ($this) {
            self::Biometrik => 'Biometrik',
            self::Manual => 'Manual',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function opsi(): array
    {
        return [
            self::Biometrik->value => self::Biometrik->label(),
            self::Manual->value => self::Manual->label(),
        ];
    }
}
