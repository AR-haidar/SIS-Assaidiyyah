@extends('humas.layout')
@section('title', 'Profil Guru Landing Page')
@section('content')
    @php
        $daftarProfil = $daftarProfil ?? collect();
        $isPaginator = $daftarProfil instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
        $statistik = $statistik ?? [
            'aktif' => 0,
            'nonaktif' => 0,
        ];
        $urlIndex = \Illuminate\Support\Facades\Route::has('humas.guru.index')
            ? route('humas.guru.index')
            : url('/humas/guru');
        $urlCreate = \Illuminate\Support\Facades\Route::has('humas.guru.create')
            ? route('humas.guru.create')
            : url('/humas/guru/tambah');
    @endphp
    <section class="humas-teachers" data-humas-teachers>
        <header class="humas-teachers-heading">
            <div>
                <h1>Kelola Profil Dewan Guru di Landing Page</h1>
                <p>Kurasi tampilan profil tenaga pendidik dan kependidikan yang ditampilkan kepada pengunjung website
                    sekolah.</p>
            </div>
            <div class="humas-teacher-top-actions">
                <a class="primary" href="{{ $urlCreate }}">&#65291; Tambah Profil Guru Baru</a>
            </div>
        </header>

        <div class="humas-teacher-notice">
            <p><strong>Pemberitahuan Otoritas Pengelolaan Humas</strong><br>Modul ini <b>hanya mengatur kartu profil guru
                    yang tampil di publik Landing Page.</b> Penambahan NUPTK, mutasi jabatan, kredensial akun SIM Madrasah,
                dan data induk kepegawaian tetap dikelola oleh <b>Operator Madrasah</b> melalui menu resmi <i>Data Induk
                    Guru</i>.</p>
        </div>

        <div class="humas-teacher-stats">
            <article>
                <span>Status Visibilitas Aktif</span>
                <strong data-teacher-published>{{ $statistik['aktif'] ?? 0 }} Guru</strong>
                <small>Live di Website</small>
            </article>
            <article>
                <span>Draf / Disembunyikan</span>
                <strong data-teacher-draft>{{ $statistik['nonaktif'] ?? 0 }} Profil</strong>
                <small>Menunggu Publikasi</small>
            </article>
        </div>

        <form method="GET" action="{{ $urlIndex }}" class="humas-teacher-filters" data-teacher-filter-form>
            <label>
                <span aria-hidden="true">&#8981;</span>
                <input type="search" name="q" value="{{ request('q', request('search')) }}"
                    placeholder="Cari nama dewan guru atau bidang studi..." aria-label="Cari profil guru">
            </label>
            <select name="aktif" aria-label="Filter visibilitas">
                <option value="">Semua Visibilitas</option>
                <option value="1" @selected(request('aktif') === '1')>Published / Aktif</option>
                <option value="0" @selected(request('aktif') === '0')>Draf / Disembunyikan</option>
            </select>
            <button type="submit"><span aria-hidden="true">&#9661;</span> Terapkan Filter</button>
        </form>

        <div class="humas-teacher-table-wrap">
            <table class="humas-teacher-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Urutan</th>
                        <th>Foto Profil</th>
                        <th>Nama &amp; Gelar Lengkap</th>
                        <th>Jabatan / Bidang Mengajar</th>
                        <th>Deskripsi Singkat</th>
                        <th>Visibilitas Landing Page</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody data-teacher-rows>
                    @forelse ($daftarProfil as $index => $profil)
                        @php
                            $nomor = $isPaginator ? $daftarProfil->firstItem() + $index : $index + 1;
                            $namaGuru = $profil->nama_tampil ?? ($profil->pegawai?->nama_lengkap ?? '—');
                            $urlEdit = \Illuminate\Support\Facades\Route::has('humas.guru.edit')
                                ? route('humas.guru.edit', $profil)
                                : url('/humas/guru/' . $profil->id . '/edit');
                            $urlDestroy = \Illuminate\Support\Facades\Route::has('humas.guru.destroy')
                                ? route('humas.guru.destroy', $profil)
                                : url('/humas/guru/' . $profil->id);
                        @endphp
                        <tr>
                            <td>{{ $nomor }}</td>
                            <td>{{ $profil->urutan ?? 0 }}</td>
                            <td>{{ $profil->foto_berkas_id ? 'Ada Foto' : '—' }}</td>
                            <td><strong>{{ $namaGuru }}</strong></td>
                            <td>{{ $profil->jabatan_tampil ?? '—' }}</td>
                            <td>{{ $profil->bio_singkat ?? '—' }}</td>
                            <td>{{ $profil->aktif ?? ($profil->tampil ?? false) ? 'Published' : 'Draft' }}</td>
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
                            <td colspan="8">Belum ada profil guru publik yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <footer>
                <span data-teacher-summary>Menampilkan {{ $daftarProfil->count() }} dari
                    {{ $isPaginator ? $daftarProfil->total() : $daftarProfil->count() }} profil guru</span>
                <nav data-teacher-pagination aria-label="Halaman profil guru">
                    @if ($isPaginator && $daftarProfil->hasPages())
                        {{ $daftarProfil->links() }}
                    @endif
                </nav>
            </footer>
        </div>

        <section class="humas-teacher-preview">
            <h2>Pratinjau Langsung Komponen Landing Page</h2>
            <p>Representasi visual kartu profil guru yang akan dilihat oleh calon wali murid di website publik.</p>
            <div data-teacher-preview-cards>
                @foreach ($daftarProfil as $profil)
                    @if ($profil->aktif ?? ($profil->tampil ?? false))
                        <article>
                            <strong>{{ $profil->nama_tampil ?? ($profil->pegawai?->nama_lengkap ?? '—') }}</strong>
                            <span>{{ $profil->jabatan_tampil ?? '—' }}</span>
                            <p>{{ $profil->bio_singkat ?? '' }}</p>
                        </article>
                    @endif
                @endforeach
            </div>
        </section>
    </section>
@endsection
