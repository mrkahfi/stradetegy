/**
 * Product:        Social - Premium Responsive Admin Template
 * Version:        v1.3.1
 * Copyright:      2013 CesarLab.com
 * License:        http://themeforest.net/licenses
 * Live Preview:   http://go.cesarlab.com/SocialAdminTemplate
 * Purchase:       http://go.cesarlab.com/PurchaseSocial
 *
*/

var chatboxManager;

chatboxManager = (function($) {
  "use strict";
  /* list of all opened boxes
  */

  var addBox, boxClosedCallback, boxList, config, getNextOffset, init, messageSentCallback, nameList, sender, showList;

  boxList = [];
  /* list of boxes shown on the page
  */

  showList = [];
  /* list of first names, for in-page demo
  */

  nameList = [];
  /* sender info
  */

  sender = [];
  config = {
    width: 200,
    gap: 20,
    maxBoxes: 3,
    sender: {
      username: "undefinied",
      lastname: "undefinied"
    },
    messageSent: function(id, to, msg, status) {
      chatboxManager.addBox(id);
      $("#" + id).chatbox("option", "boxManager").addMsg(config.sender.lastname, msg);
      /* This repeat the sent message is the user is online
           Just for demo
      */

      if (status === "online") {
        setTimeout((function() {
          $("#" + id).chatbox("option", "boxManager").addMsg(to, msg);
        }), 500);
      }
    }
  };
  init = function(options) {
    $.extend(config, options);
  };
  getNextOffset = function() {
    return (config.width + config.gap) * showList.length;
  };
  boxClosedCallback = function(id) {
    /* close button in the titlebar is clicked
    */

    var diff, i, idx, offset;

    idx = showList.indexOf(id);
    if (idx !== -1) {
      showList.splice(idx, 1);
      diff = config.width + config.gap;
      i = idx;
      while (i < showList.length) {
        offset = $("#" + showList[i]).chatbox("option", "offset");
        $("#" + showList[i]).chatbox("option", "offset", offset - diff);
        i++;
      }
    } else {
      alert("should not happen: " + id);
      return;
    }
  };
  /* caller should guarantee the uniqueness of id
  */

  addBox = function(id, user, name) {
    var el, idx1, idx2, manager;

    idx1 = showList.indexOf(id);
    idx2 = boxList.indexOf(id);
    if (idx1 !== -1) {
      /* found one in show box, do nothing
      */

      $("#" + id).parent().show();
      return $("#" + id).parent().find(".ui-chatbox-input-box").focus();
    } else if (idx2 !== -1) {
      /* exists, but hidden
          show it and put it back to showList
      */

      $("#" + id).chatbox("option", "offset", getNextOffset());
      manager = $("#" + id).chatbox("option", "boxManager");
      manager.toggleBox();
      showList.push(id);
    } else {
      el = document.createElement("div");
      el.setAttribute("id", id);
      $(el).chatbox({
        id: id,
        user: user,
        sender: sender,
        title: "<i class=\"icon-circle user-status " + user.status + "\"></i>" + user.firstname + " " + user.lastname,
        hidden: false,
        width: config.width,
        offset: getNextOffset() + config.gap,
        messageSent: messageSentCallback,
        boxClosed: boxClosedCallback
      });
      $(el).parent().find(".ui-chatbox-input-box").focus();
      $(el).parent().addClass(user.status);
      boxList.push(id);
      showList.push(id);
      nameList.push(user.firstname);
    }
  };
  messageSentCallback = function(id, user, msg) {
    var idx;

    idx = boxList.indexOf(id);
    config.messageSent(id, nameList[idx], msg, user.status);
  };
  return {
    init: init,
    addBox: addBox
  };
})(jQuery);
