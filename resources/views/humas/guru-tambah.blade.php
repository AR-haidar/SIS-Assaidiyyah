@extends('humas.layout')
@section('title', 'Tambah Sorotan Profil Guru')
@section('content')
<section class="humas-teacher-create-page">
    <a class="humas-teacher-back" href="{{ url('/humas/guru') }}">← Kembali ke Profil Guru</a>
    <h1>Tambah Sorotan Profil Guru</h1>
    <p class="humas-teacher-create-intro">Tambahkan dan atur tampilan kartu profil tenaga pendidik untuk ditampilkan pada bagian Dewan Guru di Landing Page.</p>
    <form class="humas-teacher-create-card" data-teacher-create-form>
        <div class="humas-teacher-create-notice">ⓘ <span>Humas hanya mengatur penyajian kartu profil di Landing Page. Data induk kepegawaian &amp; NUPTK tetap dikelola oleh Operator Madrasah.</span></div>
        <label>Nama Lengkap &amp; Gelar <b>*</b><input name="name" required maxlength="100" placeholder="⌕  Nama guru beserta gelar"></label>
        <label>Jabatan / Bidang Mengajar <b>*</b><input name="role" required maxlength="100" placeholder="Contoh: Guru Al-Qur'an Hadits &amp; Fiqih atau Wali Kelas IV-A"></label>
        <label><span>Deskripsi Singkat / Kutipan Motivasi <b>*</b><small><span data-teacher-description-count>0</span> / 160 Karakter</small></span><textarea name="description" required maxlength="160" rows="3" placeholder="Tuliskan kutipan inspiratif atau fokus pengajaran pendidik (maksimal 160 karakter)..."></textarea></label>
        <label>Foto Profil untuk Landing Page <b>*</b><span class="humas-teacher-upload" data-teacher-upload><img data-teacher-create-preview hidden alt="Pratinjau foto profil"><span data-teacher-upload-copy>☁<strong>Seret &amp; lepas foto ke area ini, atau klik tombol di bawah</strong><small>Format JPG, PNG, WebP • Maksimal 2MB • Rasio ideal 1:1 (Persegi)</small></span><button type="button" data-teacher-pick>Choose File</button><span data-teacher-file-name>No file chosen</span><input type="file" name="photo" accept="image/jpeg,image/png,image/webp" data-teacher-create-photo aria-label="Pilih foto profil"></span></label>
        <fieldset class="humas-teacher-create-visibility"><legend>Status Visibilitas</legend><label><input type="radio" name="status" value="Published" checked><span>Tampilkan di Landing Page (Published)</span></label><label><input type="radio" name="status" value="Draft"><span>Simpan sebagai Draf (Disembunyikan)</span></label></fieldset>
        <footer><a href="{{ url('/humas/guru') }}">Batal</a><button type="submit" name="saveMode" value="Draft">▯ Simpan Draft</button><button type="submit" class="primary" name="saveMode" value="Published">⇧ Publikasikan Sorotan</button></footer>
    </form>
</section>
@endsection
