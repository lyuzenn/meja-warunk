import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/sounds/notification.mp3', // <-- TAMBAHKAN BARIS INI
            ],
            refresh: true,
        }),
    ],
});
