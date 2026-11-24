@extends('humas.layout')
@section('title', 'Kelola Profil & Visi Misi')
@section('content')
    @php
        $profil = $profil ?? null;
        $daftarMisi = $daftarMisi ?? collect();
        $urlDashboard = \Illuminate\Support\Facades\Route::has('humas.dashboard')
            ? route('humas.dashboard')
            : url('/humas/dashboard');
        $formAction = \Illuminate\Support\Facades\Route::has('humas.profil.update')
            ? route('humas.profil.update')
            : url('/humas/profil');
    @endphp
    <section class="humas-profile-page" data-profile-editor>
        <header class="humas-profile-heading">
            <div>
                <h1>Kelola Profil &amp; Visi Misi</h1>
                <p>Atur narasi sejarah singkat madrasah, teks visi, dan butir-butir misi yang disajikan kepada masyarakat.
                </p>
            </div>
        </header>

        @if (session('status'))
            <div role="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $formAction }}" data-humas-form="profil" data-profile-form>
            @csrf
            @method('PUT')
            <div class="humas-profile-grid">
                <section class="humas-profile-card humas-vision-card">
                    <header>
                        <span class="humas-profile-icon" aria-hidden="true">&#9825;</span>
                        <div><strong>Visi Madrasah</strong><small>Arah tujuan fundamental institusi</small></div>
                    </header>
                    <p class="humas-profile-help">Teks visi akan disorot pada kartu utama bagian profil landing page publik
                        dengan tipografi display.</p>
                    <label for="profileVision">Teks Utama Visi</label>
                    <textarea id="profileVision" name="visi" maxlength="200" placeholder="Tuliskan visi madrasah">{{ old('visi', $profil->visi ?? '') }}</textarea>
                    <div class="humas-profile-count">
                        <span>Format: Teks lugas, inspiratif, 1-2 kalimat</span>
                        <span><b data-vision-count>{{ mb_strlen((string) old('visi', $profil->visi ?? '')) }}</b> / 200
                            karakter</span>
                    </div>
                    <blockquote data-vision-preview hidden>{{ old('visi', $profil->visi ?? '') }}</blockquote>
                    <div class="humas-profile-note">
                        <span aria-hidden="true">&#9432;</span>
                        <span>Perubahan visi memerlukan sinkronisasi kurikulum madrasah. Pastikan komite madrasah telah
                            menyetujui versi pembaruan.</span>
                    </div>
                </section>

                <section class="humas-profile-card humas-mission-card">
                    <header>
                        <span class="humas-profile-icon" aria-hidden="true">&#9779;</span>
                        <div><strong>Daftar Misi Sekolah</strong><small>Langkah aksi nyata pencapaian visi</small></div>
                        <button type="button" class="humas-profile-add" data-mission-add>&#65291; Tambah Butir
                            Misi</button>
                    </header>
                    <p class="humas-profile-help">Gunakan ikon penggeser atau isian di bawah untuk menyusun butir misi pada
                        portal publik.</p>
                    <ol data-mission-list>
                        @php $misiLama = old('misi', $daftarMisi->pluck('deskripsi')->all()); @endphp
                        @forelse ($misiLama as $idx => $teksMisi)
                            <li>
                                <input type="text" name="misi[]" value="{{ $teksMisi }}"
                                    placeholder="Butir misi ke-{{ $idx + 1 }}">
                            </li>
                        @empty
                            <li>
                                <input type="text" name="misi[]" value="" placeholder="Butir misi ke-1">
                            </li>
                        @endforelse
                    </ol>
                </section>
            </div>

            <footer class="humas-profile-actions">
                <a href="{{ $urlDashboard }}">Batal</a>
                <button type="submit" name="status" value="draft" data-profile-save="Draft">Simpan Draft</button>
                <button type="submit" name="status" value="terbit" class="primary"
                    data-profile-save="Published">Publikasikan ke Landing Page</button>
            </footer>
        </form>
    </section>
@endsection
