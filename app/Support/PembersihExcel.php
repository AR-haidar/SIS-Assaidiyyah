<?php

namespace App\Support;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class PembersihExcel
{
    public static function teks(mixed $nilai): ?string
    {
        if ($nilai === null) {
            return null;
        }

        if (is_float($nilai) || is_int($nilai)) {
            $teks = number_format((float) $nilai, 0, '', '');
        } else {
            $teks = trim((string) $nilai);
        }

        $teks = ltrim($teks, "'");
        $teks = trim($teks);

        return $teks === '' || strtolower($teks) === 'nan' ? null : $teks;
    }

    public static function nama(mixed $nilai): ?string
    {
        $teks = self::teks($nilai);

        if ($teks === null) {
            return null;
        }

        return rtrim($teks, " \t\n\r\0\x0B,");
    }

    public static function angkaDigit(mixed $nilai, ?int $panjangWajib = null): ?string
    {
        $teks = self::teks($nilai);

        if ($teks === null) {
            return null;
        }

        $digit = preg_replace('/\D+/', '', $teks) ?? '';

        if ($digit === '') {
            return null;
        }

        if ($panjangWajib !== null && strlen($digit) !== $panjangWajib) {
            return null;
        }

        return $digit;
    }

    public static function nomorTelepon(mixed $nilai): ?string
    {
        $teks = self::teks($nilai);

        if ($teks === null) {
            return null;
        }

        $bersih = preg_replace('/[^0-9+]/', '', $teks) ?? '';

        return $bersih === '' ? null : substr($bersih, 0, 20);
    }

    public static function tanggal(mixed $nilai): ?string
    {
        if ($nilai === null || $nilai === '') {
            return null;
        }

        try {
            if (is_numeric($nilai)) {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $nilai))
                    ->format('Y-m-d');
            }

            $teks = self::teks($nilai);

            return $teks !== null ? Carbon::parse($teks)->format('Y-m-d') : null;
        } catch (\Throwable) {
            return null;
        }
    }

    public static function jenisKelamin(mixed $nilai): ?string
    {
        $teks = strtolower((string) self::teks($nilai));

        return match ($teks) {
            'l', 'laki-laki', 'laki - laki', 'pria' => 'L',
            'p', 'perempuan', 'wanita' => 'P',
            default => null,
        };
    }
}
