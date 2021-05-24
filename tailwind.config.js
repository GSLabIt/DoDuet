const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require("tailwindcss/colors")

module.exports = {
    mode: "jit",
    dark: "class",
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                ...colors,
                primary: {
                    50: "#786d86",
                    100: "#625472",
                    200: "#4b3c5e",
                    300: "#35234a",
                    400: "#1e0b36",
                    500: "#1b0a31",
                    600: "#18092b",
                    700: "#150826",
                    800: "#120720",
                    900: "#0f061b",
                },
                secondary: {
                    100: "#e59bc1",
                    200: "#df87b4",
                    300: "#da73a8",
                    400: "#d55f9b",
                    500: "#cf4b8f",
                    600: "#ca3782",
                    700: "#b63275",
                    800: "#a22c68",
                    900: "#8d275b",
                }
            },
            backgroundSize: {
                32: "8rem",
                64: "16rem",
                96: "24rem",
                128: "32rem",
                160: "40rem"
            },
            backgroundPosition: {
                "-left-8": "-2rem center"
            }
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography')
    ],
};
