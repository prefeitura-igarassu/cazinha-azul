/** @type {import('tailwindcss').Config} */
module.exports = {
    // ...
    content: [
        "./resources/preset/**/*.{js,vue,ts}",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/tw-elements/js/**/*.js"
    ],

    theme: {
        extend: {},
    },

    darkMode: "class",

    plugins: [ 
        require('tailwindcss-primeui')
    ]
};