export default function checkers() {
    const isSupportedWallet = () => {
        return window.ethereum || window.BinanceChain
    }

    return {
        isSupportedWallet,
    }
}
