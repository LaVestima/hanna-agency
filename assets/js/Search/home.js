$(function() {
    $('.quick-view-icon').on('click', function() {
        $('.product-cell').removeClass('quick-view');
        $(this).closest('.product-cell').addClass('quick-view');
        // $(this).effect('transfer', {
        //     to: $(this).closest('.product-cell'), className: "ui-effects-transfer"
        // });
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    });

    $('.quick-view-close').on('click', function() {
        $(this).closest('.product-cell').removeClass('quick-view');
    });
});
