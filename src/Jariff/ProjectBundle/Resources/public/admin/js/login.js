/**
 * Product:        Social - Premium Responsive Admin Template
 * Version:        v1.3.1
 * Copyright:      2013 CesarLab.com
 * License:        http://themeforest.net/licenses
 * Live Preview:   http://go.cesarlab.com/SocialAdminTemplate
 * Purchase:       http://go.cesarlab.com/PurchaseSocial
 *
*/

var Login;

Login = (function($) {
  "use strict";
  var handleBackButton, handleForgotButton, init;
  init = function() {
    handleBackButton();
    handleForgotButton();
  };
  handleBackButton = function() {
    $(".btn-back").click(function() {
      $(".form-login").show();
      $(".form-forgot").hide();
    });
  };
  handleForgotButton = function() {
    $("#link-forgot").click(function() {
      $(".form-login").hide();
      $(".form-forgot").show();
    });
  };
  return {
    init: init
  };
})(jQuery);
