@extends('operator.layout')
@section('title', 'Buat Pengumuman')
@section('content')
<section class="operator-announcement-create-page">
    <header class="operator-announcement-create-heading">
        <h1>Formulir Pembuatan &amp; Publikasi Pengumuman</h1>
        <p>Susun naskah edaran resmi, tetapkan target penerima pengumuman, dan atur waktu penayangan</p>
    </header>
    <form class="operator-announcement-create-form">
        <div class="operator-announcement-create-grid">
            <section class="operator-announcement-content-card">
                <header class="operator-announcement-form-card-heading"><h2>Konten Surat &amp; Sasaran Distribusi</h2><span><b>*</b> Kolom wajib dilengkapi</span></header>
                <div class="operator-announcement-content-fields">
                    <label class="operator-announcement-field operator-announcement-field-wide"><span>Judul Pengumuman <b>*</b><small>Maks. 120 Karakter</small></span><input type="text" maxlength="120" placeholder="Masukkan judul pengumuman / surat edaran resmi..." required></label>
                    <label class="operator-announcement-field"><span>Kategori Pengumuman <b>*</b></span><select required><option value="">Pilih Kategori (Akademik, Kesiswaan,...)</option><option>Akademik</option><option>Kesiswaan</option><option>Keuangan</option><option>Informasi Umum</option></select></label>
                    <label class="operator-announcement-field"><span>Nomor Surat / Edaran Resmi <small>Opsional</small></span><input type="text" placeholder="Contoh: 045/MI-ASD/PP.00/IX/2024"></label>
                    <fieldset class="operator-announcement-recipients"><legend>Target Penerima Pengumuman <b>*</b></legend><div class="operator-announcement-recipient-options">
                        <label><input type="checkbox" name="recipients[]" value="semua"><span><strong>Semua</strong><small>Seluruh civitas akademika</small></span></label>
                        <label><input type="checkbox" name="recipients[]" value="guru"><span><strong>Dewan Guru &amp; Staff</strong><small>Pendidik &amp; tenaga pendidik</small></span></label>
                        <label><input type="checkbox" name="recipients[]" value="murid"><span><strong>Murid &amp; Wali Murid</strong><small>Siswa kelas 1 s.d. 6</small></span></label>
                    </div></fieldset>
                    <label class="operator-announcement-field operator-announcement-editor"><span>Editor Naskah Pengumuman <b>*</b></span><textarea placeholder="Ketikkan isi lengkap pengumuman resmi di sini..." required></textarea></label>
                    <label class="operator-announcement-field operator-announcement-attachment"><span>Unggah Dokumen Lampiran Resmi (PDF)</span><span class="operator-announcement-upload-box">
                        <span class="operator-announcement-upload-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4"/><path d="m7 9 5-5 5 5"/><path d="M20 16.5v2A1.5 1.5 0 0 1 18.5 20h-13A1.5 1.5 0 0 1 4 18.5v-2"/></svg></span>
                        <strong>Unggah salinan surat edaran bertandatangan (PDF, maks. 15MB)</strong><small>Tarik &amp; lepas file di sini, atau klik untuk memilih</small><input type="file" accept="application/pdf,.pdf" aria-label="Unggah dokumen lampiran PDF">
                    </span></label>
                </div>
            </section>
            <aside class="operator-announcement-publish-card">
                <header class="operator-announcement-form-card-heading"><h2>Atribut Publikasi</h2></header>
                <fieldset class="operator-announcement-status"><legend>Status Naskah</legend>
                    <label><input type="radio" name="publication_status" value="draft" checked><span><strong>Simpan sebagai Draf</strong><small>Hanya dapat dilihat oleh operator</small></span></label>
                    <label><input type="radio" name="publication_status" value="published"><span><strong>Langsung Terbitkan</strong><small>Tayang seketika di portal utama</small></span></label>
                    <label><input type="radio" name="publication_status" value="scheduled"><span><strong>Jadwalkan Penayangan</strong><small>Tayang otomatis sesuai rentang waktu</small></span></label>
                </fieldset>
                <div class="operator-announcement-schedule">
                    <label class="operator-announcement-field"><span>Tanggal Mulai Tayang</span><input type="date" aria-label="Tanggal mulai tayang"></label>
                    <label class="operator-announcement-field"><span>Tanggal Berakhir Tayang</span><input type="date" aria-label="Tanggal berakhir tayang"></label>
                </div>
                <label class="operator-announcement-pin"><input type="checkbox" name="pinned"><span><strong>Sematkan Pengumuman</strong><small>Prioritas tinggi pada pinboard atas</small></span></label>
            </aside>
        </div>
        <footer class="operator-announcement-create-actions">
            <a href="{{ url('/operator/pengumuman') }}">Batal</a><button type="button" class="secondary">Simpan Draf</button><button type="button" class="primary">Publikasikan Pengumuman <span aria-hidden="true">&#9655;</span></button>
        </footer>
    </form>
</section>
@endsection
