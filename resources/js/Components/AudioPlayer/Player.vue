<template>
    <div class="w-full flex justify-center items-center">
        <div class="w-full p-4 grid grid-cols-3">
            <div class="col-span-1 flex justify-start items-center">
                <div class="flex gap-5" v-if="currentTrack">
                    <div class="h-full">
                        <img class="self-stretch object-cover w-full" src="../../../assets/images/Rectangle31.png">
                    </div>
                    <div class="h-full flex flex-1 flex-col justify-center">
                        <div class="text-gray-100 text-lg font-extrabold">{{ currentTrack.name }}</div>
                        <div class="text-gray-400 text-sm">{{ currentTrack.artist }}</div>
                    </div>
                </div>
            </div>
            <div class="col-span-1 flex flex-col justify-center gap-2">
                <div class="w-full flex justify-center items-center">
                    <div :class="classes.playerItem" @click="prevTrack">
                        <i class="fa-solid fa-backward-step pointer-events-none"></i>
                    </div>
                    <div :class="classes.playerItem" class="border border-white" @click="play">
                        <i class="fa-solid fa-pause pointer-events-none" v-if="isTimerPlaying"></i>
                        <i class="fa-solid fa-play pointer-events-none" v-else></i>
                    </div>
                    <div :class="classes.playerItem" @click="nextTrack">
                        <i class="fa-solid fa-forward-step pointer-events-none"></i>
                    </div>

                    <!--                    <div class="player-controls__item -favorite" :class="{ active : currentTrack.favorited }"
                                             @click="favorite">
                                            <i class="fa-solid fa-heart text-lg"></i>
                                        </div>-->
                </div>
                <div class="w-full flex justify-center items-center" ref="progress">
                    <div class="text-sm text-gray-100">{{ currentTime }}</div>
                    <div class="progress__bar mx-3" @click="clickProgress">
                        <div class="progress__current" :style="{ width : barWidth }"></div>
                    </div>
                    <div class="text-sm text-gray-100">{{ duration }}</div>
                </div>
            </div>
            <div v-cloak></div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Player",
    data() {
        return {
            audio: null,
            circleLeft: null,
            barWidth: null,
            duration: null,
            currentTime: null,
            isTimerPlaying: false,
            tracks: [
                {
                    name: "Mekanın Sahibi",
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
    computed: {
        classes() {
            return {
                'playerItem': [
                    'h-10', 'w-10', 'mx-2', 'text-xl', 'flex', 'items-center', 'justify-center', 'text-gray-100',
                    'rounded-full', 'cursor-pointer'
                ],
            }
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
.icon {
    display: inline-block;
    width: 1em;
    height: 1em;
    stroke-width: 0;
    stroke: currentColor;
    fill: currentColor;
}

.player {
    background: #eef3f7;
    // box-shadow: 0px 55px 75px -10px rgba(76, 70, 124, 0.5);
    // box-shadow: 0px 55px 105px 10px rgba(76, 70, 124, 0.35);
    box-shadow: 0px 15px 35px -5px rgba(50, 88, 130, 0.32);
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

    &__top {
        display: flex;
        align-items: flex-start;
        position: relative;
        z-index: 4;
        @media screen and (max-width: 576px), (max-height: 500px) {
            flex-wrap: wrap;
        }
    }

    &-cover {
        width: 300px;
        height: 300px;
        margin-left: -70px;
        flex-shrink: 0;
        position: relative;
        z-index: 2;
        border-radius: 15px;
        // transform: perspective(512px) translate3d(0, 0, 0);
        // transition: all .4s cubic-bezier(.125, .625, .125, .875);
        z-index: 1;

        @media screen and (max-width: 576px), (max-height: 500px) {
            margin-top: -70px;
            margin-bottom: 25px;
            width: 290px;
            height: 230px;
            margin-left: auto;
            margin-right: auto;
        }

        &__item {
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            width: 100%;
            height: 100%;
            border-radius: 15px;
            position: absolute;
            left: 0;
            top: 0;

            &:before {
                content: "";
                background: inherit;
                width: 100%;
                height: 100%;
                box-shadow: 0px 10px 40px 0px rgba(76, 70, 124, 0.5);
                display: block;
                z-index: 1;
                position: absolute;
                top: 30px;
                transform: scale(0.9);
                filter: blur(10px);
                opacity: 0.9;
                border-radius: 15px;
            }

            &:after {
                content: "";
                background: inherit;
                width: 100%;
                height: 100%;
                box-shadow: 0px 10px 40px 0px rgba(76, 70, 124, 0.5);
                display: block;
                z-index: 2;
                position: absolute;
                border-radius: 15px;
            }
        }

        &__img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0px 10px 40px 0px rgba(76, 70, 124, 0.5);
            user-select: none;
            pointer-events: none;
        }
    }

    &-controls {
        /*flex: 1;
        padding-left: 20px;
        display: flex;
        //flex-direction: column;
        align-items: center;

        @media screen and (max-width: 576px), (max-height: 500px) {
            flex-direction: row;
            padding-left: 0;
            width: 100%;
            flex: unset;
        }*/

        &__item {
            display: inline-flex;
            padding: 5px;
            margin-bottom: 10px;
            color: #acb8cc;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: all 0.3s ease-in-out;

            @media screen and (max-width: 576px), (max-height: 500px) {
                padding: 5px;
                margin-right: 10px;
                color: #acb8cc;
                cursor: pointer;
                margin-bottom: 0;
            }

            &::before {
                content: "";
                position: absolute;
                width: 100%;
                height: 100%;
                border-radius: 50%;
                background: #fff;
                transform: scale(0.5);
                opacity: 0;
                box-shadow: 0px 5px 10px 0px rgba(76, 70, 124, 0.2);
                transition: all 0.3s ease-in-out;
                transition: all 0.4s cubic-bezier(0.35, 0.57, 0.13, 0.88);
            }

            @media screen and (min-width: 500px) {
                &:hover {
                    color: #532ab9;

                    &::before {
                        opacity: 1;
                        transform: scale(1.3);
                    }
                }
            }

            @media screen and (max-width: 576px), (max-height: 500px) {
                &:active {
                    color: #532ab9;

                    &::before {
                        opacity: 1;
                        transform: scale(1.3);
                    }
                }
            }

            .icon {
                position: relative;
                z-index: 2;
            }

            &.-xl {
                margin-bottom: 0;
                // filter: drop-shadow(0 2px 8px rgba(172, 184, 204, 0.3));
                // filter: drop-shadow(0 9px 6px rgba(172, 184, 204, 0.35));
                filter: drop-shadow(0 11px 6px rgba(172, 184, 204, 0.45));
                color: #fff;
                width: auto;
                height: auto;
                display: inline-flex;
                @media screen and (max-width: 576px), (max-height: 500px) {
                    margin-left: auto;
                    margin-right: 0;
                }

                &:before {
                    display: none;
                }
            }

            &.-favorite {
                &.active {
                    color: red;
                }
            }
        }
    }
}

[v-cloak] {
    display: none;
}

[v-cloak] > * {
    display: none;
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
        opacity: 0.5;
    }

    &__time {
        margin-top: 2px;
        opacity: 0.7;
    }
}

.progress__bar {
    height: 6px;
    width: 100%;
    cursor: pointer;
    background-color: #d0d8e6;
    display: inline-block;
    border-radius: 10px;
}

.progress__current {
    height: inherit;
    width: 0%;
    background-color: #a3b3ce;
    border-radius: 10px;
}


//scale out

.scale-out-enter-active {
    transition: all .35s ease-in-out;
}

.scale-out-leave-active {
    transition: all .35s ease-in-out;
}

.scale-out-enter {
    transform: scale(.55);
    pointer-events: none;
    opacity: 0;
}

.scale-out-leave-to {
    transform: scale(1.2);
    pointer-events: none;
    opacity: 0;
}


//scale in

.scale-in-enter-active {
    transition: all .35s ease-in-out;
}

.scale-in-leave-active {
    transition: all .35s ease-in-out;
}

.scale-in-enter {
    transform: scale(1.2);
    pointer-events: none;
    opacity: 0;
}

.scale-in-leave-to {
    transform: scale(.55);
    pointer-events: none;
    opacity: 0;
}

</style>
