@extends('operator.layout')
@section('title', 'Pengumuman')
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
        $urlCreate = \Illuminate\Support\Facades\Route::has('operator.pengumuman.create')
            ? route('operator.pengumuman.create')
            : url('/operator/pengumuman/tambah');
        $urlIndex = \Illuminate\Support\Facades\Route::has('operator.pengumuman.index')
            ? route('operator.pengumuman.index')
            : url('/operator/pengumuman');
    @endphp
    <section class="operator-announcements">
        <header class="operator-announcement-heading">
            <div>
                <h1>Manajemen &amp; Publikasi Pengumuman</h1>
                <p>Kanal resmi penerbitan instruksi, surat edaran, dan warta akademik Kepala Madrasah.</p>
            </div>
            <a class="operator-announcement-create" href="{{ $urlCreate }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                Buat Pengumuman Baru
            </a>
        </header>

        <section class="operator-announcement-stats" aria-label="Ringkasan pengumuman">
            <article><span>Total Publikasi</span><strong>{{ $statistik['total'] ?? 0 }}</strong><i class="operator-ann-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 11 18-5-5 18-4-8-9-5z"/><path d="M12 16 19 9"/></svg></i></article>
            <article><span>Pengumuman Aktif</span><strong>{{ $statistik['terbit'] ?? 0 }}</strong><i class="operator-ann-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 14 5.5 17 5l.5 3 2.5 2-1.5 2.5.5 3-3 .5-2 2.5-2.5-1.5-3 .5-.5-3-2.5-2L7 10l-.5-3 3-.5z"/><path d="m9 12 2 2 4-4"/></svg></i></article>
            <article><span>Draf Internal</span><strong>{{ $statistik['draft'] ?? 0 }}</strong><i class="operator-ann-icon operator-ann-icon-muted"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h5M8 17h4M17 15l3 3-3 3"/></svg></i></article>
            <article><span>Arsip Dokumen</span><strong>{{ $statistik['arsip'] ?? 0 }}</strong><i class="operator-ann-icon operator-ann-icon-muted"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 4h18v5H3zM5 9v11h14V9M10 13h4"/></svg></i></article>
        </section>

        <section class="operator-announcement-controls">
            @php $statusAktif = request('status', ''); @endphp
            <nav class="operator-announcement-tabs" aria-label="Filter status pengumuman">
                <a href="{{ $urlIndex }}" class="{{ $statusAktif === '' ? 'active' : '' }}">Semua Warta ({{ $statistik['total'] ?? 0 }})</a>
                <a href="{{ $urlIndex . '?status=terbit' }}" class="{{ $statusAktif === 'terbit' ? 'active' : '' }}">Aktif / Terbit ({{ $statistik['terbit'] ?? 0 }})</a>
                <a href="{{ $urlIndex . '?status=draft' }}" class="{{ $statusAktif === 'draft' ? 'active' : '' }}">Draf Internal ({{ $statistik['draft'] ?? 0 }})</a>
                <a href="{{ $urlIndex . '?status=arsip' }}" class="{{ $statusAktif === 'arsip' ? 'active' : '' }}">Arsip Surat ({{ $statistik['arsip'] ?? 0 }})</a>
            </nav>
            <form method="GET" action="{{ $urlIndex }}" class="operator-announcement-filters">
                <label class="operator-announcement-search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari judul pengumuman, nomor surat, atau kata kunci" aria-label="Cari pengumuman">
                </label>
                <label class="operator-announcement-select">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16l-6 7v6l-4 3v-9z"/></svg>
                    <select name="kategori_id" aria-label="Pilih kategori">
                        <option value="">Semua Kategori</option>
                        @foreach ($daftarKategori as $kat)
                            <option value="{{ $kat->id }}" @selected((string) request('kategori_id') === (string) $kat->id)>{{ $kat->label ?? $kat->nama }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="operator-announcement-select">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 6h16M7 12h10m-7 6h4"/><circle cx="7" cy="6" r="1"/><circle cx="17" cy="12" r="1"/></svg>
                    <select name="status" aria-label="Pilih status">
                        <option value="">Semua Status</option>
                        <option value="terbit" @selected($statusAktif === 'terbit')>Terbit</option>
                        <option value="draft" @selected($statusAktif === 'draft')>Draf</option>
                        <option value="arsip" @selected($statusAktif === 'arsip')>Arsip</option>
                    </select>
                </label>
                <button class="operator-announcement-apply" type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M7 12h10m-7 6h4"/></svg>Terapkan<br>Filter</button>
            </form>
        </section>

        <section class="operator-announcement-table-card" aria-label="Daftar pengumuman">
            <div class="operator-announcement-table-scroll">
                <table>
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
                                $nomor = $isPaginator ? ($daftarPengumuman->firstItem() + $index) : ($index + 1);
                                $statusVal = $item->status?->value ?? (string) ($item->status ?? 'draft');
                                $urlEdit = \Illuminate\Support\Facades\Route::has('operator.pengumuman.edit')
                                    ? route('operator.pengumuman.edit', $item)
                                    : url('/operator/pengumuman/' . $item->id . '/edit');
                                $urlDestroy = \Illuminate\Support\Facades\Route::has('operator.pengumuman.destroy')
                                    ? route('operator.pengumuman.destroy', $item)
                                    : url('/operator/pengumuman/' . $item->id);
                            @endphp
                            <tr>
                                <td>{{ $nomor }}</td>
                                <td><strong>{{ $item->judul }}</strong></td>
                                <td>{{ $item->kategori?->label ?? $item->kategori?->nama ?? '—' }}</td>
                                <td>{{ $item->nomor_surat ?? '—' }}</td>
                                <td>{{ $item->tanggal_terbit ? \Carbon\Carbon::parse($item->tanggal_terbit)->format('d/m/Y') : '—' }}</td>
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
                                <td colspan="8" class="operator-announcement-empty">
                                    <div class="shared-announcement-empty">
                                        <span class="shared-announcement-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11v2a2 2 0 0 0 2 2h2l8 5V4l-8 5H5a2 2 0 0 0-2 2Z"/><path d="M18 9a4 4 0 0 1 0 6"/></svg></span>
                                        <strong>Belum Ada Pengumuman Diterbitkan</strong>
                                        <p>Seluruh pengumuman dan surat edaran resmi yang dibuat oleh Kepala<br class="operator-ann-desktop-break"> Madrasah akan terdaftar di sini.</p>
                                        <a class="operator-announcement-create" href="{{ $urlCreate }}">+ Buat Pengumuman Baru</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <footer>
                <span>Menampilkan {{ $daftarPengumuman->count() }} dari {{ $isPaginator ? $daftarPengumuman->total() : $daftarPengumuman->count() }} pengumuman</span>
                @if ($isPaginator && $daftarPengumuman->hasPages())
                    <div>{{ $daftarPengumuman->links() }}</div>
                @endif
            </footer>
        </section>
    </section>
@endsection
