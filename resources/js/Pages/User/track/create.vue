<template>
    <app-layout>
        <template #header>
            Track upload
        </template>

        <jet-form-section @submitted="validate">
            <template #title>
                Track
            </template>

            <template #description>
                Insert all the information regarding your track, the more information you give the better.
                <br>
                Required fields are marked with *.
            </template>

            <template #form>
                <!-- Profile Photo -->
                <div class="col-span-6 sm:col-span-4">
                    <!-- Profile Photo File Input -->
                    <input type="file" class="hidden" ref="track" id="track" @change="trackUpdate"
                           accept=".mp3,.aac,.wav,.ogg,.weba,.flac,.mp4a,.m4a">
                    <jet-label for="track" value="Track *"/>
                    <label class="text-xs text-gray-800 font-semibold">Accepted formats: .mp3, .aac, .wav, .ogg, .weba,
                        .flac, .mp4a, .m4a</label>

                    <jet-secondary-button class="mt-2 mr-2" type="button" @click.prevent="$refs.track.click()">
                        Select a track
                    </jet-secondary-button>

                    <label v-if="form.track">Selected {{ form.track.name }}</label>

                    <jet-input-error :message="form.errors.track" class="mt-2"/>
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="name" value="Name *"/>
                    <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name"/>
                    <jet-input-error :message="form.errors.name" class="mt-2"/>
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="duration" value="Duration *"/>
                    <jet-input id="duration" type="text" class="mt-1 block w-full" v-model="form.duration"/>
                    <jet-input-error :message="form.errors.duration" class="mt-2"/>
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="daw" value="Daw"/>
                    <jet-input id="daw" type="text" class="mt-1 block w-full" v-model="form.daw"/>
                    <jet-input-error :message="form.errors.daw" class="mt-2"/>
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="description" value="Description"/>
                    <jet-input id="description" as="textarea" rows="4" class="mt-1 block w-full resize-y"
                               v-model="form.description"/>
                    <jet-input-error :message="form.errors.description" class="mt-2"/>
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="lyric" value="Lyric"/>
                    <jet-input id="lyric" as="textarea" rows="4" class="mt-1 block w-full resize-y"
                               v-model="form.lyric"/>
                    <jet-input-error :message="form.errors.lyric" class="mt-2"/>
                </div>
            </template>

            <template #actions>
                <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                    Uploaded.
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Upload
                </jet-button>
            </template>
        </jet-form-section>

        <popup-base v-bind="nft_creation_popup" v-model:open="nft_creation_popup.open">
            <div class="flex flex-col items-center justify-center">
                <div class="mb-4 text-[6rem] mx-auto text-secondary-300">
                    <i v-if="!nft_creation_popup.completed" class='bx bx-loader-alt bx-spin'></i>
                    <lottie-player v-else src="https://assets4.lottiefiles.com/temp/lf20_5tgmik.json"
                                   background="transparent" speed="1" style="width: 100px; height: 100px;"
                                   autoplay></lottie-player>
                </div>
                <h3 class="text-xl font-semibold mx-auto text-center" :class="{'text-red-500': nft_creation_popup.error}"
                    v-html="nft_creation_popup.state"></h3>
                <a v-if="nft_creation_popup.url" :href="nft_creation_popup.url" class="mx-auto border border-secondary-100
                    rounded bg-purple-100 px-4 py-3 text-lg mt-6 hover:bg-purple-200 hover:shadow-md transition-all duration-500"
                   target="_blank" rel="noopener">
                    {{ nft_creation_popup.button_text }}
                </a>
            </div>
        </popup-base>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import web3Interactions from "@/Composition/Web3Interactions";
import toaster from "@/Composition/toaster";
import Web3 from "web3";
import {useForm} from "@inertiajs/inertia-vue3";
import PopupBase from "@/Components/PopupBase";
import JetButton from '@/Jetstream/Button'
import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import JetLabel from '@/Jetstream/Label'
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'

export default {
    name: "create",
    components: {
        PopupBase,
        AppLayout,
        JetButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetActionMessage,
        JetSecondaryButton,
    },
    setup() {
        return {
            ...web3Interactions(),
            ...toaster()
        }
    },
    data() {
        return {
            nft_creation_popup: {
                open: false,
                title: "Uploading track",
                state: "Waiting transaction confirmation",
                url: null,
                error: false,
                button_text: "Explore transaction",
                completed: false,
            },
            form: useForm({
                name: null,
                description: null,
                lyric: null,
                daw: null,
                duration: null,
                track: null,
                nft_id: null,
            }),
            track: null,
            audio: null
        }
    },
    methods: {
        generateTime() {
            let hours = this.audio.duration / 3600 | 0,
                mins = (this.audio.duration - hours * 3600) / 60 | 0,
                secs = (this.audio.duration - hours * 3600 - mins * 60) | 0

            hours = hours < 10 ? `0${hours}` : hours
            mins = mins < 10 ? `0${mins}` : mins
            secs = secs < 10 ? `0${secs}` : secs

            return `${hours}:${mins}:${secs}`
        },
        trackUpdate() {
            this.form.track = this.$refs.track.files[0]

            const reader = new FileReader();
            reader.addEventListener('load', (event) => {
                this.audio = new Audio()
                this.audio.onloadedmetadata = () => {
                    this.form.name = this.audio.title
                    this.form.duration = this.generateTime()
                    this.form.description = this.audio.textContent
                }
                this.audio.src = event.target.result
            });
            reader.readAsDataURL(this.form.track);
        },
        checkTrackState() {
            if (!this.track) {
                this.track = this.getTrackContract(this.web3)
            }
        },

        validate() {
            let _this = this
            this.form.post(route("track-solo_validation"), {
                onSuccess() {
                    _this.submit()
                }
            })
        },

        async submit() {
            this.nft_creation_popup = {
                open: true,
                title: "Uploading track",
                state: "Waiting transaction confirmation",
                url: null,
                error: false,
                button_text: "Explore transaction",
                completed: false,
            }

            this.checkTrackState()

            this.subscribeTrackRegistrationEvent(this.web3, async (_, event) => {
                this.nft_creation_popup.state = `Transaction approved, NFT created, finalizing creation...`

                let track_id = event.returnValues.track_id,
                    _this = this

                this.form.nft_id = track_id
                this.form.post(route("track-store"), {
                    onSuccess() {
                        _this.nft_creation_popup.url = route("nft_reference", {id: track_id})
                        _this.nft_creation_popup.button_text = "Explore NFT"
                        _this.nft_creation_popup.state = `NFT creation completed, check your token clicking on the button below,
                            <br>
                            you'll be redirected to the dashboard in a moment`
                        _this.nft_creation_popup.completed = true

                        setTimeout(() => {
                            _this.$inertia.visit(route("dashboard"))
                        }, 10000)
                    }
                })
            })

            let result = await this.track.methods.registerTrack(this.address).send({
                from: this.address,
            }, (err, tx_hash) => {
                if (!err) {
                    this.nft_creation_popup.state = `Transaction confirmed, check its state clicking on the button below`
                    this.nft_creation_popup.url = `${this.getBaseTxUrl()}${tx_hash}`
                } else {
                    this.nft_creation_popup.open = false
                    this.contractErrorToast(err)
                        .setDuration(10_000)
                        .finalize()
                        .show()
                }
            })

            if (!result.status) {
                this.nft_creation_popup.state = `An error occurred during the transaction, check what happened examining the transaction`
                this.nft_creation_popup.error = true
            }
        },
    },
    created() {
        if (this.isSupportedWallet()) {
            let net = this.getWalletProvider()
            this.web3 = new Web3(window[net]);

            if (window[net].isConnected) {
                this.connect(this.web3).then(async () => {
                    if (this.network.unsupported) {
                        this.popup.open = true
                        this.popup.title = "Unsupported network"
                    } else {
                        await this.checkAllowance(this.web3)
                        this.checkTrackState()
                    }
                })
            }
            window[net].on("chainChanged", this.handleChainChange)
            window[net].on('accountsChanged', this.handleAccountChange)
        } else {
            this.$inertia.visit(route("wallet_required"))
        }
    }
}
</script>

<style scoped>

</style>
