@extends('humas.layout')
@section('title', 'Data Sekolah')
@section('page-class', 'ks-school-page')
@section('content')
    @php $sekolah = $sekolah ?? null; @endphp
    <header class="ks-page-heading">
        <h1>Data Pokok &amp; Profil MI Assaidiyyah</h1>
        <p>Informasi kelembagaan resmi, perizinan operasional, dan parameter akreditasi MI Assaidiyyah.</p>
    </header>
    <section class="ks-school-section">
        <h2>Identitas Satuan Pendidikan</h2>
        <div class="ks-school-fields">
            <article><span>NAMA MADRASAH</span><strong>{{ $sekolah->nama ?? 'Madrasah Ibtidaiyah Assaidiyyah' }}</strong>
            </article>
            <article><span>NPSN (8 DIGIT)</span><strong>{{ $sekolah->npsn ?? '-' }}</strong></article>
            <article><span>NSM (12 DIGIT)</span><strong>{{ $sekolah->nsm ?? '-' }}</strong></article>
            <article><span>STATUS SEKOLAH</span><strong
                    class="ks-school-status"><i></i>{{ $sekolah->status_sekolah ?? 'Swasta Terakreditasi' }}</strong>
            </article>
            <article><span>PERINGKAT AKREDITASI</span><strong
                    class="ks-accreditation">{{ $sekolah->akreditasi ?? 'A (Unggul)' }}</strong></article>
            <article><span>LEMBAGA PENYELENGGARA / YAYASAN</span><strong>{{ $sekolah->yayasan ?? '-' }}</strong></article>
            <article><span>NOMOR SK IZIN OPERASIONAL</span><strong>{{ $sekolah->nomor_sk_operasional ?? '-' }}</strong>
            </article>
            <article class="ks-document-field">
                <span>BERKAS SK IZIN OPERASIONAL</span>
                @if (!empty($sekolah?->sk_berkas_id))
                    <span>Tersedia (Berkas #{{ $sekolah->sk_berkas_id }})</span>
                @else
                    <span>Belum tersedia</span>
                @endif
            </article>
        </div>
    </section>
    <section class="ks-school-section ks-address-section">
        <h2>Alamat &amp; Kontak Resmi</h2>
        <div class="ks-school-fields">
            <article class="ks-school-field-wide"><span>ALAMAT LENGKAP</span><strong>{{ $sekolah->alamat ?? '-' }}</strong>
            </article>
            <article><span>KELURAHAN / DESA</span><strong>{{ $sekolah->kelurahan ?? '-' }}</strong></article>
            <article><span>KECAMATAN</span><strong>{{ $sekolah->kecamatan ?? '-' }}</strong></article>
            <article><span>KOTA / KABUPATEN</span><strong>{{ $sekolah->kota_kabupaten ?? '-' }}</strong></article>
            <article><span>PROVINSI</span><strong>{{ $sekolah->provinsi ?? '-' }}</strong></article>
            <article><span>KODE POS</span><strong>{{ $sekolah->kode_pos ?? '-' }}</strong></article>
            <article><span>TELEPON / NARAHUBUNG</span><strong>{{ $sekolah->telepon ?? '-' }}</strong></article>
            <article><span>EMAIL RESMI LEMBAGA</span><strong>{{ $sekolah->email ?? '-' }}</strong></article>
            <article><span>WEBSITE RESMI</span><strong>{{ $sekolah->website ?? '-' }}</strong></article>
        </div>
    </section>
@endsection
