var isLeftMenuActive = 0;

function showLeftMenu() {
    $(".left-menu").animate({
        left: "0px"
    }, 500);
    $(".left-menu-button").animate({
        left: "150px"
    }, 500);
    $(".body-overlay").fadeIn("fast");
    isLeftMenuActive = 1;
}

function hideLeftMenu() {
    $(".left-menu").animate({
        left: "-150px"
    }, 500);
    $(".left-menu-button").animate({
        left: "0px"
    }, 500);
    $(".body-overlay").fadeOut("fast");
    isLeftMenuActive = 0;
}

$(document).ready(function() {
    $(".body-overlay").fadeOut(0);
    $(".body-overlay").css("visibility", "visible");

    $(".left-menu-button").click(function() {
        console.log('dddd');
        if (!isLeftMenuActive) {
            showLeftMenu();
        }
        else {
            hideLeftMenu();
        }
    });

    $(".body-overlay").click(function() {
        if(isLeftMenuActive) {
            hideLeftMenu();
        }
    });

    $(document).keydown(function(event) {
        if (event.keyCode == 27) {
            if (isLeftMenuActive) {
                hideLeftMenu();
            }
        }
    });
});