<?php

namespace App\Http\Controllers\Pustakawan;

use App\Http\Controllers\Controller;
use App\Models\KategoriBuku;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class KategoriBukuController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:active,inactive'],
        ]);

        $kategori = KategoriBuku::query()
            ->when(isset($filter['status']), fn ($query) => $query->where('aktif', $filter['status'] === 'active'));

        if (isset($filter['q'])) {
            $kata = mb_strtolower($filter['q']);
            $pola = '%'.addcslashes($filter['q'], '\\%_').'%';
            $mirip = [];

            if (preg_match('/^\p{L}{4,}$/u', $kata)) {
                foreach ((clone $kategori)->select('id', 'nama')->cursor() as $calon) {
                    $kataNama = preg_split('/[^\p{L}]+/u', mb_strtolower($calon->nama), -1, PREG_SPLIT_NO_EMPTY);

                    foreach ($kataNama as $bagian) {
                        if ($this->miripSatuKata($kata, $bagian)) {
                            $mirip[] = $calon->id;
                            break;
                        }
                    }
                }
            }

            $kategori->where(fn ($query) => $query->whereLike('nama', $pola)->orWhereIntegerInRaw('id', $mirip));
        }

        return view('perpustakaan.kategori-buku', [
            'kategoriBuku' => $kategori
                ->withCount('buku')
                ->orderBy('nama')
                ->paginate(12)
                ->withQueryString(),
            'totalAktif' => KategoriBuku::where('aktif', true)->count(),
            'filter' => $filter,
        ]);
    }

    private function miripSatuKata(string $kata, string $bagian): bool
    {
        if (levenshtein($kata, $bagian) === 1) {
            return true;
        }

        $huruf = mb_str_split($kata);
        $calon = mb_str_split($bagian);
        if (count($huruf) !== count($calon)) {
            return false;
        }

        $beda = array_keys(array_diff_assoc($huruf, $calon));

        return count($beda) === 2
            && $beda[1] === $beda[0] + 1
            && $huruf[$beda[0]] === $calon[$beda[1]]
            && $huruf[$beda[1]] === $calon[$beda[0]];
    }

    public function store(Request $request): RedirectResponse
    {
        $pesanNamaDuplikat = 'Nama kategori buku sudah digunakan.';
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:kategori_buku,nama'],
            'deskripsi' => ['required', 'string'],
            'category_status' => ['required', 'in:active,inactive'],
        ], [
            'nama.required' => 'Nama kategori buku wajib diisi.',
            'nama.string' => 'Nama kategori buku harus berupa teks.',
            'nama.max' => 'Nama kategori buku maksimal 100 karakter.',
            'nama.unique' => $pesanNamaDuplikat,
            'deskripsi.required' => 'Deskripsi kategori buku wajib diisi.',
            'deskripsi.string' => 'Deskripsi kategori buku harus berupa teks.',
            'category_status.required' => 'Status kategori buku wajib dipilih.',
            'category_status.in' => 'Status kategori buku harus aktif atau nonaktif.',
        ]);

        try {
            KategoriBuku::create([
                'nama' => $data['nama'],
                'deskripsi' => $data['deskripsi'],
                'aktif' => $data['category_status'] === 'active',
            ]);
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages(['nama' => $pesanNamaDuplikat]);
        } catch (QueryException $exception) {
            report($exception);

            return redirect()->route('pustakawan.kategori-buku.create')
                ->withInput()
                ->withErrors(['simpan' => 'Kategori buku gagal disimpan. Silakan coba lagi.']);
        }

        return redirect()->route('pustakawan.kategori-buku.index')
            ->with('status', 'Kategori buku berhasil ditambahkan.');
    }

    public function destroy(int $kategoriBuku): RedirectResponse
    {
        $dihapus = DB::transaction(function () use ($kategoriBuku): bool {
            $kategori = KategoriBuku::query()->lockForUpdate()->findOrFail($kategoriBuku);

            if (DB::table('buku_kategori')->where('kategori_buku_id', $kategori->id)->exists()) {
                return false;
            }

            return $kategori->delete();
        });

        $redirect = redirect()->route('pustakawan.kategori-buku.index');

        if (! $dihapus) {
            return $redirect->withErrors(['kategori_buku' => 'Kategori buku masih digunakan oleh buku.']);
        }

        return $redirect->with('status', 'Kategori buku berhasil dihapus.');
    }
}
