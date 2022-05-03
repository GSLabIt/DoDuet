<template>
    <div v-if="hasErrors">
        <div class="font-medium text-red-600 bg-gray-200/70 p-4 rounded-md">Whoops! Something went wrong.</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600  bg-gray-200/70 p-2 rounded-md">
            <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
        </ul>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'

    export default defineComponent({
        computed: {
            errors() {
                return this.$page.props.errors
            },

            hasErrors() {
                return Object.keys(this.errors).length > 0;
            },
        }
    })
</script>
