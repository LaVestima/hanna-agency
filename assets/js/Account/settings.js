$(function() {
    $('#add-address').on('click', function() {
        var list = $($(this).attr('data-list-selector'));
        var counter = list.data('widget-counter') | list.children().length;
        var newWidget = list.attr('data-prototype');

        newWidget = newWidget.replace(/__name__/g, counter);
        counter++;

        list.data('widget-counter', counter);

        var newElem = $(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);
    });

    $('#address-fields-list').on('click', '.remove-address', function() {
        $(this).parent('li').remove();
    });
});
