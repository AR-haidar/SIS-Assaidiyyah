<?php

namespace App\Enums;

enum StatusPublikasiBuku: string
{
    case Draft = 'draft';
    case Aktif = 'aktif';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draf',
            self::Aktif => 'Aktif',
        };
    }
}
