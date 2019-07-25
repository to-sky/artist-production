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

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .copyDirectory('resources/images', 'public/images')
   .copyDirectory('resources/js/zebra', 'public/js');

mix.sass('resources/sass/reset.scss', 'public/css');

/*
 |--------------------------------------------------------------------------
 | Hall Widget Asset Management
 |--------------------------------------------------------------------------
 */

mix.js('resources/widget/main.js', 'public/widget/js/app.js');


/*
 |--------------------------------------------------------------------------
 | Reports styles
 |--------------------------------------------------------------------------
 */

mix.sass('resources/sass/reports.scss', 'public/css');