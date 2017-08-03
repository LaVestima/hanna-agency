var inputField = null;

function validateQuantity() {
    if (Number(inputField.val()) < 0) {
        inputField.val(0);
    }
    else if (Number(inputField.val()) > 0) {
        selectProduct();
    }
    else if (Number(inputField.val()) === 0) {
        unselectProduct();
    }
}

function selectProduct() {
    var inputIndex = $('.product-list-quantities input').index(inputField);
    $('#place_order_products_' + inputIndex).prop('checked', true);
}

function unselectProduct() {
    var inputIndex = $('.product-list-quantities input').index(inputField);
    $('#place_order_products_' + inputIndex).prop('checked', false);
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
    var inputIndex = $('.product-list-names input').index(inputField) + 1;
    $('#place_order_quantities_quantity_' + inputIndex).val(value);
}

$(function() {
    $('.product-list-quantities input').on('click change keyup', function() {
        inputField = $(this);
        validateQuantity();
    });

    $('.product-list-names input').on('change', function () {
        inputField = $(this);
        validateProductSelection();
    });
});