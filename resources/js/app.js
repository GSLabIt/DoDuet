require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

import 'boxicons/css/animations.css'
import 'boxicons/css/transformations.css'
import 'boxicons/css/boxicons.min.css'
import Cleave from 'vue-cleave-component';
import Toastify from 'toastify-js'
import "toastify-js/src/toastify.css"

const el = document.getElementById('app');

const app = createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
})
    .mixin({ methods: { route } })
    .use(InertiaPlugin)
    .use(Cleave);

app.config.globalProperties.$toast = Toastify;
app.config.globalProperties.$http = axios;
app.mount(el);

InertiaProgress.init({ color: '#4B5563' });
