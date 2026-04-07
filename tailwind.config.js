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
            },
            keyframes: {
                spring: {
                    '0%': { transform: 'scale(1)' },
                    '100%': { transform: 'scale(1.02)' },
                }
            }
        },
    },

    plugins: [forms],
};
