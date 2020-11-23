
var labelType, useGradients, nativeTextSupport, animate;

(function () {
    var ua = navigator.userAgent,
    iStuff = ua.match(/iPhone/i) || ua.match(/iPad/i),
    typeOfCanvas = typeof HTMLCanvasElement,
    nativeCanvasSupport = (typeOfCanvas == 'object' || typeOfCanvas == 'function'),
    textSupport = nativeCanvasSupport
    && (typeof document.createElement('canvas').getContext('2d').fillText == 'function');
    labelType = (!nativeCanvasSupport || (textSupport && !iStuff)) ? 'Native' : 'HTML';
    nativeTextSupport = labelType == 'Native';
    useGradients = nativeCanvasSupport;
    animate = !(iStuff || !nativeCanvasSupport);
})();

var Log = {
    elem: false,
    write: function (text) {
        if (!this.elem)
            this.elem = document.getElementById('log');
        this.elem.innerHTML = text;
        this.elem.style.left = (350 - this.elem.offsetWidth / 2) + 'px';
    }
};

var rootCompany;

var hiddenNodes = new Array();
var hiddenNodeIds = new Array();
var isChecked = true;

var countryCache = new Array();
var nodeCache = new Array();
var companyCache = new Array();
var codeCache = new Array();
var containerCountCache = new Array();
var htmlCache = new Array();
var currentNode;

var prevLat;
var prevLng;

var prevDepth;
var allText = '';
var nextAddress = 0;
var delay = 200;
var markersArray = [];  
var geocoder = new google.maps.Geocoder();
var map;

var prevLoc;
var sameCountryCache = new Array();

function init(slugParam, pageParam) {
    var isFirst = true;
    var isSetAsRoot = false;
    var url = Routing.generate('world_map_buyer_json', { slug : slugParam, page : pageParam });
    YUI().use("parallel", function(Y) {
        var stack = new Y.Parallel();
        var infovis = document.getElementById('infovis');
        infovis.style.align = "center";
        infovis.innerHTML = '';
        var pathOfFileToRead = "/bundles/jariffproject/frontend/xml/country.xml";
        var pathOfXmlToRead = "/bundles/jariffproject/frontend/xml/country.xml";
        var contentsOfFileAsString = FileHelper.readStringFromFileAtPath(pathOfFileToRead);
        var contentsOfFileAsXml = FileHelper.readStringFromFileAtPath(pathOfXmlToRead);
        
        $.getJSON(url, function (json) {
            var type = 'Buyer';
            var w = infovis.offsetWidth - 50, h = infovis.offsetHeight - 50;
            url = url.replace("/json/", "/");
            window.history.pushState("object or string", "Title", url);

            var ht = new $jit.Hypertree({
                injectInto: 'infovis',
                Navigation: {  
                    enable: false,  
                    panning: 'avoid nodes',  
                },  
                Edge: {  
                    overridable: true,  
                    type: 'line',  
                    color: '#fff',  
                    alpha: 0, 
                    dim: 0, 
                    lineWidth: 0
                },
                onPlaceLabel: function (domElement, node) {
                    $jit.util.addEvent(domElement, 'click', function () {
                        ht.onClick(node.id, {
                            onComplete: function () {
                                ht.controller.onComplete(node);
                            }
                        });
                    });
                    var latitude = '57.7973333';
                    var longitude = '12.0502107';
                    var address = node.data.shipper_address;
                    var containerCount = node.data.shipperContainerCount;
                    if (node._depth == 0) {
                        address = node.data.address;
                        rootCompany = node.id;
                        containerCount = node.data.containerCount;
                    }
                    address = address.replace(/[^a-zA-Z]/g, " ");
                    address = address.replace(/\s+/g, ' ');
                    var splittedWords = address.split(' ');
                    var country = '';
                    var newSplittedWords = new Array();
                    var newSplittedWords2 = new Array();
                    var isFound = false;
                    var longestWord = '';
                    for (var i = 0; i < splittedWords.length; i = i+2) {
                        newSplittedWords.push(((splittedWords[i] != undefined) ? splittedWords[i] : '') + ' ' + ((splittedWords[i+1] != undefined) ? splittedWords[i+1] : ''));
                    };

                    for (var i = 1; i < splittedWords.length; i = i+2) {
                        newSplittedWords2.push(((splittedWords[i] != undefined) ? splittedWords[i] : '') + ' ' + ((splittedWords[i+1] != undefined) ? splittedWords[i+1] : ''));
                    };

                    for (var i = 0; i < newSplittedWords.length; i++) {
                        var split = newSplittedWords[i];
                        // console.log(split + '');

                        if (contentsOfFileAsString.indexOf('name="' + split.toUpperCase() + '"') !== -1) {
                            country = country + split;
                            var splittedXml = contentsOfFileAsString.split('" name="' + split.toUpperCase())[0].split('<country code="');  /*  console.log(splittedXml);  */  
                            var countryCode = splittedXml[splittedXml.length-1];
                            codeCache.push(countryCode); /* console.log('code '+ countryCode); */
                            isFound = true; /* console.log(country); */

                            break;
                        } else {
                            var unitSplittedWords = newSplittedWords[i].split(" ");
                            if (contentsOfFileAsString.indexOf('name="' + unitSplittedWords[0].toUpperCase() + '"') !== -1) {
                                country = country + unitSplittedWords[0];
                                isFound = true; /* console.log(country); */
                                var splittedXml = contentsOfFileAsString.split('" name="' + unitSplittedWords[0].toUpperCase())[0].split('<country code="'); /*  console.log(splittedXml);  */
                                var countryCode = splittedXml[splittedXml.length-1];
                                codeCache.push(countryCode); /* console.log('code '+ countryCode); */
                                isFound = true; /* console.log(country); */
                                break;
                            }
                            if (contentsOfFileAsString.indexOf('name="' + unitSplittedWords[1].toUpperCase() + '"') !== -1) {
                                country = country + unitSplittedWords[1];
                                isFound = true; /* console.log(country); */
                                var splittedXml = contentsOfFileAsString.split('" name="' + unitSplittedWords[1].toUpperCase())[0].split('<country code="'); /*  console.log(splittedXml);  */
                                var countryCode = splittedXml[splittedXml.length-1];
                                codeCache.push(countryCode); /* console.log('code '+ countryCode); */
                                isFound = true; /* console.log(country); */
                                break;
                            }
                        }
                    }

                    if (!isFound) {
                        for (var i = 0; i < newSplittedWords2.length; i++) {
                            var split = newSplittedWords2[i];
                            if (contentsOfFileAsString.indexOf('name="' + split.toUpperCase() + '"') !== -1) {
                                country = country + split;
                                isFound = true; /* console.log(country); */
                                var splittedXml = contentsOfFileAsString.split('" name="' + split.toUpperCase())[0].split('<country code="'); /*  console.log(splittedXml);  */
                                var countryCode = splittedXml[splittedXml.length-1];
                                codeCache.push(countryCode); /* console.log('code '+ countryCode); */
                                isFound = true; /* console.log(country); */
                                break;
                            } else {
                                var unitSplittedWords = newSplittedWords2[i].split(" ");
                                if (contentsOfFileAsString.indexOf('name="' + unitSplittedWords[0].toUpperCase() + '"') !== -1) {
                                    country = country + unitSplittedWords[0];
                                    isFound = true; /* console.log(country); */
                                    var splittedXml = contentsOfFileAsString.split('" name="' + unitSplittedWords[0].toUpperCase())[0].split('<country code="'); /*  console.log(splittedXml);  */
                                    var countryCode = splittedXml[splittedXml.length-1];
                                    codeCache.push(countryCode); /* console.log('code '+ countryCode); */
                                    isFound = true; /* console.log(country); */
                                    break;
                                }
                                if (contentsOfFileAsString.indexOf('name="' + unitSplittedWords[1].toUpperCase() + '"') !== -1) {
                                    country = country + unitSplittedWords[1];
                                    isFound = true; /* console.log(country); */
                                    var splittedXml = contentsOfFileAsString.split('" name="' + unitSplittedWords[1].toUpperCase())[0].split('<country code="'); /*  console.log(splittedXml);  */
                                    var countryCode = splittedXml[splittedXml.length-1];
                                    codeCache.push(countryCode); /* console.log('code '+ countryCode); */
                                    isFound = true; /* console.log(country); */
                                    break;
                                }
                            }
                        }
                    }

                    if (!isFound) {
                        country = (node.data.country).toUpperCase();
                        var splittedXml = contentsOfFileAsString.split('" name="' + country)[0].split('<country code="'); /*  console.log(splittedXml);  */
                        var countryCode = splittedXml[splittedXml.length-1];
                        codeCache.push(countryCode); /* console.log('code '+ countryCode); */
                    }
                    if (country.length > 1) {
                        currentNode  = node;
                        if (typeof(stack.add != undefined)) {
                            var tot = countVal(countryCache, country);
                            var containerCountInt = parseInt(containerCount);
                            containerCountCache.push(containerCountInt);
                            var connCount = node.data.size;
                            var showingCount = '';
                            if (connCount != undefined) {
                                var pageParamInt = (parseInt(pageParam)+1) * 10;
                                var modulus = connCount%10;

                                showingCount = (pageParamInt - 9) + " - " + pageParamInt;

                                if (connCount -  (pageParamInt - 9) < 10) {
                                    showingCount = (pageParamInt - 10) + " - " + ((pageParamInt - 10) + modulus);
                                }
                            } else {
                                connCount = '0';
                                showingCount = 'No Connections Shown'
                            }
                            type = isOdd(node._depth) ? 'Supplier' : 'Buyer';
                            html = "<div id='node-info'><div id='company-name'><span style='font-size: 120%;'><strong>"+node.name+"</strong></span></div>Company Type<div id='company-type'><span style='font-size:120%;'>"+ type +"</span></div>Featured Address<div id='featured-address'><span style='font-size:120%;'>"+ ((node._depth > 0) ? node.data.shipper_address : node.data.address) +"</span></div>Connections<div id='connection-count'><span style='font-size:120%;'>"+connCount+"</span></div>Showing Connections<div id='showing-connection'><span style='font-size:120%;'>"+showingCount+"</span></div></div>";
                            
                            getAddress(country, node, html, stack);
                        }
                    }
                },

                onComplete: function (node) {
                }
            });

ht.loadJSON(json);
ht.refresh();
ht.controller.onComplete();
});
});
}

function isEven(n) 
{
    return isNumber(n) && (n % 2 == 0);
}

function isOdd(n)
{
    return isNumber(n) && (n % 2 == 1);
}
function isNumber(n)
{
    return n === parseFloat(n);
}

var infowindowCache = new Array();
var markerCache = new Array();

var targetSVG = "M9,0C4.029,0,0,4.029,0,9s4.029,9,9,9s9-4.029,9-9S13.971,0,9,0z M9,15.93 c-3.83,0-6.93-3.1-6.93-6.93S5.17,2.07,9,2.07s6.93,3.1,6.93,6.93S12.83,15.93,9,15.93 M12.5,9c0,1.933-1.567,3.5-3.5,3.5S5.5,10.933,5.5,9S7.067,5.5,9,5.5 S12.5,7.067,12.5,9z";

function plotMarker() {

    var normalContainerCount = Array.max(containerCountCache);
    var minContainerCount = Array.min(containerCountCache);

    // console.log(markersArray);
    // console.log('\n\n\markersArray '+markersArray.length+'\n');

    var rootMarker = markersArray[companyCache.indexOf(rootCompany)];

    var verDiff = 0;
    var horDiff = 0;
    var images = new Array();

    var worldDataProvider = {
        map: "worldLow",
        getAreasFromMap: true,
        linkToObject: rootCompany,
        images: images
    };
    var areas = new Array();

    for (var i = 0; i < markersArray.length; i++) {
        if (markersArray[i] != undefined) {
            var tot = countVal(countryCache, countryCache[i]);
            var conCount = 0;
            var lum;

            if (tot > 1) {
                for (var j = 0; j < countryCache.length; j++) {
                    if (countryCache[j] == countryCache[i]) {
                        conCount = conCount + containerCountCache[j];
                    }
                };
                lum = -(conCount/normalContainerCount) * 3/4;
            } else {
                conCount = containerCountCache[i];
                lum = -(conCount/normalContainerCount) * 3/4;
            }

            var latitude = markersArray[i].lat();
            var longitude = markersArray[i].lng(); 
            console.log('total Count of ' + countryCache[i] + ' : ' + conCount + '. Berarti lum-nya : ' + lum + '. Koordinatnya ' + longitude + " - " + latitude);

            var lines = new Array();
            var image = {
                id: nodeCache[i].id,
                color: "#000000",
                svgPath: targetSVG,
                title: (tot > 1) ? tot +' companies' : '1 company',
                latitude: latitude,
                longitude: longitude,
                scale: 1,
                zoomLevel: 2.5,
                zoomLongitude: longitude,
                zoomLatitude: latitude,
                lines: lines
            };

            var area = {
                id: codeCache[i],
                value: conCount, 
                passZoomValuesToTarget: true
            };            

            if (tot == 1) {
                image.description = htmlCache[i];
            } else {
                var html = '';
                for (var n = 0; n < countryCache.length; n++) {
                    if (countryCache[n] == countryCache[i]) {
                        html = htmlCache[n] + '<br>' + html;
                    }
                };
                image.description = html;
            }

            var verDiff1 = 0;
            var horDiff1 = 0;
            var countryCountainer = '';
            var adjacencies =  nodeCache[i].eachAdjacency(function(adj) {  
                var indexe = companyCache.indexOf(adj.nodeTo.id);
                var tot = countVal(countryCache, countryCache[indexe]);
                
                var newLoc = markersArray[indexe]; 
                if (newLoc != null) { 
                    var newLat = newLoc.lat();
                    var newLng = newLoc.lng();
                    var line = {
                        latitudes: [latitude, newLat],
                        longitudes: [longitude, newLng]
                    };
                    lines.push(line);
                }
                // console.log(line);
            }); 
            images.push(image);
            areas.push(area);
        }
    }
    console.log('woooot');

    worldDataProvider.images = images;
    worldDataProvider.areas = areas;

    var valueLegend = new AmCharts.ValueLegend();
    valueLegend.right = 10;
    valueLegend.minValue = minContainerCount;
    valueLegend.maxValue = normalContainerCount;

    AmCharts.makeChart("infovis", {
        colorSteps: 10,
        type: "map",
        pathToImages: "/bundles/jariffproject/frontend/js/ammap/images/",

        areasSettings: {
            autoZoom: true,
            unlistedAreasColor: "#CDCDD1",
            rollOverOutlineColor: "#000000",
            selectedColor: "#BDBCD4",
            rollOverColor: "#BDBCD4",
            color: ColorLuminance('BDBCD4', 0.1),
            colorSolid: ColorLuminance('BDBCD4', -0.6),
            descriptionWindowY: -150,
            descriptionWindowX: -150,
            descriptionWindowWidth: 360,
            descriptionWindowHeight: 220
        },
        imagesSettings: {
            color: "#000000",
            rollOverColor: "#CC0000",
            selectedColor: "#CC0000",
            rollOverScale: 5,
            descriptionWindowY: -150,
            descriptionWindowX: -150,
            descriptionWindowWidth: 360,
            descriptionWindowHeight: 220
        },
        dataProvider: worldDataProvider,

        backgroundZoomsToTop: true,
        linesAboveImages: true,
        showImagesInList: true,

        exportConfig: {},
        valueLegend: valueLegend
    }); 
}


function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }
    out = out + "\n\n"

    console.log(out);

    var pre = document.createElement('pre');
    pre.innerHTML = out;
    document.body.appendChild(pre)
}

function FileHelper()
{}
{
    FileHelper.readStringFromFileAtPath = function(pathOfFileToReadFrom)
    {
        var request = new XMLHttpRequest();
        request.open("GET", pathOfFileToReadFrom, false);
        request.send(null);
        var returnValue = request.responseText;

        return returnValue;
    }
}

function getAddress(search, node, html, stack) {
    geocoder.geocode({ 'address': search }, stack.add(function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            latitude = results[0].geometry.location.lat();
            longitude = results[0].geometry.location.lng();
            prevLoc = results[0].geometry.location;
            markersArray.push(results[0].geometry.location);       
            nodeCache.push(node);  
            htmlCache.push(html);  
            countryCache.push(search);
            companyCache.push(node.id);
            // console.log('OKE ' + node.name + ' ... ' + node._depth + ' ... ' + search + ' ... '+results[0].geometry.location);
        } else {
            if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
                delay = delay + 10;
                // console.log(''+delay); 
                setTimeout((function() { getAddress(search, node, html, stack); }), delay);
                var reason="Code "+status;
                var msg = 'name="'+node.name+'" ,  address="' + search + '" error=' +reason +' ms<br>';
            } else if (status == google.maps.GeocoderStatus.ZERO_RESULTS) {
                var reason="Code "+status;
                var msg = 'address="' + search + '" error=' +reason +' ms<br>';
                console.log(msg);
            } else {
                var reason="Code "+status;
                var msg = 'address="' + search + '" error=' +reason +' ms<br>';
            }   
        }
    }));
stack.done(function(){
    console.log('stacks rampung');
    plotMarker();
});  
}

/**
 * Returns a random number between min and max
 */
 function getRandomArbitary (min, max) {
    return Math.random() * (max - min) + min;
}


function getRandomInt (min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function countVal(array, value) {
  var counter = 0;
  for(var i=0;i<array.length;i++) {
    if (array[i] === value) counter++;
}
return counter;
}

Colors = {};
// human readable colors
Colors.names = {
    darkblue: "#00008b",
    darkcyan: "#008b8b",
    darkgrey: "#a9a9a9",
    darkgreen: "#006400",
    darkkhaki: "#bdb76b",
    darkmagenta: "#8b008b",
    darkolivegreen: "#556b2f",
    darkorange: "#ff8c00",
    darkorchid: "#9932cc",
    darkred: "#8b0000",
    darksalmon: "#e9967a",
    darkviolet: "#9400d3",
};

Colors.random = function() {
    var result;
    var count = 0;
    for (var prop in this.names)
        if (Math.random() < 1/++count)
           result = prop;
       return result;
   };

   function toTitleCase(str)
   {
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

function ColorLuminance(hex, lum) {

    hex = String(hex).replace(/[^0-9a-f]/gi, '');
    if (hex.length < 6) {
        hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
    }
    lum = lum || 0;

    var rgb = "#", c, i;
    for (i = 0; i < 3; i++) {
        c = parseInt(hex.substr(i*2,2), 16);
        c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
        rgb += ("00"+c).substr(c.length);
    }

    return rgb;
}

Array.max = function( array ){
    return Math.max.apply( Math, array );
};

Array.min = function( array ){
    return Math.min.apply( Math, array );
};