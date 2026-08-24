import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import daisyui from 'daisyui';

export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './app/Livewire/**/*.php',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
    },
  },

  plugins: [forms, daisyui],

  daisyui: {
    themes: ["light"],
  },
};
