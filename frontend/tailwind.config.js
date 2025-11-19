// tailwind.config.cjs
module.exports = {
  content: [
    './index.html',
    './src/**/*.{vue,js,ts,jsx,tsx}',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#007AFF',
          50: '#EAF6FF',
          100: '#D7F0FF',
          200: '#AEE2FF',
          300: '#85D4FF',
          400: '#4CC4FF',
          500: '#00B4FF',
        },
        teal: {
          DEFAULT: '#00C2CB',
        },
        accent: {
          DEFAULT: '#FFD700',
        },
        iavbg: '#F9FBFF',
      },
      borderRadius: {
        'xl-2': '18px',
      },
      boxShadow: {
        soft: '0 10px 30px rgba(2,6,23,0.08)',
      },
    },
  },
  plugins: [],
};
