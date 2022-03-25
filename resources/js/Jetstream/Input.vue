<template>
    <input class="text-gray-100 border border-b-2 border-b-gray-300 focus:border-b-0 focus:border-fuchsia-400 focus:ring focus:ring-fuchsia-200 focus:ring-opacity-50 rounded-md shadow-sm bg-transparent" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input">
</template>

<script>
    import { defineComponent } from 'vue'

    export default defineComponent({
        props: ['modelValue'],

        emits: ['update:modelValue'],

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    })
</script>
