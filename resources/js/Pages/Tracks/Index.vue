<template>
    <app-layout title="Challenge">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tracks
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-for="(track, index) of ownedTracks">
                        {{ track.name }}
                        {{ track.description }}
                        {{ track.duration }}
                        {{ track.nft_id }}
                        <button @click="update(index)">MODIFICA</button>
                        <button @click="participateToCurrentChallenge(track.id, index)" :disabled="this.inChallenge[index]">PARTECIPA CHALLENGE</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="modalShow">
            <form @submit.prevent="submit">
                <label for="name">Name:</label>
                <input id="name" v-model="form.name"/>
                <label for="description">Description:</label>
                <textarea id="description" v-model="form.description"></textarea>
                <label for="cover">Cover:</label>
                <select id="cover" type="select" v-model="form.cover_id"></select>
                <label for="lyric">Lyric:</label>
                <select id="lyric" type="select" v-model="form.lyric_id"></select>
                <label for="album">Album:</label>
                <select id="album" type="select" v-model="form.album_id"></select>
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
// TODO: list track (id, name, description, nft_id, duration) + modal update + track in election, another page for upload

export default defineComponent({
    components: {
        AppLayout,
    },
    data() {
        return {
            ownedTracks: null,
            form: useForm({
                name: null,
                description: null,
                cover_id: null,
                album_id: null,
                lyric_id: null,
            }),
            modalShow: false,
            modalIndex: null,
            inChallenge: []
        }
    },
    mounted() {
        axios
            .get(route("authenticated.track.get.track_owned", this.$attrs["user"].id))
            .then(response => {
                let owned = response.data.tracks
                this.ownedTracks = owned
                this.inChallenge = new Array(owned.length).fill(false);
                    axios
                    .get(route("authenticated.challenge.get.challenge_owned_tracks"))
                    .then(response => {
                        let ids = response.data.tracks;
                        owned.forEach(
                            (track, index) => {
                                if (ids.includes(track.id)) {
                                    this.inChallenge[index] = true;
                                }
                            }
                        )
                    })
            })
    },
    methods: {
        async participateToCurrentChallenge (id, index) {
            axios
                .post(route("authenticated.challenge.post.challenge_track_participate_in_current", id))
                .then(response => {
                    this.inChallenge[index] = response.data.success;
                    new Toaster({
                        message: "La tua canzone sta partecipando alla challenge corrente!",
                        title: "Successo",
                        type: "success",
                    });
                })
                .catch(error => (new Toaster({
                    message: error.response.data.message,
                    code: error.response.data.code
                })));
        },
        update (index) {
            let track = this.ownedTracks[index];
            this.form = useForm({
                name: track.name,
                description: track.description,
                cover_id: track.cover_id,
                album_id: track.album_id,
                lyric_id: track.lyric_id,
            });
            this.modalIndex = index;
            this.modalShow = true;
        },
        submit() {
            axios
                .put(route("authenticated.track.put.track_update", this.ownedTracks[this.modalIndex].id), this.form)
                .then(response => {
                    this.ownedTracks[this.modalIndex] = response.data.track;
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
