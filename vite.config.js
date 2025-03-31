import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin'
import path from 'path';
import react from '@vitejs/plugin-react'
import findMingles from './vendor/ijpatricio/mingle/resources/js/autoImport.js'
import tailwindcss from '@tailwindcss/vite'

const mingles = findMingles('resources/js')
// Optional: Output the mingles to the console, for a visual check
console.log('Auto-importing mingles:', mingles)

export default defineConfig({
    server: {
        cors: true, // Enable CORS
        origin: 'http://pet-clinic.test', // Specify Laravel's origin
        hmr: {
            host: 'localhost', // Ensure HMR works properly
        },
    },
    resolve: {
        alias: {
            "@mingle": path.resolve(__dirname, "/vendor/ijpatricio/mingle/resources/js"),
        },
    },
    plugins: [
        //tailwindcss('resources/css/filament/admin/tailwind.config.js'),
       //tailwindcss(),
        react(),
        laravel({
            input: ['resources/css/app.css',
                'resources/js/app.js',
                ...mingles,
                'resources/css/filament/admin/theme.css',
                'resources/css/filament/admin/themes/pet-clinic.css',
            ],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
            ],
        }),
    ],
});
