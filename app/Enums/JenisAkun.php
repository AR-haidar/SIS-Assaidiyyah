<?php

namespace App\Enums;

enum JenisAkun: string
{
    case Staf = 'staf';
    case Siswa = 'siswa';

    public function label(): string
    {
        return match ($this) {
            self::Staf => 'Staf & Guru',
            self::Siswa => 'Siswa / Wali Murid',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function opsi(): array
    {
        return [
            self::Staf->value => self::Staf->label(),
            self::Siswa->value => self::Siswa->label(),
        ];
    }
}
