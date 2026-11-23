@extends('perpustakaan.layout')
@section('title', 'Data Buku')
@section('content')
<section class="perpustakaan-books">
    @if(session('success'))<div class="perpustakaan-flash success" role="status">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="perpustakaan-flash error" role="alert">{{ session('error') }}</div>@endif
    <header class="perpustakaan-books-heading"><h1>Data Buku</h1><a href="{{ url('/perpustakaan/data-buku/tambah') }}" class="perpustakaan-add-book">+ Tambah Buku</a></header>
    <section class="perpustakaan-book-stats"><article><span>Koleksi Terdaftar</span><strong>{{ $books->count() }}</strong></article><article><span>Status Aktif</span><strong>{{ $books->where('publication_status', 'published')->count() }}</strong></article><article><span>Draft Buku</span><strong>{{ $books->where('publication_status', 'draft')->count() }}</strong></article><article><span>Modul e-Reader</span><strong class="ready">Online Ready</strong></article></section>
    <section class="perpustakaan-book-table-card"><form class="perpustakaan-book-filters" method="GET" action="{{ url('/perpustakaan/data-buku') }}"><label class="perpustakaan-book-search"><input type="search" name="search" value="{{ request('search') }}" placeholder="Cari judul atau penulis buku..."></label><label><select name="category"><option value="">Semua Kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request('category')==$category->id)>{{ $category->name }}</option>@endforeach</select></label><label><select name="status"><option value="">Semua Status</option><option value="published" @selected(request('status')==='published')>Aktif</option><option value="draft" @selected(request('status')==='draft')>Draft</option></select></label><button type="submit" class="perpustakaan-filter-submit">Terapkan Filter</button><a class="perpustakaan-filter-reset" href="{{ url('/perpustakaan/data-buku') }}">Reset</a></form><div class="perpustakaan-book-table-scroll"><table><thead><tr><th>No</th><th>Judul Buku</th><th>Penulis</th><th>Kategori</th><th>Status Publikasi</th><th>Aksi</th></tr></thead><tbody>
    @forelse($books as $book)
        <tr><td>{{ $loop->iteration }}</td><td>{{ $book->title }}</td><td>{{ $book->author }}</td><td>{{ $book->category->name }}</td><td>{{ $book->publication_status === 'published' ? 'Aktif' : 'Draft' }}</td><td class="perpustakaan-row-actions"><a class="perpustakaan-action-edit" href="{{ url('/perpustakaan/data-buku/'.$book->id.'/edit') }}">Edit</a><form method="POST" action="{{ route('library.books.delete', $book) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">@csrf @method('DELETE')<button class="perpustakaan-action-delete" type="submit">Hapus</button></form></td></tr>
    @empty
        <tr><td colspan="6" class="perpustakaan-book-empty"><strong>Belum Ada Buku</strong><p>Belum ada data koleksi buku digital yang dimuat pada sistem pustaka.</p><a href="{{ url('/perpustakaan/data-buku/tambah') }}">+ Tambah Buku</a></td></tr>
    @endforelse
    </tbody></table></div><footer class="perpustakaan-book-pagination"><span>Menampilkan <strong>{{ $books->count() }}</strong> dari <strong>{{ $books->count() }}</strong> buku</span></footer></section>
</section>
@endsection
