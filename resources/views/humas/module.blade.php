@php
    $title = $title ?? 'Modul Konten';
    $slug = $slug ?? 'konten';
    $fields = is_array($fields ?? null) ? $fields : [];
    $dashboardHumasUrl = \Illuminate\Support\Facades\Route::has('humas.dashboard')
        ? route('humas.dashboard')
        : url('/humas/dashboard');
@endphp
<section class="humas-content">
    <div class="humas-breadcrumb">CMS Humas › Pengelolaan Landing Page › <strong>{{ $title }}</strong></div>
    <header class="humas-heading">
        <div>
            <h1>{{ $title }}</h1>
            <p>Kelola konten yang tampil pada Landing Page tanpa mengubah susunan dan desain halaman publik.</p>
        </div>
        <a class="operator-primary-button" href="{{ url('/') }}">Pratinjau Landing Page</a>
    </header>
    <div class="humas-editor">
        <div class="humas-panel">
            <h2>Informasi Konten</h2>
            <form data-humas-form="{{ $slug }}">
                @foreach ($fields as $index => $field)
                    @php
                        $fieldLabel = is_array($field) ? $field['label'] ?? 'Field' : (string) $field;
                        $fieldValue = is_array($field) ? $field['value'] ?? '' : '';
                        $fieldLower = strtolower($fieldLabel);
                    @endphp
                    <label>{{ $fieldLabel }}
                        @if (str_contains($fieldLower, 'gambar') || str_contains($fieldLower, 'banner') || str_contains($fieldLower, 'foto'))
                            <input type="file" accept="image/png,image/jpeg,image/webp" data-humas-image>
                            <img data-humas-preview hidden alt="Pratinjau gambar" class="humas-image-preview">
                        @elseif (str_contains($fieldLower, 'deskripsi') || str_contains($fieldLower, 'visi') || str_contains($fieldLower, 'alamat'))
                            <textarea name="field{{ $index }}" rows="4" placeholder="Masukkan {{ $fieldLower }}">{{ $fieldValue }}</textarea>
                        @else
                            <input name="field{{ $index }}" value="{{ $fieldValue }}"
                                placeholder="Masukkan {{ $fieldLower }}">
                        @endif
                    </label>
                @endforeach
                @if (in_array($slug, ['program', 'prestasi'], true))
                    <label>Status Publikasi
                        <select name="status">
                            <option value="Draft">Draft</option>
                            <option value="Published">Published</option>
                        </select>
                    </label>
                @endif
                <div class="humas-actions">
                    <a href="{{ $dashboardHumasUrl }}">Batal</a>
                    <button type="button" data-humas-save="Draft">Simpan Draft</button>
                    <button type="button" class="primary" data-humas-save="Published">Publikasikan</button>
                </div>
            </form>
        </div>
        <aside class="humas-panel">
            <h2>Pratinjau</h2>
            <p>Pratinjau membantu memeriksa konten sebelum diterbitkan. Layout Landing Page dikelola terpisah.</p>
            <div data-humas-preview-text class="humas-preview-box">Isi form untuk melihat pratinjau.</div>
            <p class="humas-demo-note">Penyimpanan saat ini hanya demo browser; belum mengubah Landing Page publik.</p>
        </aside>
    </div>
</section>
