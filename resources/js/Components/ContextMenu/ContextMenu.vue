<template>
    <div class="border bg-gray-100 text-gray-800 rounded-md z-50 absolute shadow-lg"
         :class="classes" :style="style">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "ContextMenu",
    props: {
        visible: {
            type: Boolean,
            default: false
        },
        id: {
            type: [Number, String],
            required: true
        },
        x: {
            type: Number,
            required: true
        },
        y: {
            type: Number,
            required: true
        }
    },
    emits: ["update:visible"],
    computed: {
        style() {
            return `left: ${this.x}px; top: ${this.y}px`
        },
        classes() {
            return {
                "hidden": !this.visible
            }
        }
    },
    created() {
        window.addEventListener('click', (e) => {
            if (!this.$el.contains(e.target)){
                this.$emit("update:visible", false)
            }
        })
    }
}
</script>

<style scoped>

</style>
