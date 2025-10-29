import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/site.css',
                'resources/css/auth.css',
                'resources/js/theme-auto.js',
                'resources/js/auto-dismiss.js',
                'resources/js/close-tab.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        sourcemap: false,
        minify: 'esbuild',
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        return 'vendor';
                    }
                },
            },
        },
    },
});
