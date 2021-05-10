<template>
    <div class="border border-blue-200 p-3 rounded-lg flex items-center text-gray-800 mx-2 my-2 cursor-pointer
        hover:bg-blue-200 transition-all duration-500" :class="classes" @click="fireClick">
        <input :id="id" class="mr-3" :class="{'hidden': hideRadio}" type="radio" :name="name" v-model="model"
               :value="value" ref="radio">
        <label :for="id">
            <slot></slot>
        </label>
    </div>
</template>

<script>
export default {
    name: "BoxedRadio",
    props: {
        name: String,
        modelValue: String,
        value: String,
        hideRadio: {
            type: Boolean,
            default: false
        },
    },
    emits: ["update:modelValue"],
    methods: {
        fireClick() {
            this.$refs.radio.click()
        },
    },
    computed: {
        model: {
            get() {
                return this.modelValue
            },
            set(value) {
                this.$emit('update:modelValue', value)
            }
        },
        id() {
            return `${this.name}-${this.value}`
        },
        classes() {
            return {
                "bg-blue-200": this.value === this.modelValue
            }
        }
    },
}
</script>

<style scoped>

</style>
