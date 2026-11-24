<?php

namespace App\Enums;

enum NamaSemester: string
{
    case Ganjil = 'ganjil';
    case Genap = 'genap';

    public function label(): string
    {
        return match ($this) {
            self::Ganjil => 'Ganjil',
            self::Genap => 'Genap',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function opsi(): array
    {
        return [
            self::Ganjil->value => self::Ganjil->label(),
            self::Genap->value => self::Genap->label(),
        ];
    }
}
