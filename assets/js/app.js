// yarn encore dev

require('../css/app.scss');

var $ = require('jquery');
global.$ = global.jQuery = $;
require('@fortawesome/fontawesome-free/css/all.css');

// bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json

const routes = require('../../public/js/fos_js_routes.json');
var Routing = require('../../public/bundles/fosjsrouting/js/router.min.js');
Routing.setRoutingData(routes);
global.Routing = Routing;
