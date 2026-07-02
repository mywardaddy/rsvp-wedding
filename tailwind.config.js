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
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                gold: {
                    50: '#FFF9E6',
                    100: '#FFF0BF',
                    200: '#FFE699',
                    300: '#E5D47A',
                    400: '#D4BC45',
                    500: '#C9B037',
                    600: '#A89030',
                    700: '#8B7A1E',
                    800: '#6B5E15',
                    900: '#4A400E',
                },
                sage: {
                    50: '#F0F5EC',
                    100: '#DCE6D4',
                    200: '#C5D6B8',
                    300: '#B8C9A3',
                    400: '#A8BA94',
                    500: '#9CAF88',
                    600: '#8B9E77',
                    700: '#7A8E66',
                    800: '#5A7042',
                    900: '#3D5229',
                },
            },
        },
    },

    plugins: [forms],
};
