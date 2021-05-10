<template>
    <inertia-link :href="href" :class="classes">
        <div v-if="!!$slots.title" class="w-full flex items-center text-xl">
            <slot name="title"></slot>
        </div>
        <slot></slot>
    </inertia-link>
</template>

<script>
export default {
    name: "InertiaCard",
    props: {
        href: {
            type: String,
            required: true
        },
        colors: {
            type: String,
            default: "bg-gray-50 text-gray-800",
        }
    },
    computed: {
        classes() {
            return `rounded-sm p-4 ${this.colors} shadow-lg`
        }
    }
}
</script>

<style scoped>

</style>
