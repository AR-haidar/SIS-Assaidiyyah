@extends('humas.layout')
@section('title', 'Kontak & Media Resmi')
@section('content')
<section class="humas-contact-page" data-humas-contact>
    <div class="humas-breadcrumb">CMS Humas › Pengelolaan Landing Page › <strong>Kontak</strong></div>
    <header class="contact-heading"><div><h1>Kelola Informasi Kontak &amp; Media Resmi</h1><p>Atur alamat operasional, saluran narahubung madrasah, jam operasional<br class="contact-break"> layanan, dan tautan media sosial untuk Landing Page.</p></div><div class="contact-heading-actions"><button type="button" data-contact-preview>◎ Pratinjau Footer &amp; Kontak</button><button class="primary" type="button" data-contact-save="Published">⇧ Publikasikan Pembaruan</button></div></header>
    <form data-contact-form>
        <div class="contact-grid">
            <section class="contact-card contact-address"><header><span>♧</span><div><strong>Alamat Operasional &amp; Lokasi</strong><small>Titik fisik kampus untuk navigasi wali murid dan publik</small></div><b>Bagian Utama</b></header>
                <label>Alamat Lengkap Madrasah<textarea name="address" rows="2" placeholder="Masukkan alamat lengkap madrasah"></textarea><small>Sertakan nomor jalan, RT/RW, kode pos secara komprehensif.</small></label>
                <div class="contact-two-fields"><label>Kecamatan &amp; Kabupaten<input name="district" placeholder="Contoh: Cipanas, Cianjur"></label><label>Kode Pos Wilayah<input name="postal" placeholder="Contoh: 43253"></label></div>
                <label>Tautan Google Maps Embed / Shared URL<div class="contact-map-input"><span>https://</span><input name="map" placeholder="maps.app.goo.gl/..."><button type="button" data-contact-open-map aria-label="Buka tautan peta">↗</button></div></label>
            </section>
            <section class="contact-card contact-channels"><header><span>▣</span><div><strong>Saluran Narahubung<br>Utama</strong></div></header>
                <label>Nomor Telepon Kantor TU<input name="phone" placeholder="(0263) 123456"></label>
                <label><span>WhatsApp Humas <small>Layanan Cepat Tanggap</small></span><input name="whatsapp" placeholder="+62 812-3456-7890"></label>
                <label>Alamat Email Resmi Madrasah<input name="email" type="email" placeholder="info@madrasah.sch.id"></label>
                <label>Email Khusus SPMB / Pendaftaran<input name="admission_email" type="email" placeholder="spmb@madrasah.sch.id"></label>
            </section>
            <section class="contact-card contact-hours"><header><span>◷</span><div><strong>Jam Layanan &amp; Operasional Tata<br>Usaha</strong><small>Jadwal loket pelayanan wali santri dan tamu kedinasan</small></div></header>
                <label>Ringkasan Hari &amp; Jam Layanan<input name="hours" placeholder="Senin – Kamis: 07.30 – 14.00 WIB | Jumat: 07.30 – 11.00 WIB"><small>Gunakan tanda pemisah pipa (|) untuk keterbacaan yang rapi pada footer.</small></label>
                <label>Keterangan Tambahan / Kebijakan Loket Layanan<textarea name="service_note" rows="3" placeholder="Masukkan informasi layanan tambahan"></textarea></label>
                <p>Hari Ahad / Libur Nasional: <b>Tutup</b></p>
            </section>
            <section class="contact-card contact-social"><header><span>♧</span><div><strong>Tautan Media Sosial Resmi</strong><small>Akun publikasi &amp; galeri kegiatan madrasah</small></div><b>4 Kanal</b></header>
                <label><span>Halaman Facebook Madrasah</span><div class="contact-social-input"><b>FB</b><input name="facebook" placeholder="https://facebook.com/..."></div></label>
                <label><span>Instagram Resmi</span><div class="contact-social-input"><b>IG</b><input name="instagram" placeholder="@akun_madrasah"></div></label>
                <label><span>Saluran YouTube Madrasah</span><div class="contact-social-input"><b>YT</b><input name="youtube" placeholder="https://youtube.com/..."></div></label>
                <label><span>Saluran TikTok Madrasah <small>Opsional</small></span><div class="contact-social-input"><b>TT</b><input name="tiktok" placeholder="@akun_madrasah"></div></label>
            </section>
        </div>
        <footer class="contact-footer"><p>ⓘ Perubahan disimpan dalam draf sebelum diterbitkan langsung ke halaman depan publik.</p><div><button type="reset">Batal</button><button type="button" data-contact-save="Draft">Simpan Draf Perubahan</button><button class="primary" type="button" data-contact-save="Published">Publikasikan Informasi Kontak</button></div></footer>
    </form>
</section>
@endsection
