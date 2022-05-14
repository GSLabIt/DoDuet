<!--
  - Copyright (c) 2022 - Do Group LLC - All Right Reserved.
  - Unauthorized copying of this file, via any medium is strictly prohibited
  - Proprietary and confidential
  - Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
  -->

<template>
    <app-layout title="Challenge">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Upload Track
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="submit">
                        <label for="name">Name:</label>
                        <input id="name" v-model="form.name"/>
                        <label for="description">Description:</label>
                        <textarea id="description" v-model="form.description"></textarea>
                        <label for="duration">Duration:</label>
                        <input id="duration" v-model="form.duration"/>
                        <label for="mp3">Audio File:</label>
                        <input id="mp3" type="file" @change="uploadChange"/>
                        <label for="cover">Cover:</label>
                        <select id="cover" type="select" v-model="form.cover_id"></select>
                        <label for="lyric">Lyric:</label>
                        <select id="lyric" type="select" v-model="form.lyric_id"></select>
                        <label for="album">Album:</label>
                        <select id="album" type="select" v-model="form.album_id"></select>
                        <button type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import {defineComponent} from 'vue'
import {useForm} from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Toaster from "../../Composition/toaster";

export default defineComponent({
    components: {
        AppLayout,
    },
    data() {
        return {
            form: {
                name: null,
                description: null,
                duration: null,
                mp3: null,
                cover_id: null,
                lyric_id: null,
                album_id: null
            }
        }
    },
    methods: {
        uploadChange(e){
            this.form.mp3 = e.target.files[0]
        },
        submit() {
            let formData = new FormData();
            Object.entries(this.form).forEach(
                ([key, value]) => {
                    if (value) {
                        formData.append(key, value);
                    }
                }
            );
            axios
                .post(route("authenticated.track.post.track_create"), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(() => (this.$inertia.visit(route("authenticated.tracks-index"))))
                .catch(error => (new Toaster({
                    message: error.response.data.message,
                    code: error.response.data.code
                })));
        }
    }
})
</script>
