@extends('kepala-sekolah.layout')
@section('title', 'Kelola Data Sekolah')
@section('page-class', 'ks-school-page')
@section('content')
    @php
        $sekolah = $sekolah ?? null;
        $formAction = \Illuminate\Support\Facades\Route::has('kepala.sekolah.update')
            ? route('kepala.sekolah.update')
            : (\Illuminate\Support\Facades\Route::has('sekolah.update')
                ? route('sekolah.update')
                : url('/kepala-sekolah/data-sekolah'));
    @endphp
    <header class="ks-page-heading">
        <h1>Kelola Data Pokok &amp; Profil MI Assaidiyyah</h1>
        <p>Informasi kelembagaan resmi, perizinan operasional, dan parameter akreditasi MI Assaidiyyah.</p>
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

    <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <section class="ks-school-section">
            <h2>Identitas Satuan Pendidikan</h2>
            <div class="ks-school-fields">
                <article>
                    <span>NAMA MADRASAH</span>
                    <input type="text" name="nama"
                        value="{{ old('nama', $sekolah->nama ?? 'Madrasah Ibtidaiyah Assaidiyyah') }}" required>
                </article>
                <article>
                    <span>NPSN (8 DIGIT)</span>
                    <input type="text" name="npsn" maxlength="8" value="{{ old('npsn', $sekolah->npsn ?? '') }}"
                        placeholder="-">
                </article>
                <article>
                    <span>NSM (12 DIGIT)</span>
                    <input type="text" name="nsm" maxlength="12" value="{{ old('nsm', $sekolah->nsm ?? '') }}"
                        placeholder="-">
                </article>
                <article>
                    <span>STATUS SEKOLAH</span>
                    <input type="text" name="status_sekolah"
                        value="{{ old('status_sekolah', $sekolah->status_sekolah ?? 'Swasta Terakreditasi') }}">
                </article>
                <article>
                    <span>PERINGKAT AKREDITASI</span>
                    <input type="text" name="akreditasi"
                        value="{{ old('akreditasi', $sekolah->akreditasi ?? ($sekolah->status_akreditasi ?? 'A (Unggul)')) }}">
                </article>
                <article>
                    <span>LEMBAGA PENYELENGGARA / YAYASAN</span>
                    <input type="text" name="yayasan" value="{{ old('yayasan', $sekolah->yayasan ?? '') }}"
                        placeholder="-">
                </article>
                <article>
                    <span>NOMOR SK IZIN OPERASIONAL</span>
                    <input type="text" name="nomor_sk_operasional"
                        value="{{ old('nomor_sk_operasional', $sekolah->nomor_sk_operasional ?? '') }}" placeholder="-">
                </article>
                <article class="ks-document-field">
                    <span>BERKAS SK IZIN OPERASIONAL</span>
                    @if (!empty($sekolah?->sk_berkas_id))
                        <small>Berkas saat ini: #{{ $sekolah->sk_berkas_id }}</small>
                    @endif
                    <input type="file" name="berkas_sk" accept=".pdf,image/jpeg,image/png">
                </article>
            </div>
        </section>

        <section class="ks-school-section ks-address-section">
            <h2>Alamat &amp; Kontak Resmi</h2>
            <div class="ks-school-fields">
                <article class="ks-school-field-wide">
                    <span>ALAMAT LENGKAP</span>
                    <input type="text" name="alamat" value="{{ old('alamat', $sekolah->alamat ?? '') }}"
                        placeholder="-">
                </article>
                <article>
                    <span>KELURAHAN / DESA</span>
                    <input type="text" name="kelurahan" value="{{ old('kelurahan', $sekolah->kelurahan ?? '') }}"
                        placeholder="-">
                </article>
                <article>
                    <span>KECAMATAN</span>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan', $sekolah->kecamatan ?? '') }}"
                        placeholder="-">
                </article>
                <article>
                    <span>KOTA / KABUPATEN</span>
                    <input type="text" name="kota_kabupaten"
                        value="{{ old('kota_kabupaten', $sekolah->kota_kabupaten ?? '') }}" placeholder="-">
                </article>
                <article>
                    <span>PROVINSI</span>
                    <input type="text" name="provinsi" value="{{ old('provinsi', $sekolah->provinsi ?? '') }}"
                        placeholder="-">
                </article>
                <article>
                    <span>KODE POS</span>
                    <input type="text" name="kode_pos" maxlength="10"
                        value="{{ old('kode_pos', $sekolah->kode_pos ?? '') }}" placeholder="-">
                </article>
                <article>
                    <span>TELEPON / NARAHUBUNG</span>
                    <input type="text" name="telepon" value="{{ old('telepon', $sekolah->telepon ?? '') }}"
                        placeholder="-">
                </article>
                <article>
                    <span>EMAIL RESMI LEMBAGA</span>
                    <input type="email" name="email" value="{{ old('email', $sekolah->email ?? '') }}" placeholder="-">
                </article>
                <article>
                    <span>WEBSITE RESMI</span>
                    <input type="text" name="website" value="{{ old('website', $sekolah->website ?? '') }}"
                        placeholder="-">
                </article>
            </div>
        </section>

        <div class="ks-form-actions">
            <button type="submit" class="ks-filter-button">Simpan Data Sekolah</button>
        </div>
    </form>
@endsection
