require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import apolloProvider from './bootApollo'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        const application = createApp({render: () => h(app, props)})

        return application.use(plugin)
            .use(apolloProvider)
            .mixin({methods: {route}})
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
