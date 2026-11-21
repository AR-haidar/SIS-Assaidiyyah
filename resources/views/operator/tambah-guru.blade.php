@extends('operator.layout')
@section('title', 'Tambah Guru')
@section('content')
    <section class="operator-teacher-create">
        <h1>Formulir Data Pendidik &amp; Tenaga Kependidikan</h1>

        <form class="operator-teacher-create-grid">
            <div class="operator-teacher-create-main">
                <section class="operator-teacher-form-card">
                    <header>
                        <div><h2>Identitas Pokok Kepegawaian</h2><p>Nomor identitas kedinasan dan kependudukan resmi</p></div>
                        <span>Wajib Dilengkapi</span>
                    </header>
                    <div class="operator-teacher-fields">
                        <label class="operator-teacher-field wide">Nama Lengkap beserta Gelar <b>*</b>
                            <input type="text" placeholder="Contoh: Ustadz Ahmad Fauzi, S.Pd.I">
                            <small>Tuliskan nama lengkap sesuai SK atau ijazah dengan gelar akademik.</small>
                        </label>
                        <label class="operator-teacher-field">NIP (Nomor Induk Pegawai)
                            <input type="text" placeholder="18 digit NIP jika ASN, kosongkan jika non">
                        </label>
                        <label class="operator-teacher-field">NUPTK
                            <input type="text" placeholder="16 digit NUPTK Kemenag / Kemdikbud">
                        </label>
                        <label class="operator-teacher-field">NIK (Nomor Induk Kependudukan) <b>*</b>
                            <input type="text" placeholder="16 digit NIK">
                        </label>
                        <label class="operator-teacher-field">Status Kepegawaian <b>*</b>
                            <select><option>Pilih Status Kepegawaian</option><option>PNS</option><option>PPPK</option><option>Non-ASN</option></select>
                        </label>
                    </div>
                </section>

                <section class="operator-teacher-form-card">
                    <header><div><h2>Biodata Diri &amp; Kontak</h2><p>Informasi personal, riwayat pendidikan, dan alamat domisili</p></div></header>
                    <div class="operator-teacher-fields">
                        <label class="operator-teacher-field">Tempat Lahir
                            <input type="text" placeholder="Kota / Kabupaten Kelahiran">
                        </label>
                        <label class="operator-teacher-field">Tanggal Lahir
                            <input type="date">
                        </label>
                        <fieldset class="operator-teacher-field operator-teacher-gender">
                            <legend>Jenis Kelamin <b>*</b></legend>
                            <label><input type="radio" name="gender" value="laki-laki"> Laki-laki</label>
                            <label><input type="radio" name="gender" value="perempuan"> Perempuan</label>
                        </fieldset>
                        <label class="operator-teacher-field">Pendidikan Terakhir <b>*</b>
                            <select><option>Pilih Jenjang Terakhir</option><option>SMA / MA</option><option>S1</option><option>S2</option></select>
                        </label>
                        <label class="operator-teacher-field">Program Studi / Jurusan
                            <input type="text" placeholder="Contoh: Pendidikan Agama Islam, PGMI">
                        </label>
                        <label class="operator-teacher-field">Nomor HP / WhatsApp Aktif <b>*</b>
                            <input type="tel" placeholder="+62   Contoh: 081234567890">
                        </label>
                        <label class="operator-teacher-field">Email Resmi / Pribadi
                            <input type="email" placeholder="email@madrasah.sch.id">
                        </label>
                        <label class="operator-teacher-field wide">Alamat Domisili
                            <textarea placeholder="Alamat domisili lengkap..."></textarea>
                        </label>
                    </div>
                </section>

                <section class="operator-teacher-form-card">
                    <header><div><h2>Penugasan &amp; Tugas Mengajar Madrasah</h2><p>Peran struktural pengajaran di lingkungan MI Assaidiyyah</p></div></header>
                    <div class="operator-teacher-fields">
                        <label class="operator-teacher-field">Jenis Guru / Tugas Pokok <b>*</b>
                            <select><option>Pilih Penugasan Pokok</option><option>Guru Kelas</option><option>Guru Mata Pelajaran</option><option>Tenaga Kependidikan</option></select>
                        </label>
                        <label class="operator-teacher-field">Mata Pelajaran Diampu
                            <input type="text" placeholder="Contoh: Akidah Akhlak, Fiqih, Matematika">
                        </label>
                        <label class="operator-teacher-field">SK Pengangkatan / Penugasan
                            <input type="text" placeholder="Nomor SK Kepala Madrasah / Yayasan">
                        </label>
                        <fieldset class="operator-teacher-field operator-teacher-certification">
                            <legend>Status Sertifikasi Pendidik</legend>
                            <label><input type="radio" name="certification" value="sudah"> Sudah Bersertifikasi</label>
                            <label><input type="radio" name="certification" value="belum"> Belum Bersertifikasi</label>
                        </fieldset>
                    </div>
                </section>
            </div>

            <aside class="operator-teacher-photo-card">
                <header><h2>Pasfoto</h2><p>Dokumentasi visual biodata</p></header>
                <label class="operator-teacher-upload">
                    <span class="operator-teacher-camera"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 4h-4l-2 3H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3z"/><circle cx="12" cy="13" r="3"/><path d="M19 3v4M17 5h4"/></svg></span>
                    <strong>Unggah Pasfoto Resmi</strong>
                    <small>Format JPG/PNG. maks. 15MB</small>
                    <span class="operator-teacher-pick">♧ Pilih Berkas</span>
                    <input type="file" accept="image/png,image/jpeg">
                </label>
            </aside>

            <div class="operator-teacher-actions">
                <a href="{{ url('/operator/data-guru') }}">Batal</a>
                <button type="button">Simpan Draf</button>
                <button type="button" class="primary">Simpan Data <span aria-hidden="true">&rsaquo;</span></button>
            </div>
        </form>
    </section>
@endsection