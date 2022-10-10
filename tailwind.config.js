function dynamicHsl(h, s, l) {
    return ({ opacityVariable, opacityValue }) => {
        if (opacityValue !== undefined) {
            return `hsla(${h}, ${s}, ${l}, ${opacityValue})`
        }
        if (opacityVariable !== undefined) {
            return `hsla(${h}, ${s}, ${l}, var(${opacityVariable}, 1))`
        }
        return `hsl(${h}, ${s}, ${l})`
    }
}

function palette(color) {
    const h = `var(--color-${color}-h)`
    const s = `var(--color-${color}-s)`
    const l = `var(--color-${color}-l)`

    return {
        DEFAULT: dynamicHsl(h, s, l),
        100: dynamicHsl(h, s, `calc(${l} + 30%)`),
        200: dynamicHsl(h, s, `calc(${l} + 24%)`),
        300: dynamicHsl(h, s, `calc(${l} + 18%)`),
        400: dynamicHsl(h, s, `calc(${l} + 12%)`),
        500: dynamicHsl(h, s, `calc(${l} + 6%)`),
        600: dynamicHsl(h, s, l),
        700: dynamicHsl(h, s, `calc(${l} - 6%)`),
        800: dynamicHsl(h, s, `calc(${l} - 12%)`),
        900: dynamicHsl(h, s, `calc(${l} - 18%)`),
    }
}

module.exports = {
    content: [
        './assets/scss/purge_safelist.txt',
        './src/Bundle/Resources/views/**/*.twig',
        './assets/ts/**/*.{js,jsx,ts,tsx,vue}',
    ],
    theme: {
        screens: {
            sm: '320px',
            md: '768px',
            lg: '1024px',
        },
        extend: {
            minHeight: (theme) => ({
                ...theme('spacing'),
            }),
            inset: {
                'ui-area': '48px',
            },
            margin: {
                'ui-area': '48px',
            },
            colors: {
                light: {
                    DEFAULT: dynamicHsl('var(--color-primary-h)', 'var(--color-primary-s)', 'calc(var(--color-primary-l) + 40%)'),
                },
                primary: palette('primary'),
                secondary: palette('secondary'),
                tertiary: palette('tertiary'),
                quaternary: palette('quaternary'),
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
