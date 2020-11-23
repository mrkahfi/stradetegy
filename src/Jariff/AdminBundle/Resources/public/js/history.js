$(document).ready(function () {

    var History = window.History;
    var State   = History.getState();

    $('a.ajax').on('click', function(e) {
        e.preventDefault();
        var path  = $(this).attr('href');
        var title = $(this).attr('title');
        History.pushState('ajax',title,path);
    });

    History.Adapter.bind(window,'statechange',function() {
        load_ajax_data();
    });

    function load_ajax_data() {
        State = History.getState();   
        $.get(State.url + "?type=ajax", function(data) {
            $("#content").html(data);
        });
    }
    
});    