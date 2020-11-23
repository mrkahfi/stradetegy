$(document).ready(function () {

    function filter(dataIndx, value) {
        $grid.pqGrid("filter", {
            data: [{ dataIndx: dataIndx, value: value}]
        });
    }

    var obj = { 
        width      :980, 
        height     :400, 
        title      : "Leads",     
        flexHeight :false,
        flexWidth  :false,
        filterModel: { on: true, mode: "AND", header:true },
    };

    obj.colModel = [
        { title: "ID", width: 10, dataIndx:"id"},
        { title : "View", editable : false, width : 10, sortable : false, render : function (ui) {
            var id = ui.rowData.id;
            var url = Routing.generate('admin_lead_show', { "id": id });
            return "<a class='ajax' href='" + url + "'><img class='view' src='/bundles/jariffproject/admin/img/icons/stuttgart-icon-pack/16x16/zoom.png' /></a>";
        }},
        { title: "Temp", width: 50, dataIndx:"flag.name" },
        { title: "Date Create", width: 100, dataIndx:"dateCreate" },
        { title: "Contact #1", width: 100, dataIndx:"contact.name" },
        { title: "Company", width: 100, dataIndx:"business", filter: {
            type : 'textbox', condition : 'contain', listeners : [{ change: function (evt, ui) {
                filter("business", $(this).val());
            }}]
        }},
        { title: "Sales Executive #1", width: 100, dataIndx:"sales.name" },
        { title: "Date of last completed activity", width: 100, dataIndx:"activity.dateCompleted" },
        { title: "Country / Call Time", width: 100, dataIndx:"contact.callTime" },
        { title: "Stage", width: 100, dataIndx:"lead.stage" },
    ];
    obj.dataModel = {
        location   : "remote",
        sorting    : "remote",
        paging     : "remote",
        method     : "GET",
        curPage    : 1,
        rPP        : 20,
        sortIndx   : ["id"],
        sortDir    : ["down"],
        rPPOptions : [20, 50, 100],
        url        : Routing.generate('admin_lead_json_index'),
        getData    : function (dataJSON) {
            return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: dataJSON.data };
        }
    };


    obj.refresh = function () {
    };
    var $grid = $("#grid_paging").pqGrid(obj);
    
});