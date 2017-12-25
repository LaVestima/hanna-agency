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

$(function () {
    filters = [];

    $('.entity-list-content').html('<tr><td>Loading...</td></tr>');
});