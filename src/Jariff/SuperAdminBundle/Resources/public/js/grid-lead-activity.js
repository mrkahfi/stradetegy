$(document).ready(function () {

    function filter(dataIndx, value) {
        $grid.pqGrid("filter", {
            data: [{ dataIndx: dataIndx, value: value}]
        });
    }

    var obj = { 
        width      :980, 
        height     :400, 
        title      : "Activity",     
        flexHeight :false,
        flexWidth  :false,
        filterModel: { on: true, mode: "AND", header:true },
    };

    obj.colModel = [
        { title: "ID", width: 10, dataIndx:"id"},
        { title : "Edit", editable : false, width : 10, sortable : false, render : function (ui) {
            if (ui.rowData.status != 'Complete') {
                var id  = ui.rowData.id;
                var url = Routing.generate('admin_lead_activity_edit', { "id": id });
                return "<a class='ajax' href='" + url + "'><img class='view' src='/bundles/jariffproject/admin/img/icons/stuttgart-icon-pack/16x16/edit.png' /></a>";
            };
        }},
        { title: "Priority", width: 100, dataIndx:"priority" },
        { title: "Date Create", width: 100, dataIndx:"dateCreate" },
        { title: "Owner", width: 100, dataIndx:"owner" },
        { title: "Date Task", width: 100, dataIndx:"dateTask" },
        { title: "Assigned", width: 100, dataIndx:"assigned" },
        { title: "Date Scheduled", width: 100, dataIndx:"dateScheduled" },
        { title: "Type", width: 100, dataIndx:"type" },
        { title: "Description", width: 100, dataIndx:"description" },
        { title: "Result", width: 100, dataIndx:"result" },
        { title: "Status", width: 100, dataIndx:"status" },
        { title: "Date Completed", width: 100, dataIndx:"dateCompleted" },

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
        url        : Routing.generate('admin_lead_activity_json_index', { "id": lead_id }),
        getData    : function (dataJSON) {
            return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: dataJSON.data };
        }
    };


    obj.refresh = function () {
    };
    var $grid = $("#grid_paging").pqGrid(obj);
    
});    