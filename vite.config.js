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
                'resources/css/siswa/siswa.css',
                'resources/css/siswa/data-diri.css',
                'resources/css/siswa/absensi.css',
                'resources/css/siswa/tagihan-spp.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
