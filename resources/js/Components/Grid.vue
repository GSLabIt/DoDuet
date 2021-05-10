<template>
    <div :class="classes">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "Grid",
    props: {
        cols: {
            type: Number,
            default: 3
        },
        gap: {
            type: String,
            default: "md",
            validator(v) {
                return ["xs", "sm", "md", "lg", "xl", "2xl", "3xl", "4xl", "none"].includes(v)
            }
        }
    },
    computed: {
        classes() {
            return `grid ${this.columns} ${this.gaps}`
        },
        columns() {
            switch (this.cols) {
                case 1:
                    return "grid-cols-1"
                case 2:
                    return "grid-cols-2"
                case 3:
                    return "grid-cols-3"
                case 4:
                    return "grid-cols-4"
                case 5:
                    return "grid-cols-5"
                case 6:
                    return "grid-cols-6"
                case 7:
                    return "grid-cols-7"
                case 8:
                    return "grid-cols-8"
                case 9:
                    return "grid-cols-9"
                case 10:
                    return "grid-cols-10"
                case 11:
                    return "grid-cols-11"
                case 12:
                    return "grid-cols-12"
            }
        },
        gaps() {
            switch (this.gap) {
                case "xs":
                    return "gap-1"
                case "sm":
                    return "gap-2"
                case "md":
                    return "gap-4"
                case "lg":
                    return "gap-6"
                case "xl":
                    return "gap-8"
                case "2xl":
                    return "gap-12"
                case "3xl":
                    return "gap-16"
                case "4xl":
                    return "gap-20"
                case "none":
                    return ""
            }
        }
    }
}
</script>

<style scoped>

</style>
