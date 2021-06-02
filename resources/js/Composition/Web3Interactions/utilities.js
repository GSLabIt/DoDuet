export default function utilities() {
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

    return {
        dropDecimals,
        prettyNumber,
        prettyDecimals,
        divideDecimals,
    }
}
