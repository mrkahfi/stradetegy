/**
 * Product:        Social - Premium Responsive Admin Template
 * Version:        v1.3.1
 * Copyright:      2013 CesarLab.com
 * License:        http://themeforest.net/licenses
 * Live Preview:   http://go.cesarlab.com/SocialAdminTemplate
 * Purchase:       http://go.cesarlab.com/PurchaseSocial
 *
*/

var SocialBoxes;

SocialBoxes = (function($) {
  "use strict";
  var config, getSocialBoxesOrder, handleCollapsible, handleSomeTweaks, handleSortable, handleSortableOnMobile, ieVersion, init, isTouchDevice, setSerialisedOutput;

  ieVersion = false;
  /*
  */

  config = {
    sortable: true,
    sortableContainer: ".sortable-socialboxes",
    columnContainer: ".column",
    serialisedOutputContainer: "#serialised-utput",
    collapsible: true
  };
  /* Get the IE version.  This will be 6 for IE6, 7 for IE7, etc...
  */

  if (/MSIE\s([\d.]+)/.test(navigator.userAgent)) {
    ieVersion = Number(RegExp.$1);
  } else {
    ieVersion = false;
  }
  /*
  */

  init = function(options) {
    $.extend(config, options);
    handleSortable();
    handleSortableOnMobile();
    handleSomeTweaks();
    setSerialisedOutput();
    handleCollapsible();
  };
  /* check for device touch support
  */

  isTouchDevice = function() {
    return !!("ontouchstart" in window) || !!("onmsgesturechange" in window);
  };
  /*
  */

  handleSortable = function() {
    if (config.sortable === true) {
      $(config.sortableContainer + " " + config.columnContainer).sortable({
        cloneHelperSize: true,
        connectWith: config.sortableContainer + " " + config.columnContainer,
        items: ".social-box",
        opacity: 0.75,
        cursor: "move",
        cancel: ".tools",
        forceHelperSize: true,
        forcePlaceholderSize: true,
        placeholder: "ui-state-highlight",
        revert: 200,
        tolerance: "pointer"
      });
      $(config.sortableContainer + " " + ".social-box").addClass("sortable ui-helper-clearfix").find(".header, header").end().find(".body");
      $(config.sortableContainer + " " + config.columnContainer).disableSelection();
      /* Output state on sortstop
      */

      $(config.sortableContainer + " " + config.columnContainer).on("sortstop", function(event, ui) {
        setSerialisedOutput();
      });
    }
  };
  /* Prevent Sortable Boces on Mobile Devices
  */

  handleSortableOnMobile = function() {
    $(window).resize(function() {
      if ($(window).width() <= 768) {
        $(config.sortableContainer + " " + config.columnContainer).sortable("disable");
      } else {
        $(config.sortableContainer + " " + config.columnContainer).sortable("enable");
      }
    });
  };
  /*
  */

  handleSomeTweaks = function() {
    /* Fix bug whe you try to enter text in some form elements
    */
    $(config.sortableContainer).find("input,textarea,button,select,option").on("click.sortable mousedown.sortable", function(e) {
      e.target.focus();
    });
  };
  /* Set the social boxes status in a textarea
  */

  setSerialisedOutput = function() {
    if (window.JSON) {
      $(config.serialisedOutputContainer).val(JSON.stringify(SocialBoxes.getSocialBoxesOrder()));
      return;
    } else {
      $(config.serialisedOutputContainer).val("JSON browser support required for this demo.");
      return;
    }
  };
  /* Get the social boxes ordes/visibility
  */

  getSocialBoxesOrder = function() {
    var finalOrder;

    if (config.sortable === true) {
      finalOrder = [];
      $(config.sortableContainer + " " + config.columnContainer).each(function(index, value) {
        var colid, generalOrder, i, n, order, v;

        colid = value.id;
        generalOrder = [];
        order = $(config.sortableContainer + " #" + colid + config.columnContainer).sortable("toArray");
        i = 0;
        n = order.length;
        while (i < n) {
          v = $(config.sortableContainer + " " + "#" + order[i]).find(".body").is(":visible");
          generalOrder[i] = order[i] + ":" + v;
          i++;
        }
        finalOrder.push(generalOrder);
      });
      return finalOrder;
    } else {
      return false;
    }
  };
  /*
  */

  handleCollapsible = function() {
    var iconHeader;

    if (config.collapsible === true) {
      iconHeader = $(".social-box").find("header .icon-chevron-down, .header .icon-chevron-down");
      iconHeader.css("cursor", "pointer").click(function() {
        var box, boxBody, boxFooter;

        $(this).toggleClass("icon-chevron-down").toggleClass("icon-chevron-up");
        box = $(this).parents(".social-box:first");
        boxBody = box.find(".body");
        boxFooter = box.find(".footer, footer");
        if (boxBody.is(":visible")) {
          boxBody.slideUp(400, function() {
            setSerialisedOutput();
          });
          boxFooter.slideUp();
        } else {
          boxBody.slideDown(400, function() {
            setSerialisedOutput();
          });
          boxFooter.slideDown();
        }
      });
    }
  };
  return {
    init: init,
    getSocialBoxesOrder: getSocialBoxesOrder
  };
})(jQuery);
