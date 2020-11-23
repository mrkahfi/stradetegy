
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


var hiddenNodes = new Array();
var hiddenNodeIds = new Array();
var isChecked = true;

function init(slugParam, pageParam) {
    var isFirst = true;
    var isSetAsRoot = false;
    var infovis = document.getElementById('infovis');
    infovis.style.align = "center";
    infovis.innerHTML = '';
    var url = Routing.generate('trade_map_supplier_json', { slug : slugParam, page : pageParam });

    $.getJSON(url, function (json) {
        var type = 'supplier';
        var w = infovis.offsetWidth - 50, h = infovis.offsetHeight - 50;

        url = url.replace("/json/", "/");
        window.history.pushState("object or string", "Title", url);

        var ht = new $jit.Hypertree({

            injectInto: 'infovis',
            Navigation: {  
                enable: false,  
                panning: 'avoid nodes',  
            },  
            width: w,
            height: h,
            Node: {
                dim: 9,
                overridable: true,
                color: "#66FF33"
            },
            Tips: {  
                enable: true,  
                type: 'HTML',  
                offsetX: 0,  
                offsetY: 0,  
                onShow: function(tip, node) {  
                    tip.innerHTML = "<div  style='background-color:#F8FFC9;text-align:center;border-radius:5px; padding:10px 10px;' class='node-tip'><p style='font-size:100%;font-weight:bold;'>"+node.name+"</p><p style='font-size:50%pt'>"+node.data.address+"</p></div>"; 
                }     
            },
            Events: {  
                enable: true,  
                type: 'HTML',
                onMouseEnter: function(node, eventInfo, e){
                    var nodeId = node.id;
                    var menu1 = [
                    {'set as Root':function(menuItem,menu) { 
                        menu.hide();
                        isSetAsRoot = true; 
                        console.log(nodeId); 
                        init(nodeId, 0);
                    }},
                    ];
                    $('.node').contextMenu(menu1,{theme:'vista'});
                } 
            },  
            Edge: {
                lineWidth: 1,
                color: "#52D5DE",
                overridable: true,
            },
            onBeforePlotNode: function(node)
            {
                if (isFirst) {
                    console.log(node._depth);
                    var odd = isOdd(node._depth);
                    if (odd) {
                        node.setData('color', "#66FF33"); 
                    } else {
                        node.setData('color', "#FF3300"); 
                    }
                    isFirst = false;
                }

                var name = node.name;
                if (name.indexOf("BEHALF OF") !== -1) {
                    node.setData('color', "#68B2E3");
                    node.setData('type', 'triangle');
                }
            },
            onPlotNode: function(node)
            {
                if (isSetAsRoot) {
                    var nodeInstance = node.getNode();
                    var nodeId = nodeInstance.id;
                    init(nodeId, 0); 
                    isSetAsRoot = false;
                }
            },
            onBeforeCompute: function (domElement, node) {
                var dot = ht.graph.getClosestNodeToOrigin("current");
                type = isOdd(dot._depth) ? 'Supplier' : 'supplier';
            },
            onCreateLabel: function (domElement, node) {

                var name = node.name;
                if (name.indexOf("BEHALF OF") !== -1) {
                    var splitted = name.split("BEHALF OF");
                    name = splitted[splitted.length - 1];
                }
                domElement.innerHTML = node.name;
                $jit.util.addEvent(domElement, 'click', function () {
                    ht.onClick(node.id, {
                        onComplete: function () {
                            console.log(node.id);
                            ht.controller.onComplete(node);
                        }
                    });
                });

            },

            onPlaceLabel: function (domElement, node) {
                var style = domElement.style;
                style.display = '';
                style.cursor = 'pointer';
                if (node._depth <= 1) {
                    style.fontSize = "0.8em";
                    style.color = "#000";
                    style.fontWeight = "normal";

                } else if (node._depth == 2) {
                    style.fontSize = "0.7em";
                    style.color = "#555";

                } else {
                    style.display = 'none';
                }

                var left = parseInt(style.left);
                var w = domElement.offsetWidth;
                style.left = (left - w / 2) + 'px';

                var name = node.name;
                if (name.indexOf("BEHALF OF") !== -1) {
                    var splitted = name.split("BEHALF OF");
                    name = splitted[splitted.length - 1];
                }
                if (name.length <= 1) {
                    console.log('tereksekusi lagi');
                    hiddenNodeIds.push(node.id);
                }
                hiddenNodes.push(node);
                domElement.innerHTML = name;
                $jit.util.addEvent(domElement, 'click', function () {
                    ht.onClick(node.id, {
                        onComplete: function () {
                            ht.controller.onComplete(node);
                        }
                    });
                });
            },

            onComplete: function (node) {
                var dot = ht.graph.getClosestNodeToOrigin("current");
                console.log(dot._depth);
                var connCount = dot.data.size;
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
                type = isOdd(dot._depth) ? 'Supplier' : 'supplier';
                var html = '';
                html = "<div id='node-info'>Company Name<h7><div id='company-name'><p style='font-size:120%;'>" + dot.name + "</p></div></h7>Company Type<div id='company-type'><p style='font-size:120%;'>"+ type +"</p></div>Featured Address<div id='featured-address'><p style='font-size:120%;'>"+dot.data.address+"</p></div>Connections<div id='connection-count'><p style='font-size:120%;'>"+connCount+"</p></div>Showing Connections<div id='showing-connection'><p style='font-size:120%;'>"+showingCount+"</p></div></div>";

                if (dot.id != slugParam) {
                    html2 = "<p><input style='margin-right:10px; vertical-align:middle' type='checkbox' "+ (isChecked ? "checked='true'" : "") + " id='hide-labels' /><span>Show hidden buyers</span></p><p>See more connections by using <b>Set as Root</b></p><p><button onclick=\"init('"+dot.id+"', '0')\" class='button button-search global-button-search' >Set as Root</button>"+"<p><button onclick=\"Clickheretoprint();\" class='button button-search global-button-search' >Printer Friendly Version</button>";
                } else {
                    html2 = "<p><input style='margin-right:10px; vertical-align:middle' type='checkbox' "+ (isChecked ? "checked='true'" : "") + " id='hide-labels' /><span>Show hidden buyers</span></p><p>See more connections by using <b>Load Next Nodes</b></p><p><button onclick=\"init('"+slugParam+"', '"+(parseInt(pageParam)+1)+"')\" class='button button-search global-button-search' >Load Next Nodes</button>"+"<p><button onclick=\"Clickheretoprint();\" class='button button-search global-button-search' >Printer Friendly Version</button>";
                }
                $jit.id('inner-details').innerHTML = html;
                $jit.id('sidebar-button').innerHTML = html2;
            }
        });
        ht.loadJSON(json);
        ht.refresh();
        ht.controller.onComplete();
        if (isChecked) {
            console.log('uncheck');
            ht.op.removeNode(hiddenNodeIds, {  
                type: 'fade:con',  
            }); 
            isChecked = false;
        }
        $('input:checkbox').live('change', function(){
            if($(this).is(':checked')){
                console.log('check');
                $(this).attr("checked",true);
                ht.op.sum(json, {  
                    type: 'fade:con',  
                });
                isChecked = true;
            } else { 
                console.log('uncheck');
                $(this).removeAttr('checked');
                ht.op.removeNode(hiddenNodeIds, {  
                    type: 'fade:con',  
                }); 
                isChecked = false;
            }
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

function processAjaxData(response, urlPath){
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