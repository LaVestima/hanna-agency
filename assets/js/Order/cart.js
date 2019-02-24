$(function() {
    // $('.quantity-increase').on('click', function() {
    //     var currentQuantity = $(this).parent('.quantity').find('.quantity-value');
    //
    //     // console.log(currentQuantity);
    //
    //     console.log('+' + parseInt(currentQuantity.text()));
    //
    //     currentQuantity.text(parseInt(currentQuantity.text()) + 1);
    // });
    //
    // $('.quantity-decrease').on('click', function() {
    //     var currentQuantity = $(this).parent('.quantity').find('.quantity-value');
    //
    //     console.log('-' + parseInt(currentQuantity.text()));
    //
    //     currentQuantity.text(Math.max(parseInt(currentQuantity.text()) - 1, 1));
    //
    //
    // });

    $('.quantity-value').on('change', function() {
        var newQuantity = $(this).val();

        if (newQuantity < 1) {
            $(this).val(1);
            newQuantity = 1;
        }

        var productPrice = $(this).attr('data-product-price');
        newPrice = Number.parseFloat(newQuantity * productPrice).toFixed(2);

        $(this).parent('.pricing').find('.total-product-price').text(newPrice);
    });

});

function increaseProductCartQuantity(productPathSlug) {
    $.ajax({
        type: "GET",
        url: Routing.generate('add_product_to_cart'),
        data: {
            product: $(this).attr('data-product'),
        }
    }).done(function(data) {
        // $('.names-table').html(data);
        console.log(data);
    }).fail(function() {
        // $('.names-table').html('Connection error!');
    });
}
