$(document).ready(function () {

    var $collectionHolder;

    //        $('.j-show').children('div').addClass('form-group');

    // setup an "add a tag" link
    var $addFilterLink = $('<div class="form-group"><div class="col-md-2">' +
        ' <a href="#" class="add_filter_link btn btn-info">Add Criteria <span class="glyphicon glyphicon-circle-arrow-down" aria-hidden="true"></span></a></div></div>');
    var $newLinkLi = $('<div></div>').append($addFilterLink);

    jQuery(document).ready(function () {
        // Get the ul that holds the collection of tags
        $collectionHolder = $('div.j-show');

        // add a delete link to all of the existing tag form li elements
        $collectionHolder.find('div.j-add-filter').each(function () {
            if ($(this).length > 1) {
                addTagFormDeleteLink($(this));
            }
        });

        // add the "add a tag" anchor and li to the tags ul
        $collectionHolder.append($newLinkLi);

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addFilterLink.on('click', function (e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            var check = $('h5.show');

            if (check.length == 1) {
                $("#dialog-message").dialog({
                    modal: true
                });
                return false;
            }

            // add a new tag form (see next code block)
            addTagForm($collectionHolder, $newLinkLi);
        });


    });

    function addTagForm($collectionHolder, $newLinkLi) {
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Display the form in the page in an li, before the "Add a tag" link li
        var $newFormLi = $('<div><div style="clear: both"></div></div> ').append(newForm);
        $newLinkLi.before($newFormLi);

        // add a delete link to the new form
        addTagFormDeleteLink($newFormLi);
    }

    function addTagFormDeleteLink($tagFormLi) {
        var $removeFormA = $('<div class="form-group">' +
            '<div class="col-md-1">' +
            '<a href="#" class="j-different-remove btn btn-danger" style="font-size: 16px;"' +
            'title="Delete This Filter"> <span class="glyphicon glyphicon-remove" aria-hidden="true"' +
            '></span> remove </a>' +
            '</div>' +
            '</div>');
        $tagFormLi.append($removeFormA);

        $removeFormA.on('click', function (e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // remove the li for the tag form
            $tagFormLi.remove();
        });
    }

    $('.j-choice-country').on("change", function () {

        var company_as = $(".j-choice-as").val();
        var country = $(this).val();

        $.ajax({
            type: 'POST',
            url: Routing.generate('member_search_shipments_' + country + '_' + company_as + '_field'),
            data: $('.j-form-change').children("form").serialize(),
            beforeSend: function () {
                $('#modalLoadingClick').children('.modal-dialog').html('<div id="chart_div">' +
                    '<div class="modal-body" style="height: 100px">' +
                    '<div class="css3-spinner">' +
                    '<div class="css3-spinner-bounce1"></div>' +
                    '<div class="css3-spinner-bounce2"></div>' +
                    '<div class="css3-spinner-bounce3"></div>' +
                    '</div>' +
                    '</div>' +
                    '</div>');

                $('#modalLoadingClick').modal('show');
            },
            success: function (data) {
                var res = JSON.parse(data);
                var letterCode;
                if (res.success) {
                    $('.j-form-change').html(res.html_string);

                    var countryOption = $(".j-choice-country").children('option');
                    countryOption.removeAttr('disabled');
                    for (letterCode in res.country_list) {
                        for (var i = 0; i < countryOption.length; i++) {
                            if (countryOption.eq(i).val() == letterCode) {
                                countryOption.eq(i).attr('disabled', 'disabled');
                            }
                        }

                    }
                    $('#modalLoadingClick').modal('hide');
                } else {
                    $('#modalLoadingClick').children('.modal-dialog').html(res.html_string);
                    $('.j-form-change').html(res.html_callback);
                }


            }
        })
    })

    $('.j-choice-as').on("change", function () {

        var company_as = $(this).val();
        var country = $('.j-choice-country').val();

        $.ajax({
            type: 'POST',
            url: Routing.generate('member_search_shipments_' + country + '_' + company_as + '_field'),
            data: $('.j-form-change').children("form").serialize(),
            beforeSend: function () {
                $('#modalLoadingClick').children('.modal-dialog').html('<div id="chart_div">' +
                    '<div class="modal-body" style="height: 100px">' +
                    '<div class="css3-spinner">' +
                    '<div class="css3-spinner-bounce1"></div>' +
                    '<div class="css3-spinner-bounce2"></div>' +
                    '<div class="css3-spinner-bounce3"></div>' +
                    '</div>' +
                    '</div>' +
                    '</div>');

                $('#modalLoadingClick').modal('show');
            },
            success: function (data) {
                var res = JSON.parse(data);
                var letterCode;
                if (res.success) {
                    $('.j-form-change').html(res.html_string);

                    var countryOption = $(".j-choice-country").children('option');
                    countryOption.removeAttr('disabled');
                    for (letterCode in res.country_list) {
                        for (var i = 0; i < countryOption.length; i++) {
                            if (countryOption.eq(i).val() == letterCode) {
                                countryOption.eq(i).attr('disabled', 'disabled');
                            }
                        }

                    }
                    $('#modalLoadingClick').modal('hide');
                } else {
                    $('#modalLoadingClick').children('.modal-dialog').html(res.html_string);
                    $('.j-form-change').html(res.html_callback);
                }


            }
        })
    })

    
})