{{-- ========================================================
             PART 2 — SECTION 2: PROFIL GURU PUBLIK
             ======================================================== --}}
@php
    $daftarGuruPublik = ($profilGuruPublik ?? collect())->filter(fn($g) => $g->aktif ?? ($g->tampil ?? true));
    $daftarPrestasiPublik = ($prestasiSekolah ?? collect())->filter(fn($p) => $p->aktif ?? true);
@endphp
<section class="section-block" id="guru"
    style="width:100vw; margin-left:calc(50% - 50vw); padding:56px max(32px, calc((100vw - 1800px) / 2 + 32px)); box-sizing:border-box;">
    <div class="section-title-wrap">
        <span class="section-pre-tag">TENAGA PENDIDIK</span>
        <h2 class="section-heading">Profil Guru</h2>
        <p class="section-caption">Kenali tenaga pendidik dan staf yang mendampingi kegiatan belajar siswa.</p>
    </div>
    <div class="facilities-four-grid teacher-profile-grid">
        @forelse ($daftarGuruPublik as $guru)
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 21a8 8 0 0 1 16 0" />
                        </svg>
                    </div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">
                        {{ $guru->nama_tampil ?? ($guru->pegawai?->nama_lengkap ?? 'Pendidik') }}</h3>
                    <p class="facility-item-desc">
                        @if ($guru->jabatan_tampil)
                            <strong>{{ $guru->jabatan_tampil }}</strong> &mdash;
                        @endif
                        {{ $guru->bio_singkat ?? 'Tenaga pendidik MI Assaidiyyah.' }}
                    </p>
                </div>
            </article>
        @empty
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34" height="34"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 21a8 8 0 0 1 16 0" />
                        </svg></div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">Guru Kelas</h3>
                    <p class="facility-item-desc">Profil guru kelas dan informasi pendampingan belajar siswa.</p>
                </div>
            </article>
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34" height="34"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 21a8 8 0 0 1 16 0" />
                        </svg></div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">Guru Pendidikan Agama</h3>
                    <p class="facility-item-desc">Profil pendidik yang membina pembelajaran dan karakter keislaman.</p>
                </div>
            </article>
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34" height="34"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 21a8 8 0 0 1 16 0" />
                        </svg></div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">Guru Olahraga dan Seni</h3>
                    <p class="facility-item-desc">Profil pendidik yang mendukung kesehatan, kreativitas, dan minat
                        siswa.</p>
                </div>
            </article>
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34" height="34"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 21a8 8 0 0 1 16 0" />
                        </svg></div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">Tenaga Kependidikan</h3>
                    <p class="facility-item-desc">Profil staf yang mendukung layanan dan kegiatan madrasah.</p>
                </div>
            </article>
        @endforelse
    </div>
</section>

{{-- ========================================================
             PART 3 — SECTION 1: KATALOG PRESTASI SISWA
             ======================================================== --}}
<section class="section-block" id="prestasi"
    style="width:100vw; margin-left:calc(50% - 50vw); padding:56px max(32px, calc((100vw - 1800px) / 2 + 32px)); background:#fff; box-sizing:border-box;">
    <div class="section-title-wrap">
        <span class="section-pre-tag">KATALOG SISWA</span>
        <h2 class="section-heading">Katalog Prestasi Siswa</h2>
        <p class="section-caption">Ruang informasi untuk menampilkan capaian dan karya siswa MI Assaidiyyah.</p>
    </div>
    <div class="facilities-four-grid achievement-grid">
        @forelse ($daftarPrestasiPublik as $prestasi)
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <path d="M8 21h8m-4-4v4M7 4h10v5a5 5 0 0 1-10 0V4Z" />
                            <path d="M7 6H4v2a4 4 0 0 0 4 4m9-6h3v2a4 4 0 0 1-4 4" />
                        </svg>
                    </div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">{{ $prestasi->judul }}</h3>
                    <p class="facility-item-desc">
                        @if ($prestasi->tingkat || $prestasi->tahun)
                            <strong>{{ implode(' • ', array_filter([$prestasi->kategori, $prestasi->tingkat, $prestasi->tahun])) }}</strong>
                            &mdash;
                        @endif
                        {{ $prestasi->deskripsi ?? 'Capaian prestasi siswa MI Assaidiyyah.' }}
                    </p>
                </div>
            </article>
        @empty
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34"
                            height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M8 21h8m-4-4v4M7 4h10v5a5 5 0 0 1-10 0V4Z" />
                            <path d="M7 6H4v2a4 4 0 0 0 4 4m9-6h3v2a4 4 0 0 1-4 4" />
                        </svg></div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">Prestasi Akademik</h3>
                    <p class="facility-item-desc">Informasi lomba, olimpiade, dan capaian akademik siswa akan
                        ditampilkan di sini.</p>
                </div>
            </article>
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34"
                            height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M8 21h8m-4-4v4M7 4h10v5a5 5 0 0 1-10 0V4Z" />
                            <path d="M7 6H4v2a4 4 0 0 0 4 4m9-6h3v2a4 4 0 0 1-4 4" />
                        </svg></div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">Prestasi Keagamaan</h3>
                    <p class="facility-item-desc">Informasi capaian siswa dalam kegiatan dan kompetisi keagamaan akan
                        ditampilkan di sini.</p>
                </div>
            </article>
            <article class="facility-box-card">
                <div class="facility-img-box">
                    <div class="f-img-icon-wrap"><svg xmlns="http://www.w3.org/2000/svg" width="34"
                            height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M8 21h8m-4-4v4M7 4h10v5a5 5 0 0 1-10 0V4Z" />
                            <path d="M7 6H4v2a4 4 0 0 0 4 4m9-6h3v2a4 4 0 0 1-4 4" />
                        </svg></div>
                </div>
                <div class="facility-box-body">
                    <h3 class="facility-item-title">Prestasi Olahraga dan Seni</h3>
                    <p class="facility-item-desc">Informasi karya, pertandingan, dan kegiatan kreatif siswa akan
                        ditampilkan di sini.</p>
                </div>
            </article>
        @endforelse
    </div>
</section>
