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
    public function index(): View
    {
        return view('perpustakaan.kategori-buku', [
            'kategoriBuku' => KategoriBuku::query()->withCount('buku')->orderBy('nama')->get(),
        ]);
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
