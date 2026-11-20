@extends('kepala-sekolah.layout')
@section('title', 'Data Sekolah')
@section('page-class', 'ks-school-page')
@section('content')
    <header class="ks-page-heading"><h1>Data Pokok &amp; Profil MI Assaidiyyah</h1><p>Informasi kelembagaan resmi, perizinan operasional, dan parameter akreditasi MI Assaidiyyah.</p></header>
    <section class="ks-school-section">
        <h2>Identitas Satuan Pendidikan</h2>
        <div class="ks-school-fields">
            <article><span>NAMA MADRASAH</span><strong>Madrasah Ibtidaiyah Assaidiyyah</strong></article>
            <article><span>NPSN (8 DIGIT)</span><strong>-</strong></article>
            <article><span>NSM (12 DIGIT)</span><strong>-</strong></article>
            <article><span>STATUS SEKOLAH</span><strong class="ks-school-status"><i></i>Swasta Terakreditasi</strong></article>
            <article><span>PERINGKAT AKREDITASI</span><strong class="ks-accreditation">A (Unggul)</strong></article>
            <article><span>LEMBAGA PENYELENGGARA / YAYASAN</span><strong>-</strong></article>
            <article><span>NOMOR SK IZIN OPERASIONAL</span><strong>-</strong></article>
            <article class="ks-document-field"><span>BERKAS SK IZIN OPERASIONAL</span><button type="button">Unggah SK Izin Operasional</button></article>
        </div>
    </section>
    <section class="ks-school-section ks-address-section">
        <h2>Alamat &amp; Kontak Resmi</h2>
        <div class="ks-school-fields">
            <article class="ks-school-field-wide"><span>ALAMAT LENGKAP</span><strong>-</strong></article>
            <article><span>KELURAHAN / DESA</span><strong>-</strong></article>
            <article><span>KECAMATAN</span><strong>-</strong></article>
            <article><span>KOTA / KABUPATEN</span><strong>-</strong></article>
            <article><span>PROVINSI</span><strong>-</strong></article>
            <article><span>KODE POS</span><strong>-</strong></article>
            <article><span>TELEPON / NARAHUBUNG</span><strong>-</strong></article>
            <article><span>EMAIL RESMI LEMBAGA</span><strong>-</strong></article>
            <article><span>WEBSITE RESMI</span><strong>-</strong></article>
        </div>
    </section>
@endsection