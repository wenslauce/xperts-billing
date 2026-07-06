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
            colors: {
                xperts: {
                    orange: '#f05622',
                    'orange-dark': '#d0481d',
                    'orange-light': '#f26a3e',
                    slate: '#1e293b',
                    'slate-light': '#334155',
                    'slate-dark': '#0f172a',
                },
            },
        },
    },

    plugins: [forms],
};