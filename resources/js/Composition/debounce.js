import {ref} from "vue"

export default function debounce() {
    const debounce_id = ref(null)
    const debounce = (fn, time) => {
        if(typeof fn !== "function") {
            console.error("Debounce first parameter must be a function.")
            return;
        }
        if(typeof time !== "number") {
            console.error("Debounce last parameter must be a number (integer) representing time, in milliseconds.")
            return;
        }

        if(debounce_id !== null) {
            clearTimeout(debounce_id.value)
        }
        debounce_id.value = setTimeout(() => {
            debounce_id.value = null
            fn()
        }, time)
    }

    return {
        debounce,
        debounce_id
    }
}
