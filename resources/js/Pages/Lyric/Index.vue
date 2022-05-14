<template>
    <app-layout title="Lyrics">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Lyrics
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" v-if="ownedLyrics">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-for="(lyric, index) of ownedLyrics">
                        {{ lyric.name }}
                        <button @click="update(index)">MODIFICA</button>
                    </div>
                </div>
            </div>
            <div v-else>
                NO LYRICS
            </div>
        </div>
        <div v-if="modalShow">
            <form @submit.prevent="submit">
                <label for="name">Name:</label>
                <input id="name" v-model="form.name"/>
                <label for="lyric">Lyric:</label>
                <textarea id="lyric" v-model="form.lyric"/>
                <button type="submit">Submit</button>
            </form>
        </div>
    </app-layout>
</template>

<script>
import {defineComponent} from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import Toaster from "../../Composition/toaster";
import {useForm} from "@inertiajs/inertia-vue3";

export default defineComponent({
    components: {
        AppLayout,
    },
    data() {
        return {
            ownedLyrics: null,
            form: useForm({
                name: null,
                lyric: null,
            }),
            modalShow: false,
            modalIndex: null,
            inChallenge: []
        }
    },
    mounted() {
        axios
            .get(route("authenticated.lyric.get.lyric_owned", this.$attrs["user"].id))
            .then(response => {
                this.ownedLyrics = response.data.lyrics;
            });
    },
    methods: {
        update (index) {
            let lyric = this.ownedLyrics[index];
            this.form = useForm({
                name: lyric.name,
                lyric: lyric.lyric
            });
            this.modalIndex = index;
            this.modalShow = true;
        },
        submit() {
            axios
                .post(route("authenticated.lyric.post.lyric_update", this.ownedLyrics[this.modalIndex].id), this.form)
                .then(response => {
                    this.ownedLyrics[this.modalIndex] = response.data.lyric;
                    this.modalShow = false;
                })
                .catch(error => (new Toaster({
                    message: error.response.data.message,
                    code: error.response.data.code
                })));
        }
    }
})
</script>
