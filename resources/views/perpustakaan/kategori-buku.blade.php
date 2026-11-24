@extends('perpustakaan.layout')
@section('title', 'Kategori Buku')
@section('content')
<section class="perpustakaan-categories">
    <header class="perpustakaan-categories-heading">
        <h1>Kategori Buku</h1>
        <a href="{{ route('pustakawan.kategori-buku.create') }}" class="perpustakaan-add-category"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>Tambah Kategori</a>
    </header>

    <section class="perpustakaan-category-stat" aria-label="Jumlah kategori aktif">
        <span>Total Kategori Aktif</span>
        <strong><b>{{ $kategoriBuku->where('aktif', true)->count() }}</b> Kategori</strong>
    </section>

    <section class="perpustakaan-category-table-card" aria-label="Daftar kategori buku">
        <header class="perpustakaan-category-toolbar">
            <div><h2>Daftar Kategori</h2><span class="perpustakaan-category-count">{{ $kategoriBuku->count() }} Item</span></div>
            <label class="perpustakaan-category-search"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input type="search" placeholder="Cari nama kategori..." aria-label="Cari nama kategori"></label>
            <button type="button" class="perpustakaan-category-filter" aria-label="Filter kategori"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M7 12h10m-7 6h4"/></svg></button>
        </header>
        <div class="perpustakaan-category-table-scroll"><table>
            <thead><tr><th>No</th><th>Nama Kategori</th><th>Deskripsi</th><th>Jumlah Buku</th><th>Status</th></tr></thead>
            <tbody>
                @forelse ($kategoriBuku as $kategori)
                    <tr class="text-[10px] text-[#536b89] [&>td]:h-[37px] [&>td]:px-[13px]">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $kategori->nama }}</td>
                        <td>{{ $kategori->deskripsi }}</td>
                        <td>{{ $kategori->buku_count }}</td>
                        <td>{{ $kategori->aktif ? 'Aktif' : 'Nonaktif' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="perpustakaan-category-empty"><strong>Belum Ada Kategori Buku Ditambahkan</strong><p>Belum ada klasifikasi bidang buku yang dibuat. Tambahkan kategori<br class="perpustakaan-category-desktop-break"> baru untuk memudahkan pengelompokan buku.</p><a href="{{ route('pustakawan.kategori-buku.create') }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>Tambah Kategori Baru</a></td></tr>
                @endforelse
            </tbody>
        </table></div>
        <footer class="perpustakaan-category-pagination">
            <span>
                Menampilkan {{ $kategoriBuku->count() }} dari {{ $kategoriBuku->count() }} kategori
                @if (session('status'))
                    <br><span role="status">{{ session('status') }}</span>
                @endif
            </span>
            <nav aria-label="Navigasi halaman"><button type="button" disabled aria-label="Halaman sebelumnya">&lsaquo;</button><span>1</span><button type="button" disabled aria-label="Halaman selanjutnya">&rsaquo;</button></nav>
        </footer>
    </section>
</section>
@endsection
