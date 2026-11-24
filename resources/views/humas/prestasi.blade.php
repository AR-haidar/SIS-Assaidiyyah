@extends('humas.layout')
@section('title', 'Prestasi Siswa')
@section('content')
    @php
        $daftarPrestasi = $daftarPrestasi ?? collect();
        $isPaginator = $daftarPrestasi instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
        $statistik = $statistik ?? [
            'total' => $isPaginator ? $daftarPrestasi->total() : $daftarPrestasi->count(),
            'aktif' => 0,
            'keagamaan' => 0,
            'akademik' => 0,
            'olahraga_seni' => 0,
        ];
        $prestasiEdit = $prestasiEdit ?? null;
        $urlIndex = \Illuminate\Support\Facades\Route::has('humas.prestasi.index')
            ? route('humas.prestasi.index')
            : url('/humas/prestasi');
        $formAction = $prestasiEdit
            ? (\Illuminate\Support\Facades\Route::has('humas.prestasi.update')
                ? route('humas.prestasi.update', $prestasiEdit)
                : url('/humas/prestasi/' . $prestasiEdit->id))
            : (\Illuminate\Support\Facades\Route::has('humas.prestasi.store')
                ? route('humas.prestasi.store')
                : url('/humas/prestasi'));
    @endphp
    <section class="humas-achievements" data-humas-achievements>
        <div class="humas-breadcrumb">CMS Humas &rsaquo; Pengelolaan Landing Page &rsaquo; <strong>Prestasi Siswa</strong>
        </div>
        <header class="achievement-heading">
            <div>
                <h1>Kelola Prestasi Siswa</h1>
                <p>Manajemen dokumentasi capaian kejuaraan, olimpiade, dan prestasi keagamaan untuk<br
                        class="achievement-break"> ditampilkan di Landing Page publik.</p>
            </div>
            <a href="{{ $urlIndex }}#form-prestasi" class="achievement-add" data-achievement-focus>&#65291; Tambah
                Prestasi Baru</a>
        </header>

        @if (session('status'))
            <div role="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="achievement-stats">
            <article>
                <div><span>Total Prestasi Dicatat</span><strong
                        data-achievement-total>{{ $statistik['total'] ?? 0 }}</strong><small>&#9673;
                        {{ $statistik['aktif'] ?? 0 }} Aktif Publik</small></div>
                <b>&#9820;</b>
            </article>
            <article>
                <div><span>Bidang Keagamaan</span><strong>{{ $statistik['keagamaan'] ?? 0 }}</strong><small>Tahfidz &amp;
                        MTQ<br>unggulan</small></div>
                <b>&#9780;</b>
            </article>
            <article>
                <div><span>Bidang
                        Akademik</span><strong>{{ $statistik['akademik'] ?? 0 }}</strong><small>Sains,<br>Matematika,
                        dll</small></div>
                <b>&#9825;</b>
            </article>
            <article>
                <div><span>Olahraga &amp; Seni
                        Budaya</span><strong>{{ $statistik['olahraga_seni'] ?? 0 }}</strong><small>Pencak Silat &amp;
                        Hadroh</small></div>
                <b>&#9879;</b>
            </article>
        </div>

        <div class="achievement-layout">
            <div class="achievement-list-column">
                <form method="GET" action="{{ $urlIndex }}" class="achievement-filters" data-achievement-filter>
                    <label>&#8981; <input name="q" type="search" value="{{ request('q', request('search')) }}"
                            placeholder="Cari nama prestasi..."></label>
                    <select name="tingkat">
                        <option value="">Semua Tingkat</option>
                        <option value="Kecamatan" @selected(request('tingkat') === 'Kecamatan')>Kecamatan</option>
                        <option value="Kabupaten" @selected(request('tingkat') === 'Kabupaten')>Kabupaten</option>
                        <option value="Provinsi" @selected(request('tingkat') === 'Provinsi')>Provinsi</option>
                        <option value="Nasional" @selected(request('tingkat') === 'Nasional')>Nasional</option>
                    </select>
                    <select name="aktif">
                        <option value="">Semua Status</option>
                        <option value="1" @selected(request('aktif') === '1')>Aktif / Publik</option>
                        <option value="0" @selected(request('aktif') === '0')>Draf / Nonaktif</option>
                    </select>
                    <button type="submit">Terapkan Filter</button>
                </form>

                <section class="achievement-table-card">
                    <div class="achievement-table-scroll">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Dokumentasi</th>
                                    <th>Nama Prestasi &amp; Kejuaraan</th>
                                    <th>Kategori / Tingkat</th>
                                    <th>Tahun</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody data-achievement-rows>
                                @forelse ($daftarPrestasi as $index => $prestasi)
                                    @php
                                        $nomor = $isPaginator ? $daftarPrestasi->firstItem() + $index : $index + 1;
                                        $urlEdit = \Illuminate\Support\Facades\Route::has('humas.prestasi.edit')
                                            ? route('humas.prestasi.edit', $prestasi)
                                            : url('/humas/prestasi/' . $prestasi->id . '/edit');
                                        $urlDestroy = \Illuminate\Support\Facades\Route::has('humas.prestasi.destroy')
                                            ? route('humas.prestasi.destroy', $prestasi)
                                            : url('/humas/prestasi/' . $prestasi->id);
                                    @endphp
                                    <tr>
                                        <td>{{ $nomor }}</td>
                                        <td>{{ $prestasi->foto_berkas_id ? 'Ada Foto' : '—' }}</td>
                                        <td><strong>{{ $prestasi->judul }}</strong></td>
                                        <td>{{ $prestasi->kategori ?? '—' }} ({{ $prestasi->tingkat ?? '—' }})</td>
                                        <td>{{ $prestasi->tahun ?? ($prestasi->tanggal?->format('Y') ?? '—') }}</td>
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
                                        <td colspan="6">Belum ada data prestasi sekolah yang tercatat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <footer>
                        <span data-achievement-summary>Menampilkan {{ $daftarPrestasi->count() }} dari
                            {{ $isPaginator ? $daftarPrestasi->total() : $daftarPrestasi->count() }} data prestasi</span>
                        <nav data-achievement-pagination>
                            @if ($isPaginator && $daftarPrestasi->hasPages())
                                {{ $daftarPrestasi->links() }}
                            @endif
                        </nav>
                    </footer>
                </section>

                <aside class="achievement-sync">
                    <b>&#9635;</b>
                    <div>
                        <strong>Sinkronisasi Etalase Landing Page</strong>
                        <p>Data berstatus aktif otomatis disiarkan pada seksi Prestasi Siswa di halaman depan madrasah untuk
                            menunjang branding madrasah saat PPDB.</p>
                    </div>
                </aside>
            </div>

            <form id="form-prestasi" method="POST" action="{{ $formAction }}" enctype="multipart/form-data"
                class="achievement-form" data-achievement-form>
                @csrf
                @if ($prestasiEdit)
                    @method('PUT')
                @endif
                <header>
                    <span>&#10047;</span>
                    <div>
                        <strong>{{ $prestasiEdit ? 'Edit Prestasi Siswa' : 'Input Prestasi Siswa' }}</strong>
                        <small>Formulir publikasi katalog kejuaraan</small>
                    </div>
                </header>

                <label>
                    Nama Prestasi / Kejuaraan <small>Maks. 200 karakter</small>
                    <input name="judul" required maxlength="200" value="{{ old('judul', $prestasiEdit->judul ?? '') }}"
                        placeholder="Contoh: Juara 1 Kaligrafi Kontemporer Putra">
                </label>

                <div class="achievement-form-row">
                    <label>
                        Kategori Prestasi <b>*</b>
                        @php $katVal = old('kategori', $prestasiEdit->kategori ?? 'Keagamaan'); @endphp
                        <select name="kategori">
                            <option value="Keagamaan" @selected($katVal === 'Keagamaan')>Keagamaan</option>
                            <option value="Akademik" @selected($katVal === 'Akademik')>Akademik</option>
                            <option value="Olahraga & Seni" @selected($katVal === 'Olahraga & Seni')>Olahraga &amp; Seni</option>
                        </select>
                    </label>
                    <label>
                        Tingkat Kejuaraan <b>*</b>
                        @php $tingkatVal = old('tingkat', $prestasiEdit->tingkat ?? 'Kecamatan'); @endphp
                        <select name="tingkat">
                            <option value="Kecamatan" @selected($tingkatVal === 'Kecamatan')>Kecamatan</option>
                            <option value="Kabupaten" @selected($tingkatVal === 'Kabupaten')>Kabupaten</option>
                            <option value="Provinsi" @selected($tingkatVal === 'Provinsi')>Provinsi</option>
                            <option value="Nasional" @selected($tingkatVal === 'Nasional')>Nasional</option>
                        </select>
                    </label>
                </div>

                <div class="achievement-form-row">
                    <label>
                        Tahun <b>*</b>
                        <input type="number" name="tahun" min="1990" max="2100" required
                            value="{{ old('tahun', $prestasiEdit->tahun ?? date('Y')) }}" placeholder="Contoh: 2026">
                    </label>
                    <label>
                        Tanggal Capaian
                        <input type="date" name="tanggal"
                            value="{{ old('tanggal', isset($prestasiEdit->tanggal) ? $prestasiEdit->tanggal->format('Y-m-d') : '') }}">
                    </label>
                </div>

                <label>
                    Informasi Singkat / Deskripsi
                    <textarea name="deskripsi" rows="3"
                        placeholder="Tuliskan keterangan penyelenggara, peraih, cabang lomba, atau catatan capaian...">{{ old('deskripsi', $prestasiEdit->deskripsi ?? '') }}</textarea>
                </label>

                <label>
                    Upload Foto Dokumentasi <small>JPG, PNG, WebP maks 3MB</small>
                    <span class="achievement-upload">
                        <img data-achievement-image-preview hidden alt="Pratinjau dokumentasi">
                        <span data-achievement-upload-copy>&#9729;<strong>Tarik dan jatuhkan file foto di
                                sini</strong><small>atau pilih dari perangkat Anda</small></span>
                        <input type="file" name="foto" accept="image/jpeg,image/png,image/webp"
                            data-achievement-image>
                    </span>
                </label>

                <fieldset>
                    <legend>Status Publikasi ke Landing Page</legend>
                    @php $aktifVal = (string) old('aktif', isset($prestasiEdit) ? ($prestasiEdit->aktif ? '1' : '0') : '1'); @endphp
                    <label><input type="radio" name="aktif" value="1"
                            @checked($aktifVal === '1')><span><strong>Published / Aktif</strong><small>Tampil di portal
                                publik</small></span></label>
                    <label><input type="radio" name="aktif" value="0"
                            @checked($aktifVal === '0')><span><strong>Draf</strong><small>Disembunyikan dari
                                publik</small></span></label>
                </fieldset>

                <footer>
                    <button type="submit" name="aktif" value="0" data-achievement-save="Draft">Simpan Draf</button>
                    <button class="primary" type="submit" name="aktif" value="1"
                        data-achievement-save="Published">&#8679; Publikasikan Prestasi</button>
                </footer>
            </form>
        </div>
    </section>
@endsection
