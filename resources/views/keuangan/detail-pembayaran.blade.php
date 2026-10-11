@extends('keuangan.layout')
@section('title', 'Detail Pembayaran')
@section('content')
<section class="keuangan-payment-detail">
    <header class="keuangan-detail-heading"><h1>Detail Pembayaran</h1><p>Peninjauan bukti transaksi dan penetapan status verifikasi SPP siswa</p></header>
    <div class="keuangan-detail-grid">
        <div class="keuangan-detail-main">
            <section class="keuangan-detail-card">
                <header class="keuangan-detail-card-heading"><div><h2>Informasi Siswa</h2><p>Data registrasi siswa yang mengajukan pembayaran</p></div></header>
                <div class="keuangan-detail-info-grid">
                    <div class="keuangan-detail-data"><span>Nama Lengkap Siswa</span><strong>&mdash;</strong></div>
                    <div class="keuangan-detail-data"><span>NIS / NISN</span><strong>&mdash;</strong></div>
                    <div class="keuangan-detail-data"><span>Kelas</span><strong>&mdash;</strong></div>
                    <div class="keuangan-detail-data"><span>Tahun Ajaran / Semester</span><strong>&mdash;</strong></div>
                </div>
            </section>

            <section class="keuangan-detail-card keuangan-detail-payment-card">
                <header class="keuangan-detail-card-heading">
                    <span class="keuangan-detail-heading-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 4h11v17H5V7z"/><path d="M8 4v4H5M9 12h7M9 16h7"/></svg></span>
                    <div><h2>Rincian Pembayaran</h2><p>Parameter iuran serta nominal rincian tagihan</p></div>
                    <span class="keuangan-detail-method-badge">Metode: Transfer / Tunai</span>
                </header>
                <div class="keuangan-detail-payment-fields">
                    <div class="keuangan-detail-data"><span>Jenis Iuran</span><strong class="keuangan-detail-value">SPP Bulanan <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7h18l-2 10H5L3 7z"/><path d="M7 7a5 5 0 0 1 10 0M9 12h.01M15 12h.01"/></svg></strong></div>
                    <div class="keuangan-detail-data"><span>Periode SPP</span><strong>&mdash;</strong></div>
                    <div class="keuangan-detail-amount"><span>Total Nominal Pembayaran</span><div><strong>Rp &mdash;</strong><small>Tagihan Pokok</small></div></div>
                    <div class="keuangan-detail-data"><span>Metode Pembayaran</span><strong>&mdash; (Transfer Bank / Tunai)</strong></div>
                    <div class="keuangan-detail-data"><span>Nomor Referensi Transaksi</span><strong>&mdash;</strong></div>
                    <div class="keuangan-detail-data keuangan-detail-wide"><span>Tanggal &amp; Waktu Pengajuan</span><strong>&mdash;</strong></div>
                    <div class="keuangan-detail-data keuangan-detail-wide keuangan-detail-note"><span>Catatan</span><strong>&mdash;</strong></div>
                </div>
            </section>
        </div>

        <aside class="keuangan-detail-side">
            <section class="keuangan-detail-card keuangan-proof-card">
                <header class="keuangan-detail-card-heading"><div><h2>Bukti Transfer</h2><p>Pratinjau berkas digital dari pengirim</p></div><span class="keuangan-proof-format">JPG / PNG / PDF</span></header>
                <div class="keuangan-proof-placeholder"><strong>Lampiran Bukti Transaksi</strong><span>Format file terverifikasi: JPG, PNG, atau PDF</span></div>
                <button type="button" class="keuangan-proof-download"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5M12 15V3"/></svg>Unduh Berkas</button>
            </section>

            <section class="keuangan-detail-card keuangan-verification-actions">
                <header class="keuangan-detail-card-heading"><div><h2>Tindakan Verifikasi</h2><p>Otorisasi transaksi kas keuangan</p></div></header>
                <button type="button" class="keuangan-approve"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8 12 3 3 5-6"/></svg>Setujui Pembayaran</button>
                <div class="keuangan-decision-row"><button type="button" class="keuangan-reject"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m9 9 6 6m0-6-6 6"/></svg>Tolak Bayar</button><button type="button" class="keuangan-request-revision"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 7v5h-5M4 17v-5h5"/><path d="M5.6 9A7 7 0 0 1 18 6l2 2M4 16l2 2a7 7 0 0 0 12.4-3"/></svg>Minta Revisi</button></div>
            </section>
        </aside>
    </div>
</section>
@endsection