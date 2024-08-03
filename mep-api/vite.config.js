import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy'

export default defineConfig({
    plugins: [
        laravel([
            'resources/css/app.css',
            'resources/js/app.js',
        ]),
        viteStaticCopy({
            targets: [
                {
                    src: 'node_modules/push.js/bin/push.min.js',
                    dest: 'js'
                },
                {
                    src: 'resources/js/*',
                    dest: 'js'
                },
                {
                    src: 'resources/css/*',
                    dest: 'css'
                },
            ]
        })
    ],
    resolve: {
        alias: {
            '@': '/resources/js'
        }
    }
})
