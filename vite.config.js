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
                'resources/css/keuangan/keuangan.css',
                'resources/css/perpustakaan/perpustakaan.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
