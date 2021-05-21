<template>
    <landing-layout>
        <hero picture="/asset/disk.jpg" overlay="bg-purple-100 opacity-50" id="home" centered href="#" class="mt-0">
            <template #title>
                Do Duet
            </template>
            <template #subtitle>
                Share your musical creation,
                <br>
                Get feedbacks from worldwide and
                <br>
                <strong>Get known!</strong>
            </template>
            <template #button>Join now!</template>
        </hero>
        <container id="tracks">
            <grid :cols="3" gap="xl">
                <audio-player id="featured-0" featured></audio-player>
                <audio-player id="featured-1" featured></audio-player>
                <audio-player id="featured-2" featured></audio-player>
                <audio-player id="latest-0"></audio-player>
                <audio-player id="latest-1"></audio-player>
                <audio-player id="latest-2"></audio-player>
                <audio-player id="latest-3"></audio-player>
                <audio-player id="latest-4"></audio-player>
                <audio-player id="latest-5"></audio-player>
                <audio-player id="latest-6"></audio-player>
                <audio-player id="latest-7"></audio-player>
                <audio-player id="latest-8"></audio-player>
            </grid>
            <inertia-link class="flex items-center justify-center text-xl font-semibold text-primary-100 mt-8 p-4"
                href="#">
                Show more
            </inertia-link>
        </container>
        <hero picture="/asset/register-now.jpg" overlay="bg-purple-100 opacity-50" centered href="#">
            <template #title>
                Join now to receive 3 free upload credit!
            </template>
            <template #button>Join now!</template>
        </hero>
        <container id="how-it-works">
            <div v-for="(elem, id) of how_to_box" :key="id"
                 class="grid grid-cols-8 gap-6 py-8 transition-all duration-500 filter grayscale hover:grayscale-0">
                <div class="col-span-3 rounded-lg max-h-64 h-64"
                    :class="{
                        'col-start-1': id % 2 === 0,
                        'col-start-6': id % 2 === 1
                    }">
                    <img :src="elem.pic" :alt="elem.alt" class="object-cover rounded-lg w-full h-full">
                </div>
                <div class="col-span-5 px-8 py-4 text-lg flex flex-col justify-center"
                     :class="{
                        'col-start-1 row-start-1 text-right': id % 2 === 1,
                        'col-start-4': id % 2 === 0
                    }">
                    <h3 class="text-xl font-semibold mb-4">
                        {{ elem.title }}
                    </h3>
                    <p v-html="elem.body"></p>
                    <ul v-if="elem.prizes" class="mt-6">
                        <li v-for="(elem, id) of prizes" :key="id" class="grid grid-cols-8">
                            <div class="text-xl font-semibold flex items-center justify-center"
                                 v-html="prizePosition[id]"></div>
                            <div class="col-span-7">
                                {{ elem }}
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </container>
        <hero picture="/asset/friend.jpg" overlay="bg-pink-100 opacity-50" centered href="#">
            <template #title>
                Invite your friends
            </template>
            <template #subtitle>
                And receive 2 upload credit for each completed registration!
            </template>
            <template #button>Join now!</template>
        </hero>
        <container id="plans">
            <div class="text-center mb-4 text-2xl">
                Prices & Plans
            </div>
            <p class="text-center mb-8">
                If you are addicted to the music most of the The Ditty Tune will be free for you.<br>
                Yes, this is because for each completely listened and voted track you receive 0.25 upload credit.<br>
                The only thing you have to do to refill your upload credits is to listen to the others production and
                vote them.
            </p>
            <grid :cols="4">
                <div v-for="(elem, id) of plans" :key="id"
                              class="border border-secondary-100 p-4 rounded-lg shadow-md overflow-hidden relative flex
                                    flex-col transform-gpu transition-all duration-300 bg-purple-100">
                    <div class="text-center mb-2">
                        <h4 class="text-xl font-semibold border-secondary-100 pb-1">
                            {{ elem.title }}
                        </h4>
                        <strong v-if="elem.pack" class="text-secondary-200"><small>Pack</small></strong>
                        <strong v-else-if="elem.time" class="text-secondary-200"><small>Timed</small></strong>
                    </div>
                    <div class="my-4 text-center">
                        <div v-if="elem.credits" class="text-lg">{{ elem.credits }} upload credits</div>
                        <div v-else-if="elem.time" class="text-lg">{{ elem.time }}</div>
                        <div class="mt-2">
                            <strong>@ {{ elem.cost }} €</strong>
                        </div>
                    </div>
                    <div class="flex items-center justify-center">
                        <inertia-link class="mt-2 rounded border border-purple-400 text-primary-100 transition-all duration-300 hover:shadow-md hover:bg-purple-200 p-3
                            cursor-pointer" :href="elem.url">
                            Buy now
                        </inertia-link>
                    </div>
                </div>
            </grid>
        </container>
    </landing-layout>
</template>

<script>
import LandingLayout from "../Layouts/LandingLayout";
import Hero from "../Components/Hero";
import Container from "../Components/Container";
import "@lottiefiles/lottie-player";
import Grid from "../Components/Grid";
import StarRating from "../Components/StarRating";
import AudioPlayer from "../Components/AudioPlayer";
import AudioPlayerComplete from "../Components/AudioPlayer/AudioPlayerComplete";

export default {
    components: {
        AudioPlayerComplete,
        AudioPlayer,
        StarRating,
        Grid,
        Container,
        Hero,
        LandingLayout
    },
    props: {},
    data: () => ({
        plans: [
            {
                title: "Upload credits mini",
                pack: true,
                credits: 2,
                cost: 0.99,
                url: "",
            },
            {
                title: "Upload credits basic",
                pack: true,
                credits: 4.5,
                cost: 1.99,
                url: "",
            },
            {
                title: "Upload credits large",
                pack: true,
                credits: 7.5,
                cost: 2.99,
                url: "",
            },
            {
                title: "Upload credits xl",
                pack: true,
                credits: 11.5,
                cost: 3.99,
                url: "",
            },
            {
                title: "Unlimited upload mini",
                time: "2 weeks",
                cost: 2.99,
                url: "",
            },
            {
                title: "Unlimited upload basic",
                time: "1 month",
                cost: 3.99,
                url: "",
            },
            {
                title: "Unlimited upload large",
                time: "6 month",
                cost: 9.99,
                url: "",
            },
            {
                title: "Unlimited upload xl",
                time: "1 year",
                cost: 14.99,
                url: "",
            },
        ],
        how_to_box: [
            {
                pic: "/asset/recording.jpg",
                alt: "recording",
                title: "Record you track, ditty, song or melody",
                body: `To start using the platform follow your passion, record your song, ditty, melody or track.<br>
                       There are no minimum or maximum recording time, your songs will always be accepted.`,
            },
            {
                pic: "/asset/upload.jpg",
                alt: "upload",
                title: "Upload your track",
                body: `Upload the track to the platform and in a couple of minutes it will be listed.<br>
                        Now everyone can listen to your track and give it stars!`,
            },
            {
                pic: "/asset/prize.jpg",
                alt: "prize",
                title: "More stars means more prizes!",
                body: `Each week the top tracks receives a prize!`,
                prizes: true,
            },
        ],
        prizes: [
            "10.00 € + 10 track upload",
            "5.00 € + 5 track upload",
            "3 track upload"
        ]
    }),
    computed: {
        prizePosition() {
            return [
                `1 <sup><small>st</small></sup>`,
                `2 <sup><small>nd</small></sup>`,
                `3 <sup><small>rd</small></sup>`,
            ]
        }
    },
}
</script>

<style scoped>

</style>
