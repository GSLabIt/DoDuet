<!--
  - Copyright (c) 2022 - Do Group LLC - All Right Reserved.
  - Unauthorized copying of this file, via any medium is strictly prohibited
  - Proprietary and confidential
  - Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
  -->

<template>
    <app-layout title="Album creation">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create album
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="submit">
                        <label for="name">Name:</label>
                        <input id="name" v-model="form.name"/>
                        <label for="description">Description:</label>
                        <textarea id="description" v-model="form.description"/>
                        <label for="cover">Cover:</label>
                        <select id="cover" type="select" v-model="form.cover_id"></select>
                        <button type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import {defineComponent} from 'vue'
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
                cover_id: null
            }
        }
    },
    methods: {
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
                .post(route("authenticated.album.post.album_create"), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(() => {
                    new Toaster({
                        message: 'Album creato',
                        type: "success"
                    });
                })
                .catch(error => (new Toaster({
                    message: error.response.data.message,
                    code: error.response.data.code
                })));
        }
    }
})
</script>
