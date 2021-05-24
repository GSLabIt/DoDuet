<template>
    <app-layout>
        <div class="fixed inset-0 bg-purple-100 flex flex-col items-center justify-center z-50 text-gray-800">
            <h1 class="font-semibold text-2xl">As DoDuet relies heavely on the blockchain a wallet is required</h1>
            <h3 class="text-xl">consider the installation of one of this wallet in your browser</h3>
            <div class="flex items-center justify-center my-8">
                <a :href="metamask" target="_blank" rel="noopener"
                   class="mr-6 flex flex-col items-center justify-center">
                    <img src="/asset/metamask.png" alt="Metamask" class="object-contain h-16 w-16 filter drop-shadow-xl mb-3">
                    <small class="font-bold">
                        Metamask
                        <br>
                        &nbsp;
                    </small>
                </a>
                <a :href="binance_chain_wallet" target="_blank" rel="noopener"
                   class="ml-6 flex flex-col items-center justify-center">
                    <img src="/asset/binance-chain-wallet.png" alt="Metamask" class="object-contain h-16 w-16 filter drop-shadow-xl mb-3">
                    <small class="font-bold text-center">
                        Binance Chain Wallet
                        <br>
                        (Suggested)
                    </small>
                </a>
            </div>
            <p class="">
                Once you have installed one of the wallet click the button below.
            </p>
            <inertia-link :href="route('dashboard')"
                          class="my-4 border border-secondary-300 rounded px-4 py-3 text-center transition-all duration-500 hover:bg-purple-200 shadow-md">
                Dashboard
            </inertia-link>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Container from "@/Components/Container";
export default {
    name: "WalletRequired",
    components: {Container, AppLayout},
    data: () => ({
        metamask: "https://chrome.google.com/webstore/detail/metamask/nkbihfbeogaeaoehlefnkodbefgpgknn",
        binance_chain_wallet: "https://chrome.google.com/webstore/detail/binance-chain-wallet/fhbohimaelbohpjbbldcngcnapndodjp"
    }),
    created() {
        if(navigator.userAgent.includes("Firefox")) {
            this.metamask = "https://addons.mozilla.org/en-US/firefox/addon/ether-metamask/"
            this.binance_chain_wallet = "https://addons.mozilla.org/en-US/firefox/addon/binance-chain/"
        }
    }
}
</script>

<style scoped>

</style>
