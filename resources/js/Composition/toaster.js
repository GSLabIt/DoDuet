import BasicToast from "./toaster/basicToast"
import Toastify from "toastify-js"

export default function toaster() {
    const basicToast = new BasicToast(Toastify)

    const errorToast = (text) => {
        return basicToast.setText(text)
            .setBackgroundColor("#DC2626")
            .setStopOnFocus(true)
    }

    const contractErrorToast = (error) => {
        let text = ""
        if(error.code === -32603) { text = "Internal error, please check the gas amount" }
        else if(error.code === -32602) { text = "One or more invalid parameter submitted" }
        else if(error.code === 4001) { text = "Transaction rejected" }

        return basicToast.setText(text)
            .setBackgroundColor("#DC2626")
            .setStopOnFocus(true)
    }

    const successToast = (text) => {
        return basicToast.setText(text)
            .setBackgroundColor("#22C55E")
            .setStopOnFocus(true)
    }

    const infoToast = (text) => {
        return basicToast.setText(text)
            .setStopOnFocus(true)
    }

    return {
        errorToast,
        contractErrorToast,
        successToast,
        infoToast,
    }
}
