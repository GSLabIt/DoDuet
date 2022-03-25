<template>
    <Link :href="'/'">
        <img src="../../assets/images/logo_white.svg" alt="logo">
    </Link>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Link,
        },
    })
</script>
