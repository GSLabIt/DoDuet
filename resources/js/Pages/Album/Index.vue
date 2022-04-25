<template>
    <app-layout title="Albums">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Albums
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" v-if="ownedAlbums">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-for="(album, index) of ownedAlbums">
                        {{ album.name }}
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
                <label for="description">Description:</label>
                <textarea id="description" v-model="form.description"/>
                <label for="cover">Cover:</label>
                <select id="cover" type="select" v-model="form.cover_id"></select>
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
            ownedAlbums: null,
            form: useForm({
                name: null,
                description: null,
                cover_id: null
            }),
            modalShow: false,
            modalIndex: null,
            inChallenge: []
        }
    },
    mounted() {
        axios
            .get(route("authenticated.album.get.album_owned", this.$attrs["user"].id))
            .then(response => {
                this.ownedAlbums = response.data.albums;
            });
    },
    methods: {
        update (index) {
            let album = this.ownedAlbums[index];
            this.form = useForm({
                name: album.name,
                description: album.description,
                cover_id: album.cover_id
            });
            this.modalIndex = index;
            this.modalShow = true;
        },
        submit() {
            axios
                .post(route("authenticated.album.post.album_update", this.ownedAlbums[this.modalIndex].id), this.form)
                .then(response => {
                    this.ownedAlbums[this.modalIndex] = response.data.album;
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
