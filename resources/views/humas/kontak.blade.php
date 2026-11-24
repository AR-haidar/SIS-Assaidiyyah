@extends('humas.layout')
@section('title', 'Kontak & Media Resmi')
@section('content')
    @php
        $kontak = $kontak ?? null;
        $formAction = \Illuminate\Support\Facades\Route::has('humas.kontak.update')
            ? route('humas.kontak.update')
            : url('/humas/kontak');
    @endphp
    <section class="humas-contact-page" data-humas-contact>
        <div class="humas-breadcrumb">CMS Humas &rsaquo; Pengelolaan Landing Page &rsaquo; <strong>Kontak</strong></div>
        <header class="contact-heading">
            <div>
                <h1>Kelola Informasi Kontak &amp; Media Resmi</h1>
                <p>Atur alamat operasional, saluran narahubung madrasah, jam operasional<br class="contact-break"> layanan,
                    dan tautan media sosial untuk Landing Page.</p>
            </div>
            <div class="contact-heading-actions">
                <a href="{{ url('/#kontak') }}" data-contact-preview>&#9678; Pratinjau Footer &amp; Kontak</a>
                <button class="primary" type="submit" form="formKontakHumas" name="status" value="terbit"
                    data-contact-save="Published">&#8679; Publikasikan Pembaruan</button>
            </div>
        </header>

        @if (session('status'))
            <div role="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="formKontakHumas" method="POST" action="{{ $formAction }}" data-contact-form>
            @csrf
            @method('PUT')
            <div class="contact-grid">
                <section class="contact-card contact-address">
                    <header>
                        <span>&#9825;</span>
                        <div><strong>Alamat Operasional &amp; Lokasi</strong><small>Titik fisik kampus untuk navigasi wali
                                murid dan publik</small></div>
                        <b>Bagian Utama</b>
                    </header>
                    <label>
                        Alamat Lengkap Madrasah
                        <textarea name="alamat" rows="2" placeholder="Masukkan alamat lengkap madrasah">{{ old('alamat', $kontak->alamat ?? '') }}</textarea>
                        <small>Sertakan nomor jalan, RT/RW, kode pos secara komprehensif.</small>
                    </label>
                    <div class="contact-two-fields">
                        <label>
                            Kecamatan &amp; Kabupaten
                            <input name="wilayah" value="{{ old('wilayah', $kontak->wilayah ?? '') }}"
                                placeholder="Contoh: Cipanas, Cianjur">
                        </label>
                        <label>
                            Kode Pos Wilayah
                            <input name="kode_pos" value="{{ old('kode_pos', $kontak->kode_pos ?? '') }}"
                                placeholder="Contoh: 43253">
                        </label>
                    </div>
                    <label>
                        Tautan Google Maps Embed / Shared URL
                        <div class="contact-map-input">
                            <span>https://</span>
                            <input name="peta_url" value="{{ old('peta_url', $kontak->peta_url ?? '') }}"
                                placeholder="maps.app.goo.gl/...">
                            <button type="button" data-contact-open-map aria-label="Buka tautan peta">&#8599;</button>
                        </div>
                    </label>
                </section>

                <section class="contact-card contact-channels">
                    <header>
                        <span>&#9635;</span>
                        <div><strong>Saluran Narahubung<br>Utama</strong></div>
                    </header>
                    <label>
                        Nomor Telepon Kantor TU
                        <input name="telepon" value="{{ old('telepon', $kontak->telepon ?? '') }}"
                            placeholder="(0263) 123456">
                    </label>
                    <label>
                        <span>WhatsApp Humas <small>Layanan Cepat Tanggap</small></span>
                        <input name="whatsapp" value="{{ old('whatsapp', $kontak->whatsapp ?? '') }}"
                            placeholder="+62 812-3456-7890">
                    </label>
                    <label>
                        Alamat Email Resmi Madrasah
                        <input name="email" type="email" value="{{ old('email', $kontak->email ?? '') }}"
                            placeholder="info@madrasah.sch.id">
                    </label>
                    <label>
                        Email Khusus SPMB / Pendaftaran
                        <input name="email_spmb" type="email" value="{{ old('email_spmb', $kontak->email_spmb ?? '') }}"
                            placeholder="spmb@madrasah.sch.id">
                    </label>
                </section>

                <section class="contact-card contact-hours">
                    <header>
                        <span>&#9719;</span>
                        <div><strong>Jam Layanan &amp; Operasional Tata<br>Usaha</strong><small>Jadwal loket pelayanan wali
                                santri dan tamu kedinasan</small></div>
                    </header>
                    <label>
                        Ringkasan Hari &amp; Jam Layanan
                        <input name="jam_layanan" value="{{ old('jam_layanan', $kontak->jam_layanan ?? '') }}"
                            placeholder="Senin – Kamis: 07.30 – 14.00 WIB | Jumat: 07.30 – 11.00 WIB">
                        <small>Gunakan tanda pemisah pipa (|) untuk keterbacaan yang rapi pada footer.</small>
                    </label>
                    <label>
                        Keterangan Tambahan / Kebijakan Loket Layanan
                        <textarea name="catatan_layanan" rows="3" placeholder="Masukkan informasi layanan tambahan">{{ old('catatan_layanan', $kontak->catatan_layanan ?? '') }}</textarea>
                    </label>
                    <p>Hari Ahad / Libur Nasional: <b>Tutup</b></p>
                </section>

                <section class="contact-card contact-social">
                    <header>
                        <span>&#9825;</span>
                        <div><strong>Tautan Media Sosial Resmi</strong><small>Akun publikasi &amp; galeri kegiatan
                                madrasah</small></div>
                        <b>4 Kanal</b>
                    </header>
                    <label>
                        <span>Halaman Facebook Madrasah</span>
                        <div class="contact-social-input"><b>FB</b><input name="facebook"
                                value="{{ old('facebook', $kontak->facebook ?? '') }}"
                                placeholder="https://facebook.com/..."></div>
                    </label>
                    <label>
                        <span>Instagram Resmi</span>
                        <div class="contact-social-input"><b>IG</b><input name="instagram"
                                value="{{ old('instagram', $kontak->instagram ?? '') }}" placeholder="@akun_madrasah">
                        </div>
                    </label>
                    <label>
                        <span>Saluran YouTube Madrasah</span>
                        <div class="contact-social-input"><b>YT</b><input name="youtube"
                                value="{{ old('youtube', $kontak->youtube ?? '') }}" placeholder="https://youtube.com/...">
                        </div>
                    </label>
                    <label>
                        <span>Saluran TikTok Madrasah <small>Opsional</small></span>
                        <div class="contact-social-input"><b>TT</b><input name="tiktok"
                                value="{{ old('tiktok', $kontak->tiktok ?? '') }}" placeholder="@akun_madrasah"></div>
                    </label>
                </section>
            </div>
            <footer class="contact-footer">
                <p>&#9432; Perubahan disimpan dalam draf sebelum diterbitkan langsung ke halaman depan publik.</p>
                <div>
                    <button type="reset">Batal</button>
                    <button type="submit" name="status" value="draft" data-contact-save="Draft">Simpan Draf
                        Perubahan</button>
                    <button class="primary" type="submit" name="status" value="terbit"
                        data-contact-save="Published">Publikasikan Informasi Kontak</button>
                </div>
            </footer>
        </form>
    </section>
@endsection
