@extends('keuangan.layout')
@section('title', 'Pembayaran Siswa')
@section('content')
<section class="keuangan-payments">
    <header class="keuangan-payments-heading">
        <div><h1>Rekapitulasi Pembayaran Siswa</h1><p>Daftar pencatatan dan status riwayat verifikasi pembayaran iuran SPP siswa</p></div>
    </header>

    <section class="keuangan-payment-stats" aria-label="Ringkasan pembayaran">
        <article class="keuangan-payment-stat"><span class="keuangan-payment-stat-icon blue"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 4h11v17H5V7z"/><path d="M8 4v4H5M9 12h7M9 16h7M9 8h4"/></svg></span><span>Semua Pengajuan</span></article>
        <article class="keuangan-payment-stat"><span class="keuangan-payment-stat-icon amber"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 2h14M5 22h14M7 2v4.5a2 2 0 0 0 .59 1.41L12 12l-4.41 4.09A2 2 0 0 0 7 17.5V22M17 2v4.5a2 2 0 0 1-.59 1.41L12 12l4.41 4.09A2 2 0 0 1 17 17.5V22"/></svg></span><span>Menunggu Verifikasi</span></article>
        <article class="keuangan-payment-stat"><span class="keuangan-payment-stat-icon blue"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 3 2 1.5 2.5-.2 1 2.3 2 1.5-.8 2.4.8 2.5-2 1.5-1 2.3-2.5-.2L12 19l-2-1.5-2.5.2-1-2.3-2-1.5.8-2.5-.8-2.4 2-1.5 1-2.3 2.5.2z"/><path d="m9 11 2 2 4-4"/></svg></span><span>Disetujui / Lunas</span></article>
    </section>

    <form class="keuangan-payment-filters">
        <label class="keuangan-payment-search"><span>Pencarian Santri / Tagihan</span><span class="keuangan-payment-search-box"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input type="search" placeholder="Cari nama siswa..." aria-label="Cari nama siswa"></span></label>
        <label><span>Periode Tagihan</span><select aria-label="Pilih periode tagihan"><option value="">Semua Periode</option><option>SPP Bulanan</option><option>Daftar Ulang</option></select></label>
        <label><span>Rombongan Belajar</span><select aria-label="Pilih rombongan belajar"><option value="">Semua Kelas</option><option>Kelas 1</option><option>Kelas 2</option><option>Kelas 3</option><option>Kelas 4</option><option>Kelas 5</option><option>Kelas 6</option></select></label>
        <label><span>Status Verifikasi</span><select aria-label="Pilih status verifikasi"><option value="">Semua Status</option><option>Menunggu Verifikasi</option><option>Disetujui</option><option>Ditolak</option></select></label>
        <div class="keuangan-payment-filter-actions"><button class="keuangan-payment-reset" type="reset"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>Reset Filter</button><button class="keuangan-payment-submit" type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16l-6 7v6l-4 3v-9z"/></svg>Terapkan Filter</button></div>
    </form>

    <section class="keuangan-payment-table-card" aria-label="Daftar pembayaran siswa">
        <div class="keuangan-payment-table-scroll"><table>
            <thead><tr><th>No</th><th>Nama Siswa</th><th>NIS / NISN</th><th>Kelas</th><th>Periode SPP</th><th>Nominal</th><th>Tanggal Pembayaran</th><th>Status</th></tr></thead>
            <tbody><tr><td colspan="8" class="keuangan-payment-empty"><strong>Belum Ada Data Pembayaran Dimuat</strong><p>Gunakan filter di atas atau cari nama santri untuk memuat catatan pembayaran.<br class="keuangan-payment-desktop-break"> Seluruh transaksi tervalidasi langsung terintegrasi dengan buku kas bendahara.</p><button type="button"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 7v5h-5M4 17v-5h5"/><path d="M5.6 9A7 7 0 0 1 18 6l2 2M4 16l2 2a7 7 0 0 0 12.4-3"/></svg>Muat Ulang Transaksi</button></td></tr></tbody>
        </table></div>
    </section>
</section>
@endsection