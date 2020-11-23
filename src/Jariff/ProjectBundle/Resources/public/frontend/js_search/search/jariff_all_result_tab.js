$(document).ready(function () {
    var pageImages = [];
    var pageNum = 1;

    /**
     * Reset numbering on tab buttons
     */
    function reNumberPages() {
        pageNum = 1;
        var tabCount = $('#pageTab > li').length;
        $('#pageTab > li').each(function () {
            var pageId = $(this).children('a').attr('href');
            if (pageId == "#page1") {
                return true;
            }

        });
    }

    /**
     * Remove a Tab
     */
    $('#pageTab').on('click', ' li a .close', function () {
        var nThis = $(this);
        var tabId = $(this).parents('li').children('a').attr('href');
        var $myDialog = $('<div></div>')
            .html('<p><span class="ui-icon ui-icon-alert"></span>All data in this tab will disappear. Are you sure?</p>')
            .dialog({
                autoOpen: false,
                modal: true,
                width: 500,
                draggable: false,
                height: 200,
                title: 'Why so serious remove this tab (' + $(nThis).parents('li').children('a').attr('data-title') + ')?',
                buttons: {
                    "OK": function () {
                        $(nThis).parents('li').remove('li');
                        $(tabId).remove();
                        $('#pageTab a:first').tab('show');
                        $(this).dialog("close");
                        var url = $(nThis).attr('data-url');

                        $.ajax({
                            type: 'GET',
                            url: url,
                            success: function (data) {
                                return true;
                            }
                        })
                        return true;
                    },
                    "Cancel": function () {
                        $(this).dialog("close");
                        return false;
                    }
                }
            });

        return $myDialog.dialog('open');


    });

    /**
     * Click Tab to show its content
     */
    $("#pageTab").on("click", "a", function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    $('#exportTools').on('submit', function () {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            beforeSend: function (data) {
                $('.loading').html('Process submit...');
            },
            success: function (data) {
                var obj = JSON.parse(data);

                if (obj.success) {
                    $('.j-message').html('<div class="alert alert-success">' + obj.message + '</div>');
                } else {
                    $('.j-message').html('<div class="alert alert-error">' + obj.message + '</div>');
                }


            }
        });

        return false;
    })
})
