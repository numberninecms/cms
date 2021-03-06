const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('src/Bundle/Resources/public/build/')
    .setPublicPath('/bundles/numbernine/build')
    .setManifestKeyPrefix('bundles/numbernine/build/')

    .addEntry('adminbar', './assets/ts/adminbar.ts')
    .addStyleEntry('adminpreviewmode', './assets/scss/page_builder.scss')
    .addStyleEntry('security', './assets/scss/security.scss')

    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
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
;

module.exports = Encore.getWebpackConfig();
