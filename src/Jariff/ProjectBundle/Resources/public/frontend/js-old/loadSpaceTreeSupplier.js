
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

        var ht = new $jit.ST({  
            injectInto: 'infovis',   
            duration: 800,  
            transition: $jit.Trans.Quart.easeInOut,   
            levelDistance: 50,  
            Navigation: {  
              enable:true,  
              panning:true  
          },  
          Node: {  
            height: 20,  
            width: 60,  
            type: 'rectangle',  
            color: '#aaa',  
            overridable: true  
        },  

        Edge: {  
            type: 'bezier',  
            overridable: true  
        },  

        onBeforeCompute: function(node){  
            Log.write("loading " + node.name);  
        },  

        onAfterCompute: function(){  
            Log.write("done");  
        },  

        onCreateLabel: function(label, node){  
            label.id = node.id;              
            label.innerHTML = node.name;  
            label.onclick = function(){  
              ht.onClick(node.id);  
          };  
          var style = label.style;  
          style.width = 60 + 'px';  
          style.height = 17 + 'px';              
          style.cursor = 'pointer';  
          style.color = '#333';  
          style.fontSize = '0.8em';  
          style.textAlign= 'center';  
          style.paddingTop = '3px';  
      },  

      onBeforePlotNode: function(node){   
        if (node.selected) {  
            node.data.$color = "#ff7";  
        }  
        else {  
            delete node.data.$color;  
            if(!node.anySubnode("exist")) {  
                var count = 0;  
                node.eachSubnode(function(n) { count++; });  
                node.data.$color = ['#aaa', '#baa', '#caa', '#daa', '#eaa', '#faa'][count];                      
            }  
        }  
    },  

    onBeforePlotLine: function(adj){  
        if (adj.nodeFrom.selected && adj.nodeTo.selected) {  
            adj.data.$color = "#eed";  
            adj.data.$lineWidth = 3;  
        }  
        else {  
            delete adj.data.$color;  
            delete adj.data.$lineWidth;  
        }  
    }  
});  
ht.loadJSON(json);   
ht.compute();  
ht.geom.translate(new $jit.Complex(-200, 0), "current");  
ht.onClick(ht.root);  

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