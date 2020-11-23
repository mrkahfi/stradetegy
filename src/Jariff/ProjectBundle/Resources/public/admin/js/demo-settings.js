/**
 * Product:        Social - Premium Responsive Admin Template
 * Version:        v1.3.1
 * Copyright:      2013 CesarLab.com
 * License:        http://themeforest.net/licenses
 * Live Preview:   http://go.cesarlab.com/SocialAdminTemplate
 * Purchase:       http://go.cesarlab.com/PurchaseSocial
 * IMPORTANT:      This file is for the live preview of this template
 *
*/

var DemoSettings;

DemoSettings = (function($) {
  "use strict";
  var config, handleAutoHideSidebarOption, handleDividersSideBarOption, handleThemeOptions, init, pattern;

  pattern = ".user > span, .navigation-sidebar > span,";
  pattern += ".menu .accordion-heading .arrow,";
  pattern += ".menu .accordion-heading a > span,";
  pattern += ".menu .accordion-body > li > a,";
  pattern += ".chat-users .user-list li a > span";
  /*
  */

  config = {
    urlThemes: 'none'
  };
  /*
  */

  init = function(options) {
    $.extend(config, options);
    handleDividersSideBarOption();
    handleAutoHideSidebarOption();
    handleThemeOptions();
  };
  /* Dividers in the sidebar
  */

  handleDividersSideBarOption = function() {
    /*  Sidebar Options
    */
    $("#demo-setting #sidebar-dividers").click(function() {
      var $sidebar;

      $sidebar = $(".social-sidebar");
      if ($(this).prop("checked")) {
        $sidebar.addClass("dividers");
      } else {
        $sidebar.removeClass("dividers");
      }
    });
  };
  /* Auto Hide Sidebar Option
  */

  handleAutoHideSidebarOption = function() {
    $("#demo-setting #sidebar-autohide").click(function() {
      var $main, $navbar, $sidebar, $wraper;

      $sidebar = $(".social-sidebar");
      $wraper = $(".wraper");
      $navbar = $(".social-navbar");
      $main = $("#main");
      if ($(this).prop("checked")) {
        $sidebar.removeClass("sidebar-full");
        $sidebar.addClass("auto-hide");
        $wraper.removeClass("sidebar-full");
      } else {
        $sidebar.addClass("sidebar-full");
        $sidebar.removeClass("auto-hide");
        $navbar.removeAttr("style");
        $main.removeAttr("style");
        $sidebar.find(pattern).removeAttr("style");
        $sidebar.find(".search-sidebar img").removeAttr("style");
        $(".icon-user.trigger-user-settings, .input-filter").removeAttr("style");
        $wraper.addClass("sidebar-full");
      }
    });
  };
  /*  Navbar Options
  */

  handleThemeOptions = function() {
    $("select[name=\"colorpicker\"]").simplecolorpicker();
    $("select[name=\"colorpicker\"]").on("change", function() {
      var element, themeName, themeStyleSheet;

      themeStyleSheet = $("#theme");
      element = $("option:selected", this);
      if (typeof element.attr("data-class") !== 'undefined') {
        themeName = config.urlThemes + element.attr("data-class") + '.css';
      } else {
        themeName = '#none';
      }
      themeStyleSheet.attr('href', themeName);
    });
  };
  return {
    init: init
  };
})(jQuery);
