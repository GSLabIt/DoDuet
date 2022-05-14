<template>
    <app-layout title="Challenge">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Current Challenge
                </h2>

                <button @click="refreshTracks">
                    REFRESH
                </button>
            </div>
        </template>

        <div>
            <!--banner-->
            <weekly-election-banner class="my-5 max-w-7xl mx-auto sm:pl-3 lg:pl-4"></weekly-election-banner>

            <!--song grid-->
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 xl:grid-cols-3
                    gap-8">
                <template v-for="(item, index) of nineRandomTracks">
                    <song-card
                        :cover_id="item.cover_id"
                        :creator="item.creator"
                        :duration="item.duration"
                        :name="item.name"
                        :vote="4"
                        :id="item.id"
                        @play="listen(item.id, index)"
                    >
                    </song-card>
<!--                                        <button @click="listen(item.id, index)">ASCOLTA</button>
                                        <input type="range" min="0" max="10" id="vota" v-model.number=this.votes[index] name="vota"
                                               :disabled="!this.votable[index]"/>
                                        <label for="vota" @click="vote(item.id, index)">VOTA</label>-->
                </template>
            </div>
        </div>
    </app-layout>
</template>

<script>
import {defineComponent} from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import Toaster from "../../Composition/toaster";
import WeeklyElectionBanner from "@/Components/WeeklyElectionBanner";
import SongCard from "@/Components/SongCard";
import {MusicService} from "@/Services/MusicService";

const sodium = require("libsodium-wrappers")

export default defineComponent({
    components: {
        SongCard,
        WeeklyElectionBanner,
        AppLayout,
    },
    data: () => ({
        trackPlaying: false,
        nineRandomTracks: null,
        serverPublicKey: "",
        userSecretKey: "",
        votable: [],
        votes: []
    }),
    mounted() {
        axios
            .get(route("authenticated.challenge.get.challenge_nine_random_tracks"))
            .then(response => {
                this.votable = new Array(response.data.tracks.length).fill(false);
                this.votes = new Array(response.data.tracks.length).fill(5);
                this.nineRandomTracks = response.data.tracks
                response.data.tracks.forEach(
                    (track, index) => {
                        axios
                            .get(route("authenticated.challenge.get.challenge_track_listening_number_by_user_and_challenge", track.id))
                            .then(response => {
                                    this.votable[index] = response.data.listeningRequests > 0
                                    if (response.data.listeningRequests > 0) {
                                        axios
                                            .get(route("authenticated.challenge.get.challenge_track_vote_by_user_and_challenge", track.id))
                                            .then(response => this.votes[index] = response.data.vote || 5)
                                            .catch(error => console.log(error));
                                    }
                                }
                            );
                    }
                );
            })
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
                .then(response => {
                    this.nineRandomTracks = response.data.tracks
                    this.votable = new Array(response.data.tracks.length).fill(false);
                })
                .catch(error => (new Toaster({
                    message: error.response.data.message,
                    code: error.response.data.code
                })));
        },
        async requestVotePermission(id, index) {
            axios
                .post(route("authenticated.vote.post.vote_request_permission", id))
                .then(response => {
                    this.votable[index] = true;
                    console.log(response.data);
                })
                .catch(error => (new Toaster({
                    message: error.response.data.message,
                    code: error.response.data.code
                })));
        },
        async listen(id, index) {
            axios
                .get(route("authenticated.listening_request.get.listening_request_to_track_in_challenge", id))
                .then(response => {
                    let [encrypted, nonce] = response.data.split(":");
                    // decrypt the message
                    let message = sodium.crypto_box_open_easy(
                        sodium.from_hex(encrypted),
                        sodium.from_hex(nonce),
                        sodium.from_hex(this.serverPublicKey),
                        sodium.from_hex(this.userSecretKey),
                    );
                    // play the audio from the decoded message
                    let audio = new Audio('data:audio/ogg;base64,' + sodium.to_string(message));
                    audio.play();
                    // on audio end request vote permission
                    audio.onended = () => (this.requestVotePermission(id, index));
                })
                .catch(error => (new Toaster({
                    message: error.response.data.message,
                    code: error.response.data.code
                })));
        },
        async vote(id, index) {
            axios
                .post(route("authenticated.vote.post.vote_vote", [id, this.votes[index]]))
                .then(response => {
                    console.log(response.data);
                })
                .catch(error => (new Toaster({
                    message: error.response.data.message,
                    code: error.response.data.code
                })));
        }
    },
    created() {
        const musicService = new MusicService()
        musicService.onChange.subscribe((e)=>{
            console.log(e)
        })
        musicService.setIsPlaying(true)

        setTimeout(()=>{
            musicService.setIsPlaying(false)
        }, 5000)
    },
})
</script>
