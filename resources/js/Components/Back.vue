<template>
    <inertia-link :href="route(routeName)">
        <span class="mr-4 fas fa-chevron-left p-4"></span>
    </inertia-link>
</template>

<script>
export default {
    name: "Back",
    props: {
        routeName: {
            type: String,
            required: true
        }
    }
}
</script>

<style scoped>

</style>
