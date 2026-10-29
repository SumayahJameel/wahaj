import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Inter', {
                    weights: [400, 500, 600, 700],
                    preload: [{ weight: 400 }, { weight: 600 }],
                }),
                bunny('Playfair Display', {
                    weights: [500, 600],
                    preload: false,
                }),
                bunny('Noto Sans Arabic', {
                    weights: [400, 500, 600, 700],
                    subsets: ['arabic'],
                    preload: false,
                }),
                bunny('Amiri', {
                    weights: [400, 700],
                    subsets: ['arabic', 'latin'],
                    preload: false,
                }),
                bunny('Aref Ruqaa', {
                    weights: [400, 700],
                    subsets: ['arabic'],
                    preload: false,
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
