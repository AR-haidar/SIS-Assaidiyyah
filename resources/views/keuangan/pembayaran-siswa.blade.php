@extends('keuangan.layout')
@section('title', 'Pembayaran Siswa')
@section('content')
<section class="keuangan-payments">
    <header class="keuangan-payments-heading">
        <div><h1>Rekapitulasi Pembayaran Siswa</h1><p>Daftar seluruh tagihan dan status pembayaran siswa</p></div>
    </header>

    <section class="keuangan-payment-stats" aria-label="Ringkasan pembayaran">
        <article class="keuangan-payment-stat"><span class="keuangan-payment-stat-icon blue"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 4h11v17H5V7z"/><path d="M8 4v4H5M9 12h7M9 16h7M9 8h4"/></svg></span><span>Semua Pengajuan</span><strong>{{ number_format($stat['semua'], 0, ',', '.') }}</strong></article>
        <article class="keuangan-payment-stat"><span class="keuangan-payment-stat-icon amber"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 2h14M5 22h14M7 2v4.5a2 2 0 0 0 .59 1.41L12 12l-4.41 4.09A2 2 0 0 0 7 17.5V22M17 2v4.5a2 2 0 0 1-.59 1.41L12 12l4.41 4.09A2 2 0 0 1 17 17.5V22"/></svg></span><span>Menunggu Verifikasi</span><strong>{{ number_format($stat['menunggu'], 0, ',', '.') }}</strong></article>
        <article class="keuangan-payment-stat"><span class="keuangan-payment-stat-icon blue"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 3 2 1.5 2.5-.2 1 2.3 2 1.5-.8 2.4.8 2.5-2 1.5-1 2.3-2.5-.2L12 19l-2-1.5-2.5.2-1-2.3-2-1.5.8-2.5-.8-2.4 2-1.5 1-2.3 2.5.2z"/><path d="m9 11 2 2 4-4"/></svg></span><span>Disetujui / Lunas</span><strong>{{ number_format($stat['lunas'], 0, ',', '.') }}</strong></article>
    </section>

    <form class="keuangan-payment-filters" method="GET" action="{{ route('keuangan.pembayaran.index') }}">
        <label class="keuangan-payment-search"><span>Pencarian Santri / Tagihan</span><span class="keuangan-payment-search-box"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input type="search" name="q" value="{{ $filter['q'] }}" placeholder="Cari nama siswa atau NISN..." aria-label="Cari nama siswa atau NISN"></span></label>
        <label><span>Jenis Tagihan</span><select name="jenis" aria-label="Pilih jenis tagihan"><option value="">Semua Jenis Tagihan</option>@foreach ($jenisTagihan as $jenis)<option value="{{ $jenis->id }}" @selected($filter['jenis'] === $jenis->id)>{{ $jenis->nama }}</option>@endforeach</select></label>
        <label><span>Kelas/Rombel</span><select name="rombel" aria-label="Pilih kelas dan rombel"><option value="">Semua Kelas</option>@foreach ($rombelList as $rombel)<option value="{{ $rombel->id }}" @selected($filter['rombel'] === $rombel->id)>Kelas {{ $rombel->nama }}</option>@endforeach</select></label>
        <label><span>Status Pembayaran</span><select name="status" aria-label="Pilih status pembayaran"><option value="">Semua Status</option>@foreach ($opsiStatus as $kode => $label)<option value="{{ $kode }}" @selected($filter['status'] === $kode)>{{ $label }}</option>@endforeach</select></label>
        <div class="keuangan-payment-filter-actions"><a class="keuangan-payment-reset" href="{{ route('keuangan.pembayaran.index') }}"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>Reset Filter</a><button class="keuangan-payment-submit" type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16l-6 7v6l-4 3v-9z"/></svg>Terapkan Filter</button></div>
    </form>

    <section class="keuangan-payment-table-card" aria-label="Daftar pembayaran siswa">
        <div class="keuangan-payment-table-scroll"><table>
            <thead><tr><th>No</th><th>Nama Siswa</th><th>NISN</th><th>Kelas/Rombel</th><th>Jenis Tagihan</th><th>Nominal</th><th>Jatuh Tempo</th><th>Status</th></tr></thead>
            <tbody>
            @forelse ($tagihan as $t)
                <tr>
                    <td>{{ $tagihan->firstItem() + $loop->index }}</td>
                    <td>@if ($t->pembayaranAktif)<a href="{{ route('keuangan.pembayaran.show', $t->pembayaranAktif) }}">{{ $t->siswa?->nama_lengkap ?? '—' }}</a>@else{{ $t->siswa?->nama_lengkap ?? '—' }}@endif</td>
                    <td>{{ $t->siswa?->nisn ?? '—' }}</td>
                    <td>{{ $t->siswa?->rombel->first()?->nama ?? '—' }}</td>
                    <td>{{ $t->jenisTagihan?->nama ?? '—' }}@if ($t->periode_bulan) ({{ $t->periode_bulan->translatedFormat('M Y') }})@endif</td>
                    <td>Rp {{ number_format((float) $t->nominal, 0, ',', '.') }}</td>
                    <td>{{ $t->jatuh_tempo->format('d/m/Y') }}</td>
                    <td>{{ $t->label_status }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="keuangan-payment-empty"><strong>Tidak ada tagihan yang cocok dengan filter.</strong></td></tr>
            @endforelse
            </tbody>
        </table></div>
        {{ $tagihan->links() }}
    </section>
</section>
@endsection