<?php

namespace App\Services;

use App\Models\Berkas;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BerkasService
{
    private const DISK = 'local';

    public function simpan(UploadedFile $file, string $folder = 'umum', ?int $diunggahOleh = null): Berkas
    {
        $bersihFolder = trim(str_replace('\\', '/', $folder), '/');
        $bersihFolder = $bersihFolder !== '' ? $bersihFolder : 'umum';

        $ekstensi = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'bin');
        $namaSimpan = Str::uuid()->toString().'.'.$ekstensi;
        $path = Storage::disk(self::DISK)->putFileAs($bersihFolder, $file, $namaSimpan);

        $mime = (string) ($file->getMimeType() ?: $file->getClientMimeType() ?: 'application/octet-stream');
        $ukuran = (int) ($file->getSize() ?: 0);

        $aktorId = $diunggahOleh ?? auth()->id();

        return Berkas::create([
            'nama_asli' => mb_substr($file->getClientOriginalName(), 0, 255),
            'path' => $path !== false ? $path : $bersihFolder.'/'.$namaSimpan,
            'mime' => mb_substr($mime, 0, 100),
            'ukuran_byte' => $ukuran,
            'diunggah_oleh' => $aktorId !== null ? (int) $aktorId : null,
            'diunggah_pada' => now(),
        ]);
    }

    public function ganti(?Berkas $lama, UploadedFile $baru, string $folder = 'umum', ?int $diunggahOleh = null): Berkas
    {
        $berkasBaru = $this->simpan($baru, $folder, $diunggahOleh);

        if ($lama !== null) {
            $this->hapus($lama);
        }

        return $berkasBaru;
    }

    public function hapus(?Berkas $berkas): void
    {
        if ($berkas === null) {
            return;
        }

        if ($berkas->path !== '' && Storage::disk(self::DISK)->exists($berkas->path)) {
            Storage::disk(self::DISK)->delete($berkas->path);
        }

        if ($berkas->exists) {
            $berkas->delete();
        }
    }

    public function respons(Berkas $berkas, Request $request, bool $unduh = false): Response
    {
        abort_unless($berkas->path !== '' && Storage::disk(self::DISK)->exists($berkas->path), 404);

        $absolutePath = Storage::disk(self::DISK)->path($berkas->path);
        $ukuran = (int) filesize($absolutePath);
        $mime = $berkas->mime ?: 'application/octet-stream';
        $disposition = $unduh ? 'attachment' : 'inline';
        $namaAman = str_replace(['"', "\r", "\n"], '', $berkas->nama_asli ?: basename($absolutePath));

        $headers = [
            'Content-Type' => $mime,
            'Content-Disposition' => $disposition.'; filename="'.$namaAman.'"',
            'Accept-Ranges' => 'bytes',
        ];

        $rangeHeader = $request->header('Range');
        if (! $unduh && is_string($rangeHeader) && preg_match('/^bytes=(\d*)-(\d*)$/', trim($rangeHeader), $matches)) {
            $mulai = $matches[1] !== '' ? (int) $matches[1] : 0;
            $akhir = $matches[2] !== '' ? (int) $matches[2] : ($ukuran - 1);

            if ($mulai > $akhir || $mulai >= $ukuran) {
                return response('', 416, [
                    'Content-Range' => "bytes */{$ukuran}",
                ]);
            }

            $akhir = min($akhir, $ukuran - 1);
            $panjang = $akhir - $mulai + 1;

            return new StreamedResponse(function () use ($absolutePath, $mulai, $panjang): void {
                $handle = fopen($absolutePath, 'rb');
                if ($handle === false) {
                    return;
                }

                fseek($handle, $mulai);
                $sisa = $panjang;
                $chunkSize = 65536;

                while ($sisa > 0 && ! feof($handle)) {
                    $baca = fread($handle, min($chunkSize, $sisa));
                    if ($baca === false) {
                        break;
                    }
                    echo $baca;
                    flush();
                    $sisa -= strlen($baca);
                }

                fclose($handle);
            }, 206, array_merge($headers, [
                'Content-Length' => (string) $panjang,
                'Content-Range' => "bytes {$mulai}-{$akhir}/{$ukuran}",
            ]));
        }

        return new StreamedResponse(function () use ($absolutePath): void {
            readfile($absolutePath);
        }, 200, array_merge($headers, [
            'Content-Length' => (string) $ukuran,
        ]));
    }
}
