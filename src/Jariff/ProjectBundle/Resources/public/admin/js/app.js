/**
 * Product:        Social - Premium Responsive Admin Template
 * Version:        v1.3.1
 * Copyright:      2013 CesarLab.com
 * License:        http://themeforest.net/licenses
 * Live Preview:   http://go.cesarlab.com/SocialAdminTemplate
 * Purchase:       http://go.cesarlab.com/PurchaseSocial
 * IMPORTANT:      This file is just for demostration
 *
*/

var App;

App = (function($) {
  "use strict";
  /*
  */

  var handleNumberSignLinks, handleSidebarChat, handleSidebarOptions, ieVersion, init, isTouchDevice;

  ieVersion = false;
  /* Get the IE version.  This will be 6 for IE6, 7 for IE7, etc...
  */

  if (/MSIE\s([\d.]+)/.test(navigator.userAgent)) {
    ieVersion = Number(RegExp.$1);
  } else {
    ieVersion = false;
  }
  /* check for device touch support
  */

  isTouchDevice = function() {
    return !!("ontouchstart" in window) || !!("onmsgesturechange" in window);
  };
  /*
  */

  init = function() {
    handleNumberSignLinks();
    handleSidebarOptions();
    handleSidebarChat();
  };
  /* Disable certain links
  */

  handleNumberSignLinks = function() {
    $("[href|='#']").click(function(e) {
      e.preventDefault();
    });
  };
  /*  Sidebar Options
  */

  handleSidebarOptions = function() {
    var dividersTrigger, sidebar, wraper;

    sidebar = $(".social-sidebar");
    wraper = $(".wraper");
    return dividersTrigger = $("#panel #sidebar-dividers");
  };
  /*
  */

  handleSidebarChat = function() {
    if (typeof chatboxManager !== 'undefined') {
      chatboxManager.init({
        sender: {
          username: "Me",
          lastname: "Me"
        }
      });
      $(".chat-users .user-list li > a").click(function(event, ui) {
        var id;

        id = $(this).attr("data-userid");
        chatboxManager.addBox(id, {
          title: "chatbox" + id,
          firstname: $(this).attr("data-firstname"),
          lastname: $(this).attr("data-lastname"),
          status: $(this).attr("data-status")
        });
        event.preventDefault();
      });
      return;
    }
  };
  return {
    init: init,
    ieVersion: ieVersion,
    isTouchDevice: isTouchDevice
  };
})(jQuery);
