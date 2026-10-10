<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SIS Assaidiyyah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/js/app.js'])
</head>
<body class="login-page">

    {{-- ========================
         TOPBAR
         ======================== --}}
    <header class="login-topbar">
        <div class="topbar-left">
            <div class="logo-placeholder" title="Logo MI Assa'idiyyah">
                {{-- Placeholder Icon Logo --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
            </div>
            <span class="topbar-brand">MI ASSA'IDIYYAH</span>
        </div>
        <div class="topbar-right">
            <a href="#" class="topbar-help-btn">
                <span class="help-badge-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
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
                <div class="tab-switch" data-active="staf" id="tabSwitch">
                    <div class="tab-indicator"></div>

                    <button class="tab-btn active" id="tabStaf" onclick="switchTab('staf')">
                        <span class="tab-icon">
                            {{-- Icon: nametag staf --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="3" width="20" height="14" rx="2"/>
                                <path d="M8 21h8M12 17v4"/>
                                <circle cx="12" cy="9" r="2"/>
                                <path d="M8.5 13a4 4 0 0 1 7 0"/>
                            </svg>
                        </span>
                        Staf &amp; Guru
                    </button>
                    
                    <button class="tab-btn" id="tabWali" onclick="switchTab('wali')">
                        <span class="tab-icon">
                            {{-- Icon: keluarga (2 orang tua + 1 anak) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="7" cy="6" r="2"/>
                                <circle cx="17" cy="6" r="2"/>
                                <circle cx="12" cy="15" r="1.5"/>
                                <path d="M3.5 11a4 4 0 0 1 7 0"/>
                                <path d="M13.5 11a4 4 0 0 1 7 0"/>
                                <path d="M9.5 19a3 3 0 0 1 5 0"/>
                            </svg>
                        </span>
                        Wali Murid
                    </button>
                </div>

                {{-- Header --}}
                <div class="login-header">
                    <div class="login-logo">
                        Masuk Portal Tenaga Pendidik & Staf
                        <span>Silakan masukkan kredensial akun resmi Anda untuk mengelola pembelajaran,
                        presensi, dan administrasi.</span>
                    </div>
                </div>

                {{-- Form --}}
                <form action="#" method="POST">
                    @csrf

                    {{-- Input: Username / NIP / Email --}}
                    <div class="form-group">
                        <div class="form-label-row">
                            <label class="form-label" for="username" id="usernameLabel">
                                Masukkan Nama Pengguna
                            </label>
                        </div>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                {{-- Icon: orang/profil --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="4"/>
                                    <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                                </svg>
                            </span>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                placeholder="Masukkan NIP, Email Sekolah, atau Nama Pengguna"
                                autocomplete="username"
                            >
                        </div>
                    </div>

                    {{-- Input: Password --}}
                    <div class="form-group">
                        <div class="form-label-row">
                            <label class="form-label" for="password">Masukkan Kata Sandi Anda</label>
                            <a href="#" class="form-forgot">Lupa kata sandi?</a>
                        </div>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                {{-- Icon: gembok --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Masukkan kata sandi anda"
                                autocomplete="current-password"
                            >
                            <button type="button" class="btn-eye" id="togglePassword" title="Lihat/sembunyikan kata sandi">
                                {{-- Icon: mata terbuka (default) --}}
                                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                {{-- Icon: mata tertutup (hidden) --}}
                                <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    style="display:none;">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Checkbox: Ingat saya --}}
                    <div class="checkbox-row">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ingat saya di perangkat ini</label>
                    </div>

                    {{-- Tombol Masuk --}}
                    <button type="submit" class="btn-submit">
                        Masuk Sekarang
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </button>

                    {{-- Info Box: Khusus Wali Murid --}}
                    <div class="info-box">
                        <span class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="16" x2="12" y2="12"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>
                        </span>
                        <div class="info-content">
                            <strong id="peringatan">Khusus Orang Tua / Wali Murid:</strong>
                            <p id="isiperingatan">Klik tab Wali Murid untuk memantau nilai, kehadiran santri, dan tagihan SPP tanpa NIP.</p>
                        </div>
                    </div>

                </form>

                {{-- Helpdesk Footer --}}
                <p class="helpdesk">
                    Kendala masuk portal? Hubungi admin madrasah:
                    <a href="#" target="_blank" rel="noopener">💬 Helpdesk WhatsApp</a>
                </p>

            </div>
            {{-- /panel-left --}}

            {{-- ========================
                 PANEL KANAN — Branding
                 ======================== --}}
            <div class="panel-right">

                <div class="panel-right-bg"></div>
                <div class="panel-right-overlay"></div>

                {{-- Ganti src dengan foto sekolah asli saat sudah ada --}}
                {{-- <img class="school-photo" src="/images/school.jpg" alt="Foto Madrasah Assaidiyyah"> --}}

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
                            {{-- Icon: lokasi --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            Cipanas, Cianjur
                        </span>
                        <span class="panel-badge">
                            {{-- Icon: toga / jenjang --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
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
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </span>
            <span class="bottombar-copy-icon">©</span>
            <span>2026 MI Assa'idiyyah Cipanas</span>
        </div>
        <div class="bottombar-right">
            <a href="#" class="bottombar-link">Kebijakan Privasi</a>
            <span class="bottombar-dot">•</span>
            <a href="#" class="bottombar-link">Panduan Penggunaan</a>
            <span class="bottombar-dot">•</span>
            <a href="#" class="bottombar-link">Bantuan Teknis</a>
        </div>
    </footer>

    <script>
        function switchTab(tab) {
            const wrap = document.getElementById('tabSwitch');
            const btnStaf = document.getElementById('tabStaf');
            const btnWali = document.getElementById('tabWali');
            const usernameLabel = document.getElementById('usernameLabel');
            const usernameInput = document.getElementById('username');

            wrap.setAttribute('data-active', tab);

            if (tab === 'staf') {
                btnStaf.classList.add('active');
                btnWali.classList.remove('active');
                usernameLabel.textContent = 'Masukkan Nama Pengguna';
                usernameInput.placeholder = 'Masukkan NIP, Email Sekolah, atau Nama Pengguna';
                peringatan.textContent = 'Khusus Orang Tua / Wali Murid:';
                isiperingatan.textContent = 'Klik tab Wali Murid untuk memantau nilai, kehadiran santri, dan tagihan SPP tanpa NIP.';
            } else {
                btnWali.classList.add('active');
                btnStaf.classList.remove('active');
                usernameLabel.textContent = 'Masukkan NISN Santri / No. WhatsApp Terdaftar';
                usernameInput.placeholder = 'Masukkan NISN ananda atau No. WhatsApp (contoh: 0812xxxx)';
                peringatan.textContent = 'Panduan Masuk Wali Murid:';
                isiperingatan.textContent = 'Belum memiliki akun atau lupa PIN? Silakan hubungi Wali Kelas masing-masing atau layanan Tata Usaha Madrasah. ';
            }
        }

        (function () {
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            toggleBtn.addEventListener('click', function () {
                const isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                eyeOpen.style.display = isHidden ? 'none' : '';
                eyeClosed.style.display = isHidden ? '' : 'none';
            });
        })();
    </script>

</body>
</html>
