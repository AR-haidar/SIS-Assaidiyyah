<?php

namespace App\Enums;

enum StatusValidasiPresensi: string
{
    case Menunggu = 'menunggu';
    case Valid = 'valid';
    case Ditolak = 'ditolak';

    public function label(): string
    {
        return match ($this) {
            self::Menunggu => 'Menunggu',
            self::Valid => 'Valid',
            self::Ditolak => 'Ditolak',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function opsi(): array
    {
        return [
            self::Menunggu->value => self::Menunggu->label(),
            self::Valid->value => self::Valid->label(),
            self::Ditolak->value => self::Ditolak->label(),
        ];
    }
}
