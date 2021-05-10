<template>
    <li class="flex col-span-2 bg-gray-50 p-2 hover:bg-gray-100 transition-all duration-300">
        <span>
            <slot name="title"></slot>
        </span>
        <span :class="valueClasses">
            <slot></slot>
        </span>
    </li>
</template>

<script>
export default {
    name: "CardListItem",
    props: {
        color: {
            type: String,
            default: "text-green-500"
        }
    },
    computed: {
        valueClasses() {
            return `ml-auto ${this.color}`
        }
    }
}
</script>

<style scoped>

</style>
