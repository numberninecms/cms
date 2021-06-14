const {resolve} = require('path');

module.exports = {
    root: true,
    extends: [
        'eslint:recommended',
        'plugin:@typescript-eslint/recommended',  // Uses the recommended rules from the @typescript-eslint/eslint-plugin
        'plugin:@typescript-eslint/eslint-recommended',
        'plugin:@typescript-eslint/recommended-requiring-type-checking',
        'plugin:vue/vue3-recommended',
        'prettier',
        'plugin:prettier/recommended',  // Enables eslint-plugin-prettier and displays prettier errors as ESLint errors. Make sure this is always the last configuration in the extends array.
    ],
    plugins: [
        '@typescript-eslint',
        'vue',
    ],
    parserOptions: {
        parser: '@typescript-eslint/parser',
        sourceType: 'module',
        project: resolve(__dirname, './tsconfig.json'),
        tsconfigRootDir: __dirname,
        ecmaVersion: 2020,
        extraFileExtensions: ['.vue'],
    },
    rules: {
        '@typescript-eslint/no-explicit-any': 'off',
        '@typescript-eslint/no-non-null-assertion': 'off',
        '@typescript-eslint/no-unsafe-call': 'off',
        '@typescript-eslint/explicit-member-accessibility': 'error',
        '@typescript-eslint/ban-types': 'off',
        '@typescript-eslint/no-unsafe-assignment': 'off',
        '@typescript-eslint/no-empty-interface': 'off',
        'vue/html-indent': ['error', 4],

        // Conflict with prettier
        'vue/max-attributes-per-line': 'off',

        // Causes error in PageBuilderCompiler
        'vue/one-component-per-file': 'off',
    },
    env: {
        browser: true,
        amd: true,
        node: true,
        es6: true,
    },
};
