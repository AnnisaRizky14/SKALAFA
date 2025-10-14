// tailwind.config.js

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
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe', 
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#003f7f', // Main UNIB blue
                    700: '#002a5c',
                    800: '#001a3d',
                    900: '#000f1f',
                },
                secondary: {
                    50: '#fff7ed',

                    100: '#ffedd5',
                    200: '#fed7aa',
                    300: '#fdba74',
                    400: '#fb923c',
                    500: '#ff6b35', // Main Teknik orange
                    600: '#ea580c',
                    700: '#c2410c',
                    800: '#9a3412',
                    900: '#7c2d12',
                },
                success: {
                    50: '#ecfdf5',
                    100: '#d1fae5',
                    200: '#a7f3d0',
                    300: '#6ee7b7',
                    400: '#34d399',
                    500: '#10b981',
                    600: '#059669',
                    700: '#047857',
                    800: '#065f46',
                    900: '#064e3b',
                },
                warning: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                },
                danger: {
                    50: '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#ef4444',
                    600: '#dc2626',
                    700: '#b91c1c',
                    800: '#991b1b',
                    900: '#7f1d1d',
                },
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'fade-in-up': 'fadeInUp 0.6s ease-out',
                'slide-in-right': 'slideInRight 0.4s ease-out',
                'bounce-in': 'bounceIn 0.6s ease-out',
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'spin-slow': 'spin 3s linear infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                fadeInUp: {
                    '0%': { 
                        opacity: '0',
                        transform: 'translateY(30px)'
                    },
                    '100%': { 
                        opacity: '1',
                        transform: 'translateY(0)'
                    },
                },
                slideInRight: {
                    '0%': { 
                        opacity: '0',
                        transform: 'translateX(30px)'
                    },
                    '100%': { 
                        opacity: '1',
                        transform: 'translateX(0)'
                    },
                },
                bounceIn: {
                    '0%': { 
                        opacity: '0',
                        transform: 'scale(0.3)'
                    },
                    '50%': { 
                        opacity: '1',
                        transform: 'scale(1.05)'
                    },
                    '70%': { 
                        transform: 'scale(0.9)'
                    },
                    '100%': { 
                        opacity: '1',
                        transform: 'scale(1)'
                    },
                },
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
            },
            minHeight: {
                '48': '12rem',
                '64': '16rem',
            },
            maxWidth: {
                '8xl': '88rem',
                '9xl': '96rem',
            },
            backdropBlur: {
                xs: '2px',
            },
            boxShadow: {
                'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                'card-hover': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
            },
            borderRadius: {
                '4xl': '2rem',
            },
            zIndex: {
                '60': '60',
                '70': '70',
                '80': '80',
                '90': '90',
                '100': '100',
            }
        },
    },

    plugins: [
        forms,
        function({ addUtilities }) {
            const newUtilities = {
                '.scrollbar-hide': {
                    '-ms-overflow-style': 'none',
                    'scrollbar-width': 'none',
                    '&::-webkit-scrollbar': {
                        display: 'none',
                    },
                },
                '.text-shadow': {
                    'text-shadow': '2px 2px 4px rgba(0,0,0,0.1)',
                },
                '.text-shadow-lg': {
                    'text-shadow': '4px 4px 8px rgba(0,0,0,0.2)',
                },
                '.bg-pattern': {
                    'background-image': 'radial-gradient(circle at 1px 1px, rgba(255,255,255,0.15) 1px, transparent 0)',
                    'background-size': '20px 20px',
                },
            }
            addUtilities(newUtilities)
        }
    ],
};