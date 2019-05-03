let mix = require('laravel-mix');
require('dotenv').config();
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

resolve: {
    modules: [
        path.resolve('./node_modules')
    ]
}

let resource_url = process.env.MIX_RESOURCE_ROOT || '';
mix.setResourceRoot(resource_url);
mix.js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/eqbids.js', 'public/js')
    .js('resources/assets/js/cart.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .copyDirectory('resources/assets/images','public/images');

mix.copy('node_modules/chart.js/dist/Chart.min.js','public/js');

mix.copyDirectory('resources/assets/css/','public/css/')
    .copyDirectory('resources/assets/js/revolution','public/js/revolution')
    .copyDirectory('resources/assets/js/plugins','public/js/plugins')
    .copyDirectory('resources/assets/css/fonts/font-awesome/','public/');

