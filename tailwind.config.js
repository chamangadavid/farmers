import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            /* ---------------- Fonts ---------------- */
            fontFamily: {
                sans: ['Montserrat', ...defaultTheme.fontFamily.sans],
            },

            /* ---------------- Colors ---------------- */
            colors: {
                'primary-teal': '#004d40',
                'highlight-green': '#92e623',
                'button-green': '#28a745',
                'hero-purple': '#6A0DAD',
                gold: {
                    light: '#F7E7C5',
                    DEFAULT: '#D4AF37',
                    dark: '#A98D33',
                },
            },

            /* ---------------- Animations ---------------- */
            animation: {
                'fade-in-up': 'fadeInUp 0.8s ease-out',
                'gradient-shift': 'gradientShift 3s ease infinite',
                'slide-in-right': 'slideInRight 0.6s ease-out',
                'slide-in-left': 'slideInLeft 0.6s ease-out',
                float: 'float 6s ease-in-out infinite',
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },

            /* ---------------- Keyframes ---------------- */
            keyframes: {
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                gradientShift: {
                    '0%, 100%': { backgroundPosition: '0% 50%' },
                    '50%': { backgroundPosition: '100% 50%' },
                },
                slideInRight: {
                    '0%': { transform: 'translateX(100%)', opacity: '0' },
                    '100%': { transform: 'translateX(0)', opacity: '1' },
                },
                slideInLeft: {
                    '0%': { transform: 'translateX(-100%)', opacity: '0' },
                    '100%': { transform: 'translateX(0)', opacity: '1' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-20px)' },
                },
            },

            /* ---------------- Effects ---------------- */
            backdropBlur: {
                xs: '2px',
            },

            backgroundImage: {
                'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
            },
        },
    },

    plugins: [forms],
}



// import defaultTheme from 'tailwindcss/defaultTheme';
// import forms from '@tailwindcss/forms';

// /** @type {import('tailwindcss').Config} */
// export default {
//     content: [
//         './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
//         './storage/framework/views/*.php',
//         './resources/views/**/*.blade.php',
//         './resources/js/**/*.vue',
//     ],

//     theme: {
//         extend: {
//             fontFamily: {
//                 sans: ['Montserrat', ...defaultTheme.fontFamily.sans], // Add Montserrat
//                 //sans: ['Figtree', ...defaultTheme.fontFamily.sans],
//             },
//             colors: {
//                 'primary-teal': '#004d40', // You can remove this if you don't use it anymore
//                 'highlight-green': '#92e623',
//                 'button-green': '#28a745',
//                 // Define your new custom purple here
//                 'hero-purple': '#6A0DAD', // Example: a common shade of purple
//                 // Or a darker one:
//                 // 'hero-purple': '#4B0082', // Indigo

//                 'gold': {
//                     light: '#F7E7C5',    // For the icon box background
//                     DEFAULT: '#D4AF37',  // For the icon color and heading underline
//                     dark: '#A98D33',     // An optional darker shade if you need it
//                 },
//             },
//             // colors: {
//             //     // Define your custom colors here
//             //     'primary-teal': '#004d40',
//             //     'highlight-green': '#92e623',
//             //     'button-green': '#28a745',
//             //     // You can name them whatever makes sense for your project
//             // },
//         },
//     },

//     plugins: [forms],
// };
