import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [tailwindcss()],
    build: {
        outDir: 'docs/public/build',
        cssCodeSplit: false,
        rollupOptions: {
            input: 'docs/resources/js/app.js',
            output: {
                entryFileNames: 'app.js',
                assetFileNames: 'app[extname]',
                chunkFileNames: 'chunks/[name]-[hash].js'
            },
        },
    },
})