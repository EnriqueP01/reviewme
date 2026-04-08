import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                surface: {
                    DEFAULT: 'var(--surface)',
                    bright: 'var(--surface-bright)',
                    dim: 'var(--surface-dim)',
                    lowest: 'var(--surface-container-lowest)',
                    low: 'var(--surface-container-low)',
                    container: 'var(--surface-container)',
                    high: 'var(--surface-container-high)',
                    highest: 'var(--surface-container-highest)',
                },
                primary: {
                    DEFAULT: 'var(--primary)',
                    container: 'var(--primary-container)',
                },
                secondary: {
                    DEFAULT: 'var(--secondary)',
                    container: 'var(--secondary-container)',
                },
                tertiary: {
                    DEFAULT: 'var(--tertiary)',
                    container: 'var(--tertiary-container)',
                },
                'on-surface': {
                    DEFAULT: 'var(--on-surface)',
                    variant: 'var(--on-surface-variant)',
                },
                code: {
                    keyword: '#ff7b72',
                    function: '#d2a8ff',
                    string: '#a5d6ff',
                    variable: '#ffa657',
                    comment: '#8b949e',
                    bg: '#0d1117',
                }
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Space Grotesk', 'sans-serif'],
                mono: ['JetBrains Mono', 'monospace'],
            },
            borderRadius: {
                'round-4': '4px',
            },
            animation: {
                'spring-hover': 'spring 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)',
                'fade-in-up': 'fade-in-up 0.5s ease-out forwards',
                'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'float': 'float 3s ease-in-out infinite',
            },
            keyframes: {
                spring: {
                    '0%': { transform: 'scale(1)' },
                    '100%': { transform: 'scale(1.02)' },
                },
                'fade-in-up': {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-5px)' },
                }
            }
        },
    },

    plugins: [forms],
};
