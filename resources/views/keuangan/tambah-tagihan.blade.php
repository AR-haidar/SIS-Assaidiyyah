@extends('keuangan.layout')
@section('title', 'Input Tagihan')
@section('content')
<section class="keuangan-payments"><header class="keuangan-payments-heading"><h1>Input Tagihan</h1><p>Buat tagihan siswa secara manual</p></header>
@if (session('sukses'))<p>{{ session('sukses') }}</p>@endif
@if ($errors->any())<p>{{ $errors->first() }}</p>@endif
@if (! $tahunAjaran)<p>Belum ada tahun ajaran aktif. Tagihan belum bisa dibuat.</p>@endif
<form class="keuangan-revision-form" method="POST" action="{{ route('keuangan.tagihan.store') }}">
@csrf
<label>Pilih Siswa<select name="siswa_id" required><option value="">Pilih siswa</option>@foreach ($siswa as $s)<option value="{{ $s->id }}" @selected((int) old('siswa_id') === $s->id)>{{ $s->nama_lengkap }} — Kelas {{ $s->rombel->first()?->nama ?? '—' }}</option>@endforeach</select></label>
<label>Jenis Tagihan<select name="jenis_tagihan_id" required><option value="">Pilih jenis tagihan</option>@foreach ($jenisTagihan as $jenis)<option value="{{ $jenis->id }}" @selected((int) old('jenis_tagihan_id') === $jenis->id)>{{ $jenis->nama }}</option>@endforeach</select></label>
<label>Nominal Tagihan<input name="nominal" type="number" min="1" step="1" required placeholder="Rp" value="{{ old('nominal') }}"></label>
<label>Tanggal Tagihan<input name="tanggal_tagihan" type="date" required value="{{ old('tanggal_tagihan') }}"></label>
<label>Tanggal Jatuh Tempo<input name="jatuh_tempo" type="date" required value="{{ old('jatuh_tempo') }}"></label>
<div class="form-wide form-actions"><a class="keuangan-button" href="{{ url('/keuangan/dashboard') }}">Batal</a><button type="submit" class="keuangan-button keuangan-button-primary">Simpan Tagihan</button></div></form></section>
@endsection