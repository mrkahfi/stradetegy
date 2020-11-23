/**
 * Product:        Social - Premium Responsive Admin Template
 * Version:        v1.3.1
 * Copyright:      2013 CesarLab.com
 * License:        http://themeforest.net/licenses
 * Live Preview:   http://go.cesarlab.com/SocialAdminTemplate
 * Purchase:       http://go.cesarlab.com/PurchaseSocial
 *
*/

var FormValidation;

FormValidation = (function($) {
  "use strict";
  /*
  */

  var addChosenValidator, handleFormValidation, handleUserBirthday, handleUserTitle, handleUserWebsite, handleUsername, init;
  init = function() {
    handleUsername();
    handleUserTitle();
    handleUserWebsite();
    handleUserBirthday();
    addChosenValidator();
    handleFormValidation();
  };
  /* Propose username by combining first- and lastname
  */

  handleUsername = function() {
    $("#register-form #username").focus(function() {
      var firstname, lastname;
      firstname = $("#firstname").val();
      lastname = $("#lastname").val();
      if (firstname && lastname && !this.value) {
        this.value = firstname + "." + lastname;
      }
    });
  };
  /* Add Chosen Feature to the User Title
  */

  handleUserTitle = function() {
    $("#register-form #title").css("position", "absolute").css("z-index", "-9999").chosen().show();
  };
  /*
  */

  handleUserWebsite = function() {
    $("#register-form #url").bind("focus", function(e) {
      if ($.trim($(e.target).val()) === "") {
        $(e.target).val("http://");
      }
    });
  };
  /*
  */

  handleUserBirthday = function() {
    $("#register-form #date").mask("99/99/9999");
  };
  /*
  */

  addChosenValidator = function() {
    $.validator.addMethod("chosen", (function(value, element) {
      if (value === 0) {
        return false;
      } else {
        if (value.length === 0) {
          return false;
        } else {
          return true;
        }
      }
    }), "Please select an option");
    $.validator.addMethod("dateformat", (function(value, element) {
      return value.match(/^\d\d?\/\d\d?\/\d\d\d\d$/);
    }), "Please enter a date in the format mm/dd/yyyy");
  };
  /*
  */

  handleFormValidation = function() {
    $("#register-form").validate({
      errorElement: "span",
      errorPlacement: function(error, element) {
        error.appendTo(element.parents("div.controls"));
        error.addClass("help-block");
        element.parents(".control-group").removeClass("success").addClass("error");
        return element.parents(".control-group").find("a.chzn-single").addClass("error");
      },
      success: function(label) {
        label.parents(".control-group").removeClass("error");
        return label.parents(".control-group").find("a.chzn-single").removeClass("error");
      },
      rules: {
        title: {
          required: true,
          min: 1,
          chosen: true
        },
        firstname: {
          required: true,
          minlength: 2
        },
        lastname: {
          required: true,
          minlength: 2
        },
        username: {
          required: true,
          minlength: 2,
          maxlength: 10
        },
        password1: {
          required: true,
          minlength: 6,
          maxlength: 12
        },
        password2: {
          required: true,
          minlength: 6,
          equalTo: "#password1"
        },
        email: {
          required: true,
          email: true
        },
        date: {
          required: true,
          dateformat: true
        },
        url: {
          required: true,
          url: true
        },
        gender: {
          required: true
        },
        agree: "required"
      },
      messages: {
        title: {
          min: "Chose an option"
        },
        gender: {
          min: "Chose an option"
        }
      },
      submitHandler: function(form) {
        return $("#register-form #submit-button").button("loading");
      }
    });
  };
  return {
    init: init
  };
})(jQuery);
