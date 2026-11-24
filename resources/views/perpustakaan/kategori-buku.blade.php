@extends('perpustakaan.layout')
@section('title', 'Kategori Buku')
@section('content')
<section class="perpustakaan-list">
    <header class="perpustakaan-list-heading">
        <h1>Kategori Buku</h1>
        <a href="{{ route('pustakawan.kategori-buku.create') }}" class="perpustakaan-list-add action-button action-button--primary">
            <x-perpustakaan.icon name="plus" />Tambah Kategori
        </a>
    </header>

    <section class="perpustakaan-list-stats perpustakaan-category-stats" aria-label="Jumlah kategori aktif">
        <article class="perpustakaan-list-stat">
            <span>Total Kategori Aktif</span>
            <strong>{{ $totalAktif }} <small>Kategori</small></strong>
        </article>
    </section>

    <section class="perpustakaan-list-panel" aria-label="Daftar kategori buku">
        <form id="pencarian-kategori" action="{{ route('pustakawan.kategori-buku.index') }}" method="GET" hidden></form>
        <header class="perpustakaan-list-toolbar perpustakaan-category-toolbar">
            <div class="perpustakaan-category-toolbar-heading">
                <h2>Daftar Kategori</h2>
                <span class="perpustakaan-category-count">{{ $kategoriBuku->total() }} Item</span>
            </div>
            <label class="perpustakaan-list-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                <input type="search" form="pencarian-kategori" name="q" value="{{ $filter['q'] ?? '' }}" maxlength="100" placeholder="Cari nama kategori..." aria-label="Cari nama kategori">
            </label>
            <button type="submit" form="pencarian-kategori" class="perpustakaan-list-filter-action action-button" aria-label="Filter kategori">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M7 12h10m-7 6h4"/></svg>
            </button>
        </header>
        <div class="perpustakaan-list-scroll perpustakaan-category-table-scroll" tabindex="0" role="region" aria-label="Tabel kategori buku">
            <table>
                <thead>
                    <tr><th scope="col">No</th><th scope="col">Nama Kategori</th><th scope="col">Deskripsi</th><th scope="col">Jumlah Buku</th><th scope="col">Status</th></tr>
                </thead>
                <tbody>
                    @forelse ($kategoriBuku as $kategori)
                        <tr class="perpustakaan-list-row perpustakaan-category-row">
                            <td>{{ $kategoriBuku->firstItem() + $loop->index }}</td>
                            <td>{{ $kategori->nama }}</td>
                            <td>{{ $kategori->deskripsi ?? '—' }}</td>
                            <td class="perpustakaan-list-number">{{ $kategori->buku_count }}</td>
                            <td>
                                <span @class(['perpustakaan-list-state', 'perpustakaan-list-state--active' => $kategori->aktif])>
                                    {{ $kategori->aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="perpustakaan-list-empty">
                                @if (isset($filter['q']) || isset($filter['status']))
                                    <strong>Kategori Buku Tidak Ditemukan</strong>
                                    <p>Coba ubah atau kosongkan kata kunci, lalu terapkan kembali pencarian.</p>
                                @else
                                    <strong>Belum Ada Kategori Buku Ditambahkan</strong>
                                    <p>Belum ada klasifikasi bidang buku yang dibuat. Tambahkan kategori baru untuk memudahkan pengelompokan buku.</p>
                                    <a href="{{ route('pustakawan.kategori-buku.create') }}" class="perpustakaan-list-add action-button action-button--primary"><x-perpustakaan.icon name="plus" />Tambah Kategori Baru</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <footer class="perpustakaan-list-footer">
            <span>
                Menampilkan <strong>{{ $kategoriBuku->count() }}</strong> dari <strong>{{ $kategoriBuku->total() }}</strong> kategori
                @if (session('status'))
                    <br><span role="status">{{ session('status') }}</span>
                @endif
            </span>
            <nav aria-label="Navigasi halaman">
                @if ($kategoriBuku->previousPageUrl())
                    <a href="{{ $kategoriBuku->previousPageUrl() }}" class="action-button action-button--quiet" aria-label="Halaman sebelumnya">&lsaquo;</a>
                @else
                    <button type="button" disabled aria-label="Halaman sebelumnya">&lsaquo;</button>
                @endif
                <span aria-current="page">{{ $kategoriBuku->currentPage() }}</span>
                @if ($kategoriBuku->nextPageUrl())
                    <a href="{{ $kategoriBuku->nextPageUrl() }}" class="action-button action-button--quiet" aria-label="Halaman selanjutnya">&rsaquo;</a>
                @else
                    <button type="button" disabled aria-label="Halaman selanjutnya">&rsaquo;</button>
                @endif
            </nav>
        </footer>
    </section>
</section>
@endsection
