@extends('perpustakaan.layout')
@section('title','Edit Buku')
@section('content')
<section class="perpustakaan-add-book-page"><a href="{{ url('/perpustakaan/data-buku') }}" class="perpustakaan-form-back">Kembali ke Data Buku</a><h1>Edit Buku</h1>
<form class="perpustakaan-category-form" method="POST" action="{{ route('library.books.update',$book) }}" enctype="multipart/form-data">@csrf @method('PUT')
@if($errors->any())<div class="perpustakaan-flash error">Periksa kembali isian form.</div>@endif
<section class="perpustakaan-category-form-card"><header class="perpustakaan-category-form-heading"><div><h2>Informasi Pokok Buku</h2><p>Perbarui data bibliografi buku</p></div><span>* Wajib diisi</span></header><div class="perpustakaan-category-fields">
<label class="perpustakaan-category-field"><span>Judul Buku *</span><input name="title" value="{{ old('title',$book->title) }}" maxlength="250" required>@error('title')<small class="field-error">{{ $message }}</small>@enderror</label>
<label class="perpustakaan-category-field"><span>Penulis *</span><input name="author" value="{{ old('author',$book->author) }}" maxlength="255" required>@error('author')<small class="field-error">{{ $message }}</small>@enderror</label>
<label class="perpustakaan-category-field"><span>Kategori *</span><select name="category_id" required>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id',$book->category_id)==$category->id)>{{ $category->name }}</option>@endforeach</select>@error('category_id')<small class="field-error">{{ $message }}</small>@enderror</label>
<label class="perpustakaan-category-field"><span>Penerbit</span><input name="publisher" value="{{ old('publisher',$book->publisher) }}" maxlength="255"></label>
<label class="perpustakaan-category-field"><span>Tahun Terbit</span><input type="number" name="published_year" value="{{ old('published_year',$book->published_year) }}" min="1000" max="{{ date('Y')+1 }}">@error('published_year')<small class="field-error">{{ $message }}</small>@enderror</label>
<label class="perpustakaan-category-field"><span>Deskripsi / Sinopsis *</span><textarea name="synopsis" maxlength="1000" required>{{ old('synopsis',$book->synopsis) }}</textarea>@error('synopsis')<small class="field-error">{{ $message }}</small>@enderror</label>
<label class="perpustakaan-category-field"><span>Ganti Cover (JPG/PNG maks. 10MB)</span><input type="file" name="cover" accept="image/jpeg,image/png">@error('cover')<small class="field-error">{{ $message }}</small>@enderror</label>
<label class="perpustakaan-category-field"><span>Ganti Naskah PDF/EPUB, maks. 50MB</span><input type="file" name="digital_file" accept=".pdf,.epub,application/pdf">@error('digital_file')<small class="field-error">{{ $message }}</small>@enderror</label>
<fieldset class="perpustakaan-category-status"><legend>Status Publikasi *</legend><label><input type="radio" name="publication_status" value="published" @checked(old('publication_status',$book->publication_status)==='published') required><span><strong>Terbitkan Langsung</strong></span></label><label><input type="radio" name="publication_status" value="draft" @checked(old('publication_status',$book->publication_status)==='draft')><span><strong>Simpan sebagai Draf</strong></span></label></fieldset>
<footer class="perpustakaan-category-form-actions"><a href="{{ url('/perpustakaan/data-buku') }}">Batal</a><button type="submit">Simpan Perubahan</button></footer></div></section></form></section>
@endsection
