/**
 * Product:        Social - Premium Responsive Admin Template
 * Version:        v1.3.1
 * Copyright:      2013 CesarLab.com
 * License:        http://themeforest.net/licenses
 * Live Preview:   http://go.cesarlab.com/SocialAdminTemplate
 * Purchase:       http://go.cesarlab.com/PurchaseSocial
 *
*/

var GoogleMaps;

GoogleMaps = (function($) {
  "use strict";
  /*
  */

  var handleBasicGmap, handleCustomControlsGmaps, handleGeocodingGmaps, handleGeolocationGmaps, handleMapTypesGmaps, handleMarkersGmaps, handlePanoramaGmaps, handlePolygonsGmaps, handleRoutesGmaps, init;
  init = function() {
    handleBasicGmap();
    handlePolygonsGmaps();
    handleMarkersGmaps();
    handleGeolocationGmaps();
    handleGeocodingGmaps();
    handleRoutesGmaps();
    handleCustomControlsGmaps();
    handleMapTypesGmaps();
    handlePanoramaGmaps();
  };
  /* Basic Gmaps
  */

  handleBasicGmap = function() {
    var map;
    map = new GMaps({
      div: "#gmaps-basic",
      lat: -12.043333,
      lng: -77.028333
    });
  };
  /* Gmaps with Polygons
  */

  handlePolygonsGmaps = function() {
    var map2, path2, polygon;
    map2 = new GMaps({
      div: "#gmaps-polygons",
      lat: -12.043333,
      lng: -77.028333
    });
    path2 = [[-12.040397656836609, -77.03373871559225], [-12.040248585302038, -77.03993927003302], [-12.050047116528843, -77.02448169303511], [-12.044804866577001, -77.02154422636042]];
    polygon = map2.drawPolygon({
      paths: path2,
      strokeColor: "#BBD8E9",
      strokeOpacity: 1,
      strokeWeight: 3,
      fillColor: "#BBD8E9",
      fillOpacity: 0.6
    });
  };
  /* Gmaps with Markers
  */

  handleMarkersGmaps = function() {
    var map4;
    map4 = new GMaps({
      div: "#gmaps-markers",
      lat: -12.043333,
      lng: -77.028333
    });
    map4.addMarker({
      lat: -12.043333,
      lng: -77.03,
      title: "Lima",
      details: {
        database_id: 42,
        author: "HPNeo"
      },
      click: function(e) {
        alert("You clicked in this marker");
      }
    });
    map4.addMarker({
      lat: -12.042,
      lng: -77.028333,
      title: "Marker with InfoWindow",
      infoWindow: {
        content: "<p>HTML Content</p>"
      }
    });
  };
  /* Gmaps with Geolocation
  */

  handleGeolocationGmaps = function() {
    var map5;
    map5 = new GMaps({
      div: "#gmaps-geolocation",
      lat: -12.043333,
      lng: -77.028333
    });
    GMaps.geolocate({
      success: function(position) {
        map5.setCenter(position.coords.latitude, position.coords.longitude);
      },
      error: function(error) {
        alert("Geolocation failed: " + error.message);
      },
      not_supported: function() {
        alert("Your browser does not support geolocation");
      },
      always: function() {}
    });
  };
  /* Gmaps with Geocoding
  */

  handleGeocodingGmaps = function() {
    var map5;
    map5 = new GMaps({
      div: "#gmaps-geocoding",
      lat: -12.043333,
      lng: -77.028333
    });
    $("#geocoding_form").submit(function(e) {
      e.preventDefault();
      return GMaps.geocode({
        address: $("#address").val().trim(),
        callback: function(results, status) {
          var latlng;
          if (status === "OK") {
            latlng = results[0].geometry.location;
            map5.setCenter(latlng.lat(), latlng.lng());
            return map5.addMarker({
              lat: latlng.lat(),
              lng: latlng.lng()
            });
          }
        }
      });
    });
  };
  /* Gmaps with Routes
  */

  handleRoutesGmaps = function() {
    var map6;
    map6 = new GMaps({
      div: "#gmaps-routes",
      lat: -12.043333,
      lng: -77.028333
    });
    map6.drawRoute({
      origin: [-12.044012922866312, -77.02470665341184],
      destination: [-12.090814532191756, -77.02271108990476],
      travelMode: "driving",
      strokeColor: "#131540",
      strokeOpacity: 0.6,
      strokeWeight: 6
    });
  };
  /* Gmaps with custom controls
  */

  handleCustomControlsGmaps = function() {
    var map7;
    map7 = new GMaps({
      div: "#gmaps-custom-controls",
      zoom: 16,
      lat: -12.043333,
      lng: -77.028333
    });
    map7.addControl({
      position: "top_right",
      content: "Geolocate",
      style: {
        margin: "5px",
        padding: "1px 6px",
        border: "solid 1px #717B87",
        background: "#fff"
      },
      events: {
        click: function() {
          GMaps.geolocate({
            success: function(position) {
              map7.setCenter(position.coords.latitude, position.coords.longitude);
            },
            error: function(error) {
              alert("Geolocation failed: " + error.message);
            },
            not_supported: function() {
              alert("Your browser does not support geolocation");
            }
          });
        }
      }
    });
  };
  /* Gmaps with diferent map types
  */

  handleMapTypesGmaps = function() {
    var map8;
    map8 = new GMaps({
      div: "#gmaps-map-types",
      lat: -12.043333,
      lng: -77.028333,
      mapTypeControlOptions: {
        mapTypeIds: ["hybrid", "roadmap", "satellite", "terrain", "osm", "cloudmade"]
      }
    });
    map8.addMapType("osm", {
      getTileUrl: function(coord, zoom) {
        return "http://tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
      },
      tileSize: new google.maps.Size(256, 256),
      name: "OpenStreetMap",
      maxZoom: 18
    });
    map8.addMapType("cloudmade", {
      getTileUrl: function(coord, zoom) {
        return "http://b.tile.cloudmade.com/8ee2a50541944fb9bcedded5165f09d9/1/256/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
      },
      tileSize: new google.maps.Size(256, 256),
      name: "CloudMade",
      maxZoom: 18
    });
    map8.setMapTypeId("osm");
  };
  /* Panorama Gmaps
  */

  handlePanoramaGmaps = function() {
    var panorama;
    panorama = GMaps.createPanorama({
      el: "#gmaps-street-view",
      lat: 41.8892437,
      lng: 12.4922597
    });
  };
  return {
    init: init
  };
})(jQuery);
