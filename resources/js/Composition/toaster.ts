/*
 * Copyright (C) 2022 Do inc. - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>
 */

// @ts-ignore
import Toastify from 'toastify-js'
import "toastify-js/src/toastify.css"

class Toaster {
    constructor(options: {
        message: string,
        title?: string,
        code?: string | number,
        type?: "success" | "error",
    }) {
        let title = options?.title,
            code = options?.code || 0,
            type = options?.type || "error"

        if(!title) {
            title = type === "error" ? `Error - code: ${code}` : `Success - code: ${code}`
        }

        Toastify({
            text: `
                <div class="flex items-center">
                    <i class='bx bx-error text-2xl mr-4'></i>
                    <div class="flex flex-col">
                        <small class="font-semibold">${title}</small>
                        <p>
                            ${options.message}
                        </p>
                    </div>
                </div>
            `,
            duration: 5000,
            gravity: "top", // `top` or `bottom`
            position: "right", // `left`, `center` or `right`
            stopOnFocus: true, // Prevents dismissing of toast on hover
            style: {
                background: type === "error" ? "#DC2626" : "#059669",
            },
            escapeMarkup: false
        }).showToast()
    }
}

export {
    Toaster as default,
    Toastify
}
