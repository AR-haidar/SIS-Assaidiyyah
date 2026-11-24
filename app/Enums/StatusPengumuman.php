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
            self::Draft => 'Draf',
            self::Terbit => 'Terbit',
            self::Arsip => 'Arsip',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function opsi(): array
    {
        $opsi = [];
        foreach (self::cases() as $kasus) {
            $opsi[$kasus->value] = $kasus->label();
        }

        return $opsi;
    }
}
