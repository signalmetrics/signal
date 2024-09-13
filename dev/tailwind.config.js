import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/signalmetrics/signal/resources/views/**/*.blade.php',
        './vendor/signalmetrics/signal/resources/views/*.blade.php'
    ],

    theme: {
        extend: {

        },
    },

    plugins: [forms],
};
