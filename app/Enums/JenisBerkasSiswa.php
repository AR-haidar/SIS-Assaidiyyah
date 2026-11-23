<?php

namespace App\Enums;

enum JenisBerkasSiswa: string
{
    case Pasfoto = 'pasfoto';
    case KartuKeluarga = 'kartu_keluarga';
    case AktaLahir = 'akta_lahir';

    public function label(): string
    {
        return match ($this) {
            self::Pasfoto => 'Pasfoto',
            self::KartuKeluarga => 'Kartu Keluarga',
            self::AktaLahir => 'Akta Kelahiran',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function opsi(): array
    {
        return [
            self::Pasfoto->value => self::Pasfoto->label(),
            self::KartuKeluarga->value => self::KartuKeluarga->label(),
            self::AktaLahir->value => self::AktaLahir->label(),
        ];
    }
}
