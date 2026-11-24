@extends('perpustakaan.layout')
@use(App\Enums\StatusPublikasiBuku)
@section('title', 'Data Buku')
@section('content')
<section class="perpustakaan-list">
    <header class="perpustakaan-list-heading">
        <h1>Data Buku</h1>
        <a href="{{ route('pustakawan.data-buku.create') }}" class="perpustakaan-list-add action-button action-button--primary">
            <x-perpustakaan.icon name="plus" />Tambah Buku
        </a>
    </header>

    @if (session('status'))
        <p class="perpustakaan-book-list-status" role="status">{{ session('status') }}</p>
    @endif

    <section class="perpustakaan-list-stats" aria-label="Ringkasan data buku">
        <article class="perpustakaan-list-stat"><span>Koleksi Terdaftar</span><strong>{{ $totalBuku }}</strong></article>
        <article class="perpustakaan-list-stat"><span>Status Aktif</span><strong>{{ $bukuAktif }}</strong></article>
        <article class="perpustakaan-list-stat"><span>Draft Buku</span><strong>{{ $bukuDraf }}</strong></article>
        <article class="perpustakaan-list-stat"><span>Modul e-Reader</span><strong class="perpustakaan-list-stat-text">Online Ready</strong></article>
    </section>

    <section class="perpustakaan-list-panel" aria-label="Daftar buku digital">
        <form class="perpustakaan-list-toolbar perpustakaan-book-filters" action="{{ route('pustakawan.data-buku.index') }}" method="GET">
            <label class="perpustakaan-list-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                <input type="search" name="q" value="{{ $filter['q'] ?? '' }}" placeholder="Cari judul atau penulis buku..." aria-label="Cari judul atau penulis buku">
            </label>
            <label class="perpustakaan-list-select">
                <select name="kategori" aria-label="Filter kategori">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoriBuku as $kategori)
                        <option value="{{ $kategori->id }}" @selected(($filter['kategori'] ?? null) == $kategori->id)>{{ $kategori->nama }}</option>
                    @endforeach
                </select>
                <x-perpustakaan.icon name="chevron-down" size="14" />
            </label>
            <label class="perpustakaan-list-select">
                <select name="status" aria-label="Filter status">
                    <option value="">Semua Status</option>
                    @foreach (StatusPublikasiBuku::cases() as $status)
                        <option value="{{ $status->value }}" @selected(($filter['status'] ?? null) === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
                <x-perpustakaan.icon name="chevron-down" size="14" />
            </label>
            <button type="submit" class="perpustakaan-list-filter-action action-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16l-6 7v6l-4 3v-9z"/></svg>
                Terapkan Filter
            </button>
            <a href="{{ route('pustakawan.data-buku.index') }}" class="perpustakaan-list-filter-action action-button action-button--quiet">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                Reset
            </a>
        </form>
        <div class="perpustakaan-list-scroll perpustakaan-book-table-scroll" tabindex="0" role="region" aria-label="Tabel data buku">
            <table>
                <thead>
                    <tr>
                        <th scope="col">No</th><th scope="col">Judul Buku</th><th scope="col">Penulis</th>
                        <th scope="col">Kategori</th><th scope="col">Status Publikasi</th><th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($buku as $item)
                        <tr class="perpustakaan-list-row perpustakaan-book-row">
                            <td>{{ $buku->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="flex items-center gap-2" data-cover-preview>
                                    @if ($item->sampul)
                                        <img data-cover-image src="{{ route('pustakawan.data-buku.sampul', $item) }}" alt="Sampul {{ $item->judul }}" class="h-12 w-9 shrink-0 rounded object-cover" loading="lazy">
                                    @endif
                                    <span data-cover-placeholder @if ($item->sampul) hidden @endif class="h-12 w-9 shrink-0 rounded bg-slate-100 text-center leading-[3rem]" aria-label="Belum ada sampul">—</span>
                                    <span>{{ $item->judul }}</span>
                                </div>
                            </td>
                            <td>{{ $item->penulis }}</td>
                            <td>{{ $item->kategori->pluck('nama')->join(', ') ?: '—' }}</td>
                            <td>
                                <span @class(['perpustakaan-list-state', 'perpustakaan-list-state--active' => $item->status_publikasi === StatusPublikasiBuku::Aktif])>
                                    {{ $item->status_publikasi->label() }}
                                </span>
                            </td>
                            <td><a href="{{ route('pustakawan.data-buku.edit', $item) }}" class="perpustakaan-book-edit-link action-button">Edit</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="perpustakaan-list-empty">
                                @if ($totalBuku)
                                    <strong>Buku Tidak Ditemukan</strong>
                                    <p>Coba ubah kata kunci atau filter.</p>
                                @else
                                    <strong>Belum Ada Buku</strong>
                                    <p>Belum ada data koleksi buku digital yang dimuat pada sistem pustaka.</p>
                                    <a href="{{ route('pustakawan.data-buku.create') }}" class="perpustakaan-list-add action-button action-button--primary"><x-perpustakaan.icon name="plus" />Tambah Buku</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <footer class="perpustakaan-list-footer">
            <span>Menampilkan <strong>{{ $buku->count() }}</strong> dari <strong>{{ $buku->total() }}</strong> buku</span>
            <nav aria-label="Navigasi halaman">
                @if ($buku->previousPageUrl())
                    <a href="{{ $buku->previousPageUrl() }}" class="action-button action-button--quiet" aria-label="Halaman sebelumnya">&lsaquo;</a>
                @else
                    <button type="button" disabled aria-label="Halaman sebelumnya">&lsaquo;</button>
                @endif
                <span aria-current="page">{{ $buku->currentPage() }}</span>
                @if ($buku->nextPageUrl())
                    <a href="{{ $buku->nextPageUrl() }}" class="action-button action-button--quiet" aria-label="Halaman selanjutnya">&rsaquo;</a>
                @else
                    <button type="button" disabled aria-label="Halaman selanjutnya">&rsaquo;</button>
                @endif
            </nav>
        </footer>
    </section>
</section>
@endsection
