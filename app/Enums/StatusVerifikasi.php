<?php

namespace App\Enums;

enum StatusVerifikasi: string
{
    case Menunggu = 'menunggu';
    case Disetujui = 'disetujui';
    case Ditolak = 'ditolak';

    public function label(): string
    {
        return match ($this) {
            self::Menunggu => 'Menunggu',
            self::Disetujui => 'Disetujui',
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
            self::Disetujui->value => self::Disetujui->label(),
            self::Ditolak->value => self::Ditolak->label(),
        ];
    }
}
