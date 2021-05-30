import {ref, reactive} from "vue"
import checkers from "@/Composition/Web3Interactions/checkers";
import getters from "@/Composition/Web3Interactions/getters";
import handlers from "@/Composition/Web3Interactions/handlers";
import utilities from "@/Composition/Web3Interactions/utilities";
import eventSubscribers from "@/Composition/Web3Interactions/eventSubscribers";

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
		    // address with ico
			//address: "0x09F3DE4aD57C19fEA645d182fe840BBBec96034D",
            // address without ico
            address: "0x9e5e7C3770Dd2b711905E4A306506ecedBeea1f3",
			json: require("./contracts/Melody.json").abi
		},
		track: {
            // address with ico
            // address: "0xacfe41BC539A463eEac895c85c3Aab2c2640FD55",
            // address without ico
            address: "0x665e8D25E06Be4faf6c04faa21692851169Bb5Ec",
			json: require("./contracts/Track.json").abi
		},
        election: {
            address: "0xADaC452d05206f3266f9228ab0E6B1fAd3605C40",
            json: require("./contracts/TrackElection.json").abi
        }
	}

	// Checkers
    const {
	    isSupportedWallet
	} = checkers()

	// Getters
	const {
	    getTrackContract,
        getMelodyContract,
        getElectionContract,
        getICOContract,
        getBaseTxUrl,
        getWalletProvider,
    } = getters(contracts)

	// Handlers
    const {
	    handleAccountChange,
        handleAccountRequest,
        handleChainId,
        handleChainChange,
    } = handlers(address, network)

    // utility
    const {
	    divideDecimals,
        dropDecimals,
        prettyNumber,
        prettyDecimals,
    } = utilities()

    // Event subscriber
    const {
	    subscribeICOEvents,
        subscribeTrackRegistrationEvent,
    } = eventSubscribers(getters(contracts))

    // methods
	const connect = (web3) => {
		return Promise.all([
			web3.eth.requestAccounts(handleAccountRequest),
			web3.eth.getChainId(handleChainId)
		]).then(() => {
			connected.value = true
		})
	}

	const checkAllowance = async (web3) => {
        const track = getTrackContract(web3)
	    return await _checkAllowance(web3, track.options.address)
    }

    const _checkAllowance = async (web3, addr) => {
        const melody = getMelodyContract(web3)

        if(BigInt(await melody.methods.allowance(address.value, addr).call() / 1e18) < BigInt(1500)) {
            let res = await melody.methods
                .approve(addr, `1${"0".repeat(36)}`)
                .send({
                    from: address.value,
                    gas: 50000,
                    gasPrice: `10${"0".repeat(9)}`
                })

            return res.status || false
        }
        return true
    }

	return {
        isSupportedWallet,

        subscribeICOEvents,
        subscribeTrackRegistrationEvent,

        getWalletProvider,
        getBaseTxUrl,
        getICOContract,
        getMelodyContract,
        getTrackContract,
        getElectionContract,

        handleChainChange,
        handleAccountChange,
        handleAccountRequest,
        handleChainId,

        dropDecimals,
        prettyNumber,
        prettyDecimals,
        divideDecimals,

		connect,
        checkAllowance,

		address,
		network,
		connected,
		last_block,
	}
}
