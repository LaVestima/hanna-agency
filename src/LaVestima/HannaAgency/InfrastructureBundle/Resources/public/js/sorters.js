function sortSearch(sorter, column, path)
{
    sorterIndex = $(sorter).closest('th').index();
    sorterLabels = $(sorter).closest('tr').find('th i');

    $.each(sorterLabels, function (index, value) {
        if (index !== sorterIndex) {
            $(value).removeClass('fa-sort-asc')
                .removeClass('fa-sort-desc')
                .addClass('fa-sort');
        }
    });

    direction = 'asc';

    if ($(sorter).find('i').hasClass('fa-sort-asc')) {
        direction = 'desc';
        $(sorter).find('i').removeClass('fa-sort-asc');
        $(sorter).find('i').addClass('fa-sort-desc');
    } else if ($(sorter).find('i').hasClass('fa-sort-desc')) {
        $(sorter).find('i').addClass('fa-sort-asc');
        $(sorter).find('i').removeClass('fa-sort-desc');
    } else {
        $(sorter).find('i').addClass('fa-sort-asc');
    }

    sorters[0] = {
        direction: direction,
        column: column
    };

    $.ajax({
        type: "GET",
        url: path,
        data: {
            filters: filters,
            sorters: sorters
        }
    }).done(function(data) {
        $('.entity-list-content').html(data);
    });
}

$(function () {
    sorters = [];
});
