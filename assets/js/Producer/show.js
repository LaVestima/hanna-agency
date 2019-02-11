require('../../css/Producer/show.scss');

$('#top-bar-homepage').on('click', function() {
    $('.producer-subpage').addClass('hidden');
    $('#subpage-homepage').removeClass('hidden');

    $('.producer-top-bar-item').removeClass('active');
    $(this).addClass('active');
});

$('#top-bar-products').on('click', function() {
    $('.producer-subpage').addClass('hidden');
    $('#subpage-products').removeClass('hidden');

    $('.producer-top-bar-item').removeClass('active');
    $(this).addClass('active');
});

$('#top-bar-contact').on('click', function() {
    $('.producer-subpage').addClass('hidden');
    $('#subpage-contact').removeClass('hidden');

    $('.producer-top-bar-item').removeClass('active');
    $(this).addClass('active');
});
