<template>
    <div class="player w-full max-w-full overflow-hidden shadow-xl border border-purple-300"
         :class="{'bg-purple-200': featured, 'bg-purple-100': !featured}">
        <div class="progress text-primary-100" ref="progress">
            <div class="flex flex-col items-end">
                <div class="album-info w-full" v-if="currentTrack">
                    <div class="flex items-center">
                        <div class="album-info__name truncate">{{ currentTrack.artist }}</div>
                        <div class="flex items-center justify-center ml-auto cursor-pointer p-1" @click="favorite">
                            <i class='bx bxs-heart text-3xl transition-all duration-300'
                               :class="{ 'text-red-500' : currentTrack.favorited }"></i>
                        </div>
                    </div>

                    <div class="album-info__track truncate mt-2">{{ currentTrack.name }}</div>
                </div>
                <div class="flex items-center w-full text-primary-100 mt-6">
                    <div v-if="previous" class="flex items-center justify-center mr-3 cursor-pointer"
                         @click="prevTrack">
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
            <star-rating v-model="stars" :id="id" :disabled="stars !== null" @vote="vote"></star-rating>
        </div>
        <popup-base v-bind="nft_participation_popup" v-model:open="nft_participation_popup.open">
            <div class="flex flex-col items-center justify-center">
                <div class="mb-4 text-[6rem] mx-auto text-secondary-300">
                    <i v-if="!nft_participation_popup.completed && !nft_participation_popup.error"
                       class='bx bx-loader-alt bx-spin'></i>
                    <lottie-player v-else-if="nft_participation_popup.completed"
                                   src="https://assets4.lottiefiles.com/temp/lf20_5tgmik.json"
                                   background="transparent" speed="1" style="width: 100px; height: 100px;"
                                   autoplay></lottie-player>
                    <lottie-player v-else-if="nft_participation_popup.error"
                                   src="https://assets6.lottiefiles.com/packages/lf20_gu5zubdo.json"
                                   background="transparent" speed="1" style="width: 100px; height: 100px;"
                                   autoplay></lottie-player>
                </div>
                <h3 class="text-xl font-semibold mx-auto text-center"
                    :class="{'text-red-500': nft_participation_popup.error}"
                    v-html="nft_participation_popup.state"></h3>
                <a v-if="nft_participation_popup.url" :href="nft_participation_popup.url" class="mx-auto border border-secondary-100
                    rounded bg-purple-100 px-4 py-3 text-lg mt-6 hover:bg-purple-200 hover:shadow-md transition-all duration-500"
                   target="_blank" rel="noopener">
                    {{ nft_participation_popup.button_text }}
                </a>
            </div>
        </popup-base>
    </div>
</template>

<script>
import StarRating from "./StarRating";
import toaster from "@/Composition/toaster";
import web3Interactions from "@/Composition/Web3Interactions";
import Web3 from "web3";
import PopupBase from "@/Components/PopupBase";
import voting from "@/Composition/voting";

export default {
    name: "AudioPlayer",
    components: {
        PopupBase,
        StarRating
    },
    setup() {
        return {
            ...toaster(),
            ...web3Interactions(),
            ...voting(),
        }
    },
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
        },
        nft_id: {
            type: String,
            required: true,
        }
    },
    data() {
        return {
            stars: null,
            audio: null,
            circleLeft: null,
            barWidth: null,
            duration: null,
            currentTime: null,
            isTimerPlaying: false,
            tracks: [],
            currentTrack: null,
            currentTrackIndex: 0,
            transitionName: null,
            challenge: null,
            web3: null,
            nft_participation_popup: {
                open: false,
                title: "Voting track ${id}",
                state: "Waiting transaction confirmation",
                url: null,
                error: false,
                button_text: "Explore transaction",
                completed: false,
            },
        };
    },
    methods: {
        async vote(stars) {
            this.nft_participation_popup = {
                open: true,
                title: `Voting NFT ${this.nft_id}`,
                state: "Waiting transaction confirmation",
                url: null,
                error: false,
                button_text: "Explore transaction",
                completed: false,
            }

            const half_stars = stars * 2,
                challenge = this.getChallengeContract(this.web3)

            let tx;

            this.subscribeChallengeVoteEvent(this.web3, async (_, event) => {
                if (tx === event.transactionHash) {
                    this.nft_participation_popup.state = `Transaction approved, waiting final confirmation...`

                    let track_id = event.returnValues.track_id,
                        _this = this

                    this.$http.post(route("nft_track_vote", {nft_id: track_id, address: this.address}), {
                        half_stars
                    }).then(res => {
                        if (res.data.submitted) {
                            _this.nft_participation_popup.url = null
                            _this.nft_participation_popup.state = `Track successfully voted`
                            _this.nft_participation_popup.completed = true

                            setTimeout(() => {
                                _this.nft_participation_popup.open = false
                            }, 5000)
                        } else {
                            _this.errorToast("Track already voted")
                                .finalize()
                                .show()
                        }
                    }).catch(err => {
                        _this.errorToast(err.response.data.error)
                            .finalize()
                            .show()
                    })
                }
            })

            try {
                await challenge.methods.vote(this.nft_id, half_stars).send({
                    from: this.address,
                    gasPrice: `1${"0".repeat(10)}`,
                    gasLimit: 500000
                }, (err, tx_hash) => {
                    if (!err) {
                        tx = tx_hash
                        this.nft_participation_popup.state = `Transaction confirmed, check its state clicking on the button below`
                        this.nft_participation_popup.url = `${this.getBaseTxUrl()}${tx_hash}`
                    } else {
                        this.nft_participation_popup.open = false
                        this.contractErrorToast(err)
                            .setDuration(10_000)
                            .finalize()
                            .show()
                    }
                })
            } catch (e) {
                this.nft_participation_popup.state = `An error occurred during the transaction, check what happened examining it`
                this.nft_participation_popup.error = true
            }
        },
        async play() {
            if (this.audio.src) {
                if (this.audio.paused) {
                    this.audio.play();
                    this.isTimerPlaying = true;
                } else {
                    this.audio.pause();
                    this.isTimerPlaying = false;
                }
            } else {
                this.audio.src = await this.requestAccess(this.nft_id, this.address)

                this.audio.onended = async () => {
                    if(this.requestVote(this.nft_id, this.address)) {
                        this.stars = await this.retrievePreviousVote(this.nft_id)
                    }
                };
                this.play()
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

        if (this.isSupportedWallet()) {
            let net = this.getWalletProvider()
            this.web3 = new Web3(window[net]);

            if (window[net].isConnected) {
                this.connect(this.web3).then(async () => {
                    if (this.network.unsupported) {
                        this.popup.open = true
                        this.popup.title = "Unsupported network"
                    } else {
                        await this.checkChallengeAllowance(this.web3)
                    }
                })
            }
            window[net].on("chainChanged", this.handleChainChange)
            window[net].on('accountsChanged', this.handleAccountChange)
        } else {
            this.$inertia.visit(route("wallet_required"))
        }

        this.$http.get(route("nft_reference", {nft_id: this.nft_id})).then(v => {
            let data = v.data

            this.tracks.push({
                name: data.name,
                artist: data.artist,
                source: data.duration,
                favorited: false
            })

            this.currentTrack = this.tracks[0];
            this.audio = new Audio();
            this.audio.ontimeupdate = function () {
                vm.generateTime();
            };
            this.audio.onloadedmetadata = function () {
                vm.generateTime();
            };
        })

        this.$http.get(route("nft_registered_vote", {nft_id: this.nft_id}))
            .then(v => {
                this.stars = v.data.stars
            })
            .catch(err => {
                this.errorToast(err.response.data.error).finalize().show()
            })
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
