@extends('humas.layout')
@section('title', 'Program & Ekstrakurikuler')
@section('content')
    @php
        $daftarProgram = $daftarProgram ?? collect();
        $isPaginator = $daftarProgram instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
        $statistik = $statistik ?? [
            'total' => $isPaginator ? $daftarProgram->total() : $daftarProgram->count(),
            'draft' => 0,
            'unggulan' => 0,
        ];
        $programEdit = $programEdit ?? null;
        $urlIndex = \Illuminate\Support\Facades\Route::has('humas.program.index')
            ? route('humas.program.index')
            : url('/humas/program');
        $formAction = $programEdit
            ? (\Illuminate\Support\Facades\Route::has('humas.program.update')
                ? route('humas.program.update', $programEdit)
                : url('/humas/program/' . $programEdit->id))
            : (\Illuminate\Support\Facades\Route::has('humas.program.store')
                ? route('humas.program.store')
                : url('/humas/program'));
    @endphp
    <section class="humas-program-page" data-humas-programs>
        <header class="humas-program-heading">
            <div>
                <h1>Kelola Program &amp; Ekstrakurikuler</h1>
                <p>Manajemen katalog program kurikuler unggulan, keagamaan, dan kegiatan ekstrakurikuler santri yang
                    disinkronisasi ke portal publik madrasah.</p>
            </div>
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

        <div class="humas-program-stats">
            <article>
                <div><span>Total Program</span><strong
                        data-program-total>{{ $statistik['total'] ?? 0 }}</strong><small>Semua katalog terdaftar</small>
                </div>
                <b class="program-stat-icon" aria-hidden="true">&#9734;</b>
            </article>
            <article>
                <div><span>Status Draft</span><strong
                        data-program-drafts>{{ $statistik['draft'] ?? 0 }}</strong><small>Perlu penyelesaian data</small>
                </div>
                <b class="program-stat-icon draft" aria-hidden="true">&#9779;</b>
            </article>
        </div>

        <form method="GET" action="{{ $urlIndex }}" class="humas-program-filter" aria-label="Filter program">
            <div class="program-tabs">
                <a href="{{ $urlIndex }}" class="{{ !request('kelompok') ? 'active' : '' }}">Semua Kategori (<span
                        data-program-all-count>{{ $statistik['total'] ?? 0 }}</span>)</a>
                <a href="{{ $urlIndex . '?kelompok=unggulan' }}"
                    class="{{ request('kelompok') === 'unggulan' ? 'active' : '' }}">Program Unggulan
                    ({{ $statistik['unggulan'] ?? 0 }})</a>
                <a href="{{ $urlIndex . '?kelompok=ekstrakurikuler' }}"
                    class="{{ request('kelompok') === 'ekstrakurikuler' ? 'active' : '' }}">Ekstrakurikuler</a>
            </div>
            <label class="program-search">
                <span aria-hidden="true">&#8981;</span>
                <input type="search" name="q" value="{{ request('q') }}" data-program-search
                    placeholder="Cari nama program...">
            </label>
            <select name="status" data-program-status aria-label="Filter status">
                <option value="">Semua Status</option>
                <option value="terbit" @selected(request('status') === 'terbit')>Published</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            </select>
            <button class="program-sort" type="submit" data-program-sort aria-label="Terapkan filter dan urutkan"
                title="Terapkan Filter">&#8597;</button>
        </form>

        <section class="humas-program-table-card">
            <div class="program-table-scroll">
                <table class="humas-program-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Thumbnail</th>
                            <th>Nama Program</th>
                            <th>Kategori</th>
                            <th>Deskripsi Ringkas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody data-program-rows>
                        @forelse ($daftarProgram as $index => $prog)
                            @php
                                $nomor = $isPaginator ? $daftarProgram->firstItem() + $index : $index + 1;
                                $urlEdit = \Illuminate\Support\Facades\Route::has('humas.program.edit')
                                    ? route('humas.program.edit', $prog)
                                    : url('/humas/program/' . $prog->id . '/edit');
                                $urlDestroy = \Illuminate\Support\Facades\Route::has('humas.program.destroy')
                                    ? route('humas.program.destroy', $prog)
                                    : url('/humas/program/' . $prog->id);
                            @endphp
                            <tr>
                                <td>{{ $nomor }}</td>
                                <td>{{ $prog->foto_berkas_id ? 'Ada Foto' : '—' }}</td>
                                <td><strong>{{ $prog->nama }}</strong></td>
                                <td>{{ $prog->kategori ?? '—' }}</td>
                                <td>{{ $prog->deskripsi ?? '—' }}</td>
                                <td>{{ $prog->status?->label() ?? ucfirst((string) ($prog->status ?? 'terbit')) }}</td>
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
                                <td colspan="7">Belum ada data program &amp; ekstrakurikuler yang tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <footer>
                <span data-program-count>Menampilkan {{ $daftarProgram->count() }} dari
                    {{ $isPaginator ? $daftarProgram->total() : $daftarProgram->count() }} data program</span>
                <nav data-program-pagination aria-label="Halaman program">
                    @if ($isPaginator && $daftarProgram->hasPages())
                        {{ $daftarProgram->links() }}
                    @endif
                </nav>
            </footer>
        </section>

        <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="humas-program-form"
            data-program-form>
            @csrf
            @if ($programEdit)
                @method('PUT')
            @endif
            <header>
                <span class="program-form-icon" aria-hidden="true">&#9783;</span>
                <div>
                    <strong>{{ $programEdit ? 'Edit Program & Ekstrakurikuler' : 'Formulir Program & Ekstrakurikuler' }}</strong>
                    <small>Tambahkan atau sesuaikan detail program yang ditayangkan pada landing page portal
                        madrasah.</small>
                </div>
            </header>
            <div class="program-form-grid">
                <div class="program-form-fields">
                    <label>
                        Nama Program / Kegiatan <b>*</b>
                        <input name="nama" required maxlength="100" value="{{ old('nama', $programEdit->nama ?? '') }}">
                        <small>Nama program akan muncul sebagai judul kartu utama pada landing page.</small>
                    </label>
                    <div class="program-form-row">
                        <label>
                            Kategori Program <b>*</b>
                            @php $katVal = old('kategori', $programEdit->kategori ?? 'Program Unggulan'); @endphp
                            <select name="kategori">
                                <option value="Program Keagamaan" @selected($katVal === 'Program Keagamaan')>Program Keagamaan</option>
                                <option value="Program Unggulan" @selected($katVal === 'Program Unggulan')>Program Unggulan</option>
                                <option value="Ekstrakurikuler Wajib" @selected($katVal === 'Ekstrakurikuler Wajib')>Ekstrakurikuler Wajib
                                </option>
                                <option value="Ekstrakurikuler Pilihan" @selected($katVal === 'Ekstrakurikuler Pilihan')>Ekstrakurikuler Pilihan
                                </option>
                            </select>
                        </label>
                        <label>
                            Target Siswa / Sasaran
                            <input name="sasaran"
                                value="{{ old('sasaran', $programEdit->sasaran ?? 'Kelas 1–6 (Semua Tingkat)') }}">
                        </label>
                    </div>
                    <label>
                        Instruktur / Pembimbing
                        <input name="pembimbing" value="{{ old('pembimbing', $programEdit->pembimbing ?? '') }}">
                    </label>
                    <label>
                        Deskripsi Program <b>*</b>
                        <textarea name="deskripsi" rows="3" required maxlength="250" data-program-description>{{ old('deskripsi', $programEdit->deskripsi ?? '') }}</textarea>
                        <small class="program-description-meta">
                            <span>Maksimal 250 karakter untuk menjaga kerapian grid publik.</span>
                            <span><b
                                    data-program-description-count>{{ mb_strlen((string) old('deskripsi', $programEdit->deskripsi ?? '')) }}</b>
                                / 250 karakter</span>
                        </small>
                    </label>
                </div>
                <aside class="program-form-aside">
                    <label class="program-upload-label">
                        Foto Thumbnail Program
                        <span class="program-upload">
                            <img data-program-image-preview hidden alt="Pratinjau thumbnail">
                            <span data-program-upload-copy>&#9729;<strong>Tarik &amp; letakkan foto
                                    program</strong><small>JPG, PNG atau WebP (Maks. 2MB, rasio 4:3)</small></span>
                            <input type="file" name="foto" accept="image/png,image/jpeg,image/webp"
                                data-program-image>
                        </span>
                    </label>
                    <fieldset>
                        <legend>Status Visibilitas</legend>
                        @php $visVal = old('status', $programEdit->status?->value ?? (string) ($programEdit->status ?? 'terbit')); @endphp
                        <label><input type="radio" name="status" value="terbit"
                                @checked($visVal === 'terbit')><span><strong>Published (Tayang)</strong><small>Langsung tampil
                                    di seksi program landing page madrasah.</small></span></label>
                        <label><input type="radio" name="status" value="draft"
                                @checked($visVal === 'draft')><span><strong>Simpan sebagai Draft</strong><small>Hanya dapat
                                    diakses staf Humas internal.</small></span></label>
                    </fieldset>
                    <div class="program-form-actions">
                        <button type="submit" name="status" value="draft" data-program-save="Draft">Simpan Draft</button>
                        <button class="primary" type="submit" name="status" value="terbit"
                            data-program-save="Published">Publikasikan</button>
                    </div>
                </aside>
            </div>
        </form>
    </section>
@endsection
