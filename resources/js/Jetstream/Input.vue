<template>
    <textarea v-if="as === 'textarea'"
              class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
              :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input"></textarea>
    <cleave v-else-if="as === 'cleave'"
            class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            v-model="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input" :options="options"></cleave>
    <input v-else
           class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
           :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input">
</template>

<script>
export default {
    props: ['modelValue', 'as', 'options'],
    emits: ['update:modelValue'],

    methods: {
        focus() {
            this.$refs.input.focus()
        }
    }
}
</script>

