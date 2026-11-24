@extends('guru.layout')
@section('title', 'Profile Guru')
@section('page-class', 'guru-profile-page')
@section('content')
    @php
        $pegawai = $pegawai ?? ($guru ?? null);
        $user = auth()->user();

        $namaLengkap = $pegawai->nama_lengkap ?? ($pegawai->nama ?? ($user?->nama_lengkap ?? ($user?->nama ?? '-')));
        $nip = $pegawai->nip ?? '-';
        $nuptk = $pegawai->nuptk ?? '-';
        $nik = $pegawai->nik ?? '-';
        $statusKepegawaian =
            $pegawai->status_kepegawaian_nama ??
            ($pegawai->statusKepegawaian?->nama ?? ($pegawai->status_kepegawaian ?? '-'));
        $pendidikanTerakhir =
            $pegawai->pendidikan_terakhir_nama ??
            ($pegawai->pendidikanTerakhir?->nama ?? ($pegawai->pendidikan_terakhir ?? '-'));

        $tempatLahir = $pegawai->tempat_lahir ?? null;
        $tanggalLahir = !empty($pegawai?->tanggal_lahir)
            ? \Illuminate\Support\Carbon::parse($pegawai->tanggal_lahir)->translatedFormat('d M Y')
            : null;
        $ttl = $tempatLahir || $tanggalLahir ? ($tempatLahir ?? '-') . ', ' . ($tanggalLahir ?? '-') : '-';

        $jenisKelamin = $pegawai->jenis_kelamin ?? '-';
        $telepon = $pegawai->no_whatsapp ?? ($pegawai->no_hp ?? ($pegawai->telepon ?? '-'));
        $email = $pegawai->email ?? ($user?->email ?? '-');
        $alamat = $pegawai->alamat ?? '-';

        $fotoUrl = $fotoUrl ?? ($pegawai->foto_url ?? ($pegawai->pasfoto_url ?? null));
    @endphp
    <header class="guru-page-heading">
        <h1>Profil</h1>
    </header>
    <div class="guru-profile-layout">
        <aside class="guru-profile-card">
            <div class="guru-photo-placeholder">
                @if (!empty($fotoUrl))
                    <img src="{{ $fotoUrl }}" alt="Pasfoto {{ $namaLengkap }}">
                @else
                    <span><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 21a8 8 0 0 1 16 0" />
                        </svg></span>
                    <strong>Pasfoto Pendidik</strong>
                    <small>Format Resmi<br>3-4</small>
                @endif
            </div>
            <div class="guru-profile-summary"><span>NAMA LENGKAP<br>&amp; GELAR</span><strong>{{ $namaLengkap }}</strong>
            </div>
            <div class="guru-profile-summary"><span>Nomor Induk Pegawai<br>(NIP /
                    ID)</span><strong>{{ $nip }}</strong></div>
            <div class="guru-profile-summary"><span>Status Kepegawaian</span><strong>{{ $statusKepegawaian }}</strong></div>
        </aside>
        <div class="guru-profile-details">
            <section class="guru-profile-section">
                <h2>Identitas Pokok Kepegawaian</h2>
                <div class="guru-profile-fields">
                    <article><span>Nama Lengkap</span><strong>{{ $namaLengkap }}</strong></article>
                    <article><span>Nomor Induk Pegawai (NIP)</span><strong>{{ $nip }}</strong></article>
                    <article><span>Nomor Unik Pendidik &amp; Tenaga Kependidikan
                            (NUPTK)</span><strong>{{ $nuptk }}</strong></article>
                    <article><span>Nomor Induk Kependudukan (NIK)</span><strong>{{ $nik }}</strong></article>
                    <article><span>Status Kepegawaian</span><strong>{{ $statusKepegawaian }}</strong></article>
                    <article><span>Pendidikan Terakhir</span><strong>{{ $pendidikanTerakhir }}</strong></article>
                </div>
            </section>
            <section class="guru-profile-section">
                <h2>Informasi Pribadi &amp; Kontak Resmi</h2>
                <div class="guru-profile-fields">
                    <article><span>Tempat, Tanggal Lahir</span><strong>{{ $ttl }}</strong></article>
                    <article><span>Jenis Kelamin</span><strong>{{ $jenisKelamin }}</strong></article>
                    <article><span>Nomor Telepon / WhatsApp</span><strong>{{ $telepon }}</strong></article>
                    <article><span>Alamat Email Resmi</span><strong>{{ $email }}</strong></article>
                    <article class="guru-field-wide"><span>Alamat Domisili
                            Lengkap</span><strong>{{ $alamat }}</strong></article>
                </div>
            </section>
        </div>
    </div>
@endsection
