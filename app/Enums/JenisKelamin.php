<?php

namespace App\Enums;

enum JenisKelamin: string
{
    case L = 'L';
    case P = 'P';

    public function label(): string
    {
        return match ($this) {
            self::L => 'Laki-laki',
            self::P => 'Perempuan',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function opsi(): array
    {
        return [
            self::L->value => self::L->label(),
            self::P->value => self::P->label(),
        ];
    }
}