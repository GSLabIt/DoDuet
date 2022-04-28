/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

import "../assets/pro.css"
import "../assets/pro-v5-font-face.css"
import "../assets/pro-v4-shims.css"
import "../assets/pro-v4-font-face.css"

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

/*
|---------------------------------------------
| Import modules app.js
|---------------------------------------------
 */
[
    "Referral"
].forEach(module_name => require(`~/${module_name}/resources/assets/js/app.js`))

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        if(name.toString().includes("::")) {
            let [module_name, view] = name.toString().split("::")
            return require(`~/${module_name}/resources/assets/js/pages/${view}.vue`)
        }
        return require(`./Pages/${name}.vue`)
    },
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
