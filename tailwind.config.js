import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
        colors: {
            'stockhive-grey': '#2E3532',
            'stockhive-grey-dark': '#1c1f1e',
            'accent': '#F9B339',
            'white': '#ffffff',
            'black': '#000000',
            'grey': '#4b5563',
            'error': '#e3342f',
        },
        boxShadow: {
            'bxs': "0 0 10px #F9B339",
        },
    },

    plugins: [forms],
};
