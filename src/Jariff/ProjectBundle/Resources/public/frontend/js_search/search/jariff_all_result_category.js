$(document).ready(function () {
    $.ajax({
        type: 'GET',
        url: Routing.generate('ajax_member_cache_group_searching', {category: 'importer','s_cache':app.request.get('s_cache')}),
        success: function (data) {
            $('#importer').html(data);
        }
    })
    $.ajax({
        type: 'GET',
        url: Routing.generate('ajax_member_cache_group_searching', {category: 'exporter','s_cache':app.request.get('s_cache')}),
        success: function (data) {
            $('#exporter').html(data);
        }
    })
    $.ajax({
        type: 'GET',
        url: Routing.generate('ajax_member_cache_group_searching', {category: 'product','s_cache':app.request.get('s_cache')}),
        success: function (data) {
            $('#product').html(data);
        }
    })


})