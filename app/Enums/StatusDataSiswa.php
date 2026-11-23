<?php

namespace App\Enums;

enum StatusDataSiswa: string
{
    case Draft = 'draft';
    case Terverifikasi = 'terverifikasi';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Terverifikasi => 'Terverifikasi',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function opsi(): array
    {
        return [
            self::Draft->value => self::Draft->label(),
            self::Terverifikasi->value => self::Terverifikasi->label(),
        ];
    }
}
