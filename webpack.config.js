const Encore = require('@symfony/webpack-encore');
const WatchExternalFilesPlugin = require('webpack-watch-files-plugin').default;

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('src/Bundle/Resources/public/build/')
    .setPublicPath('/bundles/numbernine/build')
    .setManifestKeyPrefix('bundles/numbernine/build/')

    .addEntry('adminbar', './assets/ts/adminbar.ts')
    .addEntry('admin', './assets/ts/admin/admin.ts')
    .addStyleEntry('adminpreviewmode', './assets/scss/page_builder.scss')
    .addStyleEntry('security', './assets/scss/security.scss')

    .enableStimulusBridge('./assets/ts/admin/controllers.json')

    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableSassLoader()
    .enableTypeScriptLoader()
    .addRule({
        enforce: 'pre',
        test: /\.ts$/,
        exclude: /node_modules/,
        loader: 'eslint-loader',
    })
    .enablePostCssLoader((options) => {
        options.postcssOptions = {
            plugins: [
                require('postcss-import'),
                require('tailwindcss'),
                require('autoprefixer'),
            ]
        }
    })
    .addPlugin(new WatchExternalFilesPlugin({
        files: [
            './src/Bundle/Resources/views',
            './assets/scss/purge_safelist.txt',
        ],
        verbose: true
    }))
;

module.exports = Encore.getWebpackConfig();
