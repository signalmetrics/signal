import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                './vendor/signalmetrics/signal/resources/css/app.css',
                './vendor/signalmetrics/signal/resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: path.join(__dirname, "./public/build/signalmetrics/"),   
    },
});
