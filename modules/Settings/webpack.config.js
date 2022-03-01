/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

const path = require('path');

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('../../resources/js'),
            'current': path.resolve('resources/assets/js'),
        },
    },
};
