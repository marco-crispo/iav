/// <reference types="vitest" />

import { defineConfig, loadEnv } from 'vite';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig(({ mode, command }) => {
    // Carica variabili .env (VITE_* inclusi)
    const env = loadEnv(mode, process.cwd(), 'VITE_');

    // La base deve seguire il nome della repo su GitHub Pages
    const base = command === 'build'
        ? env.VITE_APP_BASE_ROUTE || '/'
        : '/';

    return {
        plugins: [vue()],
        base: base,
        build: {
            outDir: path.resolve(__dirname, '../public/app'),
            emptyOutDir: true,
        },
        resolve: {
            alias: {
                '@': path.resolve(__dirname, 'src'),
            },
        },
        define: {
            'process.env': {},
        },
        // server: {
        //     host: '0.0.0.0',
        //     port: 8100,
        // },
    };
});
