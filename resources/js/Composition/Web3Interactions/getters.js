module.exports = function getters(contracts) {
    const getWalletProvider = () => {
        return window.ethereum ? "ethereum" : "BinanceChain"
    }

    const getContract = (web3, contract) => {
        return new web3.eth.Contract(
            contracts[contract].json,
            contracts[contract].address
        )
    }

    const getBaseTxUrl = () => {
        return +window.ethereum.networkVersion === 97 ? "https://testnet.bscscan.com/tx/" : "https://bscscan.com/tx/"
    }

    const getICOContract = (web3) => { return getContract(web3, "ico") }
    const getMelodyContract = (web3) => { return getContract(web3, "melody") }
    const getTrackContract = (web3) => { return getContract(web3, "track") }
    const getChallengeContract = (web3) => { return getContract(web3, "challenge") }

    return {
        getWalletProvider,
        getBaseTxUrl,
        getICOContract,
        getMelodyContract,
        getTrackContract,
        getChallengeContract,
    }
}
