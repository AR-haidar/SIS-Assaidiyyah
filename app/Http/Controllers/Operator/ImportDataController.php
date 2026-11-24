<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Imports\GtkImport;
use App\Imports\SiswaImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportDataController extends Controller
{
    public function imporSiswa(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        $import = new SiswaImport;
        Excel::import($import, $request->file('file'));

        $pesan = "Impor siswa selesai: {$import->jumlahBerhasil} data diproses";
        if ($import->jumlahDilewati > 0) {
            $pesan .= ", {$import->jumlahDilewati} baris dilewati";
        }
        $pesan .= '.';

        return back()
            ->with('status', $pesan)
            ->with('import_warnings', $import->peringatan);
    }

    public function imporGtk(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        $import = new GtkImport;
        Excel::import($import, $request->file('file'));

        $pesan = "Impor data GTK selesai: {$import->jumlahBerhasil} data diproses";
        if ($import->jumlahDilewati > 0) {
            $pesan .= ", {$import->jumlahDilewati} baris dilewati";
        }
        $pesan .= '.';

        return back()
            ->with('status', $pesan)
            ->with('import_warnings', $import->peringatan);
    }

    public function templateSiswa(): BinaryFileResponse
    {
        $export = new class implements FromArray, WithHeadings
        {
            public function headings(): array
            {
                return [
                    'No',
                    'Nama Lengkap',
                    'NISN',
                    'NIK',
                    'Tempat Lahir',
                    'Tanggal Lahir',
                    'Tingkat - Rombel',
                    'Umur',
                    'Status',
                    'Jenis Kelamin',
                    'Alamat',
                    'No Telepon',
                    'Kebutuhan Khusus',
                    'Disabilitas',
                    'Nomor KIP/PIP',
                    'Nama Ayah Kandung',
                    'Nama Ibu Kandung',
                    'Nama Wali',
                ];
            }

            public function array(): array
            {
                return [
                    [
                        1,
                        'Contoh Nama Siswa',
                        '0123456789',
                        '3203010101010001',
                        'Cianjur',
                        '2018-05-12',
                        'Kelas 2 - 01',
                        8,
                        'Aktif',
                        'Laki-laki',
                        'Kp. Contoh No. 1',
                        '6281234567890',
                        'Tidak ada',
                        'Tidak ada',
                        '',
                        'Nama Ayah',
                        'Nama Ibu',
                        '',
                    ],
                ];
            }
        };

        return Excel::download($export, 'template-siswa-emis.xlsx');
    }

    public function templateGtk(): BinaryFileResponse
    {
        $export = new class implements FromArray, WithHeadings
        {
            public function headings(): array
            {
                return [
                    'Nama Lengkap',
                    'NIK',
                    'NUPTK',
                    'Status Kepegawaian',
                    'NIP',
                    'Jenis Kelamin',
                    'Tempat Lahir',
                    'Tanggal Lahir',
                    'Nomor Handphone',
                    'Email',
                    'Email Akun Madrasah Digital',
                    'Password Awal',
                    'Tugas',
                    'Mata Pelajaran',
                    'Penempatan',
                ];
            }

            public function array(): array
            {
                return [
                    [
                        'Contoh Nama Guru, S.Pd',
                        '3203010101010002',
                        '1234567890123456',
                        'Non PNS',
                        '',
                        'Perempuan',
                        'Cianjur',
                        '1990-03-20',
                        '6281234567891',
                        'guru@example.com',
                        '',
                        '',
                        'Guru Kelas',
                        'Tematik / Kelas',
                        'Kelas 2 - 01',
                    ],
                ];
            }
        };

        return Excel::download($export, 'template-gtk.xlsx');
    }
}
