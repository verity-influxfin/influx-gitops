const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/frontdesk/layout.js','public/js/web.js')
    .js('resources/js/backend/layout.js','public/js/backend.js')
    .sass('resources/scss/frontdesk/layout.scss','public/css/web.css')
    .sass('resources/scss/backend/layout.scss','public/css/backend.css');
