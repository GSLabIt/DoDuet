<!--
  - Copyright (c) 2022 - Do Group LLC - All Right Reserved.
  - Unauthorized copying of this file, via any medium is strictly prohibited
  - Proprietary and confidential
  - Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
  -->

<template>
    <div class="w-auto overflow-hidden shadow-xl sm:rounded-lg bg-green-500" :class="classes.banner">
        <div class="flex">
            <div class="w-full h-min relative p-4">
                <div class="flex justify-start items-center gap-3">
                    <span class="bg-yellow-50 p-1">#Listen vote and earn 🖤</span>
                    <span class="bg-yellow-50 p-1">#contest4you</span>
                    <span class="bg-yellow-50 p-1">#house</span>
                </div>

                <h1 class="font-bold text-2xl mt-3">Progressive house</h1>
                <span class="text-white">Contest</span>

                <button class="bg-black w-32 h-10 flex justify-center items-center" @click="playing = !playing">
                    <span v-if="!playing" class="text-yellow-200 font-bold text-lg pointer-events-none">PLAY <i
                        class="fas fa-play pointer-events-none"></i>
                    </span>
                    <span v-else class="text-yellow-200 font-bold text-lg pointer-events-none">
                        PAUSE <i class="fas fa-pause pointer-events-none"></i>
                    </span>
                </button>

                <img class="absolute right-10 h-full bottom-0 invisible xl:visible"
                     src="../../assets/images/listening-music1.png">
            </div>
            <div class="relative h-min w-32 bg-yellow-100 p-4">
                <span class="font-bold text-3xl">00</span><span class="font-normal text-sm">/09</span>
                <div class="w-full border border-black"/>
                <img class="absolute -right-10 -bottom-4" src="../../assets/images/listening-music-2.png">
            </div>
        </div>
        <div v-if="playing" class="flex justify-center items-center h-full">
            Song video animation
        </div>
    </div>
</template>

<script>
export default {
    name: "WeeklyElectionBanner",
    /*props: {
        playing_props: {
            type: Boolean,
            default: false,
        },
        track: {
            type: Object,
        },
        tracks: {
            type: Array,
        },
        voting_props: {
            type: Boolean,
            default: false,
        }
    },
    emits: ["update:playing", "vote"],*/
    data() {
        return {
            // voting: this.voting_props,
            playing: false,
        }
    },
    methods: {
        play_pause() {
            this.playing = !this.playing;
        }
    },
    /*watch:{
        playing(n) {
            this.$emit("update:playing", n)
        }
    },*/
    computed: {
        classes() {
            return {
                banner: {
                    'h-[30rem]': this.playing,
                    'h-auto': !this.playing,
                },
            }
        }
    },
}
</script>

<style scoped>

</style>
