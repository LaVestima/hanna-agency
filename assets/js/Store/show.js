require('../../css/Store/show.scss');

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

$('#top-bar-opinions').on('click', function() {
    $('.producer-subpage').addClass('hidden');
    $('#subpage-opinions').removeClass('hidden');

    $('.producer-top-bar-item').removeClass('active');
    $(this).addClass('active');
});

$('#top-bar-contact').on('click', function() {
    $('.producer-subpage').addClass('hidden');
    $('#subpage-contact').removeClass('hidden');

    $('.producer-top-bar-item').removeClass('active');
    $(this).addClass('active');
});


$(".producer-top-bar-item").on("click", function(e) {
    window.location.hash = $(e.target).attr("href").substr(1);
});

$(function() {
    var hash = window.location.hash;
    $('.producer-top-bar div[href="' + hash + '"]').click();
});
