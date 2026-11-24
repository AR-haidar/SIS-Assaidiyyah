@extends('kepala-sekolah.layout')
@section('title', 'Pengumuman')
@section('page-class', 'ks-announcements-page')
@section('content')
    @php
        $daftarPengumuman = $daftarPengumuman ?? collect();
        $daftarKategori = $daftarKategori ?? collect();
        $isPaginator = $daftarPengumuman instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;

        $statistik = $statistik ?? [
            'total' => $isPaginator ? $daftarPengumuman->total() : $daftarPengumuman->count(),
            'terbit' => 0,
            'draft' => 0,
            'arsip' => 0,
        ];

        $statusAktif = request('status', '');
        $urlIndex = \Illuminate\Support\Facades\Route::has('kepala.pengumuman.index')
            ? route('kepala.pengumuman.index')
            : url('/kepala-sekolah/pengumuman');
        $urlCreate = \Illuminate\Support\Facades\Route::has('kepala.pengumuman.create')
            ? route('kepala.pengumuman.create')
            : url('/kepala-sekolah/pengumuman/tambah');
    @endphp

    <header class="ks-page-heading ks-heading-with-action">
        <div>
            <h1>Manajemen &amp; Publikasi Pengumuman</h1>
            <p>Kanal resmi penerbitan instruksi, surat edaran, dan warta akademik Kepala Madrasah.</p>
        </div>
        <a href="{{ $urlCreate }}" class="ks-primary-button">+ Buat Pengumuman Baru</a>
    </header>

    <section class="ks-announcement-stats" aria-label="Ringkasan publikasi">
        <article class="ks-announcement-stat">
            <div><span>TOTAL PUBLIKASI</span><strong>{{ $statistik['total'] ?? 0 }}</strong></div>
            <i class="ks-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M3 11v2a2 2 0 0 0 2 2h2l8 5V4l-8 5H5a2 2 0 0 0-2 2Z" />
                    <path d="M18 9a4 4 0 0 1 0 6" />
                </svg>
            </i>
        </article>
        <article class="ks-announcement-stat">
            <div><span>PENGUMUMAN AKTIF</span><strong>{{ $statistik['terbit'] ?? 0 }}</strong></div>
            <i class="ks-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <circle cx="12" cy="12" r="9" />
                    <path d="m8 12 2.5 2.5L16 9" />
                </svg>
            </i>
        </article>
        <article class="ks-announcement-stat">
            <div><span>DRAF INTERNAL</span><strong>{{ $statistik['draft'] ?? 0 }}</strong></div>
            <i class="ks-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <path d="M14 2v6h6" />
                    <path d="m9 15 5-5 2 2-5 5-3 1z" />
                </svg>
            </i>
        </article>
        <article class="ks-announcement-stat">
            <div><span>ARSIP DOKUMEN</span><strong>{{ $statistik['arsip'] ?? 0 }}</strong></div>
            <i class="ks-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M4 7h16v14H4z" />
                    <path d="M3 3h18v4H3z" />
                    <path d="M9 11h6" />
                </svg>
            </i>
        </article>
    </section>

    <section class="ks-announcement-controls">
        <div class="ks-filter-tabs">
            <a href="{{ $urlIndex }}" class="{{ $statusAktif === '' ? 'selected' : '' }}">
                Semua Warta ({{ $statistik['total'] ?? 0 }})
            </a>
            <a href="{{ $urlIndex . '?status=terbit' }}" class="{{ $statusAktif === 'terbit' ? 'selected' : '' }}">
                Aktif / Terbit ({{ $statistik['terbit'] ?? 0 }})
            </a>
            <a href="{{ $urlIndex . '?status=draft' }}" class="{{ $statusAktif === 'draft' ? 'selected' : '' }}">
                Draf Internal ({{ $statistik['draft'] ?? 0 }})
            </a>
            <a href="{{ $urlIndex . '?status=arsip' }}" class="{{ $statusAktif === 'arsip' ? 'selected' : '' }}">
                Arsip Surat ({{ $statistik['arsip'] ?? 0 }})
            </a>
        </div>

        <form method="GET" action="{{ $urlIndex }}" class="ks-announcement-filter-row">
            <label class="ks-search-field">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7" />
                    <path d="m20 20-4-4" />
                </svg>
                <input type="search" name="q" value="{{ request('q') }}"
                    placeholder="Cari judul pengumuman, nomor surat, atau kata kunci" aria-label="Cari pengumuman">
            </label>

            <label class="ks-select-field">
                <span class="sr-only">Kategori</span>
                <select name="kategori_id" aria-label="Filter kategori">
                    <option value="">Semua Kategori</option>
                    @foreach ($daftarKategori as $kat)
                        <option value="{{ $kat->id }}" @selected((string) request('kategori_id') === (string) $kat->id)>
                            {{ $kat->label ?? $kat->nama }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="ks-select-field">
                <span class="sr-only">Status</span>
                <select name="status" aria-label="Filter status">
                    <option value="">Semua Status</option>
                    <option value="terbit" @selected($statusAktif === 'terbit')>Terbit</option>
                    <option value="draft" @selected($statusAktif === 'draft')>Draf</option>
                    <option value="arsip" @selected($statusAktif === 'arsip')>Arsip</option>
                </select>
            </label>

            <button type="submit" class="ks-filter-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 5h16l-6.5 7.5V19l-3 1v-7.5L4 5Z" />
                </svg>
                <span>Terapkan Filter</span>
            </button>
        </form>
    </section>

    <section class="ks-announcement-table-card">
        <div class="ks-table-scroll">
            <table class="ks-data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Pengumuman</th>
                        <th>Kategori</th>
                        <th>Nomor Surat / Edaran</th>
                        <th>Tanggal Terbit</th>
                        <th>Sasaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarPengumuman as $index => $item)
                        @php
                            $nomor = $isPaginator ? $daftarPengumuman->firstItem() + $index : $index + 1;
                            $statusVal = $item->status?->value ?? (string) ($item->status ?? 'draft');
                            $urlEdit = \Illuminate\Support\Facades\Route::has('kepala.pengumuman.edit')
                                ? route('kepala.pengumuman.edit', $item)
                                : url('/kepala-sekolah/pengumuman/' . $item->id . '/edit');
                            $urlDestroy = \Illuminate\Support\Facades\Route::has('kepala.pengumuman.destroy')
                                ? route('kepala.pengumuman.destroy', $item)
                                : url('/kepala-sekolah/pengumuman/' . $item->id);
                        @endphp
                        <tr>
                            <td>{{ $nomor }}</td>
                            <td><strong>{{ $item->judul }}</strong></td>
                            <td>{{ $item->kategori?->label ?? ($item->kategori?->nama ?? '—') }}</td>
                            <td>{{ $item->nomor_surat ?? '—' }}</td>
                            <td>{{ $item->tanggal_terbit ? \Carbon\Carbon::parse($item->tanggal_terbit)->format('d/m/Y') : '—' }}
                            </td>
                            <td>{{ $item->sasaran ?? 'Umum' }}</td>
                            <td>{{ $item->status?->label() ?? ucfirst($statusVal) }}</td>
                            <td>
                                <a href="{{ $urlEdit }}">Edit</a>
                                <form method="POST" action="{{ $urlDestroy }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="ks-empty-state">
                                    <div class="shared-announcement-empty">
                                        <span class="shared-announcement-icon" aria-hidden="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 11v2a2 2 0 0 0 2 2h2l8 5V4l-8 5H5a2 2 0 0 0-2 2Z" />
                                                <path d="M18 9a4 4 0 0 1 0 6" />
                                            </svg>
                                        </span>
                                        <strong>Belum Ada Pengumuman Diterbitkan</strong>
                                        <p>Seluruh pengumuman dan surat edaran resmi yang dibuat akan terdaftar di sini.</p>
                                        <a href="{{ $urlCreate }}" class="ks-primary-button">+ Buat Pengumuman
                                            Baru</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <footer class="ks-table-footer">
            <span>Menampilkan {{ $daftarPengumuman->count() }} dari
                {{ $isPaginator ? $daftarPengumuman->total() : $daftarPengumuman->count() }} pengumuman</span>
            @if ($isPaginator && $daftarPengumuman->hasPages())
                <div class="ks-pagination">{{ $daftarPengumuman->links() }}</div>
            @else
                <div class="ks-pagination"><button type="button" disabled>&lt;&lt;</button><span>1</span><button
                        type="button" disabled>&gt;&gt;</button></div>
            @endif
        </footer>
    </section>
@endsection
