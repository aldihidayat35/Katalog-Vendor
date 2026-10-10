import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Helpers/**/*.php',
    ],

    theme: {
        extend: {
            colors: {
                ivory: {
                    DEFAULT: '#FAF7F2',
                    50: '#FFFFFF',
                    100: '#FAF7F2',
                    200: '#F5EFE6',
                    300: '#ECE2D3',
                    400: '#DFD0BC',
                    500: '#CFBDA4',
                },
                champagne: {
                    DEFAULT: '#B8956A',
                    50: '#FBF8F3',
                    100: '#F4EFE6',
                    200: '#E7DCB9',
                    300: '#DAC29B',
                    400: '#C9A97E',
                    500: '#B8956A',
                    600: '#9E7B52',
                    700: '#7F603D',
                    800: '#60472B',
                    900: '#422F1B',
                },
                gold: {
                    DEFAULT: '#C5A059',
                    light: '#DFBD78',
                    dark: '#A68037',
                },
                charcoal: {
                    DEFAULT: '#262422',
                    50: '#F6F5F4',
                    100: '#E7E5E4',
                    200: '#C8C4C0',
                    300: '#A9A49E',
                    400: '#7F7871',
                    500: '#5C554F',
                    600: '#46403A',
                    700: '#342F2B',
                    800: '#262422',
                    900: '#171513',
                },
                sage: {
                    DEFAULT: '#82957E',
                    light: '#A6B6A3',
                    dark: '#5F735B',
                    50: '#F3F6F2',
                    100: '#E2E9E0',
                },
                dustyrose: {
                    DEFAULT: '#C48D8D',
                    light: '#DCADAD',
                    dark: '#A16969',
                    50: '#FCF7F7',
                    100: '#F7EAEA',
                },
            },
            fontFamily: {
                serif: ['"Playfair Display"', 'Cormorant Garamond', ...defaultTheme.fontFamily.serif],
                sans: ['"Plus Jakarta Sans"', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                subtle: '0 4px 20px -2px rgba(44, 36, 30, 0.05)',
                elegant: '0 10px 30px -4px rgba(44, 36, 30, 0.08), 0 4px 6px -2px rgba(44, 36, 30, 0.03)',
                goldglow: '0 8px 25px -4px rgba(184, 149, 106, 0.35)',
            },
        },
    },

    plugins: [forms, typography],
};
