var inputField = null;

function validateQuantity() {
    var quantity = Number(inputField.val());

    if (quantity < 0) {
        inputField.val(0);
    }
    else if (quantity > 0) {
        selectProduct();
    }
    else if (quantity === 0) {
        unselectProduct();
    }

    var availability = Number(inputField.closest('tr').find('.product-list-availabilities > label').html());

    if (quantity > availability) {
        inputField.val(availability);
    }
}

function selectProduct() {
    var inputIndex = $('.product-list-quantities input').index(inputField);
    $('#place_order_productsSizes_' + inputIndex).prop('checked', true);
}

function unselectProduct() {
    var inputIndex = $('.product-list-quantities input').index(inputField);
    $('#place_order_productsSizes_' + inputIndex).prop('checked', false);
}

function validateProductSelection() {
    if (inputField.prop('checked')) {
        setQuantityValue(1);
    }
    else {
        setQuantityValue(0);
    }
}

function setQuantityValue(value) {
    var inputIndex = $('.product-list-names input').index(inputField);
    $('#place_order_quantities_' + inputIndex).val(value);
}

$(function() {
    $('.product-list-quantities input').on('click change keydown keyup', function() {
        inputField = $(this);
        validateQuantity();
    });

    $('.product-list-names input').on('change', function () {
        inputField = $(this);
        validateProductSelection();
    });
});
