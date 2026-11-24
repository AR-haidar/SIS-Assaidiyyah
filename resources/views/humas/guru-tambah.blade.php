@extends('humas.layout')
@section('title', isset($profil) ? 'Edit Sorotan Profil Guru' : 'Tambah Sorotan Profil Guru')
@section('content')
    @php
        $isEdit = isset($profil);
        $daftarPegawai = $daftarPegawai ?? collect();
        $urlIndex = \Illuminate\Support\Facades\Route::has('humas.guru.index')
            ? route('humas.guru.index')
            : url('/humas/guru');
        $formAction = $isEdit
            ? (\Illuminate\Support\Facades\Route::has('humas.guru.update')
                ? route('humas.guru.update', $profil)
                : url('/humas/guru/' . $profil->id))
            : (\Illuminate\Support\Facades\Route::has('humas.guru.store')
                ? route('humas.guru.store')
                : url('/humas/guru'));
        $aktifVal = (string) old(
            'aktif',
            isset($profil) ? ($profil->aktif ?? ($profil->tampil ?? true) ? '1' : '0') : '1',
        );
    @endphp
    <section class="humas-teacher-create-page">
        <a class="humas-teacher-back" href="{{ $urlIndex }}">&larr; Kembali ke Profil Guru</a>
        <h1>{{ $isEdit ? 'Edit Sorotan Profil Guru' : 'Tambah Sorotan Profil Guru' }}</h1>
        <p class="humas-teacher-create-intro">Tambahkan dan atur tampilan kartu profil tenaga pendidik untuk ditampilkan pada
            bagian Dewan Guru di Landing Page.</p>

        @if ($errors->any())
            <div role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="humas-teacher-create-card"
            data-teacher-create-form>
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif
            <div class="humas-teacher-create-notice">&#9432; <span>Humas hanya mengatur penyajian kartu profil di Landing
                    Page. Data induk kepegawaian &amp; NUPTK tetap dikelola oleh Operator Madrasah.</span></div>

            @if ($daftarPegawai->isNotEmpty())
                <label>
                    Referensi Data Pegawai <small>Opsional</small>
                    <select name="pegawai_id">
                        <option value="">— Pilih Pegawai (Opsional) —</option>
                        @foreach ($daftarPegawai as $peg)
                            <option value="{{ $peg->id }}" @selected((string) old('pegawai_id', $profil->pegawai_id ?? '') === (string) $peg->id)>
                                {{ $peg->nama_lengkap }} {{ $peg->nip ? '(' . $peg->nip . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </label>
            @endif

            <label>
                Nama Lengkap &amp; Gelar <b>*</b>
                <input type="text" name="nama_tampil" required maxlength="150"
                    value="{{ old('nama_tampil', $profil->nama_tampil ?? '') }}" placeholder="Nama guru beserta gelar">
            </label>

            <label>
                Jabatan / Bidang Mengajar <b>*</b>
                <input type="text" name="jabatan_tampil" required maxlength="150"
                    value="{{ old('jabatan_tampil', $profil->jabatan_tampil ?? '') }}"
                    placeholder="Contoh: Guru Al-Qur'an Hadits &amp; Fiqih atau Wali Kelas IV-A">
            </label>

            <label>
                <span>Deskripsi Singkat / Kutipan Motivasi <b>*</b><small><span data-teacher-description-count>0</span> /
                        500 Karakter</small></span>
                <textarea name="bio_singkat" required maxlength="500" rows="3"
                    placeholder="Tuliskan kutipan inspiratif atau fokus pengajaran pendidik...">{{ old('bio_singkat', $profil->bio_singkat ?? '') }}</textarea>
            </label>

            <label>
                Urutan Tampil
                <input type="number" name="urutan" min="0" value="{{ old('urutan', $profil->urutan ?? 0) }}">
            </label>

            <label>
                Foto Profil untuk Landing Page
                <span class="humas-teacher-upload" data-teacher-upload>
                    <img data-teacher-create-preview hidden alt="Pratinjau foto profil">
                    <span data-teacher-upload-copy>&#9729;<strong>Seret &amp; lepas foto ke area ini, atau klik tombol di
                            bawah</strong><small>Format JPG, PNG, WebP &bull; Maksimal 2MB</small></span>
                    <button type="button" data-teacher-pick>Choose File</button>
                    <span data-teacher-file-name>No file chosen</span>
                    <input type="file" name="foto" accept="image/jpeg,image/png,image/webp" data-teacher-create-photo
                        aria-label="Pilih foto profil">
                </span>
            </label>

            <fieldset class="humas-teacher-create-visibility">
                <legend>Status Visibilitas</legend>
                <label><input type="radio" name="aktif" value="1" @checked($aktifVal === '1')><span>Tampilkan di
                        Landing Page (Published)</span></label>
                <label><input type="radio" name="aktif" value="0" @checked($aktifVal === '0')><span>Simpan sebagai
                        Draf (Disembunyikan)</span></label>
            </fieldset>

            <footer>
                <a href="{{ $urlIndex }}">Batal</a>
                <button type="submit" name="aktif" value="0">Simpan Draft</button>
                <button type="submit" class="primary" name="aktif" value="1">Publikasikan Sorotan</button>
            </footer>
        </form>
    </section>
@endsection
