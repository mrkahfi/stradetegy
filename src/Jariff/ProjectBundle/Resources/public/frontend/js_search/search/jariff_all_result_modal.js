$(document).ready(function () {
    $('.j-check-cat-modal').on('click', function () {
        if (!$("input.j-check-cat-modal:eq(0):checked").val()) {
            $('.j-modal-range-importer').css({"opacity": "0.4", "filter": "alpha(opacity=40)"})
        } else {
            $('.j-modal-range-importer').css({"opacity": "", "filter": ""})
        }
        if (!$("input.j-check-cat-modal:eq(1):checked").val()) {
            $('.j-modal-range-exporter').css({"opacity": "0.4", "filter": "alpha(opacity=40)"})
        } else {
            $('.j-modal-range-exporter').css({"opacity": "", "filter": ""})
        }
        if (!$("input.j-check-cat-modal:eq(2):checked").val()) {
            $('.j-modal-range-product').css({"opacity": "0.4", "filter": "alpha(opacity=40)"})
        } else {
            $('.j-modal-range-product').css({"opacity": "", "filter": ""})
        }

    });

    $('.j-modal-show').on('click', function () {

        $.ajax({
            type: 'GET',
            url: Routing.generate('member_export_download_list'),
            success: function (data) {
                $('#downloadList').html(data);
            }
        });
    })


})