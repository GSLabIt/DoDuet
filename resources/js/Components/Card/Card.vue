<template>
    <div :class="classes">
        <div v-if="!!$slots.title" class="w-full flex items-center text-xl">
            <slot name="title"></slot>
        </div>
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "Card",
    props: {
        colors: {
            type: String,
            default: "bg-brand-orange-200 text-gray-800",
        }
    },
    computed: {
        classes() {
            return `rounded-sm p-4 ${this.colors} shadow-md`
        }
    }
}
</script>

<style scoped>

</style>
