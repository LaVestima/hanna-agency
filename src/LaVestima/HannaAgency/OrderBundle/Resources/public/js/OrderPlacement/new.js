function validateQuantity(inputField) {
    if (inputField.val() < 0) {
        inputField.val(0);
    }
    else if (inputField.val() > 0) {
        selectProduct(inputField);
    }
    else if (inputField.val() == 0) {
        unselectProduct(inputField);
    }
}

function selectProduct(inputField) {
    var inputIndex = $('.product-list-quantities input').index(inputField);
    $('#place_order_products_' + inputIndex).prop('checked', true);
}

function unselectProduct(inputField) {
    var inputIndex = $('.product-list-quantities input').index(inputField);
    $('#place_order_products_' + inputIndex).prop('checked', false);
}

$(function() {
    $('.product-list-quantities input').click(function() {
        validateQuantity($(this));
    });

    $('.product-list-quantities input').change(function() {
        validateQuantity($(this));
    });
});