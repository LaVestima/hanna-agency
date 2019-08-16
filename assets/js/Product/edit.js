require('../../css/Product/edit.scss');

$(function() {
    $('#add-image').on('click', function() {
        var list = $($(this).attr('data-list-selector'));
        var counter = list.data('widget-counter') | list.children().length;
        var newWidget = list.attr('data-prototype');

        newWidget = newWidget.replace(/__name__/g, counter);
        counter++;

        list.data('widget-counter', counter);

        var newElem = $(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);

        $(newElem).find('input[type=file]').trigger('click');
    });

    $('#image-fields-list').on('click', '.remove-image', function() {
        $(this).parent('li').remove();
    });

    $('#add-variant').on('click', function() {
        var list = $($(this).attr('data-list-selector'));
        var counter = list.data('widget-counter') | list.children().length;
        var newWidget = list.attr('data-prototype');

        newWidget = newWidget.replace(/__name__/g, counter);
        counter++;

        list.data('widget-counter', counter);

        var newElem = $(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);
    });

    $('#variant-fields-list').on('click', '.remove-variant', function() {
        $(this).parent('li').remove();
    });

    $('#add-parameter').on('click', function() {
        var list = $($(this).attr('data-list-selector'));
        var counter = list.data('widget-counter') | list.children().length;
        var newWidget = list.attr('data-prototype');

        newWidget = newWidget.replace(/__name__/g, counter);
        counter++;

        list.data('widget-counter', counter);

        var newElem = $(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);
    });

    $('#parameter-fields-list').on('click', '.remove-parameter', function() {
        $(this).parent('li').remove();
    });



    $("#fileUpload").on('change', function () {
        if (typeof (FileReader) == "undefined") {
            alert("This browser does not support FileReader.");
            return;
        }

        var countFiles = $(this)[0].files.length;

        console.log($(this));

        var image_holder = $("#image-holder");
        image_holder.empty();

        for (var i = 0; i < countFiles; i++) {
            var file = $(this)[0].files[i];
            console.log(file);

            if (!file.type.match('image/*')) {
                continue;
            }

            var reader = new FileReader();

            console.log(reader);

            reader.onload = function (e) {
                $("<img />", {
                    "src": e.target.result,
                    "class": "thumb-image"
                }).appendTo(image_holder);
            };

            image_holder.show();
            reader.readAsDataURL($(this)[0].files[i]);
        }
    });
});
