export default class BasicToast {
    #text = ""
    #duration = 3000
    #destination = ""
    #newWindow = false
    #close = false
    #gravity = "top" // `top` or `bottom`
    #position = "center" // `left`, `center` or `right`
    #backgroundColor = ""
    #stopOnFocus = true // Prevents dismissing of toast on hover
    #onClick = function(){} // Callback after click
    #toaster = null
    #toast = null

    constructor(toaster) {
        this.#toaster = toaster
    }

    get toaster() { return this.#toaster }
    setToaster(toaster) {
        this.#toaster = toaster
        return this
    }

    get text() { return this.#text }
    setText(text) {
        this.#text = text
        return this
    }

    get duration() { return this.#duration }
    setDuration(duration) {
        if(typeof duration === "number") {
            this.#duration = duration
            return this
        }
        throw new Error("Toast duration must be a number")
    }

    get destination() { return this.#destination }
    setDestination(destination) {
        this.#destination = destination
        return this
    }

    get newWindow() { return this.#newWindow }
    setNewWindow(newWindow) {
        if(typeof newWindow === "boolean") {
            this.#newWindow = newWindow
            return this
        }
        throw new Error("Toast newWindow must be a boolean")
    }

    get close() { return this.#close }
    setClose(close) {
        if(typeof close === "boolean") {
            this.#close = close
            return this
        }
        throw new Error("Toast close must be a boolean")
    }

    get gravity() { return this.#gravity }
    setGravity(gravity) {
        if(typeof gravity === "string" && ["top", "bottom"].includes(gravity)) {
            this.#gravity = gravity
            return this
        }
        throw new Error("Toast gravity must be 'top' or 'bottom'")
    }

    get position() { return this.#position }
    setPosition(position) {
        if(typeof position === "string" && ["left", "right", "center"].includes(position)) {
            this.#position = position
            return this
        }
        throw new Error("Toast position must be 'left', 'center' or 'right'")
    }

    get backgroundColor() { return this.#backgroundColor }
    setBackgroundColor(backgroundColor) {
        this.#backgroundColor = backgroundColor
        return this
    }

    get stopOnFocus() { return this.#stopOnFocus }
    setStopOnFocus(stopOnFocus) {
        if(typeof stopOnFocus === "boolean") {
            this.#stopOnFocus = stopOnFocus
            return this
        }
        throw new Error("Toast stopOnFocus must be a boolean")
    }

    get onClick() { return this.#onClick }
    setOnClick(onClick) {
        if(typeof onClick === "function") {
            this.#onClick = onClick
            return this
        }
        throw new Error("Toast onClick must be a function")
    }

    finalize() {
        this.#toast = this.#toaster({
            text: this.#text,
            duration: this.#duration,
            destination: this.#destination,
            newWindow: this.#newWindow,
            close: this.#close,
            gravity: this.#gravity,
            position: this.#position,
            backgroundColor: this.#backgroundColor,
            stopOnFocus: this.#stopOnFocus,
            onClick: this.#onClick,
        })

        return this
    }

    show() {
        if(this.#toast) {
            this.#toast.showToast()
            return;
        }
        throw Error("Toast not finalized")
    }
}
