
/*
 * Copyright (C) 2022 Do inc. - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>
 */

export const capitalize = (str: string) => {
    return `${str[0].toUpperCase()}${str.substring(1)}`
}

export const prettyNumber = (x: number) => {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
}

export const renderNumber = (value: any, allowed_decimals: number = 18, rounding: number = 6) => {
    // if the provided value is not a string stringify it
    if (!(value instanceof String)) {
        value = value.toString()
    }
    let integer = "0"
    // check if the value has at least an integer part, in case it has one, prettify it
    if (value.length > allowed_decimals) {
        integer = prettyNumber(value.substring(0, value.length - allowed_decimals))
    }
    let decimals = "00"
    // check if the value has integer part
    if (value.length > allowed_decimals) {
        // if it has retrieve only the decimal part
        decimals = value.substring(value.length - allowed_decimals)
    } else {
        // otherwise it is a decimal number, take it all
        decimals = `${"0".repeat(allowed_decimals - value.length)}${value}`
    }
    for (let i = decimals.length - 1; i >= 2; i--) {
        // remove last zeros if any
        if (decimals[i] === "0") {
            decimals = decimals.slice(0, -1)
        } else {
            break
        }
    }
    return `${integer}.${decimals.substring(0, decimals.length > rounding ? rounding : decimals.length)}`
}

export const randomString = (() => {
    const gen = (min: number, max: number) => max++ && [...Array(max - min)].map((s, i) => String.fromCharCode(min + i));

    const sets = {
        num: gen(48, 57),
        alphaLower: gen(97, 122),
        alphaUpper: gen(65, 90),
        special: [...`~!@#$%^&*()_+-=[]\{}|;:'",./<>?`]
    };

    function* iter(len: number, set: any[]) {
        if (set.length < 1) set = Object.values(sets).flat();
        for (let i = 0; i < len; i++) yield set[Math.random() * set.length | 0]
    }

    return Object.assign(((len: number, ...set: any[]) => [...iter(len, set.flat())].join('')), sets);
})();
