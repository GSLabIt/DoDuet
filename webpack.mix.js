/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

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

/* Allow multiple Laravel Mix applications*/
require('laravel-mix-merge-manifest');

mix.ts('resources/js/app.js', 'public/js').vue()
    .copyDirectory(
        'resources/assets/webfonts',
        'public/webfonts'
    )
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .webpackConfig(require('./webpack.config'))
    .extract([
        "vue",
        "boxicons",
        "cleave.js","luxon",
        "tippy.js",
        "toastify-js",
        "@inertiajs/inertia",
        "@inertiajs/inertia-vue3",
        "@inertiajs/progress",
        "axios",
        "lodash",
    ])
    .mergeManifest();

if (mix.inProduction()) {
    mix.version();
}
else {
    mix.browserSync('localhost:8000');
}

mix.disableNotifications();
