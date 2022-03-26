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
                        <button @click="update(track.id)">MODIFICA</button>
                        <button @click="participateToCurrentChallenge(track.id, index)" :disabled="this.inChallenge[index]">PARTECIPA CHALLENGE</button>
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
// TODO: list track (id, name, description, nft_id, duration) + modal update + track in election, another page for upload

export default defineComponent({
    components: {
        AppLayout,
    },
    data: () => ({
        ownedTracks: null,
        inChallenge: []
    }),
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
                .post(route("authenticated.challenge.post.challenge_track_participate_in_current", id)) // TODO:
                .then(response => {
                    this.inChallenge[index] = response.data.success;
                    console.log(response.data);
                })
                .catch(error => (new Toaster({
                    message: error.response.data.message,
                    code: error.response.data.code
                })));
        }
    }
})
</script>
