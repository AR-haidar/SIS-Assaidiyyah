@extends('perpustakaan.layout')
@section('title', 'Kategori Buku')
@section('content')
<section class="perpustakaan-categories">
    @if(session('success'))<div class="perpustakaan-flash success" role="status">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="perpustakaan-flash error" role="alert">{{ session('error') }}</div>@endif
    <header class="perpustakaan-categories-heading"><h1>Kategori Buku</h1><a href="{{ url('/perpustakaan/kategori-buku/tambah') }}" class="perpustakaan-add-category">+ Tambah Kategori</a></header>
    <section class="perpustakaan-category-stat"><span>Total Kategori</span><strong><b>{{ $categories->count() }}</b> Kategori</strong></section>
    <section class="perpustakaan-category-table-card"><header class="perpustakaan-category-toolbar"><div><h2>Daftar Kategori</h2><span class="perpustakaan-category-count">{{ $categories->count() }} Item</span></div><label class="perpustakaan-category-search"><input type="search" name="search" form="category-filter" value="{{ request('search') }}" placeholder="Cari nama kategori..."></label><button type="submit" form="category-filter" class="perpustakaan-category-filter" aria-label="Cari kategori">⌕</button><form id="category-filter" method="GET" action="{{ url('/perpustakaan/kategori-buku') }}"></form></header>
    <div class="perpustakaan-category-table-scroll"><table><thead><tr><th>No</th><th>Nama Kategori</th><th>Deskripsi</th><th>Jumlah Buku</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
    @forelse($categories as $category)
        <tr><td>{{ $loop->iteration }}</td><td>{{ $category->name }}</td><td>{{ $category->description }}</td><td>{{ $category->books_count }}</td><td>{{ $category->status === 'active' ? 'Aktif' : 'Nonaktif' }}</td><td class="perpustakaan-row-actions"><a class="perpustakaan-action-edit" href="{{ url('/perpustakaan/kategori-buku/'.$category->id.'/edit') }}">Edit</a><form method="POST" action="{{ route('library.categories.delete', $category) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">@csrf @method('DELETE')<button class="perpustakaan-action-delete" type="submit">Hapus</button></form></td></tr>
    @empty
        <tr><td colspan="6" class="perpustakaan-category-empty"><strong>Belum Ada Kategori Buku Ditambahkan</strong><p>Tambahkan kategori untuk memudahkan pengelompokan buku.</p><a href="{{ url('/perpustakaan/kategori-buku/tambah') }}">+ Tambah Kategori Baru</a></td></tr>
    @endforelse
    </tbody></table></div><footer class="perpustakaan-category-pagination"><span>Menampilkan {{ $categories->count() }} dari {{ $categories->count() }} kategori</span></footer></section>
</section>
@endsection
