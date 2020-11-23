$(document).ready(function () {

    function filter(dataIndx, value) {
        $grid.pqGrid("filter", {
            data: [{ dataIndx: dataIndx, value: value}]
        });
    }

    var obj = { 
        width      :1080, 
        height     :400, 
        title      : "Inbound",     
        flexHeight :false,
        flexWidth  :false,
        filterModel: { on: true, mode: "AND", header:true },
    };

    obj.colModel = [
        { title: "Id", width: 10, dataIndx:"id" },
        { title : "Show", editable : false, width : 10, sortable : false, render : function (ui) {
            var id = ui.rowData.id;
            var url = Routing.generate('admin_inbound_show', { "id": id });
            return "<a class='ajax' href='" + url + "'><img class='view' src='/bundles/jariffproject/admin/img/icons/stuttgart-icon-pack/16x16/zoom.png' /></a>";
        }},
        { title: "Source", width: 100, dataIndx:"i.source", filter: { 
            type : 'textbox', condition : 'contain', listeners : [{ change: function (evt, ui) {
                filter("i.source", $(this).val());
            }}]
        }},
        { title: "Email", width: 100, dataIndx:"m.email", filter: { 
            type : 'textbox', condition : 'contain', listeners : [{ change: function (evt, ui) {
                filter("m.email", $(this).val());
            }}]
        }},
        { title: "Phone", width: 100, dataIndx:"i.phone", filter: { 
            type : 'textbox', condition : 'contain', listeners : [{ change: function (evt, ui) {
                filter("i.phone", $(this).val());
            }}]
        }},
        { title: "Business", width: 100, dataIndx:"i.business" },
        { title: "Date Create", width: 100, dataIndx:"i.dateCreate" },
        { title: "Date Finish", width: 100, dataIndx:"i.dateFinish" },
        { title: "Date Update", width: 100, dataIndx:"i.dateUpdate" },
        { title: "Description", width: 100, dataIndx:"i.description" },
        { title: "Flag", width: 100, dataIndx:"i.flag" },
        { title: "Ip Address", width: 100, dataIndx:"i.ipAddress" },
        { title: "Lead", width: 100, dataIndx:"i.lead" },
        { title: "Queue", width: 100, dataIndx:"i.queue" },
        { title: "Time Lapsed", width: 100, dataIndx:"i.timeLapsed" },
        // { title: "Visited Page", width: 500, dataIndx:"i.visitedPage" },
    ];

    obj.dataModel = {
        location   : "remote",
        sorting    : "remote",
        paging     : "remote",
        method     : "GET",
        curPage    : 1,
        rPP        : 20,
        sortIndx   : ["i.id"],
        sortDir    : ["down"],
        rPPOptions : [20, 50, 100],
        url        : Routing.generate('admin_inbound_json_index'),
        getData    : function (dataJSON) {
            return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: dataJSON.data };
        }
    };

    obj.refresh = function () {
    };
    
    var $grid = $("#grid_paging").pqGrid(obj);
});    