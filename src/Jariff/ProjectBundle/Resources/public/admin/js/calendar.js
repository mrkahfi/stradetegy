/**
 * Product:        Social - Premium Responsive Admin Template
 * Version:        v1.3.1
 * Copyright:      2013 CesarLab.com
 * License:        http://themeforest.net/licenses
 * Live Preview:   http://go.cesarlab.com/SocialAdminTemplate
 * Purchase:       http://go.cesarlab.com/PurchaseSocial
 *
*/

var Calendar;

Calendar = (function($) {
  "use strict";
  var handleNewEventsForm, init, initCalendar, initExternalEvents;
  init = function() {
    initExternalEvents();
    initCalendar();
    handleNewEventsForm();
  };
  /* initialize the external events
  */

  initExternalEvents = function() {
    $("#external-events div.external-event").each(function() {
      var eventObject;
      eventObject = {
        title: $.trim($(this).text())
      };
      $(this).data("eventObject", eventObject);
      return $(this).draggable({
        zIndex: 999,
        revert: true,
        revertDuration: 0
      });
    });
    return;
  };
  /* initialize the calendar
  */

  initCalendar = function() {
    var d, date, m, y;
    date = new Date();
    d = date.getDate();
    m = date.getMonth();
    y = date.getFullYear();
    $("#calendar").fullCalendar({
      header: {
        left: "prev,next today",
        center: "title",
        right: "month,agendaWeek,agendaDay"
      },
      events: [
        {
          title: "All Day Event",
          start: new Date(y, m, 1)
        }, {
          title: "Long Event",
          start: new Date(y, m, d - 5),
          end: new Date(y, m, d - 2)
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
      ],
      editable: true,
      droppable: true,
      drop: function(date, allDay) {
        var copiedEventObject, originalEventObject;
        originalEventObject = $(this).data("eventObject");
        copiedEventObject = $.extend({}, originalEventObject);
        copiedEventObject.start = date;
        copiedEventObject.allDay = allDay;
        $("#calendar").fullCalendar("renderEvent", copiedEventObject, true);
        if ($("#drop-remove").is(":checked")) {
          $(this).remove();
        }
      }
    });
  };
  // Add a new elements to the "Draggable Events" list

  handleNewEventsForm = function() {
    $("#add-event-form").submit(function() {
      var titleEvent, titleEventVal;
      titleEvent = void 0;
      titleEventVal = void 0;
      titleEvent = $("#add-event-form #title-event");
      titleEventVal = (!titleEvent.val() || 0 === titleEvent.val().length ? "Untitle Event" : titleEvent.val());
      titleEvent.val("");
      $("<div class='external-event ui-draggable'>" + titleEventVal + "</div>").insertAfter("#add-event-form");
      initExternalEvents();
      return false;
    });
  };
  return {
    init: init
  };
})(jQuery);
