<template>
    <app-layout>
        <template #header>
            Dashboard
        </template>

        <div class="bg-purple-200 overflow-hidden shadow-xl border border-secondary-100 rounded-lg p-4">
            <h2 class="text-xl font-semibold flex items-center">
                Your tracks
                <inertia-link :href="route('track-create')" class="ml-auto border border-secondary-100 rounded text-base px-3 py-2 bg-pink-100
                    hover:bg-pink-50 hover:shadow-lg transition-all duration-500 select-none">
                    Upload track
                </inertia-link>
            </h2>
            <data-table :data="tracks" class="mt-6">
                <template v-slot:item.stars.total="{value}">
                    <div class="flex items-center justify-center">
                        <span>{{ value }}</span>
                        <span class='bx bxs-star ml-2 text-2xl text-amber-400 mb-1'></span>
                    </div>
                </template>
                <template v-slot:item.stars.average="{index}">
                    <star-rating v-model="tracks.values[index].stars.average" disabled :id="`stars-average-${index}`"></star-rating>
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

export default {
    components: {
        PopupBase,
        DataTable,
        StarRating,
        AppLayout,
    },
    setup() {
        return {
            ...web3Interactions(),
            ...toaster()
        }
    },
    data() {
        return {
            tracks: {
                cols: [
                    {
                        name: "Name",
                        left: true,
                        class: "w-2/5",
                        value: "name"
                    },
                    {
                        name: "Listened",
                        centered: true,
                        class: "w-1/5",
                        value: "listened"
                    },
                    {
                        name: "Total stars",
                        centered: true,
                        class: "w-2/5",
                        value: "stars.total"
                    },
                    {
                        name: "Average star",
                        centered: true,
                        class: "w-2/5",
                        value: "stars.average"
                    },
                ],
                values: [
                    {
                        name: "Test",
                        listened: "1234 times",
                        stars: {
                            total: 3702,
                            average: 3
                        }
                    },
                    {
                        name: "Test",
                        listened: "4567 times",
                        stars: {
                            total: 3,
                            average: 1
                        }
                    },
                    {
                        name: "Test",
                        listened: "4567 times",
                        stars: {
                            total: 3,
                            average: 1
                        }
                    },
                    {
                        name: "Test",
                        listened: "1234 times",
                        stars: {
                            total: 3702,
                            average: 3
                        }
                    },
                ],
            },
            popup: {
                open: false,
                title: ""
            },
        }
    },
    created() {
        if (this.isSupportedWallet()) {
            let net = this.getWalletProvider()
            this.web3 = new Web3(window[net]);

            if(window[net].isConnected) {
                this.connect(this.web3).then(async () => {
                    if(this.network.unsupported) {
                        this.popup.open = true
                        this.popup.title = "Unsupported network"
                    }
                    else { await this.checkAllowance(this.web3) }
                })
            }
            window[net].on("chainChanged", this.handleChainChange)
            window[net].on('accountsChanged', this.handleAccountChange)
        }
        else {
            this.$inertia.visit(route("wallet_required"))
        }
    }
}
</script>
