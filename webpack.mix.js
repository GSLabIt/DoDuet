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

mix.ts('resources/js/app.js', 'public/js').vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .webpackConfig(require('./webpack.config'))
    .extract([
        "vue",
        "@apollo/client",
        "@vue/apollo-option",
        "boxicons",
        "cleave.js",
        "graphql",
        "graphql-tag",
        "luxon",
        "tippy.js",
        "toastify-js",
        "@inertiajs/inertia",
        "@inertiajs/inertia-vue3",
        "@inertiajs/progress",
        "axios",
        "lodash",
    ]);

if (mix.inProduction()) {
    mix.version();
}
else {
    mix.browserSync('localhost:8000');
}

mix.disableNotifications();
