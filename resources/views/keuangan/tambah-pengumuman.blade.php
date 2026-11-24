@extends('keuangan.layout')
@section('title', isset($pengumuman) ? 'Edit Pengumuman Keuangan' : 'Tambah Pengumuman Keuangan')
@section('content')
    @php
        $isEdit = isset($pengumuman);
        $daftarKategori = $daftarKategori ?? collect();
        $urlIndex = \Illuminate\Support\Facades\Route::has('keuangan.pengumuman.index')
            ? route('keuangan.pengumuman.index')
            : url('/keuangan/pengumuman');
        $formAction = $isEdit
            ? (\Illuminate\Support\Facades\Route::has('keuangan.pengumuman.update')
                ? route('keuangan.pengumuman.update', $pengumuman)
                : url('/keuangan/pengumuman/' . $pengumuman->id))
            : (\Illuminate\Support\Facades\Route::has('keuangan.pengumuman.store')
                ? route('keuangan.pengumuman.store')
                : url('/keuangan/pengumuman'));
        $urlDestroy = $isEdit
            ? (\Illuminate\Support\Facades\Route::has('keuangan.pengumuman.destroy')
                ? route('keuangan.pengumuman.destroy', $pengumuman)
                : url('/keuangan/pengumuman/' . $pengumuman->id))
            : null;
    @endphp
    <section class="keuangan-payments">
        <header class="keuangan-payments-heading">
            <h1>{{ $isEdit ? 'Edit Pengumuman Keuangan' : 'Tambah Pengumuman Keuangan' }}</h1>
        </header>

        @if ($errors->any())
            <div role="alert" class="keuangan-alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="keuangan-revision-form">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif
            <label>
                Judul
                <input type="text" name="judul" value="{{ old('judul', $pengumuman->judul ?? '') }}" required>
            </label>
            <label>
                Tanggal Publikasi
                <input type="date" name="tanggal_terbit"
                    value="{{ old('tanggal_terbit', isset($pengumuman->tanggal_terbit) ? \Carbon\Carbon::parse($pengumuman->tanggal_terbit)->format('Y-m-d') : now()->format('Y-m-d')) }}"
                    required>
            </label>
            <label>
                Jenis Informasi
                <select name="kategori_id">
                    @forelse ($daftarKategori as $kat)
                        <option value="{{ $kat->id }}" @selected((string) old('kategori_id', $pengumuman->kategori_id ?? '') === (string) $kat->id)>
                            {{ $kat->label ?? $kat->nama }}
                        </option>
                    @empty
                        <option value="Informasi Umum" @selected(old('kategori_id', $pengumuman->kategori_id ?? '') === 'Informasi Umum')>Informasi Umum</option>
                        <option value="Tagihan" @selected(old('kategori_id', $pengumuman->kategori_id ?? '') === 'Tagihan')>Tagihan</option>
                        <option value="Pembayaran" @selected(old('kategori_id', $pengumuman->kategori_id ?? '') === 'Pembayaran')>Pembayaran</option>
                        <option value="Kegiatan" @selected(old('kategori_id', $pengumuman->kategori_id ?? '') === 'Kegiatan')>Kegiatan</option>
                    @endforelse
                </select>
            </label>
            <label>
                Status
                @php $statusVal = old('status', $isEdit ? ($pengumuman->status?->value ?? (string) $pengumuman->status) : 'terbit'); @endphp
                <select name="status">
                    <option value="terbit" @selected($statusVal === 'terbit')>Terbit</option>
                    <option value="draft" @selected($statusVal === 'draft')>Draf</option>
                    <option value="arsip" @selected($statusVal === 'arsip')>Arsip</option>
                </select>
            </label>
            <label class="form-wide">
                Isi Pengumuman
                <textarea name="isi" rows="5" required>{{ old('isi', $pengumuman->isi ?? '') }}</textarea>
            </label>
            <label class="form-wide">
                Lampiran (JPG, PNG, PDF, DOC, DOCX, XLS, XLSX, ODT)
                <input name="lampiran" type="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.odt">
                <small data-file-name>Pilih foto atau dokumen</small>
            </label>
            <div class="form-wide form-actions">
                <a class="keuangan-button" href="{{ $urlIndex }}">Batal</a>
                <button type="submit" class="keuangan-button keuangan-button-primary">Simpan Pengumuman</button>
            </div>
        </form>

        @if ($isEdit && $urlDestroy)
            <form method="POST" action="{{ $urlDestroy }}" class="keuangan-delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="keuangan-button keuangan-delete-announcement">Hapus Pengumuman</button>
            </form>
        @endif
    </section>
@endsection
