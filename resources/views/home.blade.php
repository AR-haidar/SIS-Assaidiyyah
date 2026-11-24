<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Profil madrasah, program, prestasi siswa, dan layanan digital MI Assaidiyyah.">
    <title>MI Assaidiyyah | Sistem Informasi Madrasah</title>
    <link rel="icon" href="{{ asset('images/logo-assaidiyyah.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
</head>
<body class="landing-page">
@php
    $namaSekolah = $sekolah?->nama ?: 'MI Assaidiyyah';
    $alamat = $sekolah?->alamat;
    $katalog = route('publik.perpustakaan.index');
    $misi = $misiSekolah->isNotEmpty() ? $misiSekolah->pluck('isi') : [
        'Menanamkan keimanan dan ketakwaan melalui pengamalan karakter keislaman.',
        'Membina kemandirian peserta didik melalui kegiatan pembiasaan kewirausahaan dan pengembangan diri.',
        'Mengoptimalkan proses pembelajaran dan bimbingan.',
        'Mengembangkan ilmu pengetahuan dan teknologi berdasarkan minat, bakat, dan potensi.',
        'Menjalin kerja sama yang harmonis antarwarga sekolah dan lembaga lain yang terkait.',
    ];
    $programContoh = [
        ['Program Utama', 'Pembelajaran Madrasah', 'Kompetensi dasar dan nilai keislaman dalam kegiatan belajar.', 'Jenjang Kelas I–VI'],
        ['Program Utama', 'Pembinaan Keislaman', 'Penguatan bacaan Al-Qur’an dan pembiasaan ibadah.', 'Kegiatan Keagamaan'],
        ['Pengembangan Minat', 'Ekstrakurikuler', 'Ruang untuk melatih keterampilan, kreativitas, dan kerja sama siswa.', 'Peminatan Siswa'],
    ];
    $guruContoh = [
        ['Guru Kelas', 'Mendampingi siswa dalam pembelajaran sehari-hari.', 'school'],
        ['Guru Pendidikan Agama', 'Membimbing pembelajaran agama dan pembiasaan ibadah.', 'building'],
        ['Guru Pengembangan Minat', 'Mendampingi kegiatan olahraga, seni, dan keterampilan.', 'book'],
        ['Tenaga Kependidikan', 'Mendukung kegiatan belajar dan layanan madrasah.', 'globe'],
    ];
    $kolomKontak = [
        'nama_pengirim' => ['Nama Lengkap', 'name', 150],
        'kontak' => ['Email / Kontak Aktif', null, 100],
        'subjek' => ['Subjek', null, 150],
        'pesan' => ['Pesan', null, 3000],
    ];
@endphp

<x-public-header :school-name="$namaSekolah" :portal="$portal" />

<main id="beranda" tabindex="-1">
    <section class="landing-band landing-hero" aria-labelledby="landing-title">
        <div class="landing-wrap landing-hero-grid">
            <div>
                <span class="landing-chip">{{ $sekolah?->status_akreditasi ? 'Akreditasi '.$sekolah->status_akreditasi : 'Madrasah Ibtidaiyah' }}</span>
                <h1 id="landing-title">Sistem Informasi &amp; Portal Akademik {{ $namaSekolah }}</h1>
                <p class="landing-intro">{{ $sekolah?->deskripsi_singkat ?: 'Portal informasi, akademik, administrasi, dan layanan digital MI Assaidiyyah untuk siswa, orang tua, guru, dan tenaga kependidikan.' }}</p>
                <div class="landing-hero-lines" aria-hidden="true"><span></span><span></span></div>
                <div class="landing-actions">
                    <a class="landing-button" href="#profil">Jelajahi Profil Madrasah</a>
                    <a class="landing-button landing-button-soft" href="#program">Program Unggulan</a>
                </div>
                <div class="landing-hero-meta">
                    <span>Kurikulum Merdeka &amp; Kemenag RI</span><span class="landing-hero-portal-meta"><span aria-hidden="true">•</span> Portal Terintegrasi</span>
                </div>
            </div>
            <figure class="landing-hero-photo">
                <x-landing-photo
                    :src="$sekolah?->fotoGedung ? route('publik.foto', ['gedung', $sekolah->id]) : null"
                    label="Foto Gedung & Lingkungan Madrasah"
                    :caption="$sekolah?->fotoGedung ? null : 'Foto resmi madrasah dalam format lanskap'"
                />
                <figcaption>
                    <span>Gedung dan Lingkungan {{ $namaSekolah }}</span>
                    @unless ($sekolah?->fotoGedung)<span>Rasio 4:3</span>@endunless
                </figcaption>
            </figure>
        </div>
    </section>

    <section class="landing-band landing-band-soft" id="profil" aria-labelledby="landing-profil-title">
        <div class="landing-wrap">
            <header class="landing-section-head">
                <p class="landing-eyebrow">Profil Lembaga</p>
                <h2 id="landing-profil-title">Visi &amp; Misi Madrasah</h2>
                <p>Landasan filosofis dan komitmen pendidikan dasar Islam di lingkungan {{ $namaSekolah }}.</p>
            </header>
            <div class="landing-split">
                <article class="landing-card landing-profile-card landing-vision">
                    <header class="landing-panel-head"><h3>Visi Utama</h3><span>{{ $namaSekolah }}</span></header>
                    <blockquote>“{{ $sekolah?->visi ?: 'Membentuk Pribadi Islami Nan Mandiri, Berbudi Pekerti dan Bermoderasi di Era Digitalisasi' }}”</blockquote>
                    <footer>Visi Madrasah</footer>
                </article>
                <article class="landing-card landing-profile-card">
                    <header class="landing-panel-head"><h3>Misi Madrasah</h3><span>Komitmen Madrasah</span></header>
                    <ol class="landing-missions">
                        @foreach ($misi as $isi)
                            <li><span aria-hidden="true">{{ sprintf('%02d', $loop->iteration) }}</span><p>{{ $isi }}</p></li>
                        @endforeach
                    </ol>
                </article>
            </div>
        </div>
    </section>

    <section class="landing-band" id="program" aria-labelledby="landing-program-title">
        <div class="landing-wrap">
            <header class="landing-section-head landing-section-rule">
                <p class="landing-eyebrow">Kurikulum &amp; Ekstrakurikuler</p>
                <h2 id="landing-program-title">Program Unggulan Madrasah</h2>
            </header>
            <div class="landing-grid">
                @forelse ($programUnggulan as $program)
                    <article class="landing-card landing-tile">
                        <x-landing-photo :src="$program->foto ? route('publik.foto', ['program', $program->id]) : null" :label="'Foto '.$program->nama" />
                        <div class="landing-tile-body">
                            <span class="landing-chip">Program Madrasah</span>
                            <h3>{{ $program->nama }}</h3>
                            @if ($program->deskripsi)
                                <p>{{ Str::limit($program->deskripsi, 160) }}</p>
                                <footer class="landing-tile-foot">
                                    <button class="landing-text-action" type="button" data-landing-detail data-title="{{ $program->nama }}" data-description="{{ $program->deskripsi }}">Detail Program <x-landing-icon name="arrow" /></button>
                                </footer>
                            @endif
                        </div>
                    </article>
                @empty
                    @foreach ($programContoh as [$jenis, $nama, $deskripsi, $cakupan])
                        <article class="landing-card landing-tile">
                            <x-landing-photo label="Foto Program" />
                            <div class="landing-tile-body">
                                <span class="landing-chip">{{ $jenis }}</span><h3>{{ $nama }}</h3><p>{{ $deskripsi }}</p>
                                <footer class="landing-tile-foot"><span>{{ $cakupan }}</span></footer>
                            </div>
                        </article>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    <section class="landing-band landing-band-soft" id="fasilitas" aria-labelledby="landing-guru-title">
        <div class="landing-wrap" id="guru">
            <header class="landing-section-head">
                <h2 id="landing-guru-title">Profil Guru</h2>
                <p>Kenali tenaga pendidik dan staf yang mendampingi kegiatan belajar siswa.</p>
            </header>
            <div class="landing-grid landing-grid-four">
                @forelse ($profilGuruPublik as $guru)
                    @php($namaGuru = $guru->nama_tampil ?: ($guru->pegawai?->nama_lengkap ?: 'Tenaga Pendidik'))
                    <article class="landing-card landing-tile">
                        <x-landing-photo :src="$guru->foto ? route('publik.foto', ['guru', $guru->id]) : null" :label="'Foto '.$namaGuru" icon="person" />
                        <div class="landing-tile-body">
                            <h3>{{ $namaGuru }}</h3>
                            @if ($guru->jabatan_tampil)<p>{{ $guru->jabatan_tampil }}</p>@endif
                            @if ($guru->bio_singkat)<p>{{ $guru->bio_singkat }}</p>@endif
                        </div>
                    </article>
                @empty
                    @foreach ($guruContoh as [$nama, $deskripsi, $ikon])
                        <article class="landing-card landing-tile">
                            <x-landing-photo :label="'Foto '.$nama" :icon="$ikon" />
                            <div class="landing-tile-body"><h3>{{ $nama }}</h3><p>{{ $deskripsi }}</p></div>
                        </article>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    <section class="landing-band" id="berita" aria-labelledby="landing-prestasi-title">
        <div class="landing-wrap" id="prestasi">
            <header class="landing-section-head landing-section-rule">
                <p class="landing-eyebrow">Katalog Siswa</p>
                <h2 id="landing-prestasi-title">Katalog Prestasi Siswa</h2>
            </header>
            <div class="landing-grid">
                @forelse ($prestasiSekolah as $prestasi)
                    <article class="landing-card landing-tile">
                        <x-landing-photo :src="$prestasi->foto ? route('publik.foto', ['prestasi', $prestasi->id]) : null" :label="'Foto '.$prestasi->judul" icon="" />
                        <div class="landing-tile-body">
                            <div class="landing-item-meta">
                                <span>{{ $prestasi->kategori ?: 'Prestasi Siswa' }}</span>
                                @if ($prestasi->tanggal || $prestasi->tahun)
                                    <span aria-hidden="true">•</span><span>{{ $prestasi->tanggal?->locale('id')->translatedFormat('j F Y') ?? $prestasi->tahun }}</span>
                                @endif
                            </div>
                            <h3>{{ $prestasi->judul }}</h3>
                            @if ($prestasi->deskripsi)
                                <p>{{ Str::limit($prestasi->deskripsi, 160) }}</p>
                                <footer class="landing-tile-foot">
                                    <button class="landing-text-action" type="button" data-landing-detail data-title="{{ $prestasi->judul }}" data-description="{{ $prestasi->deskripsi }}">Baca Selengkapnya <x-landing-icon name="arrow" /></button>
                                </footer>
                            @endif
                        </div>
                    </article>
                @empty
                    @foreach (['Akademik', 'Seni', 'Olahraga'] as $kategori)
                        <article class="landing-card landing-tile">
                            <x-landing-photo label="Foto Prestasi" icon="" />
                            <div class="landing-tile-body">
                                <div class="landing-item-meta">{{ $kategori }}</div>
                                <h3>Prestasi {{ $kategori }}</h3>
                                <p>Belum ada prestasi yang dipublikasikan.</p>
                            </div>
                        </article>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    <section class="landing-band landing-library-band" id="perpustakaan" aria-labelledby="landing-library-title">
        <div class="landing-wrap">
            <div class="landing-card landing-library">
                <div>
                    <span class="landing-chip">Koleksi Literasi Madrasah</span>
                    <h2 id="landing-library-title">Perpustakaan Digital</h2>
                    <p>
                        Akses modul pembelajaran, buku pegangan siswa, dan literatur Islam secara daring.
                        @auth Buka katalog untuk menemukan bacaan yang tersedia. @else Masuk ke portal untuk membuka katalog bacaan madrasah. @endauth
                    </p>
                    <div class="landing-actions">
                        <a class="landing-button" href="{{ $katalog }}"><x-landing-icon name="book" /> Buka Perpustakaan Digital</a>
                        <small>Mendukung format e-book dan modul pembelajaran.</small>
                    </div>
                </div>
                <x-landing-photo class="landing-library-preview" label="Pratinjau Katalog" caption="E-Book Pembelajaran MI" icon="document" />
            </div>
        </div>
    </section>

    <section class="landing-band" id="kontak" aria-labelledby="landing-contact-title">
        <div class="landing-wrap">
            <header class="landing-section-head">
                <p class="landing-eyebrow">Sekretariat Madrasah</p>
                <h2 id="landing-contact-title">Hubungi Kami</h2>
                <p>Saluran komunikasi resmi untuk pertanyaan wali siswa, pendaftaran, dan informasi umum.</p>
            </header>
            <div class="landing-split landing-contact">
                <div class="landing-contact-sidebar">
                    <dl class="landing-card landing-contact-info">
                        <div><dt>Alamat Madrasah</dt><dd>{{ $alamat ?: 'Alamat madrasah belum dipublikasikan.' }}</dd></div>
                        <div><dt>Telepon / WhatsApp</dt><dd>{{ $sekolah?->telepon ?: 'Nomor kontak belum dipublikasikan.' }}</dd></div>
                        <div><dt>Pos-el Resmi</dt><dd>{{ $sekolah?->email ?: 'Alamat email belum dipublikasikan.' }}</dd></div>
                        <div><dt>Waktu Pelayanan Administrasi</dt><dd>Hubungi Tata Usaha untuk informasi jam pelayanan.</dd></div>
                    </dl>
                    <div class="landing-card landing-map">
                        @if ($alamat)
                            <a class="landing-map-inner" href="https://www.google.com/maps/search/?api=1&amp;query={{ rawurlencode($alamat) }}"><x-landing-icon name="pin" /><span>Lihat Lokasi Madrasah di Peta</span></a>
                        @else
                            <div class="landing-map-inner">
                                <x-landing-icon name="pin" /><span>Peta Lokasi Madrasah</span><small>Lokasi belum dipublikasikan.</small>
                            </div>
                        @endif
                    </div>
                </div>
                <form class="landing-card landing-contact-form" method="POST" action="{{ route('publik.kontak.store') }}" novalidate>
                    @csrf
                    <h3>Kirim Pesan ke Tata Usaha</h3>
                    @if (session('pesan_kontak_status'))
                        <p class="landing-contact-status" role="status">{{ session('pesan_kontak_status') }}</p>
                    @endif
                    @foreach ($kolomKontak as $nama => [$label, $autocomplete, $batas])
                        <div class="landing-field">
                            <label for="landing-contact-{{ $nama }}">{{ $label }}</label>
                            @if ($nama === 'pesan')
                                <textarea id="landing-contact-{{ $nama }}" name="{{ $nama }}" maxlength="{{ $batas }}" rows="4" required @error($nama, 'kontak') aria-invalid="true" aria-describedby="landing-error-{{ $nama }}" @enderror>{{ old($nama) }}</textarea>
                            @else
                                <input id="landing-contact-{{ $nama }}" name="{{ $nama }}" maxlength="{{ $batas }}" value="{{ old($nama) }}" required @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif @error($nama, 'kontak') aria-invalid="true" aria-describedby="landing-error-{{ $nama }}" @enderror>
                            @endif
                            @error($nama, 'kontak')<p class="landing-field-error" id="landing-error-{{ $nama }}">{{ $message }}</p>@enderror
                        </div>
                    @endforeach
                    <button class="landing-button" type="submit">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </section>
</main>

<footer class="landing-footer">
    <div class="landing-wrap">
        <div class="landing-footer-grid">
            <div>
                <h2>{{ $namaSekolah }}</h2>
                <p>{{ $sekolah?->deskripsi_singkat ?: 'Madrasah ibtidaiyah yang memadukan pendidikan dasar dan pembiasaan nilai keislaman.' }}</p>
                @if ($sekolah?->status_akreditasi)<p>Akreditasi Kemenag {{ $sekolah->status_akreditasi }}</p>@endif
            </div>
            <div>
                <h3>Tautan Halaman</h3>
                <ul>
                    <li><a href="#beranda">Beranda Utama</a></li>
                    <li><a href="#profil">Profil &amp; Visi Misi</a></li>
                    <li><a href="#program">Program Unggulan</a></li>
                    <li><a href="#fasilitas">Profil Guru</a></li>
                    <li><a href="#berita">Prestasi Siswa</a></li>
                </ul>
            </div>
            <div>
                <h3>Layanan Internal</h3>
                <ul>
                    <li><a href="{{ $katalog }}">Perpustakaan Digital</a></li>
                    <li><a href="{{ $portal }}">Portal Siswa &amp; Wali</a></li>
                    <li><a href="{{ $portal }}">Portal Pendidik</a></li>
                    <li><a href="{{ $portal }}">Portal Tata Usaha</a></li>
                </ul>
            </div>
            <div>
                <h3>Sekretariat</h3>
                <ul>
                    <li><a href="#kontak">Informasi Kontak Madrasah</a></li>
                    @if ($sekolah?->telepon)<li>{{ $sekolah->telepon }}</li>@endif
                    @if ($sekolah?->email)<li>{{ $sekolah->email }}</li>@endif
                </ul>
                <p>Kemenag EMIS 4.0 &amp; SIMPATIKA</p>
            </div>
        </div>
        <div class="landing-footer-end">
            <span>© {{ now()->year }} {{ $namaSekolah }}. Sistem Informasi Madrasah.</span>
        </div>
    </div>
</footer>

<dialog class="landing-dialog" id="landing-detail" aria-labelledby="landing-detail-title">
    <header>
        <h2 id="landing-detail-title"></h2>
        <form method="dialog">
            <button class="landing-menu" type="submit" aria-label="Tutup detail"><x-landing-icon name="close" /></button>
        </form>
    </header>
    <p data-landing-detail-description></p>
</dialog>
</body>
</html>
