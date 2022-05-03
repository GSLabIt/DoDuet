<!--
  - Copyright (c) 2022 - Do Group LLC - All Right Reserved.
  - Unauthorized copying of this file, via any medium is strictly prohibited
  - Proprietary and confidential
  - Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
  -->

<template>
    <app-layout title="Lyric creation">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create lyric
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="submit">
                        <label for="name">Name:</label>
                        <input id="name" v-model="form.name"/>
                        <label for="lyric">Lyric:</label>
                        <textarea id="lyric" v-model="form.lyric"/>
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
                lyric: null
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
                .post(route("authenticated.lyric.post.lyric_create"), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(() => {
                    new Toaster({
                        message: 'Lyric creata',
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
