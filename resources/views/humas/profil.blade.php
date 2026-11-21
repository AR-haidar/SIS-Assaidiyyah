@extends('humas.layout')
@section('title', 'Kelola Profil & Visi Misi')
@section('content')
<section class="humas-profile-page" data-profile-editor>
    <div class="humas-breadcrumb">CMS Humas › Pengelolaan Landing Page › <strong>Profil &amp; Visi Misi</strong></div>
    <header class="humas-profile-heading"><div><h1>Kelola Profil &amp; Visi Misi</h1><p>Atur narasi sejarah singkat madrasah, teks visi, dan butir-butir misi yang disajikan kepada masyarakat.</p></div></header>
    <div class="humas-profile-grid">
        <section class="humas-profile-card humas-vision-card"><header><span class="humas-profile-icon">♧</span><div><strong>Visi Madrasah</strong><small>Arah tujuan fundamental institusi</small></div><span class="humas-profile-live">Live di Beranda</span></header><p class="humas-profile-help">Teks visi akan disorot pada kartu utama bagian profil landing page publik dengan tipografi display.</p><form data-humas-form="profil" data-profile-form><label for="profileVision">Teks Utama Visi</label><textarea id="profileVision" name="field0" maxlength="200" placeholder="Tuliskan visi madrasah"></textarea><div class="humas-profile-count"><span>Format: Teks lugas, inspiratif, 1–2 kalimat</span><span><b data-vision-count>0</b> / 200 karakter</span></div></form><div class="humas-vision-preview"><strong>◎ Tampilan Ringkas Pengunjung:</strong><blockquote data-vision-preview>Pratinjau visi akan tampil di sini.</blockquote><small>— Madrasah Ibtidaiyah Assa'idiyyah</small></div><div class="humas-profile-note">ⓘ Perubahan visi memerlukan sinkronisasi kurikulum madrasah. Pastikan komite madrasah telah menyetujui versi pembaruan.</div></section>
        <section class="humas-profile-card humas-mission-card"><header><span class="humas-profile-icon">☷</span><div><strong>Daftar Misi Sekolah</strong><small>Langkah aksi nyata pencapaian visi</small></div><button type="button" class="humas-profile-add" data-mission-add>＋ Tambah Butir Misi</button></header><p class="humas-profile-help">Gunakan tombol panah untuk menyusun ulang prioritas tampilan butir misi pada portal publik secara langsung.</p><ol data-mission-list></ol></section>
    </div>
    <footer class="humas-profile-actions"><a href="{{ url('/humas/dashboard') }}">Batal</a><button type="button" data-profile-save="Draft">▯ Simpan Draft</button><button type="button" class="primary" data-profile-save="Published">Publikasikan ke Landing Page</button></footer>
</section>
@endsection
