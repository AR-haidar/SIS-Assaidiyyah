@extends('operator.layout')
@section('title', isset($pegawai) && $pegawai->exists ? 'Edit Data Guru' : 'Tambah Guru')
@section('content')
    @php
        $sedangEdit = isset($pegawai) && $pegawai->exists;
        $formAction = $sedangEdit ? route('operator.pegawai.update', $pegawai) : route('operator.pegawai.store');
        $jkSaatIni = old(
            'jenis_kelamin',
            $pegawai?->jenis_kelamin?->value ?? (string) ($pegawai?->jenis_kelamin ?? ''),
        );
        $sertifikasiSaatIni = old(
            'status_sertifikasi',
            isset($pegawai?->bersertifikasi) ? ($pegawai->bersertifikasi ? 'sudah' : 'belum') : '',
        );
    @endphp

    <section class="operator-teacher-create">
        <h1>{{ $sedangEdit ? 'Pembaruan Data Pendidik & Tenaga Kependidikan' : 'Formulir Data Pendidik & Tenaga Kependidikan' }}
        </h1>

        @if ($errors->any())
            <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <strong class="block font-semibold mb-1">Periksa kembali isian formulir:</strong>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="operator-teacher-create-grid">
            @csrf
            @if ($sedangEdit)
                @method('PUT')
            @endif

            <div class="operator-teacher-create-main">
                <section class="operator-teacher-form-card">
                    <header>
                        <div>
                            <h2>Identitas Pokok Kepegawaian</h2>
                            <p>Nomor identitas kedinasan dan kependudukan resmi</p>
                        </div>
                        <span>Wajib Dilengkapi</span>
                    </header>
                    <div class="operator-teacher-fields">
                        <label class="operator-teacher-field wide">Nama Lengkap beserta Gelar <b>*</b>
                            <input type="text" name="nama_lengkap"
                                value="{{ old('nama_lengkap', $pegawai?->nama_lengkap) }}" required
                                placeholder="Contoh: Ustadz Ahmad Fauzi, S.Pd.I">
                            <small>Tuliskan nama lengkap sesuai SK atau ijazah dengan gelar akademik.</small>
                        </label>
                        <label class="operator-teacher-field">NIP (Nomor Induk Pegawai)
                            <input type="text" name="nip" value="{{ old('nip', $pegawai?->nip) }}" maxlength="18"
                                placeholder="18 digit NIP jika ASN, kosongkan jika non">
                        </label>
                        <label class="operator-teacher-field">NUPTK
                            <input type="text" name="nuptk" value="{{ old('nuptk', $pegawai?->nuptk) }}" maxlength="16"
                                placeholder="16 digit NUPTK Kemenag / Kemdikbud">
                        </label>
                        <label class="operator-teacher-field">NIK (Nomor Induk Kependudukan) <b>*</b>
                            <input type="text" name="nik" value="{{ old('nik', $pegawai?->nik) }}" maxlength="16"
                                placeholder="16 digit NIK">
                        </label>
                        <label class="operator-teacher-field">Status Kepegawaian <b>*</b>
                            <select name="status_kepegawaian_id">
                                <option value="">Pilih Status Kepegawaian</option>
                                @foreach ($opsiStatusKepegawaian ?? collect() as $item)
                                    <option value="{{ $item->id }}" @selected((string) old('status_kepegawaian_id', $pegawai?->status_kepegawaian_id) === (string) $item->id)>
                                        {{ $item->label }}
                                    </option>
                                @endforeach
                            </select>
                        </label>
                        <label class="operator-teacher-field wide">Akun Pengguna Staf (Opsional)
                            <select name="pengguna_id">
                                <option value="">Belum ditautkan ke akun login</option>
                                @foreach ($daftarAkunGuru ?? collect() as $akun)
                                    <option value="{{ $akun->id }}" @selected((string) old('pengguna_id', $pegawai?->pengguna_id) === (string) $akun->id)>
                                        {{ $akun->nama_lengkap }} ({{ $akun->username }})
                                    </option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                </section>

                <section class="operator-teacher-form-card">
                    <header>
                        <div>
                            <h2>Biodata Diri &amp; Kontak</h2>
                            <p>Informasi personal, riwayat pendidikan, dan alamat domisili</p>
                        </div>
                    </header>
                    <div class="operator-teacher-fields">
                        <label class="operator-teacher-field">Tempat Lahir
                            <input type="text" name="tempat_lahir"
                                value="{{ old('tempat_lahir', $pegawai?->tempat_lahir) }}"
                                placeholder="Kota / Kabupaten Kelahiran">
                        </label>
                        <label class="operator-teacher-field">Tanggal Lahir
                            <input type="date" name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $pegawai?->tanggal_lahir ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('Y-m-d') : '') }}">
                        </label>
                        <fieldset class="operator-teacher-field operator-teacher-gender">
                            <legend>Jenis Kelamin <b>*</b></legend>
                            <label><input type="radio" name="jenis_kelamin" value="L" @checked(in_array($jkSaatIni, ['L', 'laki-laki'], true))>
                                Laki-laki</label>
                            <label><input type="radio" name="jenis_kelamin" value="P" @checked(in_array($jkSaatIni, ['P', 'perempuan'], true))>
                                Perempuan</label>
                        </fieldset>
                        <label class="operator-teacher-field">Pendidikan Terakhir <b>*</b>
                            <select name="pendidikan_terakhir_id">
                                <option value="">Pilih Jenjang Terakhir</option>
                                @foreach ($opsiPendidikan ?? collect() as $item)
                                    <option value="{{ $item->id }}" @selected((string) old('pendidikan_terakhir_id', $pegawai?->pendidikan_terakhir_id) === (string) $item->id)>
                                        {{ $item->label }}
                                    </option>
                                @endforeach
                            </select>
                        </label>
                        <label class="operator-teacher-field">Program Studi / Jurusan
                            <input type="text" name="program_studi"
                                value="{{ old('program_studi', $pegawai?->program_studi) }}"
                                placeholder="Contoh: Pendidikan Agama Islam, PGMI">
                        </label>
                        <label class="operator-teacher-field">Nomor HP / WhatsApp Aktif <b>*</b>
                            <input type="tel" name="no_hp" value="{{ old('no_hp', $pegawai?->no_hp) }}"
                                placeholder="+62   Contoh: 081234567890">
                        </label>
                        <label class="operator-teacher-field">Email Resmi / Pribadi
                            <input type="email" name="email" value="{{ old('email', $pegawai?->email) }}"
                                placeholder="email@madrasah.sch.id">
                        </label>
                        <label class="operator-teacher-field wide">Alamat Domisili
                            <textarea name="alamat" placeholder="Alamat domisili lengkap...">{{ old('alamat', $pegawai?->alamat) }}</textarea>
                        </label>
                    </div>
                </section>

                <section class="operator-teacher-form-card">
                    <header>
                        <div>
                            <h2>Penugasan &amp; Tugas Mengajar Madrasah</h2>
                            <p>Peran struktural pengajaran di lingkungan MI Assaidiyyah</p>
                        </div>
                    </header>
                    <div class="operator-teacher-fields">
                        <label class="operator-teacher-field">Jenis Guru / Tugas Pokok <b>*</b>
                            <select name="jenis_gtk_id">
                                <option value="">Pilih Penugasan Pokok</option>
                                @foreach ($opsiJenisGtk ?? collect() as $item)
                                    <option value="{{ $item->id }}" @selected((string) old('jenis_gtk_id', $pegawai?->jenis_gtk_id) === (string) $item->id)>
                                        {{ $item->label }}
                                    </option>
                                @endforeach
                            </select>
                        </label>
                        <label class="operator-teacher-field">Mata Pelajaran Diampu
                            <input type="text" name="mata_pelajaran"
                                value="{{ old('mata_pelajaran', $pegawai?->mata_pelajaran) }}"
                                placeholder="Contoh: Akidah Akhlak, Fiqih, Matematika">
                        </label>
                        <label class="operator-teacher-field">SK Pengangkatan / Penugasan
                            <input type="text" name="nomor_sk" value="{{ old('nomor_sk', $pegawai?->nomor_sk) }}"
                                placeholder="Nomor SK Kepala Madrasah / Yayasan">
                        </label>
                        <fieldset class="operator-teacher-field operator-teacher-certification">
                            <legend>Status Sertifikasi Pendidik</legend>
                            <label><input type="radio" name="status_sertifikasi" value="sudah"
                                    @checked($sertifikasiSaatIni === 'sudah')> Sudah Bersertifikasi</label>
                            <label><input type="radio" name="status_sertifikasi" value="belum"
                                    @checked($sertifikasiSaatIni === 'belum')> Belum Bersertifikasi</label>
                        </fieldset>
                    </div>
                </section>
            </div>

            <aside class="operator-teacher-photo-card">
                <header>
                    <h2>Pasfoto</h2>
                    <p>Dokumentasi visual biodata</p>
                </header>
                <label class="operator-teacher-upload">
                    <span class="operator-teacher-camera"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M14 4h-4l-2 3H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3z" />
                            <circle cx="12" cy="13" r="3" />
                            <path d="M19 3v4M17 5h4" />
                        </svg></span>
                    <strong>Unggah Pasfoto Resmi</strong>
                    <small>Format JPG/PNG. maks. 15MB</small>
                    <span class="operator-teacher-pick">♧ Pilih Berkas</span>
                    <input type="file" name="pasfoto" accept="image/png,image/jpeg">
                </label>
            </aside>

            <div class="operator-teacher-actions">
                <a href="{{ route('operator.pegawai.index') }}">Batal</a>
                <button type="submit" name="aksi" value="draft">Simpan Draf</button>
                <button type="submit" name="aksi" value="simpan"
                    class="primary">{{ $sedangEdit ? 'Perbarui Data' : 'Simpan Data' }} <span
                        aria-hidden="true">&rsaquo;</span></button>
            </div>
        </form>
    </section>
@endsection
