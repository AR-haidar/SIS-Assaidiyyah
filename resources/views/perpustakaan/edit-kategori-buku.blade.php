@extends('perpustakaan.layout')
@section('title','Edit Kategori Buku')
@section('content')
<section class="perpustakaan-add-category-page"><a href="{{ url('/perpustakaan/kategori-buku') }}" class="perpustakaan-category-back">Kembali ke Kategori Buku</a><h1>Edit Kategori Buku</h1>
<form class="perpustakaan-category-form" method="POST" action="{{ route('library.categories.update',$category) }}">@csrf @method('PUT')
@if($errors->any())<div class="perpustakaan-flash error">Periksa kembali isian form.</div>@endif
<section class="perpustakaan-category-form-card"><header class="perpustakaan-category-form-heading"><div><h2>Perbarui Kategori Pustaka</h2><p>Perbarui rincian klasifikasi buku</p></div><span>* Bidang Wajib</span></header><div class="perpustakaan-category-fields">
<label class="perpustakaan-category-field"><span>Nama Kategori *</span><input name="name" value="{{ old('name',$category->name) }}" maxlength="100" required>@error('name')<small class="field-error">{{ $message }}</small>@enderror</label>
<label class="perpustakaan-category-field"><span>Deskripsi Kategori *</span><textarea name="description" maxlength="2000" required>{{ old('description',$category->description) }}</textarea>@error('description')<small class="field-error">{{ $message }}</small>@enderror</label>
<fieldset class="perpustakaan-category-status"><legend>Status Kategori *</legend><label><input type="radio" name="status" value="active" @checked(old('status',$category->status)==='active') required><span><strong>Aktif</strong></span></label><label><input type="radio" name="status" value="inactive" @checked(old('status',$category->status)==='inactive')><span><strong>Nonaktif</strong></span></label></fieldset>
<footer class="perpustakaan-category-form-actions"><a href="{{ url('/perpustakaan/kategori-buku') }}">Batal</a><button type="submit">Simpan Perubahan</button></footer></div></section></form></section>
@endsection
