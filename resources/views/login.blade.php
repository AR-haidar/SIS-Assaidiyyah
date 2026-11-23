<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SIS Assaidiyyah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/js/app.js'])
    <style>
        /* Penyesuaian alert validasi & responsif lintas ukuran layar */
        .alert-error-box {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.85rem;
            line-height: 1.45;
            margin-bottom: 16px;
        }

        .alert-status-box {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.85rem;
            line-height: 1.45;
            margin-bottom: 16px;
        }

        .field-error-text {
            display: block;
            color: #dc2626;
            font-size: 0.78rem;
            margin-top: 5px;
            font-weight: 500;
        }

        .input-wrapper.is-invalid {
            border-color: #ef4444 !important;
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Tablet (<= 960px) */
        @media (max-width: 960px) {
            .login-main {
                padding: 24px 16px;
                align-items: flex-start;
            }

            .login-card {
                flex-direction: column;
                max-width: 540px;
                width: 100%;
                min-height: auto;
            }

            .panel-left,
            .panel-right {
                width: 100%;
                max-width: 100%;
            }

            .panel-left {
                padding: 28px 24px;
            }

            .panel-right {
                min-height: auto;
                padding: 28px 24px;
            }

            .panel-right-content {
                padding: 0;
            }
        }

        /* Mobile (<= 640px) */
        @media (max-width: 640px) {
            .login-topbar {
                padding: 12px 16px;
            }

            .topbar-brand {
                font-size: 0.9rem;
            }

            .topbar-help-btn span:last-child {
                font-size: 0.8rem;
            }

            .login-main {
                padding: 16px 12px;
            }

            .panel-left {
                padding: 22px 16px;
            }

            .login-logo {
                font-size: 1.15rem;
            }

            .login-logo span {
                font-size: 0.82rem;
            }

            .input-wrapper input {
                font-size: 16px;
                /* Cegah auto-zoom pada iOS/Android */
            }

            .form-label-row {
                flex-wrap: wrap;
                gap: 4px;
            }

            .panel-right {
                padding: 22px 16px;
            }

            .panel-tagline {
                font-size: 1.15rem;
            }

            .panel-desc {
                font-size: 0.82rem;
            }

            .panel-badges {
                flex-wrap: wrap;
                gap: 8px;
            }

            .login-bottombar {
                flex-direction: column;
                gap: 8px;
                text-align: center;
                padding: 14px 16px;
            }

            .bottombar-right {
                flex-wrap: wrap;
                justify-content: center;
                gap: 6px;
            }
        }

        /* Layar sangat kecil (<= 380px) */
        @media (max-width: 380px) {
            .tab-btn {
                font-size: 0.8rem;
                padding: 9px 8px;
                gap: 5px;
            }

            .tab-icon svg {
                width: 15px;
                height: 15px;
            }
        }
    </style>
</head>

<body class="login-page">

    @php
        $tabAwal = old('jenis_akun') === 'siswa' ? 'wali' : 'staf';
    @endphp

    {{-- ========================
         TOPBAR
         ======================== --}}
    <header class="login-topbar">
        <div class="topbar-left">
            <div class="logo-placeholder" title="Logo MI Assa'idiyyah">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                    <path d="M6 12v5c3 3 9 3 12 0v-5" />
                </svg>
            </div>
            <span class="topbar-brand">MI ASSA'IDIYYAH</span>
        </div>
        <div class="topbar-right">
            <a href="#helpdeskInfo" class="topbar-help-btn">
                <span class="help-badge-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                </span>
                <span>Pusat Bantuan</span>
            </a>
        </div>
    </header>

    {{-- ========================
         MAIN CONTENT (CARD)
         ======================== --}}
    <main class="login-main">
        <div class="login-card">

            {{-- ========================
                 PANEL KIRI — Form Login
                 ======================== --}}
            <div class="panel-left">

                {{-- Tab Switch: Staf & Guru | Wali Murid --}}
                <div class="tab-switch" data-active="{{ $tabAwal }}" id="tabSwitch">
                    <div class="tab-indicator"></div>

                    <button type="button" class="tab-btn {{ $tabAwal === 'staf' ? 'active' : '' }}" id="tabStaf"
                        onclick="switchTab('staf')">
                        <span class="tab-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="2" y="3" width="20" height="14" rx="2" />
                                <path d="M8 21h8M12 17v4" />
                                <circle cx="12" cy="9" r="2" />
                                <path d="M8.5 13a4 4 0 0 1 7 0" />
                            </svg>
                        </span>
                        Staf &amp; Guru
                    </button>

                    <button type="button" class="tab-btn {{ $tabAwal === 'wali' ? 'active' : '' }}" id="tabWali"
                        onclick="switchTab('wali')">
                        <span class="tab-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="7" cy="6" r="2" />
                                <circle cx="17" cy="6" r="2" />
                                <circle cx="12" cy="15" r="1.5" />
                                <path d="M3.5 11a4 4 0 0 1 7 0" />
                                <path d="M13.5 11a4 4 0 0 1 7 0" />
                                <path d="M9.5 19a3 3 0 0 1 5 0" />
                            </svg>
                        </span>
                        Wali Murid
                    </button>
                </div>

                {{-- Header --}}
                <div class="login-header">
                    <div class="login-logo">
                        <div id="loginTitle">Masuk Portal Tenaga Pendidik &amp; Staf</div>
                        <span id="loginSubtitle">Silakan masukkan kredensial akun resmi Anda untuk mengelola
                            pembelajaran,
                            presensi, dan administrasi.</span>
                    </div>
                </div>

                {{-- Flash Status / Alert Validasi --}}
                @if (session('status'))
                    <div class="alert-status-box" role="status">
                        {{ session('status') }}
                    </div>
                @endif

                @error('jenis_akun')
                    <div class="alert-error-box" role="alert">
                        {{ $message }}
                    </div>
                @enderror

                {{-- Form --}}
                <form action="{{ route('login.store') }}" method="POST" id="loginForm">
                    @csrf
                    <input type="hidden" name="jenis_akun" id="jenisAkun" value="{{ old('jenis_akun', 'staf') }}">

                    {{-- Input: Username / NISN --}}
                    <div class="form-group">
                        <div class="form-label-row">
                            <label class="form-label" for="username" id="usernameLabel">
                                Masukkan Nama Pengguna
                            </label>
                        </div>
                        <div class="input-wrapper @error('username') is-invalid @enderror">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="4" />
                                    <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                                </svg>
                            </span>
                            <input type="text" id="username" name="username" value="{{ old('username') }}"
                                placeholder="Masukkan Nama Pengguna" autocomplete="username" required autofocus>
                        </div>
                        @error('username')
                            <span class="field-error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Input: Password / PIN --}}
                    <div class="form-group">
                        <div class="form-label-row">
                            <label class="form-label" for="password" id="passwordLabel">Masukkan Kata Sandi
                                Anda</label>
                            <a href="#helpdeskInfo" class="form-forgot" id="forgotLink">Lupa kata sandi?</a>
                        </div>
                        <div class="input-wrapper @error('password') is-invalid @enderror">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </span>
                            <input type="password" id="password" name="password"
                                placeholder="Masukkan kata sandi anda" autocomplete="current-password" required>
                            <button type="button" class="btn-eye" id="togglePassword"
                                title="Lihat/sembunyikan kata sandi">
                                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" style="display:none;">
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" />
                                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
                                    <line x1="1" y1="1" x2="23" y2="23" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="field-error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Checkbox: Ingat saya --}}
                    <div class="checkbox-row">
                        <input type="checkbox" id="remember" name="remember" value="1"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Ingat saya di perangkat ini</label>
                    </div>

                    {{-- Tombol Masuk --}}
                    <button type="submit" class="btn-submit" id="btnSubmit">
                        <span id="btnSubmitText">Masuk Sekarang</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </button>

                    {{-- Info Box --}}
                    <div class="info-box">
                        <span class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                        </span>
                        <div class="info-content">
                            <strong id="peringatan">Khusus Orang Tua / Wali Murid:</strong>
                            <p id="isiperingatan">Klik tab Wali Murid untuk memantau kehadiran santri dan tagihan SPP
                                menggunakan NISN &amp; PIN.</p>
                        </div>
                    </div>

                </form>

                {{-- Helpdesk Footer --}}
                <p class="helpdesk" id="helpdeskInfo">
                    Kendala masuk portal atau lupa sandi/PIN? Hubungi Tata Usaha / Admin Madrasah.
                </p>

            </div>
            {{-- /panel-left --}}

            {{-- ========================
                 PANEL KANAN — Branding
                 ======================== --}}
            <div class="panel-right">

                <div class="panel-right-bg"></div>
                <div class="panel-right-overlay"></div>

                <div class="panel-right-content">
                    <p class="panel-tagline">
                        Membentuk generasi qurani,<br>cerdas &amp; berakhlak mulia
                    </p>
                    <p class="panel-desc">
                        Sistem Informasi Madrasah Terpadu mengintegrasikan kurikulum Kemenag,
                        kurikulum nasional, Tahfidzul Qur'an, serta komunikasi orang tua
                        secara digital real-time.
                    </p>
                    <div class="panel-badges">
                        <span class="panel-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            Cipanas, Cianjur
                        </span>
                        <span class="panel-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                                <path d="M6 12v5c3 3 9 3 12 0v-5" />
                            </svg>
                            Jenjang MI / SD
                        </span>
                    </div>
                </div>

            </div>
            {{-- /panel-right --}}

        </div>
        {{-- /login-card --}}
    </main>

    {{-- ========================
         BOTTOMBAR
         ======================== --}}
    <footer class="login-bottombar">
        <div class="bottombar-left">
            <span class="bottombar-icon" title="Koneksi Aman">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" />
                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                </svg>
            </span>
            <span class="bottombar-copy-icon">©</span>
            <span>2026 MI Assa'idiyyah Cipanas</span>
        </div>
        <div class="bottombar-right">
            <a href="#helpdeskInfo" class="bottombar-link">Kebijakan Privasi</a>
            <span class="bottombar-dot">•</span>
            <a href="#helpdeskInfo" class="bottombar-link">Panduan Penggunaan</a>
            <span class="bottombar-dot">•</span>
            <a href="#helpdeskInfo" class="bottombar-link">Bantuan Teknis</a>
        </div>
    </footer>

    <script>
        function switchTab(tab) {
            const wrap = document.getElementById('tabSwitch');
            const btnStaf = document.getElementById('tabStaf');
            const btnWali = document.getElementById('tabWali');
            const jenisAkunInput = document.getElementById('jenisAkun');
            const loginTitle = document.getElementById('loginTitle');
            const loginSubtitle = document.getElementById('loginSubtitle');
            const usernameLabel = document.getElementById('usernameLabel');
            const usernameInput = document.getElementById('username');
            const passwordLabel = document.getElementById('passwordLabel');
            const passwordInput = document.getElementById('password');
            const forgotLink = document.getElementById('forgotLink');
            const peringatan = document.getElementById('peringatan');
            const isiperingatan = document.getElementById('isiperingatan');

            wrap.setAttribute('data-active', tab);

            if (tab === 'staf') {
                btnStaf.classList.add('active');
                btnWali.classList.remove('active');
                jenisAkunInput.value = 'staf';
                loginTitle.textContent = 'Masuk Portal Tenaga Pendidik & Staf';
                loginSubtitle.textContent =
                    'Silakan masukkan kredensial akun resmi Anda untuk mengelola pembelajaran, presensi, dan administrasi.';
                usernameLabel.textContent = 'Masukkan Nama Pengguna';
                usernameInput.placeholder = 'Masukkan Nama Pengguna';
                usernameInput.setAttribute('inputmode', 'text');
                passwordLabel.textContent = 'Masukkan Kata Sandi Anda';
                passwordInput.placeholder = 'Masukkan kata sandi anda';
                forgotLink.textContent = 'Lupa kata sandi?';
                peringatan.textContent = 'Khusus Orang Tua / Wali Murid:';
                isiperingatan.textContent =
                    'Klik tab Wali Murid untuk memantau kehadiran santri dan tagihan SPP menggunakan NISN & PIN.';
            } else {
                btnWali.classList.add('active');
                btnStaf.classList.remove('active');
                jenisAkunInput.value = 'siswa';
                loginTitle.textContent = 'Masuk Portal Orang Tua / Wali Murid';
                loginSubtitle.textContent =
                    'Silakan masukkan NISN santri dan PIN yang diberikan pihak madrasah untuk memantau kehadiran dan administrasi.';
                usernameLabel.textContent = 'Masukkan NISN Santri';
                usernameInput.placeholder = 'Contoh: 0012345678';
                usernameInput.setAttribute('inputmode', 'numeric');
                passwordLabel.textContent = 'Masukkan PIN Santri';
                passwordInput.placeholder = 'Masukkan PIN Anda';
                forgotLink.textContent = 'Lupa PIN?';
                peringatan.textContent = 'Panduan Masuk Wali Murid:';
                isiperingatan.textContent =
                    'Belum memiliki akun atau lupa PIN? Silakan hubungi Wali Kelas masing-masing atau layanan Tata Usaha Madrasah.';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const initialTab = document.getElementById('tabSwitch').getAttribute('data-active') || 'staf';
            switchTab(initialTab);

            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            toggleBtn.addEventListener('click', function() {
                const isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                eyeOpen.style.display = isHidden ? 'none' : '';
                eyeClosed.style.display = isHidden ? '' : 'none';
            });

            const loginForm = document.getElementById('loginForm');
            const btnSubmit = document.getElementById('btnSubmit');
            const btnSubmitText = document.getElementById('btnSubmitText');

            loginForm.addEventListener('submit', function() {
                btnSubmit.disabled = true;
                btnSubmitText.textContent = 'Memproses...';
            });
        });
    </script>

</body>

</html>
