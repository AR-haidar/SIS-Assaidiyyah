<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LibraryController extends Controller
{
    public function books(Request $request): View
    {
        $query = Book::with('category')->latest();
        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(fn ($builder) => $builder->where('title', 'like', "%{$search}%")->orWhere('author', 'like', "%{$search}%"));
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }
        if ($request->filled('status')) {
            $query->where('publication_status', $request->input('status'));
        }

        return view('perpustakaan.data-buku', [
            'books' => $query->get(),
            'categories' => BookCategory::orderBy('name')->get(),
        ]);
    }

    public function categories(Request $request): View
    {
        $query = BookCategory::withCount('books')->orderBy('name');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->string('search')->toString().'%');
        }

        return view('perpustakaan.kategori-buku', [
            'categories' => $query->get(),
        ]);
    }

    public function storeBook(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:250'],
            'author' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:book_categories,id'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'published_year' => ['nullable', 'integer', 'between:1000,'.(date('Y') + 1)],
            'synopsis' => ['required', 'string', 'max:1000'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:10240'],
            'digital_file' => ['required', 'file', 'mimes:pdf,epub', 'max:51200'],
            'publication_status' => ['required', 'in:published,draft'],
        ]);
        if ($request->hasFile('cover')) {
            $data['cover_path'] = $request->file('cover')->store('library/covers', 'public');
        }
        if ($request->hasFile('digital_file')) {
            $data['file_path'] = $request->file('digital_file')->store('library/books', 'public');
        }
        unset($data['cover'], $data['digital_file']);
        Book::create($data);

        return redirect('/perpustakaan/data-buku')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function updateBook(Request $request, Book $book): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:250'],
            'author' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:book_categories,id'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'published_year' => ['nullable', 'integer', 'between:1000,'.(date('Y') + 1)],
            'synopsis' => ['required', 'string', 'max:1000'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:10240'],
            'digital_file' => ['nullable', 'file', 'mimes:pdf,epub', 'max:51200'],
            'publication_status' => ['required', 'in:published,draft'],
        ]);
        if ($request->hasFile('cover')) {
            $data['cover_path'] = $request->file('cover')->store('library/covers', 'public');
        }
        if ($request->hasFile('digital_file')) {
            $data['file_path'] = $request->file('digital_file')->store('library/books', 'public');
        }
        unset($data['cover'], $data['digital_file']);
        $book->update($data);

        return redirect('/perpustakaan/data-buku')->with('success', 'Buku berhasil diperbarui.');
    }

    public function deleteBook(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect('/perpustakaan/data-buku')->with('success', 'Buku berhasil dihapus.');
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:book_categories,name'],
            'description' => ['required', 'string', 'max:2000'],
            'status' => ['required', 'in:active,inactive'],
        ]);
        BookCategory::create($data);

        return redirect('/perpustakaan/kategori-buku')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, BookCategory $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:book_categories,name,'.$category->id],
            'description' => ['required', 'string', 'max:2000'],
            'status' => ['required', 'in:active,inactive'],
        ]);
        $category->update($data);

        return redirect('/perpustakaan/kategori-buku')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function deleteCategory(BookCategory $category): RedirectResponse
    {
        if ($category->books()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh data buku.');
        }
        try {
            $category->delete();
        } catch (QueryException) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh data buku.');
        }

        return redirect('/perpustakaan/kategori-buku')->with('success', 'Kategori berhasil dihapus.');
    }
}
