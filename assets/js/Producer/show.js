require('../../css/Producer/show.scss');

$('#top-bar-products').on('click', function() {
    $('#page-contact').fadeOut(0);
    $('#page-products').fadeIn(0);
});

$('#top-bar-contact').on('click', function() {
    $('#page-products').fadeOut(0);
    $('#page-contact').fadeIn(0);
});
