<?php

namespace App\Enums;

enum StatusPengumuman: string
{
    case Draft = 'draft';
    case Terbit = 'terbit';
    case Arsip = 'arsip';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Terbit => 'Terbit',
            self::Arsip => 'Arsip',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function opsi(): array
    {
        return [
            self::Draft->value => self::Draft->label(),
            self::Terbit->value => self::Terbit->label(),
            self::Arsip->value => self::Arsip->label(),
        ];
    }
}
