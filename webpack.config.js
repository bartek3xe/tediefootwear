const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore.enableSassLoader()

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .addEntry('admin/app', './assets/js/admin/app.js')
    .addEntry('admin/login', './assets/js/admin/login.js')
    .addEntry('admin/form-language-field', './assets/js/admin/form-language-field.js')
    .addEntry('admin/filepond', './assets/js/admin/filepond.js')
    .addEntry('admin/sidebar', './assets/js/admin/sidebar.js')
    .addEntry('js/flash', './assets/js/flash.js')
    .addEntry('js/navbar', './assets/js/navbar.js')
    .addEntry('js/faq', './assets/js/faq.js')
    .addEntry('js/mobile-categories', './assets/js/mobile-categories.js')
    .addEntry('js/product-details', './assets/js/product-details.js')
    .addEntry('js/search', './assets/js/search.js')
    .addEntry('js/list', './assets/js/admin/list.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    .copyFiles({
        from: './assets/images',
        to: 'images/[path][name].[ext]',
    })

    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
