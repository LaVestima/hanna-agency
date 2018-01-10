var isLeftMenuActive = 0;
var leftMenuWidth = 0;

function showLeftMenu() {
    $(".left-menu").animate({
        left: "0px"
    }, 500);
    $(".left-menu-button").animate({
        left: leftMenuWidth + "px"
    }, 500);
    $('.left-menu-button > span').animate({
        width: 0,
        opacity: 0
    }, 500);
    $(".body-overlay").fadeIn("fast");
    isLeftMenuActive = 1;
}

function hideLeftMenu() {
    $(".left-menu").animate({
        left: "-" + leftMenuWidth + "px"
    }, 500);
    $(".left-menu-button").animate({
        left: "0px"
    }, 500);
    $('.left-menu-button > span').animate({
        width: '55px',
        opacity: 1
    }, 500);
    $(".body-overlay").fadeOut("fast");
    isLeftMenuActive = 0;
}

$('.left-menu').css({
    "left": "-9999px",
    "opacity": "0"
});

$(document).ready(function() {
    leftMenuWidth = $('.left-menu').width();

    $(window).resize(function () {
        leftMenuWidth = $('.left-menu').width();

        if (!isLeftMenuActive) {
            $('.left-menu').css({
                'left': '-' + leftMenuWidth + 'px'
            });
            $('.left-menu-button').css({
                'left': '0'
            });
        } else {
            $('.left-menu-button').css({
                'left': leftMenuWidth + 'px'
            });
        }
    });

    $(".body-overlay").fadeOut(0);
    $(".body-overlay").css("visibility", "visible");

    $('.left-menu').css({
        "left": "-" + leftMenuWidth + "px",
        "opacity": "1"
    });

    $(".left-menu-button").click(function() {
        if (!isLeftMenuActive) {
            showLeftMenu();
        }
        else {
            hideLeftMenu();
        }
    });

    $(".left-menu-close-button").click(function() {
        hideLeftMenu();
    });

    $(".body-overlay").click(function() {
        if(isLeftMenuActive) {
            hideLeftMenu();
        }
    });

    $(document).keydown(function(event) {
        if (event.keyCode === 27) {
            if (isLeftMenuActive) {
                hideLeftMenu();
            }
        }
    });
});