$(document).ready(function () {
    $('#subscription_everythingPlan').on('change', function () {
        alert($('#subscription_everythingPlan').val());
    });

        validatePIF();

        // start update pricing
        $('#button_everything').on('click', function (e) {
            everything();
            updatePrice();
            e.preventDefault();
        })

        $('#button_custom').on('click', function (e) {
            custom();
            updatePrice();
            e.preventDefault();
        })
        $('#subscription_history').on('change', function () {
            updatePrice();
        })
        $('#subscription_search').on('change', function () {
            updatePrice();
        })
        $('#subscription_download').on('change', function () {
            updatePrice();
        })
        $('#subscription_bigPicture').on('change', function () {
            updatePrice();
        })
        $('#subscription_month').on('change', function () {
            updatePrice();
        })
        $('#subscription_customDiscount').on('change paste keyup', function () {
            updatePrice();
        })
        $('#subscription_paymentTerm').on('change', function () {
            updatePrice();
            validatePIF();
        })


        function validatePIF(){
            // jika paypal dan mtm maka tidak bisa,
            // kasih opsi untuk bayar PIF atau CC
                if ($('#subscription_paymentTerm').val() == 'mtm' && member_cc == null ) {
                    $( "#dialog-confirm" ).dialog({
                        resizable: false,
                        height:300,
                        width:500,
                        modal: true,
                        buttons: {
                            "Change term to PIF": function() {
                                $('#subscription_paymentTerm').val("pif");
                                updatePrice();
                                $( this ).dialog( "close" );
                            },
                            "Add new CC": function() {
                                $( location ).attr('href', Routing.generate('admin_member_cc_new', { "id": member_id }));
                            }
                        }
                    });
                }
        }

        
        function updatePrice(){
            // update payment term
            var paymentTerm    = $('#subscription_paymentTerm').val();
            if (paymentTerm == 'mtm' ) {
                $('#subscription_month').parent().hide();
                $('#total_payment_text').text("Per month");
            } else if(paymentTerm == 'pif') {
                $('#subscription_month').parent().show();
                $('#total_payment_text').text("OTP due today");
            }

            // calculate custom price 
            var subscription_history    = parseInt($('#subscription_history').val());
            var subscription_search     = parseInt($('#subscription_search').val());
            var subscription_download   = parseInt($('#subscription_download').val());
            var subscription_bigPicture = parseInt($('#subscription_bigPicture').val());
            var price_custom       = subscription_history + subscription_search + subscription_download + subscription_bigPicture;
            $('#price_custom').text( price_custom );

            // calculate PIF month
            var subscription_month_string = $('#subscription_month').val();
            var subscription_month_length = 1;

            // calculate monthly price
            var price = price_custom;
            if ($('#subscription_everythingPlan').val() == "true") {
                price = 200;
            }

            // calculate discount based on pif / mtm
            var paymentTerm    = $('#subscription_paymentTerm').val();
            var total_discount = 0;
            if(paymentTerm == 'pif') {
                if (subscription_month_string == '20' ) {
                    subscription_month_length = 12;
                }
                if (subscription_month_string == '15' ) {
                    subscription_month_length = 6;
                }
                if (subscription_month_string == '10' ) {
                    subscription_month_length = 3;
                }
                var subscription_month_percent = parseInt(subscription_month_string) / 100;
                total_discount                 = Math.ceil(price * subscription_month_percent * subscription_month_length);
            }
            var custom_discount = 0;
            if( parseInt($('#subscription_customDiscount').val()) >= 0 ){
                custom_discount = parseInt($('#subscription_customDiscount').val());
                $('#total_custom_discount').text( custom_discount );
            }
            // update total price
            $('#total_discount').text( total_discount );
            $('#total_payment').text( price * subscription_month_length - total_discount - custom_discount );
        }

        // show everything pricing table
        function everything(){
            $('#button_everything').addClass("btn-primary");
            $('#button_custom').removeClass("btn-primary");
            $('#button_everything').addClass("disabled");
            $('#button_custom').removeClass("disabled");

            $('#subscription_everythingPlan').val("true");

            $('#subscription_history').parent().hide();
            $('#subscription_download').parent().hide();
            $('#subscription_bigPicture').parent().hide();
            $('#subscription_search').parent().hide();
        }

        // show custom pricing table
        function custom(){
            $('#button_custom').addClass("btn-primary");
            $('#button_everything').removeClass("btn-primary");
            $('#button_custom').addClass("disabled");
            $('#button_everything').removeClass("disabled");

            $('#subscription_everythingPlan').val("false");

            $('#subscription_history').parent().show();
            $('#subscription_download').parent().show();
            $('#subscription_bigPicture').parent().show();
            $('#subscription_search').parent().show();
        }
        // end update pricing
});    