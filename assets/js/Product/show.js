require('../../css/Product/show.scss');

$(function() {
    $('#add_to_cart_form').on('submit', function(e) {
        e.preventDefault();

        var form = $('#add_to_cart_form');

        $.ajax({
            type: "POST",
            url: Routing.generate('add_product_to_cart'),
            data: form.serialize(),
        }).done(function(data) {
            $('#top-bar-icon-cart-total').html(data);
            $('#top-bar-icon-cart-total').effect('shake');

            // $('#add_to_cart_submit').effect('transfer', { to: "#top-bar-icon-cart-total", className: "ui-effects-transfer" });

        }).fail(function() {
            console.log('Error!');
        });

        return false;
    });

    $('.lds-spinner').fadeOut(0);
    $('.fotorama').fadeIn();
});
