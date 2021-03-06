const mix = require('laravel-mix');
module.exports = {
    mode: "production"
}
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
    .js('resources/js/cerdascermat','public/js/webapp')
    .js('resources/js/cerdascermatfree','public/js/webapp')
    .js('resources/js/cerdascermatstart','public/js/webapp')
    .js('resources/js/cerdascermathistory','public/js/webapp')
    .sass('resources/sass/app.scss', 'public/css');
