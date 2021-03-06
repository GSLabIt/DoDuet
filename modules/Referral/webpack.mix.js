/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

const dotenvExpand = require('dotenv-expand');
dotenvExpand(require('dotenv').config({ path: '../../.env'/*, debug: true*/}));

const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../public')
    .ts(__dirname + '/resources/assets/js/app.js', 'js/referral.js')
    .vue()
    .postCss(__dirname + '/resources/assets/css/app.css', 'css/referral.css', [
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .extract(["vue"])
    .webpackConfig(require('./webpack.config'))
    .mergeManifest();

if (mix.inProduction()) {
    mix.version();
}
