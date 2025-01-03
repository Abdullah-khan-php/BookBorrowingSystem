import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/bootstrap.min.css',
                'resources/js/bootstrap.bundle.min.js',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        sourcemap: false, // Disable source maps for production
    },
    server: {
        sourcemap: false, // Disable source maps in development
    },
    resolve: {
        alias: {
            $: 'jquery',
            jQuery: 'jquery',
        },
    },
});
