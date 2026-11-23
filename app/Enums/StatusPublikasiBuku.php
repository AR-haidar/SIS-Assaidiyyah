<?php

namespace App\Enums;

enum StatusPublikasiBuku: string
{
    case Draft = 'draft';
    case Aktif = 'aktif';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Aktif => 'Aktif',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function opsi(): array
    {
        return [
            self::Draft->value => self::Draft->label(),
            self::Aktif->value => self::Aktif->label(),
        ];
    }
}
