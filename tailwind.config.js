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

module.exports = {
    mode: 'jit',
    purge: [
        './assets/scss/purge_safelist.txt',
        './src/Bundle/Resources/views/admin/**/*.twig',
        './assets/ts/admin/**/*.{js,jsx,ts,tsx,vue}',
    ],
    theme: {
        screens: {
            sm: '320px',
            md: '768px',
            lg: '1024px',
        },
        extend: {
            colors: {
                light: {
                    DEFAULT: dynamicHsl('var(--color-primary-h)', 'var(--color-primary-s)', 'calc(var(--color-primary-l) + 40%)'),
                },
                primary: {
                    DEFAULT: dynamicHsl('var(--color-primary-h)', 'var(--color-primary-s)', 'var(--color-primary-l)'),
                    100: dynamicHsl('var(--color-primary-h)', 'var(--color-primary-s)', 'calc(var(--color-primary-l) + 30%)'),
                    200: dynamicHsl('var(--color-primary-h)', 'var(--color-primary-s)', 'calc(var(--color-primary-l) + 24%)'),
                    300: dynamicHsl('var(--color-primary-h)', 'var(--color-primary-s)', 'calc(var(--color-primary-l) + 18%)'),
                    400: dynamicHsl('var(--color-primary-h)', 'var(--color-primary-s)', 'calc(var(--color-primary-l) + 12%)'),
                    500: dynamicHsl('var(--color-primary-h)', 'var(--color-primary-s)', 'calc(var(--color-primary-l) + 6%)'),
                    600: dynamicHsl('var(--color-primary-h)', 'var(--color-primary-s)', 'var(--color-primary-l)'),
                    700: dynamicHsl('var(--color-primary-h)', 'var(--color-primary-s)', 'calc(var(--color-primary-l) - 6%)'),
                    800: dynamicHsl('var(--color-primary-h)', 'var(--color-primary-s)', 'calc(var(--color-primary-l) - 12%)'),
                    900: dynamicHsl('var(--color-primary-h)', 'var(--color-primary-s)', 'calc(var(--color-primary-l) - 18%)'),
                },

                secondary: {
                    DEFAULT: dynamicHsl('var(--color-secondary-h)', 'var(--color-secondary-s)', 'var(--color-secondary-l)'),
                    100: dynamicHsl('var(--color-secondary-h)', 'var(--color-secondary-s)', 'calc(var(--color-secondary-l) + 30%)'),
                    200: dynamicHsl('var(--color-secondary-h)', 'var(--color-secondary-s)', 'calc(var(--color-secondary-l) + 24%)'),
                    300: dynamicHsl('var(--color-secondary-h)', 'var(--color-secondary-s)', 'calc(var(--color-secondary-l) + 18%)'),
                    400: dynamicHsl('var(--color-secondary-h)', 'var(--color-secondary-s)', 'calc(var(--color-secondary-l) + 12%)'),
                    500: dynamicHsl('var(--color-secondary-h)', 'var(--color-secondary-s)', 'calc(var(--color-secondary-l) + 6%)'),
                    600: dynamicHsl('var(--color-secondary-h)', 'var(--color-secondary-s)', 'var(--color-secondary-l)'),
                    700: dynamicHsl('var(--color-secondary-h)', 'var(--color-secondary-s)', 'calc(var(--color-secondary-l) - 6%)'),
                    800: dynamicHsl('var(--color-secondary-h)', 'var(--color-secondary-s)', 'calc(var(--color-secondary-l) - 12%)'),
                    900: dynamicHsl('var(--color-secondary-h)', 'var(--color-secondary-s)', 'calc(var(--color-secondary-l) - 18%)'),
                },

                tertiary: {
                    DEFAULT: dynamicHsl('var(--color-tertiary-h)', 'var(--color-tertiary-s)', 'var(--color-tertiary-l)'),
                    100: dynamicHsl('var(--color-tertiary-h)', 'var(--color-tertiary-s)', 'calc(var(--color-tertiary-l) + 30%)'),
                    200: dynamicHsl('var(--color-tertiary-h)', 'var(--color-tertiary-s)', 'calc(var(--color-tertiary-l) + 24%)'),
                    300: dynamicHsl('var(--color-tertiary-h)', 'var(--color-tertiary-s)', 'calc(var(--color-tertiary-l) + 18%)'),
                    400: dynamicHsl('var(--color-tertiary-h)', 'var(--color-tertiary-s)', 'calc(var(--color-tertiary-l) + 12%)'),
                    500: dynamicHsl('var(--color-tertiary-h)', 'var(--color-tertiary-s)', 'calc(var(--color-tertiary-l) + 6%)'),
                    600: dynamicHsl('var(--color-tertiary-h)', 'var(--color-tertiary-s)', 'var(--color-tertiary-l)'),
                    700: dynamicHsl('var(--color-tertiary-h)', 'var(--color-tertiary-s)', 'calc(var(--color-tertiary-l) - 6%)'),
                    800: dynamicHsl('var(--color-tertiary-h)', 'var(--color-tertiary-s)', 'calc(var(--color-tertiary-l) - 12%)'),
                    900: dynamicHsl('var(--color-tertiary-h)', 'var(--color-tertiary-s)', 'calc(var(--color-tertiary-l) - 18%)'),
                },

                quaternary: {
                    DEFAULT: dynamicHsl('var(--color-quaternary-h)', 'var(--color-quaternary-s)', 'var(--color-quaternary-l)'),
                    100: dynamicHsl('var(--color-quaternary-h)', 'var(--color-quaternary-s)', 'calc(var(--color-quaternary-l) + 30%)'),
                    200: dynamicHsl('var(--color-quaternary-h)', 'var(--color-quaternary-s)', 'calc(var(--color-quaternary-l) + 24%)'),
                    300: dynamicHsl('var(--color-quaternary-h)', 'var(--color-quaternary-s)', 'calc(var(--color-quaternary-l) + 18%)'),
                    400: dynamicHsl('var(--color-quaternary-h)', 'var(--color-quaternary-s)', 'calc(var(--color-quaternary-l) + 12%)'),
                    500: dynamicHsl('var(--color-quaternary-h)', 'var(--color-quaternary-s)', 'calc(var(--color-quaternary-l) + 6%)'),
                    600: dynamicHsl('var(--color-quaternary-h)', 'var(--color-quaternary-s)', 'var(--color-quaternary-l)'),
                    700: dynamicHsl('var(--color-quaternary-h)', 'var(--color-quaternary-s)', 'calc(var(--color-quaternary-l) - 6%)'),
                    800: dynamicHsl('var(--color-quaternary-h)', 'var(--color-quaternary-s)', 'calc(var(--color-quaternary-l) - 12%)'),
                    900: dynamicHsl('var(--color-quaternary-h)', 'var(--color-quaternary-s)', 'calc(var(--color-quaternary-l) - 18%)'),
                },
            },
        },
    },
    variants: {},
    plugins: [],
}
