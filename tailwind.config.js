import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        // Les vues Laravel pagination (souvent utilisées par défaut)
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',

        // Vues compilées stockées temporairement (cache Blade)
        './storage/framework/views/*.php',

        // Toutes tes vues Blade (templates)
        './resources/views/**/*.blade.php',

        // Ajout du JS dans resources pour scanner les classes dynamiques
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                // Extension de la famille sans-serif avec Figtree en priorité
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        // Plugin officiel Tailwind pour améliorer le rendu des formulaires
        forms,
    ],
};
