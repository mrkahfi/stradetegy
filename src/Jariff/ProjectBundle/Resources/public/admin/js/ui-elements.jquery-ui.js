/**
 * Product:        Social - Premium Responsive Admin Template
 * Version:        v1.3.1
 * Copyright:      2013 CesarLab.com
 * License:        http://themeforest.net/licenses
 * Live Preview:   http://go.cesarlab.com/SocialAdminTemplate
 * Purchase:       http://go.cesarlab.com/PurchaseSocial
 *
*/

var jQueryUI;

jQueryUI = (function($, window) {
  "use strict";
  /*
  */

  var handleGeneralExamples, handleModalExamples, handleSlidersExamples, handleTabsExample, init;

  init = function() {
    handleGeneralExamples();
    handleTabsExample();
    handleModalExamples();
    handleSlidersExamples();
  };
  /*
  */

  handleGeneralExamples = function() {
    $("button,.button,#sampleButton").button();
    $("#menu-collapse").accordion({
      header: "> div > h3"
    }).sortable({
      axis: "y",
      handle: "h3",
      stop: function(event, ui) {
        ui.item.children("h3").triggerHandler("focusout");
      }
    });
    $(".ui-dialog :button").blur();
    $("#from").datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 3,
      onClose: function(selectedDate) {
        $("#to").datepicker("option", "minDate", selectedDate);
      }
    });
    $("#to").datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 3,
      onClose: function(selectedDate) {
        $("#from").datepicker("option", "maxDate", selectedDate);
      }
    });
  };
  /*
  */

  handleTabsExample = function() {
    var addTab, dialog, form, tabContent, tabCounter, tabTemplate, tabTitle, tabs;

    tabTitle = $("#tab_title");
    tabContent = $("#tab_content");
    tabTemplate = '<li><a href="#{href}">#{label}</a> <span class="ui-icon ui-icon-close" role="presentation">Remove Tab</span></li>';
    tabCounter = 2;
    tabs = $("#tabs").tabs();
    dialog = $("#dialog").dialog({
      autoOpen: false,
      modal: true,
      buttons: {
        Add: function() {
          addTab();
          $(this).dialog("close");
        },
        Cancel: function() {
          $(this).dialog("close");
        }
      },
      close: function() {
        form[0].reset();
      }
    });
    form = dialog.find("form").submit(function(event) {
      addTab();
      dialog.dialog("close");
      event.preventDefault();
    });
    addTab = function() {
      var id, label, li, tabContentHtml;

      label = tabTitle.val() || "Tab " + tabCounter;
      id = "tabs-" + tabCounter;
      li = $(tabTemplate.replace(/#\{href\}/g, "#" + id).replace(/#\{label\}/g, label));
      tabContentHtml = tabContent.val() || "Tab " + tabCounter + " content.";
      tabs.find(".ui-tabs-nav").append(li);
      tabs.append("<div id='" + id + "'><p>" + tabContentHtml + "</p></div>");
      tabs.tabs("refresh");
      tabCounter++;
    };
    $("#add_tab").button().click(function() {
      dialog.dialog("open");
    });
    tabs.delegate("span.ui-icon-close", "click", function() {
      var panelId;

      panelId = $(this).closest("li").remove().attr("aria-controls");
      $("#" + panelId).remove();
      tabs.tabs("refresh");
    });
    tabs.bind("keyup", function(event) {
      var panelId;

      if (event.altKey && event.keyCode === $.ui.keyCode.BACKSPACE) {
        panelId = tabs.find(".ui-tabs-active").remove().attr("aria-controls");
        $("#" + panelId).remove();
        tabs.tabs("refresh");
      }
    });
  };
  handleModalExamples = function() {
    $("#dialog_link").click(function() {
      $("#dialog_simple").dialog("open");
      return false;
    });
    $("#modal_link").click(function() {
      $("#dialog-message").dialog("open");
      return false;
    });
    $("#modal_form").button().click(function() {
      $("#dialog-form").dialog("open");
    });
    $("#dialog_link, #modal_link, #modal_form, ul#icons li").hover((function() {
      $(this).addClass("ui-state-hover");
    }), function() {
      $(this).removeClass("ui-state-hover");
    });
    $("#dialog_simple").dialog({
      autoOpen: false,
      width: 600,
      buttons: {
        Ok: function() {
          $(this).dialog("close");
        },
        Cancel: function() {
          $(this).dialog("close");
        }
      }
    });
    $("#dialog-message").dialog({
      autoOpen: false,
      modal: true,
      buttons: {
        Ok: function() {
          $(this).dialog("close");
        }
      }
    });
    $("#dialog-form").dialog({
      autoOpen: false,
      height: 380,
      width: 350,
      modal: true,
      buttons: {
        "Create an account": function() {
          return $(this).dialog("close");
        },
        Cancel: function() {
          $(this).dialog("close");
        }
      }
    });
  };
  handleSlidersExamples = function() {
    var hexFromRGB, refreshSwatch;

    hexFromRGB = function(r, g, b) {
      var hex;

      hex = [r.toString(16), g.toString(16), b.toString(16)];
      $.each(hex, function(nr, val) {
        if (val.length === 1) {
          hex[nr] = "0" + val;
        }
      });
      return hex.join("").toUpperCase();
    };
    refreshSwatch = function() {
      var blue, green, hex, red;

      red = $("#sliders #red").slider("value");
      green = $("#sliders #green").slider("value");
      blue = $("#sliders #blue").slider("value");
      hex = hexFromRGB(red, green, blue);
      $("#sliders #swatch").css("background-color", "#" + hex);
    };
    $("#sliders #red, #sliders #green, #sliders #blue").slider({
      orientation: "horizontal",
      range: "min",
      max: 255,
      value: 127,
      slide: refreshSwatch,
      change: refreshSwatch
    });
    $("#sliders #red").slider("value", 255);
    $("#sliders #green").slider("value", 140);
    $("#sliders #blue").slider("value", 60);
    $("#sliders #eq > span").each(function() {
      var value;

      value = parseInt($(this).text(), 10);
      $(this).empty().slider({
        value: value,
        range: "min",
        animate: true,
        orientation: "vertical"
      });
    });
    $("#sliders #slider-range").slider({
      range: true,
      min: 0,
      max: 500,
      values: [75, 300],
      slide: function(event, ui) {
        $("#sliders #amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
      }
    });
    $("#sliders #amount").val("$" + $("#sliders #slider-range").slider("values", 0) + " - $" + $("#sliders #slider-range").slider("values", 1));
    $("#sliders #slider-range-max").slider({
      range: "max",
      min: 1,
      max: 10,
      value: 2,
      slide: function(event, ui) {
        $("#sliders #amount2").val(ui.value);
      }
    });
    $("#sliders #amount2").val($("#sliders #slider-range-max").slider("value"));
    $("#sliders #slider-range-min").slider({
      range: "min",
      value: 37,
      min: 1,
      max: 700,
      slide: function(event, ui) {
        return $("#sliders #amount3").val("$" + ui.value);
      }
    });
    $("#sliders #amount3").val("$" + $("#sliders #slider-range-min").slider("value"));
    $("#sliders #slider").slider({
      value: 100,
      min: 0,
      max: 500,
      step: 50,
      slide: function(event, ui) {
        $("#sliders #amount4").val("$" + ui.value);
      }
    });
    $("#sliders #amount4").val("$" + $("#sliders #slider").slider("value"));
  };
  return {
    init: init
  };
})(jQuery, window);
