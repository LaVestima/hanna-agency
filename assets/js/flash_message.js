$(function() {
    $('.flash-message').click(function() {
        $(this).fadeOut('slow');
    });

    $('.flash-message-list').delay(200)
        .animate({
            top: "0px"
        }, 500)
        .delay(5000)
        .fadeOut('slow');
});
