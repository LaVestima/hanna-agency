$(function() {
    $('#add_to_cart_form').on('submit', function(e) {
        e.preventDefault();

        var form = $('#add_to_cart_form');

        $.ajax({
            type: "GET",
            url: Routing.generate('add_product_to_cart'),
            data: form.serialize(),
        }).done(function(data) {
            console.log(data);
        }).fail(function() {
            console.log('error!');
        });

        return false;
    });
});
