@extends('keuangan.layout')
@section('title', 'Pengumuman Keuangan')
@section('content')
    @php
        $daftarPengumuman = $daftarPengumuman ?? collect();
        $isPaginator = $daftarPengumuman instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
        $statistik = $statistik ?? [
            'total' => $isPaginator ? $daftarPengumuman->total() : $daftarPengumuman->count(),
            'terbit' => 0,
            'draft' => 0,
            'arsip' => 0,
        ];
        $urlCreate = \Illuminate\Support\Facades\Route::has('keuangan.pengumuman.create')
            ? route('keuangan.pengumuman.create')
            : url('/keuangan/pengumuman/tambah');
    @endphp
    <section class="keuangan-payments">
        <header class="keuangan-payments-heading keuangan-announcement-heading">
            <div>
                <h1>Pengumuman Keuangan</h1>
                <p>Informasi keuangan untuk siswa dan orang tua</p>
            </div>
            <a class="keuangan-button keuangan-button-primary" href="{{ $urlCreate }}">Tambah Pengumuman</a>
        </header>
        @include('shared.announcement-controls', [
            'announcementStats' => [
                ['key' => 'total', 'label' => 'Total Publikasi', 'value' => $statistik['total'] ?? 0],
                ['key' => 'active', 'label' => 'Pengumuman Aktif', 'value' => $statistik['terbit'] ?? 0],
                ['key' => 'draft', 'label' => 'Draf Internal', 'value' => $statistik['draft'] ?? 0],
                ['key' => 'archive', 'label' => 'Arsip Dokumen', 'value' => $statistik['arsip'] ?? 0],
            ],
            'announcementTabs' => [
                ['value' => 'all', 'label' => 'Semua Warta'],
                ['value' => 'terbit', 'label' => 'Aktif / Terbit'],
                ['value' => 'draft', 'label' => 'Draf Internal'],
                ['value' => 'arsip', 'label' => 'Arsip Surat'],
            ],
            'announcementCategories' => ['Tagihan', 'Pembayaran', 'Kegiatan', 'Informasi Umum'],
            'announcementCategoryLabel' => 'Semua Jenis Informasi',
            'announcementSearch' => 'Cari judul pengumuman keuangan',
        ])
        <section class="announcement-table-card" aria-label="Daftar pengumuman keuangan">
            <div class="announcement-table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Pengumuman</th>
                            <th>Tanggal Publikasi</th>
                            <th>Lampiran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody data-announcement-list>
                        @forelse ($daftarPengumuman as $index => $item)
                            @php
                                $nomor = $isPaginator ? $daftarPengumuman->firstItem() + $index : $index + 1;
                                $urlEdit = \Illuminate\Support\Facades\Route::has('keuangan.pengumuman.edit')
                                    ? route('keuangan.pengumuman.edit', $item)
                                    : url('/keuangan/pengumuman/' . $item->id . '/edit');
                            @endphp
                            <tr>
                                <td>{{ $nomor }}</td>
                                <td><strong>{{ $item->judul }}</strong></td>
                                <td>{{ $item->tanggal_terbit ? \Carbon\Carbon::parse($item->tanggal_terbit)->format('d/m/Y') : '—' }}
                                </td>
                                <td>{{ $item->gambar_berkas_id ? 'Ada Lampiran' : '—' }}</td>
                                <td><a href="{{ $urlEdit }}">Edit</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="shared-announcement-empty">
                                        <span class="shared-announcement-icon" aria-hidden="true"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="23" height="23"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 11v2a2 2 0 0 0 2 2h2l8 5V4l-8 5H5a2 2 0 0 0-2 2Z" />
                                                <path d="M18 9a4 4 0 0 1 0 6" />
                                            </svg></span>
                                        <strong>Belum Ada Pengumuman Keuangan</strong>
                                        <p>Informasi pembayaran dan tagihan dari madrasah akan ditampilkan di sini.</p>
                                        <a class="shared-announcement-action" href="{{ $urlCreate }}">+ Tambah
                                            Pengumuman</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <footer>Menampilkan <span data-announcement-visible>{{ $daftarPengumuman->count() }}</span> dari <span
                    data-announcement-total>{{ $isPaginator ? $daftarPengumuman->total() : $daftarPengumuman->count() }}</span>
                pengumuman</footer>
        </section>
    </section>
@endsection
