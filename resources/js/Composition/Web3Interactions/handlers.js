export default function handlers(address, network) {
    const handleChainChange = () => {
        location.reload()
    }

    const handleAccountChange = () => {
        address.value = window[getWalletProvider()].selectedAddress
    }

    const handleAccountRequest = (error, result) => {
        if(!error) { address.value = result[0] }
        else { console.error(error) }
    }

    const handleChainId = (error, id) => {
        if(!error) {
            network.id = id

            if(+id === 97) {
                network.testnet = true
                network.unsupported = false
                network.mainnet = false
            }
            else if(+id === 56) {
                network.testnet = false
                network.unsupported = false
                network.mainnet = true
            }
            else {
                network.testnet = false
                network.unsupported = true
                network.mainnet = false
            }
        }
        else { console.error(error) }
    }

    return {
        handleChainChange,
        handleAccountChange,
        handleAccountRequest,
        handleChainId,
    }
}
