<?php

namespace App\Enums;

enum JenisSasaran: string
{
    case Publik = 'publik';
    case Peran = 'peran';
    case Rombel = 'rombel';

    public function label(): string
    {
        return match ($this) {
            self::Publik => 'Publik',
            self::Peran => 'Peran',
            self::Rombel => 'Rombel',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function opsi(): array
    {
        return [
            self::Publik->value => self::Publik->label(),
            self::Peran->value => self::Peran->label(),
            self::Rombel->value => self::Rombel->label(),
        ];
    }
}
