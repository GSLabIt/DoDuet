<template>
    <app-layout title="Challenge">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Current Challenge
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-for="(e,id) of nineRandomTracks">
                        ID
                        TITOLO
                        BOTTONE IGNORANTE CON LISTENING
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import {defineComponent} from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import {GET_ALL_TRACKS_IN_CHALLENGE, GET_SERVER_PUBLIC_KEY, GET_USER_SECRET_KEY} from "../../GraphQL";

export default defineComponent({
    components: {
        AppLayout,
    },
    data: () => ({
        nineRandomTracks: [1, 2],
        serverPublicKey: "",
        userSecretKey: ""
    }),
    apollo: {
        serverPublicKey: {
            query: GET_SERVER_PUBLIC_KEY,
            update: data => data.serverPublicKey
        },
        userSecretKey: {
            query: GET_USER_SECRET_KEY,
            update: data => data.userSecretKey
        }
    }
})
</script>
