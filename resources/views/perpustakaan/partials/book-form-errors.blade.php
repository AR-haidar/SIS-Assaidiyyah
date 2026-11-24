@if ($errors->any())
    <p class="perpustakaan-book-validation-status perpustakaan-book-error" role="alert">
        {{ $errors->first('simpan') ?: 'Periksa isian yang ditandai. Pilih kembali berkas yang akan diunggah.' }}
    </p>
@endif
