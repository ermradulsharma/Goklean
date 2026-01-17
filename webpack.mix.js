const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js').vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .styles([
        'node_modules/primevue/resources/themes/saga-blue/theme.css',
        'node_modules/primevue/resources/primevue.min.css',
        'node_modules/primeicons/primeicons.css',
        'node_modules/vue3-perfect-scrollbar/dist/style.css',
        'node_modules/vue-good-table-next/dist/vue-good-table-next.css',
        'node_modules/v-calendar/dist/style.css',
        'node_modules/vue-select/dist/vue-select.css'
    ], 'public/css/vendor.css')
    .webpackConfig({
        plugins: [
            new (require('webpack')).DefinePlugin({
                __VUE_OPTIONS_API__: true,
                __VUE_PROD_DEVTOOLS__: false,
                __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: false,
            }),
        ],
        resolve: require('./webpack.config').resolve, // Preserve existing resolve config if any
    })
    .copy('node_modules/primeicons/fonts', 'public/css/fonts');

if (mix.inProduction()) {
    mix.version();
}
