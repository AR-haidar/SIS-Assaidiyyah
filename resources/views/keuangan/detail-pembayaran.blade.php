@extends('keuangan.layout')
@section('title', 'Detail Pembayaran')
@section('content')
<section class="keuangan-payment-detail">
    <header class="keuangan-detail-heading"><h1>Detail Pembayaran</h1><p>Peninjauan bukti transaksi dan penetapan status pembayaran</p></header>
    <div class="keuangan-detail-grid">
        <div class="keuangan-detail-main">
            <section class="keuangan-detail-card">
                <header class="keuangan-detail-card-heading"><div><h2>Informasi Siswa</h2><p>Data registrasi siswa yang mengajukan pembayaran</p></div></header>
                <div class="keuangan-detail-info-grid">
                    <div class="keuangan-detail-data"><span>Nama Lengkap Siswa</span><strong>{{ $siswa?->nama_lengkap ?? '—' }}</strong></div>
                    <div class="keuangan-detail-data"><span>NIS / NISN</span><strong>{{ $siswa?->nis ?? '—' }} / {{ $siswa?->nisn ?? '—' }}</strong></div>
                    <div class="keuangan-detail-data"><span>Kelas</span><strong>{{ $kelas ?? '—' }}</strong></div>
                    <div class="keuangan-detail-data"><span>Tahun Ajaran / Semester</span><strong>{{ $tagihan?->tahunAjaran?->kode ?? '—' }}</strong></div>
                </div>
            </section>

            <section class="keuangan-detail-card keuangan-detail-payment-card">
                <header class="keuangan-detail-card-heading">
                    <span class="keuangan-detail-heading-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 4h11v17H5V7z"/><path d="M8 4v4H5M9 12h7M9 16h7"/></svg></span>
                    <div><h2>Rincian Pembayaran</h2><p>Parameter iuran serta nominal rincian tagihan</p></div>
                    <span class="keuangan-detail-method-badge">Metode: {{ $pembayaran->metodePembayaran?->label ?? '—' }}</span>
                </header>
                <div class="keuangan-detail-payment-fields">
                    <div class="keuangan-detail-data"><span>Jenis Tagihan</span><strong class="keuangan-detail-value">{{ $tagihan?->jenisTagihan?->nama ?? '—' }}</strong></div>
                    <div class="keuangan-detail-data"><span>Periode Tagihan</span><strong>{{ $tagihan?->periode_bulan?->translatedFormat('F Y') ?? '—' }}</strong></div>
                    <div class="keuangan-detail-amount"><span>Total Nominal Pembayaran</span><div><strong>Rp {{ number_format((float) $pembayaran->nominal, 0, ',', '.') }}</strong><small>Tagihan Pokok</small></div></div>
                    <div class="keuangan-detail-data"><span>Metode Pembayaran</span><strong>{{ $pembayaran->metodePembayaran?->label ?? '—' }}</strong></div>
                    <div class="keuangan-detail-data"><span>Nomor Referensi Transaksi</span><strong>&mdash;</strong></div>
                    <div class="keuangan-detail-data keuangan-detail-wide"><span>Tanggal &amp; Waktu Pengajuan</span><strong>{{ $pembayaran->tanggal_pengajuan?->translatedFormat('d F Y, H:i') ?? '—' }}</strong></div>
                    <div class="keuangan-detail-data keuangan-detail-wide keuangan-detail-note"><span>Catatan</span><strong>{{ $pembayaran->alasan_penolakan ?? '—' }}</strong></div>
                </div>
            </section>
        </div>

        <aside class="keuangan-detail-side">
            <section class="keuangan-detail-card keuangan-proof-card">
                <header class="keuangan-detail-card-heading"><div><h2>Bukti Transfer</h2><p>Pratinjau berkas digital dari pengirim</p></div><span class="keuangan-proof-format">JPG / PNG / PDF</span></header>
                @if ($buktiUrl && str_starts_with($pembayaran->bukti->mime, 'image/'))
                <img src="{{ $buktiUrl }}" alt="Bukti pembayaran">
                @elseif ($buktiUrl)
                <a href="{{ $buktiUrl }}" target="_blank" rel="noopener">Buka bukti: {{ $pembayaran->bukti->nama_asli }}</a>
                @else
                <div class="keuangan-proof-placeholder"><strong>Tidak Ada Bukti</strong><span>Pembayaran ini diinput tanpa lampiran.</span></div>
                @endif
                @if ($buktiUrl)
                <a class="keuangan-proof-download" href="{{ $buktiUrl }}" download="{{ $pembayaran->bukti->nama_asli }}">{{-- svg yang lama dipertahankan --}}Unduh Berkas</a>
                @endif
            </section>

            <section class="keuangan-detail-card keuangan-verification-actions">
                <header class="keuangan-detail-card-heading"><div><h2>Tindakan Verifikasi</h2><p>Otorisasi transaksi kas keuangan</p></div></header>
                @if ($errors->any())<p>{{ $errors->first() }}</p>@endif
                @if ($pembayaran->status_verifikasi === \App\Enums\StatusVerifikasi::Menunggu)
                <form method="POST" action="{{ route('keuangan.pembayaran.setujui', $pembayaran) }}">
                @csrf
                <button type="submit" class="keuangan-approve">{{-- svg lama --}}Setujui Pembayaran</button>
                </form>
                <form method="POST" action="{{ route('keuangan.pembayaran.tolak', $pembayaran) }}">
                @csrf
                <textarea name="alasan_penolakan" rows="3" required placeholder="Alasan penolakan (wajib)">{{ old('alasan_penolakan') }}</textarea>
                <div class="keuangan-decision-row">
                <button type="submit" class="keuangan-reject">{{-- svg lama --}}Tolak Bayar</button>
                <button type="button" class="keuangan-request-revision">{{-- svg lama --}}Minta Revisi</button>
                </div>
                </form>
                @else
                <p>Pembayaran ini sudah {{ strtolower($pembayaran->status_verifikasi->label()) }}.</p>
                @endif
            </section>
        </aside>
    </div>
</section>
@endsection
