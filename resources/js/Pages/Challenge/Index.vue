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
                        {{ item.creator }}
                        {{ item.duration }}
                        {{ item.cover_id }}
                        <button @click="listen(item.id, item.duration)">ASCOLTA</button>
                        <input type="range" min="0" max="10" id="vota" value="axiom" name="vota" disabled/><label for="vota">VOTA</label>
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
// TODO: track put track, list, track in election
const sodium = require("libsodium-wrappers")

export default defineComponent({
    components: {
        AppLayout,
    },
    data: () => ({
        nineRandomTracks: null,
        serverPublicKey: "",
        userSecretKey: "",
    }),
    mounted () {
        axios
            .get(route("authenticated.challenge.get.challenge_nine_random_tracks"))
            .then(response => (this.nineRandomTracks = response.data.tracks))
        axios
            .get(route("authenticated.settings.get.settings_server_public_key"))
            .then(response => (this.serverPublicKey = response.data.key))
        axios
            .get(route("authenticated.settings.get.settings_user_secret_key"))
            .then(response => (this.userSecretKey = response.data.key))
    },
    methods: {
        async refreshTracks() {
            axios({
                method: "post",
                url: route("authenticated.challenge.post.challenge_refresh_nine_random_tracks"),
                responseType: "json"
            })
                .then(response => (this.nineRandomTracks = response.data.tracks))
                .catch(error => (new Toaster({
                    message: error.response.data.message,
                    code: error.response.data.code
                })));
        },
        durationToMilliseconds(duration) {
            let timeArr = duration.split(':'),
                seconds = 0, multiplier = 1000;

            while (timeArr.length > 0) {
                seconds += multiplier * parseInt(timeArr.pop(), 10);
                multiplier *= 60;
            }

            return seconds;
        },
        async requestVotePermission(id) {
            axios
                .post(route("authenticated.vote.post.vote_request_permission", id))
                .then(response => {
                    console.log(response.data);
                })
                .catch(error => (new Toaster({
                    message: error.response.data.message,
                    code: error.response.data.code
                })));
        },
        async listen(id, duration) {
            axios
                .get(route("authenticated.listening_request.get.listening_request_to_track_in_challenge", id))
                .then(response => {
                    let [encrypted, nonce] = response.data.split(":");
                    let message = sodium.crypto_box_open_easy(
                        sodium.from_hex(encrypted),
                        sodium.from_hex(nonce),
                        sodium.from_hex(this.serverPublicKey),
                        sodium.from_hex(this.userSecretKey),
                    );
                    // play the audio from the decoded message
                    new Audio('data:audio/ogg;base64,' + sodium.to_string(message)).play();
                    // request vote permission 10 seconds after the track has finished playing
                    setTimeout(() => this.requestVotePermission(id), this.durationToMilliseconds(duration) + 10000)
                })
                .catch(error => (new Toaster({
                    message: error.response.data.message,
                    code: error.response.data.code
                })));
        }
    }
})
// sodium native https://github.com/sodium-friends/sodium-native to decrypt listening_request stream
</script>
