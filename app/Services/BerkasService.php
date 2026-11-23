<?php

namespace App\Services;

use App\Models\Berkas;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class BerkasService
{
    public function __construct(
        private readonly string $disk = 'public',
    ) {}

    public function simpan(UploadedFile $file, string $folder = 'umum'): Berkas
    {
        $folderBersih = trim($folder, '/');
        $path = $file->store($folderBersih, $this->disk);

        if (! is_string($path) || $path === '') {
            throw new \RuntimeException('Gagal menyimpan berkas ke penyimpanan.');
        }

        $mime = $file->getMimeType();
        if (! is_string($mime) || $mime === '') {
            $mime = $file->getClientMimeType();
        }
        if (! is_string($mime) || $mime === '') {
            $mime = 'application/octet-stream';
        }

        $ukuranByte = $file->getSize();
        $pengunggahId = Auth::id();

        return Berkas::query()->create([
            'nama_asli' => $file->getClientOriginalName(),
            'path' => $path,
            'mime' => $mime,
            'ukuran_byte' => is_numeric($ukuranByte) ? (int) $ukuranByte : 0,
            'diunggah_oleh' => is_numeric($pengunggahId) ? (int) $pengunggahId : null,
        ]);
    }

    public function ganti(?Berkas $lama, UploadedFile $baru, string $folder = 'umum'): Berkas
    {
        $berkasBaru = $this->simpan($baru, $folder);

        if ($lama instanceof Berkas) {
            $this->hapus($lama);
        }

        return $berkasBaru;
    }

    public function hapus(?Berkas $berkas): void
    {
        if (! $berkas instanceof Berkas) {
            return;
        }

        $storage = $this->storage();

        if ($berkas->path !== '' && $storage->exists($berkas->path)) {
            $storage->delete($berkas->path);
        }

        $berkas->delete();
    }

    public function respons(Berkas $berkas, Request $request, bool $unduh = false): Response
    {
        $storage = $this->storage();

        abort_unless(
            $berkas->path !== '' && $storage->exists($berkas->path),
            404
        );

        $headers = [
            'Content-Type' => $berkas->mime !== '' ? $berkas->mime : 'application/octet-stream',
        ];

        if ($unduh) {
            return $storage->download($berkas->path, $berkas->nama_asli, $headers);
        }

        return $storage->response($berkas->path, $berkas->nama_asli, $headers);
    }

    private function storage(): FilesystemAdapter
    {
        /** @var FilesystemAdapter $adapter */
        $adapter = Storage::disk($this->disk);

        return $adapter;
    }
}
