


jQuery(document).ready(function() {


    $(".datetimepicker").datetimepicker();


    // callTime other start
    updateDataInterestOther();
    function updateDataInterestOther () {
      $( "select[id$='_dataInterest']" ).each(function( index, element ) {

        $(this).select2();
        $(this).on("change", function() {
            updateDataInterestOther();
        })

        if (parseInt($(this).val()) > 0 ) {
            $(this).parent().next().hide();
        } else {
            $(this).parent().next().show();
        }
      });
    }
    // callTime other end

    // callTime other start
    updateCallTimeOther();
    function updateCallTimeOther () {
      $( "select[id$='_callTime']" ).each(function( index, element ) {

        $(this).on("change", function() {
            updateCallTimeOther();
        })

        if (parseInt($(this).val()) > 0 ) {
            $(this).parent().next().hide();
        } else {
            $(this).parent().next().show();
        }
      });
      $( "select[id$='_country']" ).select2();
    }
    // callTime other end

    // competitor other start
    updateCompetitorOther();
    $("#lead_competitor").on("change", function() {
        updateCompetitorOther();
    })
    function updateCompetitorOther () {
        if (parseInt($('#lead_competitor').val()) > 0 ) {
            $('input#lead_competitorOther').parent().hide();
        } else {
            $('input#lead_competitorOther').parent().show();
        }
    }
    // competitor other end

    // business type other start
    updateBusinessTypeOther();
    $("#lead_businessType").on("change", function() {
        updateBusinessTypeOther();
    })
    function updateBusinessTypeOther () {
        if (parseInt($('#lead_businessType').val()) > 0 ) {
            $('input#lead_businessTypeOther').parent().hide();
        } else {
            $('input#lead_businessTypeOther').parent().show();
        }
    }
    // business type other end

    // lead stage start
    updateStageReason();
    $("#lead_stage").on("change", function() {
        updateStageReason();
    })
    function updateStageReason () {
        if (parseInt($('#lead_stage').val()) == 3 ) {
            $('input#lead_stageReason').parent().show();
        } else {
            $('input#lead_stageReason').parent().hide();
        }
    }
    // lead stage end

    // lead source start
    updateSourceOther();
    $("#lead_source").on("change", function() {
        updateSourceOther();
    })
    function updateSourceOther () {
        if (parseInt($('#lead_source').val()) > 0) {
            $('input#lead_sourceOther').parent().hide();
        } else {
            $('input#lead_sourceOther').parent().show();
        }
    }
    // lead source end

    // Competitor start
    // first page load init
    updateCompetitor();
    updateCompetitorStatus();

    // binding function
    $("#lead_competitor").on("change", function() {
        updateCompetitor();
        updateCompetitorStatus();
    })
    $('#lead_competitorStatus').on("change", function () {
        updateCompetitorStatus();
    })

    // reusable function
    function updateCompetitor () {
        // alert($('#lead_competitor').val());
        if (parseInt($('#lead_competitor').val()) > 0) {
            $('#lead_competitorStatus').parent().show();
        } else {
            $('#lead_competitorStatus').parent().hide();
        }
    }

    function updateCompetitorStatus () {
        if (parseInt($('#lead_competitor').val()) > 0) {
            if ($('#lead_competitorStatus').val() == 'Active Subscriber' ) {
                $('input#lead_competitorDateEnd').parent().show();
            } else {
                $('input#lead_competitorDateEnd').parent().hide();
            }
        } else {
            $('input#lead_competitorDateEnd').parent().hide();
        }

        // $('input#lead_competitorDateEnd').datetimepicker();
    }
    // Competitor end


    // setup an "add a tag" link
    var $addContactLink    = $('<a href="#" class="btn btn-primary">Add Contact</a>');
    var $newLinkLi         = $('<div class="row-fluid"></div>').append($addContactLink);
    
    // Get the div that holds the collection of contact
    var $collectionContact = $('div#contact');

    // add the "add a tag" anchor and li to the contact ul
    $collectionContact.after($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionContact.data('index', $collectionContact.find(':input').length);

    $addContactLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addContactForm($collectionContact, $newLinkLi);
    });

    function addContactForm($collectionContact, $newLinkLi) {

        // Get the data-prototype explained earlier
        var prototype = $collectionContact.data('prototype');

        // get the new index
        var index = $collectionContact.data('index');

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionContact.data('index', index + 1);

        // Display the form in the page in an li, before the "Add a tag" link li
        var $newFormLi = $('<div class="row-fluid"></div>').append(newForm);
        $newLinkLi.before($newFormLi);

        updateCallTimeOther();
    }

    // Data Interest start
    var $addDataInterestLink    = $('<a href="#" class="btn btn-primary">Add Data Interest</a>');
    var $newLinkDataInterest                = $('<div></div>').append($addDataInterestLink);
    var $collectionDataInterest = $('div#dataInterest');

    $collectionDataInterest.append($newLinkDataInterest);
    $collectionDataInterest.data('index', $collectionDataInterest.find(':input').length);
    $addDataInterestLink.on('click', function(e) {
        e.preventDefault();
        addDataInterestForm($collectionDataInterest, $newLinkDataInterest);
    });

    function addDataInterestForm($collectionDataInterest, $newLinkDataInterest) {
        var prototype = $collectionDataInterest.data('prototype');
        var index     = $collectionDataInterest.data('index');
        var newForm   = prototype.replace(/__name__/g, index);
        $collectionDataInterest.data('index', index + 1);
        var $newForm = $('<div></div>').append(newForm);
        $newLinkDataInterest.before($newForm);
        updateDataInterestOther();
    }
    // Data Interest end

    // Sales start    
    var $addSalesLink    = $('<a href="#" class="btn btn-primary">Add Sales</a>');
    var $newLinkSales    = $('<div class="row-fluid"></div>').append($addSalesLink);
    var $collectionSales = $('div#sales');

    $collectionSales.append($newLinkSales);
    $collectionSales.data('index', $collectionSales.find(':input').length);
    $addSalesLink.on('click', function(e) {
        e.preventDefault();
        addSalesForm($collectionSales, $newLinkSales);
    });

    function addSalesForm($collectionSales, $newLinkSales) {
        var prototype = $collectionSales.data('prototype');
        var index     = $collectionSales.data('index');
        var newForm   = prototype.replace(/__name__/g, index);
        $collectionSales.data('index', index + 1);
        var $newForm = $('<div class="row-fluid"></div>').append(newForm);
        $newLinkSales.before($newForm);
    }
    // Sales end    
});