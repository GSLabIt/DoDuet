<template>
    <app-layout>
        <template #header>
            Dashboard
        </template>

        <div class="bg-purple-200 shadow-xl border border-secondary-100 rounded-lg p-4">
            <h2 class="text-xl font-semibold flex items-center">
                Your tracks
                <inertia-link :href="route('track-create')" class="ml-auto border border-secondary-100 rounded text-base px-3 py-2 bg-pink-100
                    hover:bg-pink-50 hover:shadow-lg transition-all duration-500 select-none">
                    Upload track
                </inertia-link>
            </h2>
            <data-table :data="tracks_" class="mt-6 relative">
                <template v-slot:item.stars.total="{value}">
                    <div class="flex items-center justify-center">
                        <span>{{ value }}</span>
                        <span class='bx bxs-star ml-2 text-2xl text-amber-400 mb-1'></span>
                    </div>
                </template>
                <template v-slot:item.stars.average="{index}">
                    <star-rating v-model="tracks_.values[index].stars.average" disabled
                                 :id="`stars-average-${index}`"></star-rating>
                </template>
                <template v-slot:item.action="{row}">
                    <jet-dropdown width="" wrapper-classes="top-0 right-8 w-64"
                                  content-classes="py-1 bg-purple-100 border border-purple-200 ring-1 ring-purple-200">
                        <template #trigger>
                            <div class="flex items-center justify-end text-3xl cursor-pointer">
                                <i class='bx bx-menu-alt-right'></i>
                            </div>
                        </template>

                        <template #content>
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-secondary-200 font-bold text-center">
                                Manage Track
                            </div>

                            <jet-dropdown-link v-if="!row.election" as="button" @click.prevent="participateToCurrentElection(row)">
                                <i class='bx bxs-trophy mr-2'></i>
                                Participate to election
                            </jet-dropdown-link>

                            <jet-dropdown-link v-else as="button" @click.prevent="">
                                <i class='bx bxs-bar-chart-alt-2 mr-2'></i>
                                Election overview
                            </jet-dropdown-link>
                        </template>
                    </jet-dropdown>
                </template>
            </data-table>
        </div>
        <popup-base v-bind="popup" v-model:open="popup.open">
            You landed on an unsupported network, this is probably an issue with Metamask default installation,
            please follow the next guide in order to correctly setup Metamask, switch the network then reload this page.
            <br>
            <a href="https://academy.binance.com/en/articles/connecting-metamask-to-binance-smart-chain"
               target="_blank" rel="noopener" class="text-blue-600 hover:text-blue-700 transition-all duration-500">
                Metamask setup
            </a>
        </popup-base>
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
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import StarRating from "@/Components/StarRating";
import DataTable from "@/Components/DataTable";
import web3Interactions from "@/Composition/Web3Interactions"
import Web3 from "web3";
import PopupBase from "@/Components/PopupBase";
import toaster from "@/Composition/toaster";
import JetDropdown from '@/Jetstream/Dropdown'
import JetDropdownLink from '@/Jetstream/DropdownLink'

export default {
    components: {
        PopupBase,
        DataTable,
        StarRating,
        AppLayout,
        JetDropdown,
        JetDropdownLink,
    },
    setup() {
        return {
            ...web3Interactions(),
            ...toaster()
        }
    },
    props: {
        tracks: Array,
    },
    data() {
        return {
            tracks_: {
                cols: [
                    {
                        name: "Name",
                        left: true,
                        class: "w-1/3",
                        value: "name",
                    },
                    {
                        name: "Listened",
                        centered: true,
                        class: "w-1/6",
                        value: "listened",
                    },
                    {
                        name: "Total stars",
                        centered: true,
                        class: "w-1/3",
                        value: "stars.total",
                    },
                    {
                        name: "Average star",
                        centered: true,
                        class: "w-1/3",
                        value: "stars.average",
                    },
                    {
                        name: "Actions",
                        class: "w-1/6",
                        value: "action",
                    },
                ],
                values: [],
            },
            popup: {
                open: false,
                title: ""
            },
            nft_participation_popup: {
                open: false,
                title: "Participating to current election",
                state: "Waiting transaction confirmation",
                url: null,
                error: false,
                button_text: "Explore transaction",
                completed: false,
            },
        }
    },
    methods: {
        async fillTrackData(track) {
            try {
                const listening = (await this.$http.get(route("track-insight_listening", {track: track.id}))).data.listened,
                    votes = (await this.$http.get(route("track-insight_votes", {track: track.id}))).data.voted,
                    average_vote = (await this.$http.get(route("track-insight_average_vote", {track: track.id}))).data.average,
                    election_participation = (await this.$http.get(route("track-insight_participating_in_election", {track: track.id}))).data.participating

                this.tracks_.values.push({
                    nft: track.nft_id,
                    id: track.id,
                    name: track.name,
                    listened: `${listening} times`,
                    stars: {
                        total: votes,
                        average: average_vote
                    },
                    election: election_participation
                })
            } catch (e) {
                console.log(e.message)
            }
        },
        async participateToCurrentElection(track) {
            let tx;
            const election = this.getElectionContract(this.web3)
            this.nft_participation_popup = {
                open: true,
                title: "Registering to current election",
                state: "Waiting transaction confirmation",
                url: null,
                error: false,
                button_text: "Explore transaction",
                completed: false,
            }

            this.subscribeElectionParticipationEvent(this.web3, async (_, event) => {
                if (tx === event.transactionHash) {
                    this.nft_participation_popup.state = `Transaction approved, waiting final confirmation...`

                    let track_id = event.returnValues.track_id,
                        _this = this

                    this.$http.post(route("track-participate_to_election", {nft_id: track_id})).then(res => {
                        _this.nft_participation_popup.url = null
                        _this.nft_participation_popup.state = `Track successfully registered for participation in current election`
                        _this.nft_participation_popup.completed = true

                        setTimeout(() => {
                            _this.nft_participation_popup.open = false
                        }, 5000)
                    }).catch(err => {
                        _this.errorToast(err.response.data.error)
                            .finalize()
                            .show()
                    })
                }
            })

            try {
                await election.methods.participate(track.nft).send({
                    from: this.address,
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
        }
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
                        await this.checkElectionAllowance(this.web3)
                    }
                })
            }
            window[net].on("chainChanged", this.handleChainChange)
            window[net].on('accountsChanged', this.handleAccountChange)
        } else {
            this.$inertia.visit(route("wallet_required"))
        }

        // complete the retrieval of the tracks
        for (let elem of this.tracks) {
            this.fillTrackData(elem)
        }
    }
}
</script>
