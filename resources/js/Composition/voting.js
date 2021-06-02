import Web3 from "web3";
import toaster from "@/Composition/toaster";
import axios from "axios";

export default function voting() {
    let token = null,
        toaster = toaster()

    const retrieveToken = async () => {
        if (token === null) {
            try {
                let response = await axios.get(route("token-retrieve"))
                token = response.data.bearer
                return token
            } catch (err) {
                outputError(err)
            }
        }
        return token
    }

    const outputError = (err) => {
        toaster.errorToast(err.response ? err.response.data.error : err.message).finalize().show()
    }

    const requestAccess = async (nft_id, address) => {
        try {
            let response = await axios.get(
                route("nft_access", {nft_id, address}),
                {
                    Header: {
                        Authorization: `Bearer ${await retrieveToken()}`
                    }
                }
            )
            return response.data.url
        } catch (err) {
            outputError(err)
        }
        return null
    }

    const requestVote = async (nft_id, address) => {
        let data
        try {
            data = (await axios.get(
                route("nft_vote", {nft_id, address}),
                {
                    Header: {
                        Authorization: `Bearer ${await retrieveToken()}`
                    }
                }
            )).data
        } catch (err) {
            outputError(err)
            return false
        }

        if (data.submitted) {
            toaster.successToast("Track vote request submitted, waiting for response")
                .finalize()
                .show()
        } else {
            toaster.infoToast("You can now vote this track")
                .finalize()
                .show()
        }

        return true
    }

    const retrievePreviousVote = async (nft_id) => {
        try {
            let response = await axios.get(
                route("nft_registered_vote", {nft_id}),
                {
                    Header: {
                        Authorization: `Bearer ${await retrieveToken()}`
                    }
                }
            )
            return +response.data.stars
        } catch (err) {
            outputError(err)
        }
        return null
    }

    return {
        requestAccess,
        requestVote,
        retrievePreviousVote,
    }
}
