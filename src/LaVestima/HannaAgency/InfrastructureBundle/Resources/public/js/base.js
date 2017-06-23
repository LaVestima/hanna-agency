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

    // var lastScrollTop = 0;
    //
    // $(window).scroll(function () {
    //     var currentScrollTop = $(this).scrollTop();
    //
    //     if (currentScrollTop > 35 && currentScrollTop > lastScrollTop) {
    //         $('.page-title').addClass('page-title-scrolled');
    //     } else {
    //         $('.page-title').removeClass('page-title-scrolled');
    //     }
    //
    //     console.log(lastScrollTop);
    //     console.log(currentScrollTop);
    //
    //     lastScrollTop = currentScrollTop;
    // })
});
