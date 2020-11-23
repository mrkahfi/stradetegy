$(document).ready(function () {
    var check = $('.input_searching').length;

    if (check > 1)
        $('.j-condition').attr('style', 'margin-right:100px');

    $('.j-condition').on('click', function () {
        $('.j-condition').attr('style', 'margin-right:100px');
        var numItems = $('.j-append-condition').length;
        if (numItems < 14) {

            $.ajax({
                type: 'GET',
                url: Routing.generate('member_search_fix_append_form'),
                success: function (data) {
                    $('.j-add-condition').append(data);
                }
            })
        } else {
            alert('Sorry cannot add condition again');
        }


        return false;

    });

    $('.j-size-per-page').on('change', function () {

        $('.j-size').val($(this).val());

        ajaxResultData($('.j-searching').serialize());
//                window.history.pushState("object or string", "Title", url + "?" + $('.j-searching').serialize());
//                return false;
    });

    $('.j-filter-see').on("click", function () {
        var total = $('.j-filter-see').length;
        var data = '';
        for (var i = 0; i < total; i++) {
            if ($("input.j-filter-see:eq(" + i + "):checked").val()) {
                if (data != '')
                    data += ' or ' + $("input.j-filter-see:eq(" + i + "):checked").val();
                else
                    data += $("input.j-filter-see:eq(" + i + "):checked").val();
            }
        }


        $('.j-filter-leftbar').val(data);
        ajaxResultData($('.j-searching').serialize());
    });

    $('.j-check-country').on("click", function () {
        var total = $('.j-check-country').length;
        var data = '';
        var textCountry = '';
        for (var i = 0; i < total; i++) {

            if ($("input.j-check-country:eq(" + i + "):checked").val()) {
                if (data != '') {
                    data += ' or ' + $("input.j-check-country:eq(" + i + "):checked").val();
                    textCountry += ', ' + $("input.j-check-country:eq(" + i + "):checked").val();
                } else {
                    data += $("input.j-check-country:eq(" + i + "):checked").val();
                    textCountry += $("input.j-check-country:eq(" + i + "):checked").val();
                }
            }
        }

        if (textCountry == '') {
            $('.j-all-export-result').html('(All Category Importer)');
        } else {
            $('.j-all-export-result').html('(All Category Importer and filter country ' + textCountry + ')');
        }

        $('.j-filter-country').val(data);
        ajaxResultData($('.j-searching').serialize());

    });


    $.ajax({
        type: 'GET',
        url: Routing.generate('member_search_fix_ajax_total_product'),
        data: $('.j-searching').serialize(),
        beforeSend: function () {
            $('.j-total-product').html('<img src="/bundles/jariffproject/frontend/images/301_12.GIF">');
            $('.j-total-exporter').html('<img src="/bundles/jariffproject/frontend/images/301_12.GIF">');
            $('.j-total-importer').html('<img src="/bundles/jariffproject/frontend/images/301_12.GIF">');
        },
        success: function (data) {
            var totalExporter = $('.j-total-exporter').attr('data-total');
            var totalImporter = $('.j-total-importer').attr('data-total');


            if (isNaN(data))
                data = 0;


            $('.j-total-product').html(data);
            $('.j-total-modal-product').html(data);
            $('.j-tot-val-product').val(data);

            if (isNaN(totalExporter))
                totalExporter = 0;

            if (isNaN(totalImporter))
                totalImporter = 0;




            $('.j-total-exporter').html(parseInt(totalExporter));
            $('.j-total-modal-exporter').html(parseInt(totalExporter));

            $('.j-total-importer').html(parseInt(totalImporter));
            $('.j-total-modal-importer').html(parseInt(totalImporter));

            $('.j-export-to').html('<option value="all">All Category (' + (parseInt(totalExporter) + parseInt(totalImporter)) + ')</option>' +
                '<option value="importer">Importir (' + totalImporter + ') </option>' +
                '<option value="exporter">Exportir (' + totalExporter + ') </option>')
        }
    });

    $('.j-export-to').on('change', function () {
        if ($(this).val() == 'all')
            $('.j-all-export-result').html('(All Category)');
        if ($(this).val() == 'importer')
            $('.j-all-export-result').html('(Importer)');
        if ($(this).val() == 'exporter')
            $('.j-all-export-result').html('(Exporter)');
    })

    $('.j-add-tab-product').click(function () {

        var nThis = $(this);
        var header = $(nThis).parent('.image_rollover').siblings('.header-search-trade').children('.imartist').children('.j-add-tab-products').html();

        $.ajax({
            type: 'GET',
            url: $(nThis).parent('.image_rollover').siblings('.header-search-trade').children('.imartist').children('.j-add-tab-products').attr('data-url'),
            data: {header: header, body: $('.j-body-products').val()},

            success: function (data) {
                $('#product').html(data);
            }
        });


        $(nThis).parent('.image_rollover').parent('.popresult').addClass('disable-choice');


        var $myDialog = $('<div></div>')
            .html('<p><span class="ui-icon ui-icon-alert"></span>Your choice will be input into the tab product.</p>')
            .dialog({
                autoOpen: false,
                modal: true,
                width: 500,
                draggable: false,
                height: 200,
                title: 'Product Choice',
                open: function (event, ui) {
                    setTimeout(function () {
                        $myDialog.dialog('close');
                        return true;
                    }, 2000);
                }
            });

        return $myDialog.dialog('open');

    });

    $('.j-add-tab-import').on('click', function () {

        var nThis = $(this);
        $.ajax({
            type: 'GET',
            url: $(nThis).parent('.image_rollover').siblings('.header-search-trade').children('.imartist').children('.j-add-tab-importer').attr('data-url'),
            data: {header: '', body: ''},

            success: function (data) {
                $('#importer').html(data);
            }
        })

        $(nThis).parent('.image_rollover').parent('.popresult').addClass('disable-choice');

        var $myDialog = $('<div></div>')
            .html('<p><span class="ui-icon ui-icon-alert"></span>Your choice will be input into the tab importer.</p>')
            .dialog({
                autoOpen: false,
                modal: true,
                width: 500,
                draggable: false,
                height: 200,
                title: 'Importer Choice',
                open: function (event, ui) {
                    setTimeout(function () {
                        $myDialog.dialog('close');
                        return true;
                    }, 2000);
                }
            });

        return $myDialog.dialog('open');

//                $('#importers').tab('show');
    });

    $('.j-add-tab-export').on('click', function () {
        var nThis = $(this);
        $.ajax({
            type: 'GET',
            url: $(nThis).parent('.image_rollover').siblings('.header-search-trade').children('.imartist').children('.j-add-tab-exporter').attr('data-url'),
            data: {header: '', body: ''},

            success: function (data) {
                $('#exporter').html(data);
            }
        })
        $(nThis).parent('.image_rollover').parent('.popresult').addClass('disable-choice');

        var $myDialog = $('<div></div>')
            .html('<p><span class="ui-icon ui-icon-alert"></span>Your choice will be input into the tab exporter.</p>')
            .dialog({
                autoOpen: false,
                modal: true,
                width: 500,
                draggable: false,
                height: 200,
                title: 'Exporter Choice',
                open: function (event, ui) {
                    setTimeout(function () {
                        $myDialog.dialog('close');
                        return true;
                    }, 2000);
                }
            });

        return $myDialog.dialog('open');
    });


    $('.pagination ul li a').on('click', function () {
        $.ajax({
            type: 'GET',
            url: $(this).attr('href'),
            data: $('.j-searching').serialize(),
            beforeSend: function () {
                $('#page1').html('<div style="text-align: center">' +
                    '<img src="/bundles/jariffproject/frontend/images/301.GIF">' +
                    '<h5 style="margin-top: 15px">Loading...</h5>' +
                '</div>'
                )
                ;
            },
            success: function (data) {
                $('#page1').html(data);
            }
        })

        var urlSearch = Routing.generate('member_search_result');
        var url = document.URL.split('?');
        var page = $(this).attr('href').split('/');

        if (parseInt(page[page.length - 1]))
            page = page[page.length - 1];
        else
            page = 1;

        window.history.pushState("object or string", "Title", urlSearch + '/' + page + '?' + url[1]);

        return false;
    })

    function ajaxResultData(formSubmit) {
        $.ajax({
            type: 'GET',
            url: Routing.generate('member_search_filter_ajax'),
            data: formSubmit,
            beforeSend: function () {
                $('#page1').html('<div style="margin-top:20px;text-align: center">' +
                    '<img src="/bundles/jariffproject/frontend/images/301.GIF">' +
                    '<h5 style="margin-top: 15px">Loading...</h5>' +
                '</div>');
            },
            success: function (data) {
                $('#page1').html(data);
            }
        });
    }


    $('.j-remove-append').on('click', function () {
        $(this).parent('.button_click').parent('.form_info').parent('.cmsms_media_box').parent('.cmsms_media').parent('.j-append-condition').remove();
    })

    $('.toolti').tooltip('toggle');
    $('.tooltip').hide();

})