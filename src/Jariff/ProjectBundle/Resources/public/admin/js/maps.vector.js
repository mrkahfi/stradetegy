/**
 * Product:        Social - Premium Responsive Admin Template
 * Version:        v1.3.1
 * Copyright:      2013 CesarLab.com
 * License:        http://themeforest.net/licenses
 * Live Preview:   http://go.cesarlab.com/SocialAdminTemplate
 * Purchase:       http://go.cesarlab.com/PurchaseSocial
 *
*/

var VectorMaps;

VectorMaps = (function($) {
  "use strict";
  /*
  */

  var handleGermanyRussia, handleVMapRussia, handleVMapUSA, handleVMapWorld, init;
  init = function() {
    handleVMapWorld();
    handleVMapUSA();
    handleVMapRussia();
    handleGermanyRussia();
  };
  /*
  */

  handleVMapWorld = function() {
    $("#vmap-world").vectorMap({
      map: "world_en",
      backgroundColor: "#fff",
      color: "#ccc",
      hoverOpacity: 0.7,
      selectedColor: "#666666",
      enableZoom: true,
      showTooltip: true,
      values: sample_data,
      scaleColors: ["#C8EEFF", "#006491"],
      normalizeFunction: "polynomial"
    });
  };
  /*
  */

  handleVMapUSA = function() {
    $("#vmap-usa").vectorMap({
      map: "usa_en",
      enableZoom: true,
      showTooltip: true,
      selectedRegion: "MO"
    });
  };
  /*
  */

  handleVMapRussia = function() {
    $("#vmap-russia").vectorMap({
      map: "russia_en",
      backgroundColor: "#333333",
      color: "#ffffff",
      hoverOpacity: 0.7,
      selectedColor: "#999999",
      enableZoom: true,
      showTooltip: true,
      values: sample_data,
      scaleColors: ["#C8EEFF", "#006491"],
      normalizeFunction: "polynomial"
    });
  };
  /*
  */

  handleGermanyRussia = function() {
    $("#vmap-germany").vectorMap({
      map: "germany_en",
      onRegionClick: function(element, code, region) {
        var message;
        message = "You clicked \"" + region + "\" which has the code: " + code.toUpperCase();
        alert(message);
      }
    });
  };
  return {
    init: init
  };
})(jQuery);
