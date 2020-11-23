// $('#work_date').datetimepicker();


$(function() {

  $("#template-contactform").validate({
    submitHandler: function (form) {
      $('.form-process').fadeIn();
      $('#contact-form-result').attr('data-notify-msg', 'Sending your message...').html('');
      SEMICOLON.widget.notifications($('#contact-form-result'));
      $('#demo_request_button').prop("disabled", true);
      // $("#contactform_submit").prop("disabled", true).addClass("ui-state-disabled");
      $("#contactform_submit").prop('value', 'Sending your message...');
      $(form).ajaxSubmit({
        target: '#contact-form-result',
        success: function () {
         console.log('contact success');
         $('.form-process').fadeOut();
         $('#template-contactform').find('.sm-form-control').val('');
         $('#contact-form-result').attr('data-notify-msg', '<i class=icon-ok-sign></i> Request Sent Successfully!').html('');
         SEMICOLON.widget.notifications($('#contact-form-result'));
         // $("#contactform_submit").prop("disabled", false).addClass("ui-state-enable");
         $("#contactform_submit").prop('value', 'SEND MESSAGE');
      $('#demo_request_button').prop("disabled", false);
       }
     });
    }
  });

var dialog, form,

emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
name = $( "#first_name" ),
email = $( "#demo_email" ),
allFields = $( [] ).add( name ).add( email ),
tips = $( ".validateTips" );

function updateTips( t ) {
 tips
 .text( t )
 .addClass( "ui-state-highlight" );
 setTimeout(function() {
  tips.removeClass( "ui-state-highlight", 1500 );
}, 500 );
}

function checkLength( o, n, min, max ) {
 if ( o.val().length > max || o.val().length < min ) {
  o.addClass( "ui-state-error" );
  updateTips( "Length of " + n + " must be between " +
   min + " and " + max + "." );
  return false;
} else {
  return true;
}
}

function checkRegexp( o, regexp, n ) {
 if ( !( regexp.test( o.val() ) ) ) {
  o.addClass( "ui-state-error" );
  updateTips( n );
  return false;
} else {
  return true;
}
}

function validate() {
 var valid = true;
 allFields.removeClass( "ui-state-error" );

 valid = valid && checkLength( email, "email", 6, 80 );
 valid = valid && checkRegexp( email, emailRegex, "eg. myname@something.com" );

 if (valid) {
  $('.demo-form-process').fadeIn();
  $('#demo-form-result').attr('data-notify-msg', 'Sending your request...');
  SEMICOLON.widget.notifications($('#demo-form-result'));
  $(form).ajaxSubmit({
    target: '#demo-form-result',
    success: function () {
      dialog.dialog( "close" );
      console.log('success');
      $('.demo-form-process').fadeOut();
      $('#template-demoform').find('.sm-form-control').val('');
      $('#demo-form-result').attr('data-notify-msg', '<i class=icon-ok-sign></i> Request Sent Successfully!');
      SEMICOLON.widget.notifications($('#demo-form-result'));
    }
  });
}
return valid;
}

dialog = $("#dialog-form").dialog({
 autoOpen: false,
 height: 600,
 width: 650,
 modal: true,
      buttons: {
        "SEND REQUEST": validate,
        Cancel: function() {
          dialog.dialog( "close" );
        }
      },
 close: function() {
  form[ 0 ].reset();
  allFields.removeClass( "ui-state-error" );
}
});

// dialog.bind('clickoutside',function(){
//   dialog.dialog('close');
// });

$('#demo_request_button').button().on("click", function() {
 validate();
 return false;
});

form = dialog.find("form").on( "submit", function( event ) {
 event.preventDefault();
 validate();
});

$("#demo-button").button().on("click", function() {
 dialog.dialog("open");
 return false;
});

});