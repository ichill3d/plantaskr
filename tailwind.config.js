import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            maxWidth: {
                '8xl': '90rem', // Example custom width (1440px)
                '9xl': '100rem', // Example custom width (1600px)
            },


                        typography: {
                            compact: {
                                css: {
                                    lineHeight: '1.4', // Adjusts line spacing
                                    p: {
                                        marginTop: '0.5rem',
                                        marginBottom: '0.5rem',
                                    },
                                    h1: {
                                        fontSize: '1.875rem', // Tailwind default is 2.25rem
                                        marginTop: '1.5rem',
                                        marginBottom: '0.75rem',
                                    },
                                    h2: {
                                        fontSize: '1.5rem',
                                        marginTop: '1.25rem',
                                        marginBottom: '0.5rem',
                                    },
                                    h3: {
                                        fontSize: '1.25rem',
                                        marginTop: '1rem',
                                        marginBottom: '0.5rem',
                                    },
                                    ul: {
                                        marginTop: '0.5rem',
                                        marginBottom: '0.5rem',
                                        paddingLeft: '1.25rem',
                                    },
                                    ol: {
                                        marginTop: '0.5rem',
                                        marginBottom: '0.5rem',
                                        paddingLeft: '1.25rem',
                                    },
                                    li: {
                                        marginTop: '0.25rem',
                                        marginBottom: '0.25rem',
                                    },
                                    blockquote: {
                                        marginTop: '0.75rem',
                                        marginBottom: '0.75rem',
                                        paddingLeft: '1rem',
                                        borderLeftWidth: '4px',
                                        lineHeight: '1.3',
                                    },
                                    img: {
                                        marginTop: '0.75rem',
                                        marginBottom: '0.75rem',
                                    },
                                },
                            },
                        },






        },
    },

    plugins: [forms, typography],
};
