/**
 * Product:        Social - Premium Responsive Admin Template
 * Version:        v1.3.1
 * Copyright:      2013 CesarLab.com
 * License:        http://themeforest.net/licenses
 * Live Preview:   http://go.cesarlab.com/SocialAdminTemplate
 * Purchase:       http://go.cesarlab.com/PurchaseSocial
 *
*/

var UIElementsGeneral;

UIElementsGeneral = (function($) {
  "use strict";
  /*
  */

  var config, handleAvgrundExample, handleBigChatExample, handleDateRangePickerExample, handleLightGritterExample, handleMaxGritterExample, handleNoImageGritterExample, handlePopoversTooltips, handlePulsateExamples, handleRatingSystemExamples, handleRegularGritterExample, handleScrollFeedsList, handleStickyGritterExample, init, removeAllGritters;

  config = {
    urlAvatar: 'none'
  };
  init = function(options) {
    $.extend(config, options);
    handlePopoversTooltips();
    handleAvgrundExample();
    handleBigChatExample();
    handleRegularGritterExample();
    handleStickyGritterExample();
    handleNoImageGritterExample();
    handleMaxGritterExample();
    handleLightGritterExample();
    removeAllGritters();
    handleScrollFeedsList();
    handleDateRangePickerExample();
    handlePulsateExamples();
    handleRatingSystemExamples();
  };
  /*
  */

  handlePopoversTooltips = function() {
    $("[data-toggle='popover']").popover({
      container: "#main",
      trigger: "hover"
    });
    $("[data-toggle='tooltip']").tooltip();
  };
  /* Avgrund Example
  */

  handleAvgrundExample = function() {
    /* Fix conflict with the avgrund css style
    */

    var avgrundContent;

    $("html").css("height", "auto");
    /*
    */

    avgrundContent = "";
    avgrundContent += "<aside class=\"\">";
    avgrundContent += "  <div class=\"modal-header\">";
    avgrundContent += "    <button type=\"button\" class=\"close avgrund-close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>";
    avgrundContent += "    <h3>Modal header</h3>";
    avgrundContent += "  </div>";
    avgrundContent += "  <div class=\"modal-body\">";
    avgrundContent += "    <p>One fine body…</p>";
    avgrundContent += "  </div>";
    avgrundContent += "  <div class=\"modal-footer\">";
    avgrundContent += "    <a href=\"#\" class=\"btn avgrund-close\">Close</a>";
    avgrundContent += "    <a href=\"#\" class=\"btn btn-primary\">Save changes</a>";
    avgrundContent += "  </div>";
    avgrundContent += "</aside>";
    $("#show-avgrund").avgrund({
      height: 170,
      width: 560,
      holderClass: "custom",
      showClose: false,
      enableStackAnimation: true,
      onBlurContainer: ".wraper",
      template: avgrundContent,
      /* Fix conflict with the avgrund css style
      */

      onLoad: function() {
        $("html").css("height", "100%");
        $("#uipro_right").hide();
      },
      /* Fix conflict with the avgrund css style
      */

      onUnload: function() {
        $("html").css("height", "auto");
        $("#uipro_right").show();
      }
    });
  };
  /*
  */

  handleBigChatExample = function() {
    /* Activate the scrollbar for the chat window
    */

    var chatWindow;

    chatWindow = $(".chat-messages-list .content");
    chatWindow.slimScroll({
      start: "bottom",
      railVisible: true,
      alwaysVisible: true,
      height: '400px'
    });
  };
  /* Regular Gritter Example
  */

  handleRegularGritterExample = function() {
    $("#gritters #add-regular").click(function() {
      $.gritter.add({
        title: "This is a regular notice!",
        text: "This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href=\"#\" style=\"color:#ccc\">magnis dis parturient</a> montes, nascetur ridiculus mus.",
        image: config.urlAvatar,
        sticky: false,
        time: ""
      });
      return false;
    });
  };
  /* Sticky Gritter
  */

  handleStickyGritterExample = function() {
    $("#gritters #add-sticky").click(function() {
      var unique_id;

      unique_id = $.gritter.add({
        title: "This is a sticky notice!",
        text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href=\"#\">magnis dis parturient</a> montes, nascetur ridiculus mus.",
        image: config.urlAvatar,
        sticky: true,
        time: "",
        class_name: "gritter-light"
      });
      return false;
    });
  };
  /* Without image Gritter
  */

  handleNoImageGritterExample = function() {
    $("#gritters #add-without-image").click(function() {
      $.gritter.add({
        title: "This is a notice without an image!",
        text: "This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href=\"#\">magnis dis parturient</a> montes, nascetur ridiculus mus.",
        class_name: "gritter-light"
      });
      return false;
    });
  };
  /* Max Gritter Example
  */

  handleMaxGritterExample = function() {
    $("#gritters #add-max").click(function() {
      $.gritter.add({
        title: "This is a notice with a max of 3 on screen at one time!",
        text: "This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href=\"#\">magnis dis parturient</a> montes, nascetur ridiculus mus.",
        image: "http://a0.twimg.com/profile_images/59268975/jquery_avatar_bigger.png",
        sticky: false,
        class_name: "gritter-light",
        before_open: function() {
          if ($(".gritter-item-wrapper").length === 3) {
            return false;
          }
        }
      });
      return false;
    });
  };
  /* Light Gritter Example
  */

  handleLightGritterExample = function() {
    $("#gritters #add-gritter-light").click(function() {
      $.gritter.add({
        title: "This is a light notification",
        text: "Just add a \"gritter-light\" class_name to your $.gritter.add or globally to $.gritter.options.class_name",
        class_name: "gritter-light"
      });
      return false;
    });
  };
  /* Remove All Gritter
  */

  removeAllGritters = function() {
    $("#gritters #remove-all").click(function() {
      $.gritter.removeAll();
      return false;
    });
  };
  /* Activate the scrollbar for the feed lists
  */

  handleScrollFeedsList = function() {
    $("#feeds .content").slimScroll({
      height: '445px'
    });
  };
  /* Date Range Picker Example
  */

  handleDateRangePickerExample = function() {
    $('#reservation').daterangepicker();
    $("#reportrange").daterangepicker({
      ranges: {
        Today: [new Date(), new Date()],
        Yesterday: [moment().subtract("days", 1), moment().subtract("days", 1)],
        "Last 7 Days": [moment().subtract("days", 6), new Date()],
        "Last 30 Days": [moment().subtract("days", 29), new Date()],
        "This Month": [moment().startOf("month"), moment().endOf("month")],
        "Last Month": [moment().subtract("month", 1).startOf("month"), moment().subtract("month", 1).endOf("month")]
      },
      opens: "left",
      format: "MM/DD/YYYY",
      separator: " to ",
      startDate: moment().subtract("days", 29),
      endDate: new Date(),
      minDate: "01/01/2012",
      maxDate: "12/31/2013",
      locale: {
        applyLabel: "Submit",
        fromLabel: "From",
        toLabel: "To",
        customRangeLabel: "Custom Range",
        daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
        monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        firstDay: 1
      },
      showWeekNumbers: true,
      buttonClasses: ["btn-danger"],
      dateLimit: false
    }, function(start, end) {
      $("#reportrange span").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
    });
    $("#reportrange span").html(moment().subtract("days", 29).format("MMMM D, YYYY") + " - " + moment().format("MMMM D, YYYY"));
    $(".daterange").daterangepicker({
      minDate: "01/01/2010",
      maxDate: "12/31/2015",
      showDropdowns: true
    });
    $("#limited").daterangepicker({
      dateLimit: {
        days: 3
      }
    });
  };
  /* Pulsate Examples
  */

  handlePulsateExamples = function() {
    $("#pulse").pulsate({
      color: "#2c467e"
    });
    $(".pulse1").pulsate({
      glow: false,
      color: "#2c467e"
    });
    $(".pulse2").pulsate({
      color: "#f89406"
    });
    $(".pulse3").pulsate({
      reach: 100,
      color: "#222222"
    });
    $(".pulse4").pulsate({
      speed: 2500,
      color: "#51a351"
    });
    $(".pulse5").pulsate({
      pause: 1000,
      color: "#bd362f"
    });
    $(".pulse6").pulsate({
      onHover: true,
      color: "#e9eaed"
    });
  };
  /* jQuery Raty Examples
  */

  handleRatingSystemExamples = function() {
    $("#star1").raty({
      score: 3
    });
    $('#star2').raty({
      half: true,
      cancel: true
    });
    $("#star3").raty({
      cancel: true,
      cancelOff: "cancel-off-big.png",
      cancelOn: "cancel-on-big.png",
      half: true,
      size: 24,
      starHalf: "star-half-big.png",
      starOff: "star-off-big.png",
      starOn: "star-on-big.png"
    });
    $('#star4').raty({
      cancel: true,
      target: '#hint1',
      targetText: '--'
    });
  };
  return {
    init: init
  };
})(jQuery);
