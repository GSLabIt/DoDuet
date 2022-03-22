<template>
    <app-layout title="Challenge">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tracks
            </h2>

            <template>
                <div class="container">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>File
                            <input type="file" id="file" ref="file" v-on:change="handleFileUpload()"/>
                        </label>
                        <button v-on:click="submitFile()">Submit</button>
                    </div>
                </div>
                <button @click="uploadTrack">
                    Upload a Track
                </button>
            </template>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-for="item of ownedTracks">
                        {{ item.name }}
                        {{ item.creator }}
                        {{ item.duration }}
                        {{ item.cover_id }}
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

export default defineComponent({
    components: {
        AppLayout,
    },
    data: () => ({
        ownedTracks: null,
    }),
    mounted () {
        axios
            .get(route("authenticated.track.get.track_owned", this.$attrs["user"].id))
            .then(response => (this.ownedTracks = response.data.tracks))
    },
    methods: {

    }
})
</script>
