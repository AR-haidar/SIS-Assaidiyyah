@extends('keuangan.layout')
@section('title', 'Input Tagihan')
@section('content')
<section class="keuangan-payments"><header class="keuangan-payments-heading"><h1>Input Tagihan</h1><p>Buat tagihan siswa secara manual</p></header>
<form class="keuangan-revision-form" data-demo-form="invoice"><label>Pilih Siswa<select name="student" required><option value="">Pilih siswa</option><option>Ahmad Fauzi — Kelas 1A</option><option>Siti Aminah — Kelas 2B</option><option>Muhammad Rizki — Kelas 5B</option></select></label>
<label>Jenis Tagihan<select name="type" required><option>Infaq</option><option>Uang Buku</option><option>Wisata</option><option>Seragam</option><option value="Lainnya">Lainnya</option></select></label><label class="is-hidden" data-other-name>Nama Tagihan<input name="customType" type="text"></label>
<label>Nominal Tagihan<input name="amount" type="number" min="1" required placeholder="Rp"></label><label>Tanggal Tagihan<input name="issuedAt" type="date" required></label><label>Tanggal Jatuh Tempo<input name="dueAt" type="date" required></label><label class="form-wide">Keterangan (opsional)<textarea name="note" rows="3"></textarea></label>
<div class="form-wide form-actions"><a class="keuangan-button" href="{{ url('/keuangan/dashboard') }}">Batal</a><button class="keuangan-button keuangan-button-primary">Simpan Tagihan</button></div></form><div class="keuangan-revision-list" data-invoice-list></div></section>
@endsection
