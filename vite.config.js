import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        port: process.env.VITE_PORT || 5173, // Usa el puerto VITE_PORT del entorno, o 5173 si no está definido
    },
});