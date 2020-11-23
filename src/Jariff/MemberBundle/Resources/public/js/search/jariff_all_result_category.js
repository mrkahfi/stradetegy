$(document).ready(function () {
    $.ajax({
        type: 'GET',
        url: Routing.generate('ajax_member_cache_group_searching', {category: 'importer'}),
        success: function (data) {
            $('#importer').html(data);
        }
    })
    $.ajax({
        type: 'GET',
        url: Routing.generate('ajax_member_cache_group_searching', {category: 'exporter'}),
        success: function (data) {
            $('#exporter').html(data);
        }
    })
    $.ajax({
        type: 'GET',
        url: Routing.generate('ajax_member_cache_group_searching', {category: 'product'}),
        success: function (data) {
            $('#product').html(data);
        }
    })


})