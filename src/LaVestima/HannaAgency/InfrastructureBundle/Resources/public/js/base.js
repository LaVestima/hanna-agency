$(function() {
    $('.flash-message').click(function() {
        $(this).fadeOut('slow');
    });

    $('.module-box-title').click(function () {
        $(this).parent().find('.module-box-content').toggleClass('hidden');
    });


    $('.flash-message-list').delay(500)
        .animate({
            top: "0px"
        }, 500)
        .delay(3000)
        .fadeOut('slow');
});
