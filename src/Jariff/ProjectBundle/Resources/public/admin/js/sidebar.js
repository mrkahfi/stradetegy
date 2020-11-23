/**
 * Product:        Social - Premium Responsive Admin Template
 * Version:        v1.3.1
 * Copyright:      2013 CesarLab.com
 * License:        http://themeforest.net/licenses
 * Live Preview:   http://go.cesarlab.com/SocialAdminTemplate
 * Purchase:       http://go.cesarlab.com/PurchaseSocial
 *
*/

var SideBar;

SideBar = (function($, window) {
  "use strict";
  var anmationDuration, chatUsers, config, expadSidebar, handleSideBarAccordions, handleSideBarScroll, handleSideBarWidth, handleSidebarChatScroll, handleSidebarChatUsersFilter, handleSidebarChatUsersResize, handleUserSettingsContainer, ieVersion, init, isSidebarAutoHide, isTouchDevice, pattern, reduceSidebar, sidebarScroll, socialBarContainer, userSettingsContainer, wrapperCantainer;

  config = {
    shortenOnClickOutside: true,
    animation: false
  };
  anmationDuration = 250;
  socialBarContainer = $(".social-sidebar");
  wrapperCantainer = $(".wraper");
  userSettingsContainer = $(".social-sidebar .user-settings");
  sidebarScroll = $(".social-sidebar-content .scrollable");
  chatUsers = $(".social-sidebar").find(".chat-users");
  pattern = ".user > span, .navigation-sidebar > span,";
  pattern += ".menu .accordion-heading .arrow,";
  pattern += ".menu .accordion-heading a > span,";
  pattern += ".menu .accordion-body > li > a,";
  pattern += ".chat-users .user-list li a > span";
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
    return ("ontouchstart" in window) || window.DocumentTouch && document instanceof DocumentTouch;
  };
  /*
  */

  isSidebarAutoHide = function() {
    return socialBarContainer.hasClass("auto-hide");
  };
  /*
  */

  init = function(options) {
    $.extend(config, options);
    handleUserSettingsContainer();
    handleSideBarWidth();
    handleSideBarAccordions();
    handleSideBarScroll();
    handleSidebarChatScroll();
    handleSidebarChatUsersResize();
    handleSidebarChatUsersFilter();
  };
  /*
  */

  handleUserSettingsContainer = function() {
    // Hide user-settings menu when click on its content
    userSettingsContainer.find(".user-settings-content").on("click", function(e) {
      e.stopPropagation();
      userSettingsContainer.toggle();
    });
    // Hide user-settings menu when click on its link in the footer area
    userSettingsContainer.find(".user-settings-footer a").on("click", function(e) {
      e.stopPropagation();
      userSettingsContainer.toggle();
    });
    // Handle when click outside the user settings menu
    userSettingsContainer.clickOutside(function(event, obj) {
      /* if it's clicked the user settings trigger (small upper left icon)
         the user settings menu will be hidden or shown **/
      if (event.target.className.indexOf("trigger-user-settings") >= 0) {
        obj.toggle();
      /* If we dom't click on the trigger or the user settings menu, this will
        be hide */
      } else {
        obj.hide();
      }
    });
  };
  /* This will expad the size of the sidebar
  */

  expadSidebar = function() {
    var marginRight;

    $("body").css("overflow-x", "hidden");
    chatUsers.find(".user-filter .dropdown-toggle").dropdown();
    /*
    */

    if (isSidebarAutoHide() && $(window).width() >= 979) {
      marginRight = -146;
    } else {
      marginRight = 0;
    }
    if (config.animation === true) {
      socialBarContainer.find(".search-sidebar img").stop().fadeOut(anmationDuration);
      socialBarContainer.stop().addClass("sidebar-full", anmationDuration);
      chatUsers.find('.user-filter .input-filter').stop().animate({
        width: "143"
      }, anmationDuration * 2);
      chatUsers.find('.user-list li a i.user-status').stop().animate({
        marginRight: '14',
        marginTop: '4'
      }, anmationDuration * 2);
      sidebarScroll.find('.menu .badge').stop().animate({
        marginRight: '5'
      }, anmationDuration * 2);
      socialBarContainer.find(".user .trigger-user-settings").stop().animate({
        marginRight: "15"
      }, anmationDuration);
      $("#main").stop().animate({
        marginRight: marginRight,
        marginLeft: "200"
      }, anmationDuration);
      $(".social-navbar").stop().animate({
        left: "200"
      }, anmationDuration, function() {
        socialBarContainer.find(pattern).css("opacity", '1');
        socialBarContainer.find(pattern).stop().fadeIn(anmationDuration * 1.5);
        $(".wraper").addClass("sidebar-full", anmationDuration).removeClass("sidebar-icon");
        return;
        return socialBarContainer.attr("style", "");
      });
    } else {
      socialBarContainer.addClass("sidebar-full");
      $(".wraper").addClass("sidebar-full").removeClass("sidebar-icon");
      $("#main").css("margin-right", marginRight).css("margin-left", 200);
      $(".social-navbar").css("left", 200);
    }
  };
  /* This will reduce the size of the sidebar
  */

  reduceSidebar = function() {
    /*
    */

    var marginRight;

    userSettingsContainer.hide();
    chatUsers.find(".user-filter .btn-group").attr('class', 'btn-group dropup');
    socialBarContainer.find(".accordion-body.in").collapse("hide");
    /*
    */

    if (isSidebarAutoHide() && $(window).width() >= 979) {
      marginRight = -20;
    } else {
      marginRight = 0;
    }
    if (config.animation === true) {
      socialBarContainer.find(pattern).stop().hide();
      socialBarContainer.removeClass("sidebar-full", anmationDuration * 2);
      chatUsers.find('.user-filter .input-filter').stop().animate({
        width: "0"
      }, anmationDuration * 2);
      chatUsers.find('.user-list li a i.user-status').stop().animate({
        marginRight: '6',
        marginTop: '6'
      }, anmationDuration * 2);
      socialBarContainer.find(".user .trigger-user-settings").stop().animate({
        marginRight: "-12"
      }, anmationDuration);
      $("#main").stop().animate({
        marginLeft: "54",
        marginRight: "0"
      }, anmationDuration * 2, 'swing', function() {
        return $("body").css("overflow-x", "auto");
      });
      $(".social-navbar").stop().animate({
        left: "54"
      }, anmationDuration * 2, 'swing', function() {
        return socialBarContainer.find(".search-sidebar img").stop().fadeIn(anmationDuration * 2);
      });
      sidebarScroll.find('.menu .badge').stop().animate({
        marginRight: '-4',
        marginLeft: "-12"
      }, anmationDuration * 2);
      $(".wraper").removeClass("sidebar-full", anmationDuration * 2);
      socialBarContainer.attr("style", "");
    } else {
      socialBarContainer.removeClass("sidebar-full");
      $(".wraper").removeClass("sidebar-full");
      $("#main").css("margin-right", 0).css("margin-left", 54);
      $(".social-navbar").css("left", 54);
    }
  };
  /*
  */

  handleSideBarWidth = function() {
    /*
    */
    $(".switch-sidebar-icon").click(function() {
      if (socialBarContainer.hasClass("sidebar-full")) {
        reduceSidebar();
      } else {
        expadSidebar();
      }
      return false;
    });
    $(".search-sidebar img, .user-filter .dropdown-toggle").click(function() {
      expadSidebar();
      return false;
    });
    if (isTouchDevice === true) {
      sidebarScroll.find(".navigation-sidebar").show();
    } else {
      socialBarContainer.on("mouseleave", function(event) {
        if (($(window).width() >= 979) && isSidebarAutoHide()) {
          reduceSidebar();
        }
        return false;
      });
      socialBarContainer.on("mouseenter", function(event) {
        if (($(window).width() >= 979) && isSidebarAutoHide()) {
          expadSidebar();
        }
        return false;
      });
    }
    if (config.shortenOnClickOutside === true) {
      socialBarContainer.clickOutside(function(event, obj) {
        if ($(window).width() >= 979) {
          reduceSidebar();
        }
      });
    }
    socialBarContainer.on("show", function() {
      expadSidebar();
    });
    $(window).resize(function() {
      if ($(window).width() < 979) {
        expadSidebar();
      }
    });
  };
  /*
  */

  handleSideBarAccordions = function() {
    $(".accordion-body").on("show", function() {
      return $(this).parent().find(".accordion-toggle").addClass("opened");
    });
    $(".accordion-body").on("hide", function() {
      return $(this).parent().find(".accordion-toggle").removeClass("opened");
    });
  };
  /* Sidebar Scroll
  */

  handleSideBarScroll = function() {
    var fixeSidebarScroll, resizeHandler, sidebarScrollOptions;

    sidebarScroll.css("height", $(window).height() - chatUsers.height());
    sidebarScrollOptions = {
      height: $(window).height() - chatUsers.outerHeight(),
      size: "8px",
      railVisible: true,
      railColor: "#000"
    };
    /* This function handle the sidebar scrroll whe the window is resized*/
    resizeHandler = function() {
      var windowHeight;

      windowHeight = $(window).height();
      sidebarScroll.css("height", (windowHeight - chatUsers.outerHeight()) + "px");
      $(".social-sidebar-content").find(".slimScrollDiv").css("height", (windowHeight - chatUsers.outerHeight()) + "px");
    };
    fixeSidebarScroll = function() {
      if ($(window).width() <= 979) {
        $(".social-sidebar .slimScrollDiv").attr('style', '');
        if (socialBarContainer.hasClass("in")) {
          sidebarScroll.css("height", $(window).height() - $(".social-navbar").height());
          $(".social-sidebar-content").css("height", $(document).height());
        }
      }
    };
    sidebarScroll.slimscroll(sidebarScrollOptions);
    $(window).resize(function() {
      if ($(window).width() > 979) {
        resizeHandler();
        sidebarScroll.attr("style", "");
      }
      fixeSidebarScroll();
    });
    socialBarContainer.on("show", function() {
      sidebarScroll.css("height", $(window).height() - $(".social-navbar").height());
      fixeSidebarScroll();
    });
    $(".social-sidebar .accordion-body").on("shown", function() {
      if ($(window).width() <= 979) {
        sidebarScroll.css("height", $(window).height());
      } else {
        sidebarScroll.css("height", $(window).height() - chatUsers.outerHeight());
      }
      sidebarScroll.slimscroll(sidebarScrollOptions);
    });
    $(".social-sidebar .accordion-body").on("hidden", function() {
      if ($(window).width() <= 979) {
        sidebarScroll.css("height", $(window).height());
      } else {
        sidebarScroll.css("height", $(window).height() - chatUsers.outerHeight());
      }
      sidebarScroll.slimscroll(sidebarScrollOptions);
    });
    /* We make sure that the user settings menu will be hiden whe we scroll
       the sidebar */
    sidebarScroll.bind("slimscrolling", function(e, pos) {
      userSettingsContainer.hide();
    });
  };
  /*
  */

  handleSidebarChatScroll = function() {
    $(".user-list").slimscroll({
      height: chatUsers.find(".user-list").height(),
      size: "8px",
      railColor: "#000",
      wheelStep: 15
    });
  };
  /*
  */

  handleSidebarChatUsersResize = function() {
    if (!$().resizable) {
      return;
    }
    chatUsers.resizable({
      handles: "n",
      maxHeight: 400,
      minHeight: 110,
      resize: function(event, ui) {
        var currentHeight, padding;

        currentHeight = ui.size.height;
        padding = 3;
        $(this).height(currentHeight);
        $(this).css('top', 'auto');
        chatUsers.find('.slimScrollDiv, .user-list').height(currentHeight - 42);
        sidebarScroll.parent().height($(window).height() - currentHeight - padding);
        sidebarScroll.height($(window).height() - currentHeight - padding);
      }
    });
  };
  /*
  */

  handleSidebarChatUsersFilter = function() {
    if (!$().liveFilter) {
      return;
    }
    chatUsers.find(".user-list").liveFilter(".chat-users .user-filter input.input-filter", "li", {
      filterChildSelector: "a",
      after: function() {
        if ($('.user-list li:visible').length === 0) {
          chatUsers.find(".no-user").show();
        } else {
          chatUsers.find(".no-user").hide();
        }
      }
    });
  };
  return {
    init: init,
    isTouchDevice: isTouchDevice
  };
})(jQuery, window);
