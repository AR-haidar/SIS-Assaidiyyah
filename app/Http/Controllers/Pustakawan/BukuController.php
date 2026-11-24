<?php

namespace App\Http\Controllers\Pustakawan;

use App\Enums\StatusPublikasiBuku;
use App\Http\Controllers\Controller;
use App\Models\Berkas;
use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class BukuController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->validate([
            'q' => ['nullable', 'string', 'max:'.Buku::MAKS_JUDUL_KARAKTER],
            'kategori' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', Rule::enum(StatusPublikasiBuku::class)],
        ]);

        $buku = Buku::query()
            ->with('kategori')
            ->when(isset($filter['q']), function ($query) use ($filter): void {
                $kata = $filter['q'];
                $query->where(function ($query) use ($kata): void {
                    $query->whereLike('judul', '%'.$kata.'%')->orWhereLike('penulis', '%'.$kata.'%');
                });
            })
            ->when($filter['kategori'] ?? null, fn ($query, $id) => $query->whereHas('kategori', fn ($query) => $query->whereKey($id)))
            ->when($filter['status'] ?? null, fn ($query, $status) => $query->where('status_publikasi', $status))
            ->latest(Buku::CREATED_AT)
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('perpustakaan.data-buku', [
            'buku' => $buku,
            'kategoriBuku' => KategoriBuku::query()->orderBy('nama')->get(['id', 'nama']),
            'filter' => $filter,
            'totalBuku' => Buku::count(),
            'bukuAktif' => Buku::aktif()->count(),
            'bukuDraf' => Buku::where('status_publikasi', StatusPublikasiBuku::Draft->value)->count(),
        ]);
    }

    public function create(): View
    {
        return view('perpustakaan.tambah-data-buku', [
            'kategoriBuku' => KategoriBuku::query()->where('aktif', true)->orderBy('nama')->get(['id', 'nama']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->simpan($request);

        return redirect()->route('pustakawan.data-buku.index')->with('status', 'Buku berhasil ditambahkan.');
    }

    public function edit(Buku $buku): View
    {
        $buku->load(['sampul', 'naskah', 'kategori']);
        $kategoriSaatIni = $buku->kategori->min('id');

        return view('perpustakaan.edit-data-buku', [
            'buku' => $buku,
            'kategoriSaatIni' => $kategoriSaatIni,
            'kategoriBuku' => KategoriBuku::query()
                ->where(fn ($query) => $query->where('aktif', true)->orWhere('id', $kategoriSaatIni))
                ->orderBy('nama')
                ->get(['id', 'nama']),
        ]);
    }

    public function update(Request $request, Buku $buku): RedirectResponse
    {
        $this->simpan($request, $buku);

        return redirect()->route('pustakawan.data-buku.index')->with('status', 'Perubahan buku berhasil disimpan.');
    }

    public function destroy(Buku $buku): RedirectResponse
    {
        $buku->delete();

        return redirect()->route('pustakawan.data-buku.index')->with('status', 'Buku berhasil dihapus dari daftar.');
    }

    public function sampul(Buku $buku): StreamedResponse
    {
        $sampul = $buku->sampul;
        $disk = Storage::disk('local');

        abort_unless($sampul && in_array($sampul->mime, ['image/jpeg', 'image/png', 'image/webp'], true)
            && $disk->exists($sampul->path), 404);

        return $disk->response($sampul->path, null, [
            'Content-Type' => $sampul->mime,
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    private function simpan(Request $request, ?Buku $buku = null): void
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:'.Buku::MAKS_JUDUL_KARAKTER],
            'penulis' => ['required', 'string', 'max:'.Buku::MAKS_NAMA_KARAKTER],
            'kategori_buku_id' => ['required', 'integer', Rule::exists('kategori_buku', 'id')->whereNull('dihapus_pada')],
            'penerbit' => ['nullable', 'string', 'max:'.Buku::MAKS_NAMA_KARAKTER],
            'tahun_terbit' => ['nullable', 'integer', 'between:1,9999'],
            'sinopsis' => ['required', 'string', 'max:'.Buku::MAKS_SINOPSIS_KARAKTER],
            'status_publikasi' => ['required', Rule::enum(StatusPublikasiBuku::class)],
            'sampul' => ['nullable', 'file', $buku ? 'mimetypes:image/jpeg,image/png,image/webp' : 'mimetypes:image/jpeg,image/png', 'max:'.(Buku::MAKS_SAMPUL_MB * 1024)],
            'naskah' => [$buku?->naskah()->exists() ? 'nullable' : 'required', 'file', 'mimetypes:application/pdf,application/epub+zip', 'max:'.(Buku::MAKS_NASKAH_MB * 1024)],
        ], [
            'required' => ':Attribute wajib diisi.',
            'string' => ':Attribute harus berupa teks.',
            'max.string' => ':Attribute maksimal :max karakter.',
            'kategori_buku_id.required' => 'Pilih kategori buku.',
            'kategori_buku_id.integer' => 'Pilih kategori buku yang tersedia.',
            'kategori_buku_id.exists' => 'Kategori buku tersebut sudah tidak tersedia.',
            'tahun_terbit.integer' => 'Masukkan tahun terbit dalam angka bulat.',
            'tahun_terbit.between' => 'Masukkan tahun terbit antara 1 dan 9999.',
            'status_publikasi.required' => 'Pilih status publikasi buku.',
            'status_publikasi.enum' => 'Pilih status publikasi yang tersedia.',
            'sampul.file' => 'Pilih berkas sampul yang valid.',
            'sampul.mimetypes' => $buku ? 'Pilih sampul dalam format JPG, PNG, atau WEBP.' : 'Pilih sampul dalam format JPG atau PNG.',
            'sampul.max' => 'Ukuran sampul maksimal '.Buku::MAKS_SAMPUL_MB.' MB.',
            'sampul.uploaded' => 'Sampul gagal diunggah. Periksa ukuran berkas dan batas unggahan server.',
            'naskah.required' => 'Unggah berkas naskah digital.',
            'naskah.file' => 'Pilih berkas naskah yang valid.',
            'naskah.mimetypes' => 'Pilih naskah dalam format PDF atau ePub.',
            'naskah.max' => 'Ukuran naskah maksimal '.Buku::MAKS_NASKAH_MB.' MB.',
            'naskah.uploaded' => 'Naskah gagal diunggah. Periksa ukuran berkas dan batas unggahan server.',
        ], [
            'judul' => 'judul buku',
            'penulis' => 'nama penulis',
            'penerbit' => 'nama penerbit',
            'sinopsis' => 'deskripsi atau sinopsis',
        ]);

        $kategoriId = (int) $data['kategori_buku_id'];
        $penggunaId = $request->user()->getAuthIdentifier();
        unset($data['kategori_buku_id'], $data['sampul'], $data['naskah']);
        $berkasBaru = [];
        $pathTerunggah = [];

        try {
            foreach (['sampul', 'naskah'] as $jenis) {
                $file = $request->file($jenis);

                if (! $file) {
                    continue;
                }

                $path = $file->store('perpustakaan/'.$jenis, 'local');

                if ($path === false) {
                    throw new RuntimeException('Tidak dapat menyimpan berkas buku.');
                }

                $pathTerunggah[] = $path;
                $berkasBaru[$jenis] = [
                    'path' => $path,
                    'nama_asli' => Str::limit($file->getClientOriginalName(), 255, ''),
                    'mime' => $file->getMimeType(),
                    'ukuran_byte' => $file->getSize(),
                    'diunggah_oleh' => $penggunaId,
                ];
            }

            DB::transaction(function () use ($buku, $data, $kategoriId, $penggunaId, $berkasBaru): void {
                $rekaman = $buku ? Buku::query()->lockForUpdate()->findOrFail($buku->id) : new Buku(['dibuat_oleh' => $penggunaId]);
                $kategoriSaatIni = $rekaman->exists ? $rekaman->kategori()->min('kategori_buku.id') : null;
                $kategori = KategoriBuku::query()->lockForUpdate()->find($kategoriId);

                if (! $kategori || (! $kategori->aktif && (int) $kategoriSaatIni !== $kategoriId)) {
                    throw ValidationException::withMessages(['kategori_buku_id' => 'Pilih kategori buku yang masih aktif.']);
                }

                $rekaman->fill($data);

                foreach ($berkasBaru as $jenis => $atribut) {
                    $rekaman->setAttribute($jenis.'_berkas_id', Berkas::create($atribut)->id);
                }

                $rekaman->save();

                if ((int) $kategoriSaatIni !== $kategoriId) {
                    $rekaman->kategori()->sync([$kategoriId]);
                }
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($pathTerunggah);

            if ($exception instanceof ValidationException || $exception instanceof ModelNotFoundException) {
                throw $exception;
            }

            report($exception);

            throw ValidationException::withMessages(['simpan' => 'Buku gagal disimpan. Silakan coba kembali.']);
        }
    }
}
