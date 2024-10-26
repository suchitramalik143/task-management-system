let mix = require('laravel-mix');


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
mix.js('resources/assets/admin/app.js', 'public/admin/js').vue()
    .sass('resources/assets/admin/app.scss', 'public/admin/css')
    .version();


mix.js('resources/assets/frontend/app.js', 'public/frontend/js').vue()
  .sass('resources/assets/frontend/app.scss', 'public/frontend/css')
  .version();

  mix.css('resources/css/app.css', 'public/css');
  mix.js('resources/js/app.js', 'public/js');
