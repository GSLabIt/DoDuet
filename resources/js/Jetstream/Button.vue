<template>
    <button :type="type" class="w-full inline-flex justify-center items-center px-4 py-2 bg-[#FF46FF] border border-transparent rounded-md
    font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#cc38cc] active:bg-[#802380] focus:outline-none
    focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
        <slot></slot>
    </button>
</template>

<script>
    import { defineComponent } from 'vue'

    export default defineComponent({
        props: {
            type: {
                type: String,
                default: 'submit',
            },
        }
    })
</script>
