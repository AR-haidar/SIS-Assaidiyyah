<?php

namespace App\Services;

use App\Models\Berkas;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class BerkasService
{
    public const DISK = 'public';
    public const MAKS_BYTE = 50 * 1024 * 1024; // 50 MB, keputusan tim

    public function simpan(UploadedFile $file, string $folder): Berkas
    {
        if ($file->getSize() > self::MAKS_BYTE) {
            throw ValidationException::withMessages([
                'berkas' => 'Ukuran berkas maksimal 50 MB.',
            ]);
        }

        // store() memakai nama acak (hashName), bukan nama asli.
        $path = $file->store($folder, self::DISK);

        if ($path === false) {
            throw new RuntimeException('Gagal menyimpan berkas ke disk.');
        }

        try {
            return Berkas::create([
                'nama_asli'     => $file->getClientOriginalName(),
                'path'          => $path,
                'mime'          => $file->getMimeType() ?? 'application/octet-stream',
                'ukuran_byte'   => $file->getSize(),
                'diunggah_oleh' => Auth::id(), // kolom NOT NULL: wajib ada user login
            ]);
        } catch (Throwable $e) {
            Storage::disk(self::DISK)->delete($path); // jangan tinggalkan file yatim
            throw $e;
        }
    }

    public function url(Berkas $berkas): string
    {
        return Storage::disk(self::DISK)->url($berkas->path);
    }

    public function hapus(Berkas $berkas): void
    {
        Storage::disk(self::DISK)->delete($berkas->path);
        $berkas->delete(); // soft delete
    }
}