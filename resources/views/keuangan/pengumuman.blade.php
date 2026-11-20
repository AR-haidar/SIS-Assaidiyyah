@extends('keuangan.layout')
@section('title', 'Pengumuman Keuangan')
@section('content')
<section class="keuangan-payments"><header class="keuangan-payments-heading"><div><h1>Pengumuman Keuangan</h1><p>Informasi keuangan untuk siswa dan orang tua</p></div><a class="keuangan-button keuangan-button-primary" href="{{ url('/keuangan/pengumuman/tambah') }}">Tambah Pengumuman</a></header><div class="keuangan-revision-list" data-announcement-list><p>Belum ada pengumuman keuangan.</p></div></section>
@endsection
