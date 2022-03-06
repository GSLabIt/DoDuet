<template>
    <app-layout title="Challenge">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Current Challenge
            </h2>

            <button @click="refreshTracks">
                REFRESH
            </button>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-for="item of nineRandomTracks">
                        {{ item.name }}
                        {{ item.creator_id }}
                        {{ item.duration }}
                        {{ item.cover_id }}
                        <button @click="listen(item.id)">ASCOLTA</button>
                        <input type="range" min="0" max="10" id="vota" value="axiom" name="vota"/><label for="vota">VOTA</label>
                    </div>
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
    data: () => ({
        nineRandomTracks: null,
        serverPublicKey: "",
        userSecretKey: "",
        listening: null
    }),
    mounted () {
        axios
            .get(route("authenticated.challenge.get.challenge_nine_random_tracks"))
            .then(response => (this.nineRandomTracks = response.data.tracks))
    },
    methods: {
        async refreshTracks() {
            axios({
                method: "post",
                url: route("authenticated.challenge.post.challenge_refresh_nine_random_tracks"),
                responseType: "json"
            })
                .then(response => (this.nineRandomTracks = response.data.tracks))
                .catch(error => (new Toaster({message: error.response.data.message})));// TODO: with new middleware also return code
        },
        async listen(id) {
            axios({
                method: "get",
                url: route("authenticated.listening_request.get.listening_request_to_track_in_challenge", "wrong-id"),
                responseType: "stream"
            })
                .then(function (response) {
                    /*let sodium = require('sodium-native');
                    sodium.crypto_box_open_easy(response.data.pipe )*/
                    console.log(response.data)
                    this.listening = response.data
                })
                .catch(error => (new Toaster({message: error.response.data.message})));// TODO: with new middleware also return code
        }
    }
})
// sodium native https://github.com/sodium-friends/sodium-native to decrypt listening_request stream
</script>
