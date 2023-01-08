const webpack = require('webpack');
const Encore = require('@symfony/webpack-encore');
const path = require('path');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/bundles/numbernine/build')
    .setManifestKeyPrefix('bundles/numbernine/build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('adminbar', './assets/ts/adminbar.ts')
    .addEntry('admin', './assets/ts/admin/admin.ts')
    .addStyleEntry('adminpreviewmode', './assets/scss/page_builder.scss')
    .addStyleEntry('security', './assets/scss/security.scss')

    .addAliases({
        'admin': path.resolve(__dirname, 'assets/ts/admin/'),
        'styles': path.resolve(__dirname, 'assets/scss/'),
        'images': path.resolve(__dirname, 'assets/images/'),
        'assets': path.resolve(__dirname, 'assets/'),
        'vue': 'vue/dist/vue.esm-bundler',
    })

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/ts/admin/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // configure Babel
    // .configureBabel(function(config) {
    //     config.plugins.push(['prismjs', {
    //         'languages': ['shortcode'],
    //         'css': true,
    //     }]);
    // })

    // enables and configure @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    // enables Sass/SCSS support
    .enableSassLoader(() => {}, { resolveUrlLoader: false })

    // uncomment if you use TypeScript
    .enableTypeScriptLoader()
    .enableForkedTypeScriptTypesChecking()

    .enableVueLoader(() => {}, { runtimeCompilerBuild: false })

    .enablePostCssLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()

    .addPlugin(new webpack.DefinePlugin({
        __VUE_OPTIONS_API__: true,
        __VUE_PROD_DEVTOOLS__: false,
    }))

    .addPlugin(new webpack.ProvidePlugin({
        process: 'process/browser',
    }))
;

module.exports = Encore.getWebpackConfig();
