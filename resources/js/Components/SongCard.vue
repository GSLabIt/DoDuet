<!--
  - Copyright (c) 2022 - Do Group LLC - All Right Reserved.
  - Unauthorized copying of this file, via any medium is strictly prohibited
  - Proprietary and confidential
  - Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
  -->

<template>
    <div :key="id" class="rounded-md grid grid-cols-2 border bg-amber-500">
        <div class="w-full h-full p-4">
            <img class="self-stretch object-cover w-full" src="../../assets/images/Rectangle31.png">
        </div>
        <div class="w-full h-full bg-yellow-500 p-2 overflow-hidden">
            <h1 class="whitespace-nowrap">{{name}}</h1>
            <div class="flex">
<!--                <div class="w-full h-1 rounded-full bg-gray-800">
                    <div class="bg-gradient-to-r from-yellow-200 to-yellow-500 rounded-full w-2/3">
                        &lt;!&ndash;{{percentageWidth}}&ndash;&gt;
                    </div>
                </div>-->
                <span>{{ duration }}</span>
            </div>
            <span>{{creator}}</span>
        </div>
        <div>
            <star-rating id="star" :min="1" :max="10" v-model="vote" disabled/>
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
    emits: ["listen"],
}
</script>

<style scoped>

</style>
