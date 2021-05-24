import {ref, reactive} from "vue"

export default function web3Interactions() {
	const address = ref(null),
		network = reactive({
			id: null,
			testnet: false,
			mainnet: false,
			unsupported: false
		}),
		connected = ref(false),
		last_block = ref(0)

	const contracts = {
		ico: {
			address: "0xBaD6C6F089AE67772A51597bd41D172cf1Fd6F5d",
			json: require("./contracts/Crowdsale.json").abi
		},
		melody: {
			address: "0x09F3DE4aD57C19fEA645d182fe840BBBec96034D",
			json: require("./contracts/Melody.json").abi
		},
		track: {
			address: "0x2506b72ac9175F8A16D9451feF72dc83899ed2B9",
			json: require("./contracts/Track.json").abi
		}
	}

	// Checkers
	const isSupportedWallet = () => {
		return window.ethereum || window.BinanceChain
	}

	// Getters
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

	// Handlers
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

	const connect = (web3) => {
		return Promise.all([
			web3.eth.requestAccounts(handleAccountRequest),
			web3.eth.getChainId(handleChainId)
		]).then(() => {
			connected.value = true
		})
	}

	// Helpers
	const prettyNumber = (x) => {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
	}

	const dropDecimals = (number, decimals = 18) => {
		const str = number.toString()
		return str.substr(0, str.length - decimals)
	}

	const prettyDecimals = (number, decimals = 18) => {
		const str = number.toString()
		return `${prettyNumber(str.substr(0, str.length - decimals)) || 0}.${str.substr(str.length - decimals) || 0}`
	}

	const divideDecimals = (number, decimals = 18) => {
		const str = number.toString(),
			decimal = str.substr(str.length - decimals)
		return `${str.substr(0, str.length - decimals) || 0}.${(decimal !== "0".repeat(decimals) ? decimal : 0) || 0}`
	}

	// Event subscriber
	const subscribeICOEvents = (web3, buy_function = (err, res) => {}, finalize_function = (err, res) => {}, redeem_function = (err, res) => {}) => {
		const ico = getICOContract(web3)
		ico.events.Buy({}, buy_function)
		ico.events.Finalize({}, finalize_function)
		ico.events.Redeemed({}, redeem_function)
	}

	return {
		isSupportedWallet,

		getWalletProvider,
		getBaseTxUrl,
		getICOContract,
		getMelodyContract,
		getTrackContract,

		handleChainChange,
		handleAccountChange,
		handleAccountRequest,
		handleChainId,
		connect,

		dropDecimals,
		prettyNumber,
		prettyDecimals,
		divideDecimals,

		subscribeICOEvents,

		address,
		network,
		connected,
		last_block,
	}
}
