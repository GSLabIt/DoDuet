<template>
    <app-layout title="Covers">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Covers
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" v-if="ownedCovers">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-for="(cover, index) of ownedCovers">
                        {{ cover.name }}
                        <button @click="update(index)">MODIFICA</button>
                    </div>
                </div>
            </div>
            <div v-else>
                NO COVERS
            </div>
        </div>
        <div v-if="modalShow">
            <form @submit.prevent="submit">
                <label for="name">Name:</label>
                <input id="name" v-model="form.name"/>
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
            ownedCovers: null,
            form: useForm({
                name: null,
            }),
            modalShow: false,
            modalIndex: null,
            inChallenge: []
        }
    },
    mounted() {
        axios
            .get(route("authenticated.cover.get.cover_owned", this.$attrs["user"].id))
            .then(response => {
                this.ownedCovers = response.data.covers;
            });
    },
    methods: {
        update (index) {
            let cover = this.ownedCovers[index];
            this.form = useForm({
                name: cover.name,
            });
            this.modalIndex = index;
            this.modalShow = true;
        },
        submit() {
            axios
                .post(route("authenticated.cover.post.cover_update", this.ownedCovers[this.modalIndex].id), this.form)
                .then(response => {
                    this.ownedCovers[this.modalIndex] = response.data.cover;
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
