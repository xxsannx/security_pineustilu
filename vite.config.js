import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Global CSS & JS (loaded on all pages)
                'resources/css/app.css',
                'resources/js/app.js',
                
                // Page-specific JS (loaded only on respective pages)
                'resources/js/pages/dashboard.js',
                'resources/js/pages/profile.js',
                'resources/js/pages/faq.js',
                'resources/js/pages/cerita.js',
                'resources/js/pages/pedoman.js',
                'resources/js/pages/aktivitas.js',
                'resources/js/pages/morikafe.js',
                'resources/js/pages/reschedule.js',
                'resources/js/pages/cancellation.js',
                'resources/js/pages/barang-tambahan.js',
                'resources/js/pages/area.js',
                'resources/js/pages/detail-pesanan.js',
                'resources/js/pages/cancel-booking-modal.js',
                'resources/js/pages/availability.js',
                'resources/js/pages/glamping-map.js',
                'resources/js/pages/navbar-menu.js',
                'resources/js/pages/reservasi-glamping.js',
                'resources/js/pages/reservasi-outbound.js',
                'resources/js/pages/order-details-modal.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
    },
    build: {
        // Use esbuild for minification (faster, built-in)
        minify: 'esbuild',
        // Split chunks for better caching
        rollupOptions: {
            output: {
                manualChunks(id) {
                    // Vendor chunk for node_modules
                    if (id.includes('node_modules')) {
                        return 'vendor';
                    }
                    // Shared utilities chunk
                    if (id.includes('resources/js/utils/') || id.includes('resources/js/components/')) {
                        return 'shared';
                    }
                },
            },
        },
        // Optimize CSS
        cssMinify: true,
        // Generate source maps only for development
        sourcemap: false,
    },
});