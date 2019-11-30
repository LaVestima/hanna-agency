// yarn encore dev

require('../css/app.scss');
require('@fortawesome/fontawesome-free/css/all.css');

var $ = require('jquery');
global.$ = global.jQuery = $;


// bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json

const routes = require('../../public/js/fos_js_routes.json');
var Routing = require('../../public/bundles/fosjsrouting/js/router.min.js');
Routing.setRoutingData(routes);
global.Routing = Routing;

require('jqtree');

$(function () {
    $('.select2').select2();
    $('[title]').tipso({
        speed: 100,
        delay: 0,
        useTitle: true,
        position: 'top',
        background: '#333333',
        width: 'auto',
    });
    $('.tipso-bottom').tipso({
        speed: 100,
        delay: 0,
        useTitle: true,
        position: 'bottom',
        background: '#333333',
        width: 'auto',
    });

    window.cookieconsent.initialise({
        "palette": {
            "popup": {
                "background": "#000",
                "text": "#ffffff"
            },
            "button": {
                "background": "transparent",
                "text": "#ff0000",
                "border": "#ff0000"
            }
        },
        "position": "bottom-right"
    });

    $('#search-bar #query').keyup(function() {
        $.ajax({
            type: "GET",
            url: Routing.generate('search_bar_suggestions'),
            data: {
                q: $(this).val(),
            }
        }).done(function(data) {
            $('#search-bar-suggestions').empty();

            if ($('#search-bar #query').val().length > 0) {
                $.each(data, function (id, elem) {
                    $('#search-bar-suggestions').append(
                        '<li><a href="' + elem.url + '">' + elem.name + '</a></li>'
                    );
                });
            }
        }).fail(function() {
            console.log('Connection error!');
        });
    });

    $('.dropdown').on('click', function() {
        $(this).find('.dropdown-content').toggleClass('visible');
    });

    $(document).click(function(event) {
        $target = $(event.target);
        // console.log($target);

        if ($target.hasClass('dropdown')) {
            // $(this).find('.dropdown-content').toggleClass('visible');
        }

        $visibleDropdown = $('.dropdown-content:visible');
        // console.log($visibleDropdown.closest('.dropdown'));
        // console.log($target.closest('.dropdown'));

        if (!$visibleDropdown.closest('.dropdown').is($target.closest('.dropdown'))) {
            // console.log('CLOSE');
            $visibleDropdown.removeClass('visible');
        }

        // if (!$target.hasClass('dropdown-content')) {
        if(!$target.closest('.dropdown').find('.dropdown-content').length && $('.dropdown-content').is(":visible")) {
            // $('.dropdown-content').removeClass('visible');
        }
    });
});


