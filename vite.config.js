import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/login.css',
                'resources/css/home.css',
                'resources/css/perpustakaan-digital.css',
                'resources/css/detail-buku.css',
                'resources/css/halaman-baca-buku.css',
                'resources/css/siswa/siswa.css',
                'resources/css/siswa/data-diri.css',
                'resources/css/siswa/absensi.css',
                'resources/css/siswa/tagihan-spp.css',
                'resources/css/operator/operator.css',
                'resources/css/humas/humas.css',
                'resources/css/humas/dashboard.css',
                'resources/css/humas/layout.css',
                'resources/css/humas/beranda.css',
                'resources/css/humas/profil.css',
                'resources/css/humas/beranda-tweaks.css',
                'resources/css/humas/guru.css',
                'resources/css/humas/guru-create.css',
                'resources/css/humas/guru-tweaks.css',
                'resources/css/humas/program.css',
                'resources/css/humas/prestasi.css',
                'resources/css/humas/prestasi-tweaks.css',
                'resources/css/humas/kontak.css',
                'resources/css/keuangan/keuangan.css',
                'resources/css/keuangan/revisi.css',
                'resources/css/perpustakaan/perpustakaan.css',
                'resources/js/app.js',
                'resources/js/frontend-demo.js',
                'resources/js/humas.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
