<!--
  - Copyright (c) 2022 - Do Group LLC - All Right Reserved.
  - Unauthorized copying of this file, via any medium is strictly prohibited
  - Proprietary and confidential
  - Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
  -->

<template>
    <div :key="id"
         class="rounded-2xl grid grid-cols-2 border bg-gradient-to-tr from-[#2F0843]/80 to-[#042843]/80 shadow-lg">
        <div class="w-full h-full p-4">
            <img class="self-stretch object-cover w-full" src="../../assets/images/Rectangle31.png">
        </div>
        <div class="w-full h-full flex flex-col justify-between pr-3 pt-3 text-gray-50">
            <div>
                <h1 class="text-lg truncate">{{ name }}</h1>

                <div class="flex justify-center items-center text-sm gap-2">
                    <div class="w-full h-1 rounded-full bg-gray-500">
                        <div class="bg-gradient-to-r from-yellow-200 to-yellow-500 rounded-full w-2/3">
                            <!--{{perxcentageWidth}}-->
                        </div>
                    </div>
                    <span class="text-xs">{{ duration }}</span>
                </div>
            </div>
            <button class="w-10 h-10 rounded-full border border-white focus:outline-none"
                    @click="$emit('play')">
                <i class="fa fa-play text-white" id="play-btn"></i>
            </button>
            <span class="text-md truncate">{{ creator }}</span>
        </div>
        <div class="col-span-full flex items-center justify-center">
            <div class="bg-gray-100 translate-y-1/2 py-0.5 px-2 rounded-md">
                <star-rating id="star" :min="1" :max="10" v-model="vote" disabled/>
            </div>
        </div>
    </div>
</template>

<script>
import StarRating from "@/Components/StarRating";

export default {
    name: "SongCard",
    components: {StarRating},
    props: {
        id: String,
        name: String,
        creator: String,
        duration: String,
        cover_id: String,
        vote: Number,
    },
    emits: ["play"],
    methods:{
        playPause(){
            this.$emit("play")
        }
    },
}
</script>

<style scoped>

</style>
