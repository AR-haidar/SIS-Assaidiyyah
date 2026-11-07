@extends('operator.layout')
@section('title', 'Data Sekolah')
@section('content')
    <section class="operator-school">
        <header class="operator-school-heading">
            <h1>Profil Data Sekolah</h1>
            <p>Pembaruan informasi master lembaga, legalitas perizinan, alamat resmi, kontak, serta visi misi madrasah</p>
        </header>

        <form class="operator-school-form">
            <div class="operator-school-primary">
                <section class="operator-school-card">
                    <h2>Identitas Resmi</h2>
                    <div class="operator-school-fields">
                        <label class="operator-school-field operator-school-wide">Nama Resmi Madrasah <b>*</b>
                            <input type="text" placeholder="Madrasah Ibtidaiyyah Assaidiyyah">
                        </label>
                        <label class="operator-school-field operator-school-wide">Nomor Pokok Sekolah Nasional<br>(NPSN) <b>*</b>
                            <input type="text" placeholder="8 digit NPSN">
                        </label>
                        <label class="operator-school-field operator-school-wide">Status Akreditasi
                            <select><option>Pilih status akreditasi BAN-S/M</option></select>
                        </label>
                        <label class="operator-school-field">Nomor SK Izin Operasional
                            <input type="text" placeholder="">
                        </label>
                        <label class="operator-school-field">Tanggal SK Izin Operasional
                            <input type="date">
                        </label>
                        <label class="operator-school-field operator-school-wide">Lembaga / Yayasan Penyelenggara
                            <input type="text" placeholder="Yayasan Assaidiyyah...">
                        </label>
                    </div>
                </section>

                <section class="operator-school-card">
                    <h2>Visi, Misi &amp; Tujuan</h2>
                    <div class="operator-school-fields">
                        <label class="operator-school-field operator-school-wide">Visi Lembaga
                            <textarea placeholder="Tuliskan visi resmi madrasah..."></textarea>
                        </label>
                        <label class="operator-school-field operator-school-wide">Misi Lembaga
                            <textarea placeholder="Tuliskan butir-butir misi madrasah..."></textarea>
                        </label>
                    </div>
                </section>
            </div>

            <section class="operator-school-card operator-school-contact">
                <h2>Alamat &amp; Kontak Resmi</h2>
                <div class="operator-school-fields">
                    <label class="operator-school-field operator-school-wide">Alamat Lengkap <b>*</b>
                        <textarea placeholder="Jalan, RT/RW, Dusun, Desa/Kelurahan"></textarea>
                    </label>
                    <label class="operator-school-field">Kecamatan <b>*</b>
                        <input type="text" placeholder="Kecamatan">
                    </label>
                    <label class="operator-school-field">Kabupaten / Kota <b>*</b>
                        <input type="text" placeholder="Kota / Kabupaten">
                    </label>
                    <label class="operator-school-field">Provinsi <b>*</b>
                        <input type="text" placeholder="Provinsi">
                    </label>
                    <label class="operator-school-field">Kode Pos
                        <input type="text" placeholder="5 digit kode pos">
                    </label>
                    <label class="operator-school-field operator-school-wide">Nomor Telepon / Narahubung Resmi
                        <span class="operator-school-input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.8 19.8 0 0 1 3.08 5.18 2 2 0 0 1 5.07 3h3a2 2 0 0 1 2 1.72l.45 3.02a2 2 0 0 1-.57 1.7l-1.27 1.27a16 16 0 0 0 5.66 5.66l1.27-1.27a2 2 0 0 1 1.7-.57l3.02.45a2 2 0 0 1 1.67 1.94z"/></svg>
                            <input type="tel" placeholder="Nomor telepon madrasah">
                        </span>
                    </label>
                    <label class="operator-school-field operator-school-wide">Alamat Email Resmi Lembaga
                        <span class="operator-school-input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                            <input type="email" placeholder="madrasah@kemenag.go.id">
                        </span>
                    </label>
                    <label class="operator-school-field operator-school-wide">Situs Web Resmi
                        <span class="operator-school-input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            <input type="url" placeholder="https://...">
                        </span>
                    </label>
                </div>
            </section>

            <div class="operator-school-actions">
                <button class="operator-school-reset" type="reset">Batal / Reset Formulir</button>
                <button class="operator-school-save" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
                    Simpan Perubahan Data Sekolah
                </button>
            </div>
        </form>
    </section>
@endsection