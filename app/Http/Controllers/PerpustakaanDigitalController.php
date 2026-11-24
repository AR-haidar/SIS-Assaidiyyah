<?php

namespace App\Http\Controllers;

use App\Models\Berkas;
use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PerpustakaanDigitalController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->validate([
            'q' => ['nullable', 'string', 'max:'.Buku::MAKS_JUDUL_KARAKTER],
            'kategori' => ['nullable', 'integer', Rule::exists('kategori_buku', 'id')->where('aktif', true)->whereNull('dihapus_pada')],
        ]);

        $katalog = $this->katalog()
            ->when(isset($filter['q']), function ($query) use ($filter): void {
                $pola = '%'.addcslashes($filter['q'], '\\%_').'%';
                $query->where(fn ($query) => $query->whereLike('judul', $pola)->orWhereLike('penulis', $pola));
            })
            ->when(isset($filter['kategori']), fn ($query) => $query->whereHas('kategori', fn ($query) => $query->whereKey($filter['kategori'])))
            ->latest(Buku::CREATED_AT)
            ->orderByDesc('id')
            ->get()
            ->filter(fn (Buku $buku) => $this->tersedia($buku->naskah))
            ->each(fn (Buku $buku) => $buku->setRelation('sampul', $this->sampulTersedia($buku->sampul)));

        return view('perpustakaan-digital', [
            'katalog' => $katalog,
            'kategoriBuku' => KategoriBuku::where('aktif', true)->orderBy('nama')->get(['id', 'nama']),
            'filter' => $filter,
        ]);
    }

    public function detail(int $bookId): View
    {
        $buku = $this->buku($bookId);
        $buku->setRelation('sampul', $this->sampulTersedia($buku->sampul));

        return view('detail-buku', ['buku' => $buku]);
    }

    public function baca(Request $request, int $bookId): View
    {
        abort_unless($request->user()->aktif, 403);

        return view('halaman-baca-buku', ['buku' => $this->buku($bookId)]);
    }

    public function sampul(int $bookId): BinaryFileResponse
    {
        $sampul = $this->sampulTersedia($this->buku($bookId)->sampul);
        abort_if($sampul === null, 404);

        return response()->file(Storage::disk('local')->path($sampul->path), $this->headerBerkas($sampul))->setPrivate();
    }

    public function naskah(Request $request, int $bookId): BinaryFileResponse
    {
        abort_unless($request->user()->aktif, 403);

        $naskah = $this->buku($bookId)->naskah;
        abort_unless($naskah->mime === 'application/pdf', 404);

        return response()->file(Storage::disk('local')->path($naskah->path), $this->headerBerkas($naskah))
            ->setContentDisposition('inline', 'buku-'.$bookId.'.pdf')
            ->setPrivate();
    }

    private function katalog(): Builder
    {
        return Buku::aktif()
            ->whereHas('naskah', fn ($query) => $query->whereIn('mime', ['application/pdf', 'application/epub+zip']))
            ->with(['kategori:id,nama', 'sampul', 'naskah']);
    }

    private function buku(int $id): Buku
    {
        $buku = $this->katalog()->findOrFail($id);
        abort_unless($this->tersedia($buku->naskah), 404, 'Naskah buku tidak tersedia.');

        return $buku;
    }

    private function tersedia(?Berkas $berkas): bool
    {
        return $berkas !== null && Storage::disk('local')->fileExists($berkas->path);
    }

    private function sampulTersedia(?Berkas $sampul): ?Berkas
    {
        return $sampul && in_array($sampul->mime, ['image/jpeg', 'image/png', 'image/webp'], true)
            && $this->tersedia($sampul) ? $sampul : null;
    }

    private function headerBerkas(Berkas $berkas): array
    {
        return [
            'Content-Type' => $berkas->mime,
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ];
    }
}
