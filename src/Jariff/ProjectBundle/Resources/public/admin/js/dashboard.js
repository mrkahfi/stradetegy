/**
 * Product:        Social - Premium Responsive Admin Template
 * Version:        v1.3.1
 * Copyright:      2013 CesarLab.com
 * License:        http://themeforest.net/licenses
 * Live Preview:   http://go.cesarlab.com/SocialAdminTemplate
 * Purchase:       http://go.cesarlab.com/PurchaseSocial
 *
*/

var Dashboard;

Dashboard = (function($) {
  "use strict";
  var config, handleBigChatExample, handleDateRangePicker, handleFlotExample, handleFullCalendarExample, handleIndicatorsStuff, handleJustGageExample, handleScrollFeedsList, handleSparklineExample, handleVMapExample, init;

  config = {
    urlAvatar: 'none'
  };
  /*
  */

  init = function(options) {
    $.extend(config, options);


    handleFlotExample();
    handleFullCalendarExample();
    handleSparklineExample();
    handleBigChatExample();
    handleScrollFeedsList();
    handleDateRangePicker();
//    handleIndicatorsStuff();
  };
  /* vMap Example
  */


  /* Flot Example
  */

  handleFlotExample = function() {
    var datasets, i, options, placeholder, plot, plotAccordingToChoices;

    datasets = [
      {
        label: "Russia",
        data: [[1992, 13.4], [1993, 12.2], [1994, 10.6], [1995, 10.2], [1996, 10.1], [1997, 9.7], [1998, 9.5], [1999, 9.7], [2000, 9.9], [2001, 9.9], [2002, 9.9], [2003, 10.3], [2004, 10.5]],
        flag: "ru",
        color: "#5bb75b"
      }, {
        label: "Canada",
        data: [[1990, 10.0], [1991, 11.3], [1992, 9.9], [1993, 9.6], [1994, 9.5], [1995, 9.5], [1996, 9.9], [1997, 9.3], [1998, 9.2], [1999, 9.2], [2000, 9.5], [2001, 9.6], [2002, 9.3], [2003, 9.4], [2004, 9.79]],
        flag: "ca",
        color: "#4e6599"
      }, {
        label: "Germany",
        data: [[1990, 12.4], [1991, 11.2], [1992, 10.8], [1993, 10.5], [1994, 10.4], [1995, 10.2], [1996, 10.5], [1997, 10.2], [1998, 10.1], [1999, 9.6], [2000, 9.7], [2001, 10.0], [2002, 9.7], [2003, 9.8], [2004, 9.79]],
        flag: "de",
        color: "#49afcd"
      }, {
        label: "Sweden",
        data: [[1990, 5.8], [1991, 6.0], [1992, 5.9], [1993, 5.5], [1994, 5.7], [1995, 5.3], [1996, 6.1], [1997, 5.4], [1998, 5.4], [1999, 5.1], [2000, 5.2], [2001, 5.4], [2002, 6.2], [2003, 5.9], [2004, 5.89]],
        flag: "se",
        color: "#faa732"
      }, {
        label: "Norway",
        data: [[1990, 8.3], [1991, 8.3], [1992, 7.8], [1993, 8.3], [1994, 8.4], [1995, 5.9], [1996, 6.4], [1997, 6.7], [1998, 6.9], [1999, 7.6], [2000, 7.4], [2001, 8.1], [2002, 12.5], [2003, 9.9], [2004, 19.0]],
        flag: "no",
        color: "#da4f49"
      }
    ];
    options = {
      series: {
        lines: {
          show: true
        },
        points: {
          show: true
        }
      },
      legend: {
        noColumns: 2
      },
      xaxis: {
        tickDecimals: 0
      },
      yaxis: {
        min: 0
      },
      selection: {
        mode: "x"
      }
    };
    placeholder = $("#demo-plot");
    placeholder.bind("plotselected", function(event, ranges) {});
    plot = $.plot(placeholder, datasets, options);
    plotAccordingToChoices = function() {
      var data;

      data = void 0;
      data = [];
      if (data.length > 0) {
        return $.plot("#demo-plot", data, {
          yaxis: {
            min: 0
          },
          xaxis: {
            tickDecimals: 0
          }
        });
      }
    };
    i = 0;
    $.each(datasets, function(key, val) {
      val.color = i;
      return ++i;
    });
    plot.setSelection({
      xaxis: {
        from: 1994,
        to: 1995
      }
    });
  };
  /* Full Calendar Example
  */

  handleFullCalendarExample = function() {
    var d, date, m, y;

    date = new Date();
    d = date.getDate();
    m = date.getMonth();
    y = date.getFullYear();
    $("#demo-calendar1").fullCalendar({
      header: {
        left: "prev,next",
        center: "title",
        right: "month,agendaWeek,agendaDay"
      },
      editable: true,
      events: [
        {
          title: "All Day Event",
          start: new Date(y, m, 1)
        }, {
          title: "Long Event",
          start: new Date(y, m, d - 5),
          end: new Date(y, m, d - 2)
        }, {
          id: 999,
          title: "Repeating",
          start: new Date(y, m, d - 3, 16, 0),
          allDay: false
        }, {
          id: 999,
          title: "Repeating",
          start: new Date(y, m, d + 4, 16, 0),
          allDay: false
        }, {
          title: "Meeting",
          start: new Date(y, m, d, 10, 30),
          allDay: false
        }, {
          title: "Lunch",
          start: new Date(y, m, d, 12, 0),
          end: new Date(y, m, d, 14, 0),
          allDay: false
        }, {
          title: "Birthday Party",
          start: new Date(y, m, d + 1, 19, 0),
          end: new Date(y, m, d + 1, 22, 30),
          allDay: false
        }, {
          title: "Click for Google",
          start: new Date(y, m, 28),
          end: new Date(y, m, 29),
          url: "#http://google.com/"
        }
      ]
    });
    /* END Full Calendar Example
    */

  };
  /* Sparkline Example
  */

  handleSparklineExample = function() {
    $("#compositebar").sparkline([50, 60, 62, 35, 40, 50, 38, 38, 38, 40, 60, 38, 50, 60, 38, 45, 62, 38, 38, 40, 30], {
      type: "line",
      width: "100px",
      height: "29px",
      drawNormalOnTop: false
    });
  };
  /*
  */

  handleBigChatExample = function() {
    var chatWindow;

    chatWindow = $(".chat-messages-list .content");
    /* Activate the scrollbar for the chat window
    */

    chatWindow.slimScroll({
      railVisible: true,
      alwaysVisible: true,
      start: "bottom",
      height: '400px'
    });
  };
  /* Activate the scrollbar for the feed lists
  */

  handleScrollFeedsList = function() {
    $("#feeds .content").slimScroll({
      height: '300px'
    });
  };
  /* Date Range Picker
  */

  handleDateRangePicker = function() {
    var dashboardReportRange;

    dashboardReportRange = $("#dashboard-reportrange");
    dashboardReportRange.daterangepicker({
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
      $("#dashboard-reportrange span").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
    });
    $("#dashboard-reportrange span").html(moment().subtract("days", 29).format("MMMM D, YYYY") + " - " + moment().format("MMMM D, YYYY"));
  };
  /*
  */

  handleIndicatorsStuff = function() {
    $(".trigger-user-settings").pulsate({
      color: "#696d76"
    });
    $("#demo-setting").pulsate({
      color: "#ffffff"
    });
    $("a[href='#pages']").pulsate({
      color: "#ffffff"
    });
    $.uiPro.open('right');
    setTimeout((function() {
      $.gritter.add({
        title: "This is a regular notice!",
        text: "This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href=\"#\" style=\"color:#ccc\">magnis dis parturient</a> montes, nascetur ridiculus mus.",
        image: config.urlAvatar,
        sticky: false,
        time: ""
      });
    }), 1000);
    if (typeof chatboxManager !== 'undefined') {
      setTimeout((function() {
        $("a[data-userid=3]").click();
        $("#3").chatbox("option", "boxManager").addMsg($("a[data-userid=3]").attr("data-firstname"), "Hello");
      }), 500);
    }
  };
  return {
    init: init
  };
})(jQuery);
