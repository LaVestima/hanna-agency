$(function() {
    $('#add-to-cart').on('click', function() {
        console.log('ajax');
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
    });

});
