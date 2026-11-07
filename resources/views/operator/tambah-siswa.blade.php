@extends('operator.layout')
@section('title', 'Tambah Siswa')
@section('content')
    <section class="operator-student-create">
        <a class="operator-student-back" href="{{ url('/operator/data-siswa') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/><path d="M20 12H9"/></svg>
            Kembali ke Data Siswa
        </a>
        <h1>Formulir Pembaruan Data Siswa</h1>

        <form class="operator-student-create-form">
            <section class="operator-student-step">
                <header><div><h2>Informasi Akademik &amp; Penempatan Kelas</h2><p>Keterkaitan penempatan kelas dan sinkronisasi sistem siswa</p></div><span>Langkah 1/4</span></header>
                <div class="operator-student-fields operator-student-academic">
                    <label>Nomor Induk Siswa Lokal (NIS) <small>(Otomatis/Manual)</small><input type="text" placeholder="Contoh: 2425001"><small>Format registrasi internal MI Assaidiyyah</small></label>
                    <label>Nomor Induk Siswa Nasional (NISN) <b>*</b><input type="text" placeholder="10 digit nomor NISN Kemenag"><small>Wajib valid terdaftar pada database</small></label>
                    <label>Tahun Masuk / Angkatan <b>*</b><select><option>Pilih Tahun Masuk</option></select></label>
                    <label>Kelas <b>*</b><select><option>Pilih Kelas</option></select></label>
                    <fieldset><legend>Status Masuk <b>*</b></legend><label><input type="radio" name="entry-status" value="baru"> Siswa Baru</label><label><input type="radio" name="entry-status" value="mutasi"> Pindahan / Mutasi</label></fieldset>
                </div>
            </section>

            <section class="operator-student-step">
                <header><div><h2>Biodata Diri Siswa</h2><p>Data kependudukan sipil berdasarkan Kartu Keluarga (KK) &amp; Akta Kelahiran</p></div><span>Langkah 2/4</span></header>
                <div class="operator-student-fields operator-student-biodata">
                    <label>Nama Lengkap Sesuai Akta <b>*</b><input type="text" placeholder="Masukkan nama lengkap siswa..."><small>Hindari penulisan gelar atau singkatan yang tidak tertera pada akta</small></label>
                    <label>NIK Siswa (18 digit) <b>*</b><input type="text" placeholder="Nomor Induk Kependudukan sesuai KK"></label>
                    <label>Tempat Lahir <b>*</b><input type="text" placeholder="Kota / Kabupaten"></label>
                    <label>Tanggal Lahir <b>*</b><input type="date" aria-label="Tanggal lahir"></label>
                    <fieldset class="operator-student-gender"><legend>Jenis Kelamin <b>*</b></legend><label><input type="radio" name="student-gender" value="laki-laki"> Laki-laki</label><label><input type="radio" name="student-gender" value="perempuan"> Perempuan</label></fieldset>
                    <label class="wide">Alamat Tempat Tinggal Lengkap <b>*</b><textarea placeholder="Nama jalan, RT/RW, Dusun, Desa/Kelurahan, Kecamatan"></textarea></label>
                </div>
            </section>

            <section class="operator-student-step">
                <header><div><h2>Data Orang Tua / Wali Murid</h2><p>Data penanggung jawab siswa untuk keperluan pelaporan dan kontak resmi</p></div><span>Langkah 3/4</span></header>
                <div class="operator-student-fields operator-student-parents">
                    <label>Nama Lengkap Ayah Kandung <b>*</b><input type="text" placeholder="Nama ayah..."></label>
                    <label>NIK Ayah<input type="text" placeholder="16 digit NIK ayah"></label>
                    <label>Pekerjaan Ayah<select><option>Pilih Pekerjaan</option></select></label>
                    <label>Nama Lengkap Ibu Kandung <b>*</b><input type="text" placeholder="Nama ibu..."></label>
                    <label>NIK Ibu<input type="text" placeholder="16 digit NIK ibu"></label>
                    <label>Nomor WhatsApp / HP Orang Tua <b>*</b><input type="tel" placeholder="Contoh: 081234567890"></label>
                </div>
            </section>

            <section class="operator-student-step">
                <header><div><h2>Pasfoto &amp; Dokumen Pelengkap</h2><p>Arsip digital untuk cetak Kartu Pelajar dan sinkronisasi</p></div><span>Langkah 4/4</span></header>
                <div class="operator-student-uploads">
                    <label><span class="operator-student-upload-icon"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 4h-4L8 7H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3z"/><circle cx="12" cy="13" r="3"/><path d="M19 3v4M17 5h4"/></svg></span><strong>Pilih Berkas atau Tarik ke Sini</strong><small>Unggah pasfoto resmi ukuran 3x4 (Maks. 10MB,<br> JPG/PNG)</small><input type="file" accept="image/*"></label>
                    <label><span class="operator-student-upload-icon muted"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h6"/></svg></span><strong>Unggah Berkas Kependudukan</strong><small>Gabungan file PDF KK &amp; Akta Kelahiran santri (Maks.<br> 15MB)</small><input type="file" accept=".pdf,image/*"></label>
                </div>
            </section>

            <div class="operator-student-create-actions">
                <a href="{{ url('/operator/data-siswa') }}">Batal</a>
                <button type="button" class="secondary">Simpan sebagai Draf</button>
                <button type="button" class="primary"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 11v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h9"/><path d="m16 3 5 5-9 9-4 1 1-4z"/></svg>Simpan &amp; Verifikasi Data Siswa</button>
            </div>
        </form>
    </section>
@endsection