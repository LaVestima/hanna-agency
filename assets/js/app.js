// yarn encore dev

// any CSS you require will output into a single css file (app.scss in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
var $ = require('jquery');
global.$ = global.jQuery = $;
require('@fortawesome/fontawesome-free/css/all.css');

const routes = require('../../public/js/fos_js_routes.json');
var Routing = require('../../public/bundles/fosjsrouting/js/router.min.js');
Routing.setRoutingData(routes);
global.Routing = Routing;
