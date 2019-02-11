function filterSearch(filter, column, path) {
    if (filter !== undefined && column !== undefined) {
        filterIndex = $(filter).closest('td').index();
        filterInputs = $(filter).closest('tr').find('td input');
        filterValue = $(filterInputs[filterIndex]).val();

        filters[filterIndex] = {
            value: filterValue,
            column: column
        };
    }

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

function sortSearch(sorter, column, path)
{
    sorterIndex = $(sorter).closest('th').index();
    sorterLabels = $(sorter).closest('tr').find('th i');

    $.each(sorterLabels, function (index, value) {
        if (index !== sorterIndex) {
            $(value).removeClass('fa-sort-up')
                .removeClass('fa-sort-down')
                .addClass('fa-sort');
        }
    });

    direction = 'asc';

    if ($(sorter).find('i').hasClass('fa-sort-up')) {
        direction = 'desc';
        $(sorter).find('i').removeClass('fa-sort-up');
        $(sorter).find('i').addClass('fa-sort-down');
    } else if ($(sorter).find('i').hasClass('fa-sort-down')) {
        $(sorter).find('i').addClass('fa-sort-up');
        $(sorter).find('i').removeClass('fa-sort-down');
    } else {
        $(sorter).find('i').addClass('fa-sort-up');
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

function tableSearch(input) {
    var filter, table, tr, td, i;

    columnIndex = $(input).closest('td').index();
    filter = input.value.toUpperCase();
    filterTr = $(input).closest('tr');
    trs = $(input).closest('table').find('tr');

    for (i = filterTr.index() + 1; i < trs.length; i++) {
        tr = $(trs[i]);
        td = $(tr.find('td')[columnIndex]);
        tdText = td.text();

        if (tdText) {
            if (tdText.toUpperCase().indexOf(filter) > -1) {
                tr.fadeIn('fast');
            } else {
                tr.fadeOut('fast');
            }
        }
    }
}

$(function() {
    filters = [];
    sorters = [];

    $('.entity-list-content').html('<tr><td>Loading...</td></tr>');
    filterSearch(undefined, undefined, '/' + $('.entity-list').attr('entity') + '/Async/list');

    $('.entity-list-filters input').keyup(function() {
        filterSearch($(this), $(this).attr('column'), Routing.generate($(this).attr('route')))
    });

    $('.entity-list-header ua').on('click', function() {
        sortSearch($(this), $(this).attr('column'), Routing.generate($(this).attr('route')))
    });
});

$(document).on("click", '.pagination .button', function(event) {
    // console.log('aaaaaaaaaa');
    link = $(this).closest('ua').attr('href');
    if (link) {
        filterSearch(undefined, undefined, link);
    }
    link = undefined;
});
