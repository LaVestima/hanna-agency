var Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    .addEntry('app', './assets/js/app.js')
    .addEntry('access_control_registration', './assets/js/AccessControl/registration.js')
    .addEntry('flash_message', './assets/js/flash_message.js')
    .addEntry('list', './assets/js/list.js')
    .addEntry('store_show', './assets/js/Store/show.js')
    .addEntry('product_edit', './assets/js/Product/edit.js')
    .addEntry('product_show', './assets/js/Product/show.js')
    .addEntry('product_cell', './assets/js/Product/cell.js')
    .addEntry('order_cart', './assets/js/Order/cart.js')
    .addEntry('search_home', './assets/js/Search/home.js')


    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()
    .splitEntryChunks()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()

    // .autoProvideVariables({
    //     // "Routing": 'router'
    //     "Routing": 'Routing'
    // })
    // .addLoader({
    //     test: /jsrouting-bundle\/Resources\/public\/js\/router.js$/,
    //     loader: "exports-loader?router=window.Routing"
    // })
;

let config = Encore.getWebpackConfig();
module.exports = config;


