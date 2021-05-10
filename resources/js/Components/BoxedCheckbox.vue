<template>
    <div class="border border-blue-200 p-3 rounded-lg flex items-center text-gray-800 mx-2 my-2 cursor-pointer
        hover:bg-blue-200 transition-all duration-500" :class="classes" @click="fireClick">
        <input :id="id" class="mr-3" :class="{'hidden': hideRadio}" type="checkbox" :name="name" v-model="model"
               :value="value" ref="checkbox">
        <label v-if="!flex" :for="id">
            <slot></slot>
        </label>
        <div v-else class="flex items-center w-full">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "BoxedCheckbox",
    props: {
        name: String,
        modelValue: String,
        value: String,
        hideRadio: {
            type: Boolean,
            default: false
        },
        flex: {
            type: Boolean,
            default: false
        }
    },
    emits: ["update:modelValue"],
    methods: {
        fireClick() {
            this.$refs.checkbox.click()
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
                "bg-blue-200": typeof(this.modelValue) !== "object" ? this.value === this.modelValue : this.modelValue.includes(this.value)
            }
        }
    },
}
</script>

<style scoped>

</style>
