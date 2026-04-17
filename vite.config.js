import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/admin/common.js',
                'resources/js/admin/category/category-create.js',
                'resources/js/admin/category/category-detail.js'
            ],
            refresh: true,
        }),
    ],
});
