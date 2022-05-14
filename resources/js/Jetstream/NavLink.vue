<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Link,
        },
        props: ['href', 'active'],

        computed: {
            classes() {
                return this.active
                    ? 'inline-flex w-full items-center px-1 pt-1 text-md font-black leading-5 text-gray-200 transition bg-gray-800/60'
                    : 'inline-flex w-full items-center px-1 pt-1 text-md leading-5 text-gray-100 hover:text-gray-100 hover:bg-gray-800/10 focus:text-gray-100 transition'
            }
        }
    })
</script>
