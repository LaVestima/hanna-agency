$(function() {
    $(window).click(function () {
        $('.action-bar-export-list > li').addClass('hidden');
    });

    $('.flash-message').click(function() {
        $(this).fadeOut('slow');
    });

    $('.module-box-title').click(function () {
        $(this).parent().find('.module-box-content').toggleClass('hidden');
    });

    $('.flash-message-list').delay(300)
        .animate({
            top: "0px"
        }, 500)
        .delay(3000)
        .fadeOut('slow');

    $('.action-bar-export-button').click(function () {
        event.stopPropagation();
        $('.action-bar-export-list > li').toggleClass('hidden');
    });
});
