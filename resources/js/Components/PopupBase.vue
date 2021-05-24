<template>
    <div class="fixed inset-0 flex items-center justify-center w-full h-full bg-gray-800 bg-opacity-50 transition-all duration-500"
         :class="wrapperClass" @click="close">
        <div class="bg-gray-100 w-1/2 p-4 rounded-lg transform-gpu transition-all duration-500 delay-300" :class="containerClass"
            @click.stop="">
            <div class="text-xl font-semibold flex items-center">
                <h2>{{ title }}</h2>
                <div class="p-2 flex items-center justify-center ml-auto cursor-pointer text-3xl" @click="close">
                    <i class='bx bx-x'></i>
                </div>
            </div>
            <div class="my-4">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PopupBase",
    emits: ["close", "update:open"],
    props: {
        open: {
            type: Boolean,
            default: false
        },
        title: {
            type: String,
            require: true,
        }
    },
    methods: {
        close() {
            this.$emit('close')
            this.$emit('update:open', !this.open)
        }
    },
    computed: {
        wrapperClass() {
            return {
                "opacity-0 z-[-10]": !this.open,
                "z-50": this.open
            }
        },
        containerClass() {
            return {
                "opacity-0 -translate-y-full": !this.open
            }
        }
    },
}
</script>

<style scoped>

</style>
