<template>
    <div class="relative h-full min-h-96 w-full flex text-primary-200 flex-col relative my-20">
        <div v-if="picture" class="absolute w-full h-full inset-0" :class="effect">
            <img :src="picture" class="object-cover w-full h-full max-h-screen max-w-screen absolute inset-0" :alt="picture">
            <div class="w-full h-full absolute inset-0" :class="overlay"></div>
        </div>
        <container :class="{'py-20 mb-6': !noModifier}">
            <h1 v-if="$slots.title" class="text-5xl font-semibold mb-3">
                <template v-if="centered">
                    <div class="text-center mb-8">
                        <slot name="title"></slot>
                    </div>
                </template>
                <slot v-else name="title"></slot>
            </h1>
            <h2 v-if="$slots.subtitle" class="text-2xl">
                <template v-if="centered">
                    <div class="text-3xl text-center mb-16">
                        <slot name="subtitle"></slot>
                    </div>
                </template>
                <slot v-else name="subtitle"></slot>
            </h2>
            <slot></slot>
        </container>
        <inertia-link v-if="$slots.button" :href="href"
                      class="absolute bottom-4 md:bottom-12 left-1/2 transform -translate-x-1/2 rounded-full px-8 py-3 md:w-auto
                        text-primary-100 bg-purple-200 transition-all duration-300 transform-gpu hover:shadow-md hover:scale-105
                        text-lg font-semibold w-5/6 text-center">
            <slot name="button"></slot>
        </inertia-link>
    </div>
</template>

<script>
import Container from "./Container";
export default {
    name: "Hero",
    components: {Container},
    props: {
        overlay: {
            type: String,
            default: "bg-white bg-opacity-50",
        },
        picture: {
            type: String,
            default: undefined,
            validator(v) {
                return v.startsWith("https://") || v.startsWith("/")
            }
        },
        href: {
            type: String,
            default: ""
        },
        noModifier: {
            type: Boolean,
            default: false
        },
        effect: {
            type: String,
            default: ""
        },
        centered: {
            type: Boolean,
            default: false,
        }
    },
    computed: {
        style() {
            return `background-image: ${this.gradient}${this.picture ? `, url(${this.picture});` : ""}`
        }
    }
}
</script>

<style scoped>

</style>
