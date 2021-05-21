<template>
    <div class="player w-full max-w-full overflow-hidden shadow-xl border border-purple-300" :class="{'bg-purple-200': featured, 'bg-purple-100': !featured}">
        <div class="progress text-primary-100" ref="progress">
            <div class="flex flex-col items-end">
                <div class="album-info w-full" v-if="currentTrack">
                    <div class="flex items-center">
                        <div class="album-info__name truncate">{{ currentTrack.artist }}</div>
                        <div class="flex items-center justify-center ml-auto cursor-pointer p-1" @click="favorite">
                            <i class='bx bxs-heart text-3xl transition-all duration-300' :class="{ 'text-red-500' : currentTrack.favorited }"></i>
                        </div>
                    </div>

                    <div class="album-info__track truncate mt-2">{{ currentTrack.name }}</div>
                </div>
                <div class="flex items-center w-full text-primary-100 mt-6">
                    <div v-if="previous" class="flex items-center justify-center mr-3 cursor-pointer" @click="prevTrack">
                        <i class='bx bx-skip-previous-circle text-2xl'></i>
                    </div>
                    <div class="flex items-center justify-center cursor-pointer" @click="play">
                        <i v-if="!isTimerPlaying" class='bx bx-play-circle text-2xl'></i>
                        <i v-else class='bx bx-pause-circle text-2xl'></i>
                    </div>
                    <div v-if="next" class="flex items-center justify-center ml-3 cursor-pointer" @click="nextTrack">
                        <i class='bx bx-skip-next-circle text-2xl'></i>
                    </div>
                    <div class="progress__duration ml-auto">{{ duration }}</div>
                </div>
            </div>
            <div class="progress__bar" @click="clickProgress">
                <div class="progress__current" :style="{ width : barWidth }"></div>
            </div>
            <div class="progress__time">{{ currentTime }}</div>
        </div>
        <div class="flex items-center justify-center w-full mt-4">
            <star-rating v-model="stars" :id="id"></star-rating>
        </div>
    </div>
</template>

<script>
import StarRating from "./StarRating";
export default {
    name: "AudioPlayer",
    components: {StarRating},
    props: {
        next: {
            type: Boolean,
            default: false,
        },
        previous: {
            type: Boolean,
            default: false,
        },
        id: {
            type: String,
            required: true
        },
        featured: {
            type: Boolean,
            default: false,
        }
    },
    data() {
        return {
            stars: 0,
            audio: null,
            circleLeft: null,
            barWidth: null,
            duration: null,
            currentTime: null,
            isTimerPlaying: false,
            tracks: [
                {
                    name: "Mekanın Sahibi aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                    artist: "Norm Ender",
                    cover: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/img/1.jpg",
                    source: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/mp3/1.mp3",
                    url: "https://www.youtube.com/watch?v=z3wAjJXbYzA",
                    favorited: false
                },
                {
                    name: "Everybody Knows",
                    artist: "Leonard Cohen",
                    cover: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/img/2.jpg",
                    source: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/mp3/2.mp3",
                    url: "https://www.youtube.com/watch?v=Lin-a2lTelg",
                    favorited: true
                },
                {
                    name: "Extreme Ways",
                    artist: "Moby",
                    cover: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/img/3.jpg",
                    source: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/mp3/3.mp3",
                    url: "https://www.youtube.com/watch?v=ICjyAe9S54c",
                    favorited: false
                },
                {
                    name: "Butterflies",
                    artist: "Sia",
                    cover: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/img/4.jpg",
                    source: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/mp3/4.mp3",
                    url: "https://www.youtube.com/watch?v=kYgGwWYOd9Y",
                    favorited: false
                },
                {
                    name: "The Final Victory",
                    artist: "Haggard",
                    cover: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/img/5.jpg",
                    source: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/mp3/5.mp3",
                    url: "https://www.youtube.com/watch?v=0WlpALnQdN8",
                    favorited: true
                },
                {
                    name: "Genius ft. Sia, Diplo, Labrinth",
                    artist: "LSD",
                    cover: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/img/6.jpg",
                    source: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/mp3/6.mp3",
                    url: "https://www.youtube.com/watch?v=HhoATZ1Imtw",
                    favorited: false
                },
                {
                    name: "The Comeback Kid",
                    artist: "Lindi Ortega",
                    cover: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/img/7.jpg",
                    source: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/mp3/7.mp3",
                    url: "https://www.youtube.com/watch?v=me6aoX0wCV8",
                    favorited: true
                },
                {
                    name: "Overdose",
                    artist: "Grandson",
                    cover: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/img/8.jpg",
                    source: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/mp3/8.mp3",
                    url: "https://www.youtube.com/watch?v=00-Rl3Jlx-o",
                    favorited: false
                },
                {
                    name: "Rag'n'Bone Man",
                    artist: "Human",
                    cover: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/img/9.jpg",
                    source: "https://raw.githubusercontent.com/muhammederdem/mini-player/master/mp3/9.mp3",
                    url: "https://www.youtube.com/watch?v=L3wKzyIN1yk",
                    favorited: false
                }
            ],
            currentTrack: null,
            currentTrackIndex: 0,
            transitionName: null
        };
    },
    methods: {
        play() {
            if (this.audio.paused) {
                this.audio.play();
                this.isTimerPlaying = true;
            } else {
                this.audio.pause();
                this.isTimerPlaying = false;
            }
        },
        generateTime() {
            let width = (100 / this.audio.duration) * this.audio.currentTime;
            this.barWidth = width + "%";
            this.circleLeft = width + "%";
            let durmin = Math.floor(this.audio.duration / 60);
            let dursec = Math.floor(this.audio.duration - durmin * 60);
            let curmin = Math.floor(this.audio.currentTime / 60);
            let cursec = Math.floor(this.audio.currentTime - curmin * 60);
            if (durmin < 10) {
                durmin = "0" + durmin;
            }
            if (dursec < 10) {
                dursec = "0" + dursec;
            }
            if (curmin < 10) {
                curmin = "0" + curmin;
            }
            if (cursec < 10) {
                cursec = "0" + cursec;
            }
            this.duration = durmin + ":" + dursec;
            this.currentTime = curmin + ":" + cursec;
        },
        updateBar(x) {
            let progress = this.$refs.progress;
            let maxduration = this.audio.duration;
            let position = x - (progress.offsetLeft + progress.offsetParent.offsetLeft);
            let percentage = (100 * position) / progress.offsetWidth;
            console.log(position, percentage, progress.offsetWidth, x, progress.offsetLeft)
            console.log(progress)
            if (percentage > 100) {
                percentage = 100;
            }
            if (percentage < 0) {
                percentage = 0;
            }
            this.barWidth = percentage + "%";
            this.circleLeft = percentage + "%";
            this.audio.currentTime = (maxduration * percentage) / 100;
            this.audio.play();
        },
        clickProgress(e) {
            this.isTimerPlaying = true;
            this.audio.pause();
            this.updateBar(e.pageX);
        },
        prevTrack() {
            this.transitionName = "scale-in";
            this.isShowCover = false;
            if (this.currentTrackIndex > 0) {
                this.currentTrackIndex--;
            } else {
                this.currentTrackIndex = this.tracks.length - 1;
            }
            this.currentTrack = this.tracks[this.currentTrackIndex];
            this.resetPlayer();
        },
        nextTrack() {
            this.transitionName = "scale-out";
            this.isShowCover = false;
            if (this.currentTrackIndex < this.tracks.length - 1) {
                this.currentTrackIndex++;
            } else {
                this.currentTrackIndex = 0;
            }
            this.currentTrack = this.tracks[this.currentTrackIndex];
            this.resetPlayer();
        },
        resetPlayer() {
            this.barWidth = 0;
            this.circleLeft = 0;
            this.audio.currentTime = 0;
            this.audio.src = this.currentTrack.source;
            setTimeout(() => {
                if (this.isTimerPlaying) {
                    this.audio.play();
                } else {
                    this.audio.pause();
                }
            }, 300);
        },
        favorite() {
            this.tracks[this.currentTrackIndex].favorited = !this.tracks[
                this.currentTrackIndex
                ].favorited;
        }
    },
    created() {
        let vm = this;
        this.currentTrack = this.tracks[0];
        this.audio = new Audio();
        this.audio.src = this.currentTrack.source;
        this.audio.ontimeupdate = function () {
            vm.generateTime();
        };
        this.audio.onloadedmetadata = function () {
            vm.generateTime();
        };
        this.audio.onended = function () {
            vm.nextTrack();
            this.isTimerPlaying = true;
        };

        // this is optional (for preload covers)
        for (let index = 0; index < this.tracks.length; index++) {
            const element = this.tracks[index];
            let link = document.createElement('link');
            link.rel = "prefetch";
            link.href = element.cover;
            link.as = "image"
            document.head.appendChild(link)
        }
    }
}
</script>

<style lang="scss" scoped>
.player {
    /*background: #eef3f7;*/
    // box-shadow: 0px 55px 75px -10px rgba(76, 70, 124, 0.5);
    // box-shadow: 0px 55px 105px 10px rgba(76, 70, 124, 0.35);
    //box-shadow: 0px 15px 35px -5px rgba(50, 88, 130, 0.32);
    border-radius: 15px;
    padding: 30px;
    @media screen and (max-width: 576px), (max-height: 500px) {
        width: 95%;
        padding: 20px;
        margin-top: 75px;
        min-height: initial;
        padding-bottom: 30px;
        max-width: 400px;
    }
}

.progress {
    width: 100%;
    margin-top: -15px;
    user-select: none;
    position: relative;

    &__top {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
    }

    &__duration {
        //color: #71829e;
        font-weight: 700;
        font-size: 20px;
        opacity: 0.5;
    }

    &__time {
        margin-top: 2px;
        //color: #71829e;
        font-weight: 700;
        font-size: 16px;
        opacity: 0.7;
    }
}

.progress__bar {
    height: 6px;
    width: 100%;
    cursor: pointer;
    background-color: #e59bc1;
    display: inline-block;
    border-radius: 10px;
}

.progress__current {
    height: inherit;
    width: 0%;
    background-color: #cf4b8f;
    border-radius: 10px;
}

.album-info {
    //color: #71829e;
    flex: 1;

    @media screen and (max-width: 576px), (max-height: 500px) {
        padding-right: 30px;
    }

    &__name {
        font-size: 20px;
        font-weight: bold;
        line-height: 1.3em;
        @media screen and (max-width: 576px), (max-height: 500px) {
            font-size: 18px;
            margin-bottom: 9px;
        }
    }

    &__track {
        font-weight: 400;
        font-size: 20px;
        opacity: 0.7;
        line-height: 1.3em;
        @media screen and (max-width: 576px), (max-height: 500px) {
            font-size: 18px;
            min-height: 50px;
        }
    }
}
</style>
