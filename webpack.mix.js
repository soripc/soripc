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

mix.js('qpos_frontend/js/app.js', 'public/js')
   .sass('qpos_frontend/sass/style.scss', 'public/css/app.css')
   .sass('qpos_frontend/sass/auth.scss', 'public/css/auth.css')
   .extract(['vue'])
   .version();

mix.webpackConfig({
    resolve: {
        alias: {
            '@components': path.resolve(__dirname, 'qpos_frontend/js/components'),
            '@views': path.resolve(__dirname, 'qpos_frontend/js/views/tenant'),
            '@helpers': path.resolve(__dirname, 'qpos_frontend/js/helpers'),
            '@mixins': path.resolve(__dirname, 'qpos_frontend/js/mixins'),
        }
    }
}).sourceMaps()

mix.disableSuccessNotifications();
