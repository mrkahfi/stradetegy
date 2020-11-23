$(document).ready(function () {

    function filter(dataIndx, value) {
        $grid.pqGrid("filter", {
            data: [{ dataIndx: dataIndx, value: value}]
        });
    }

    var obj = { 
        width      :1080, 
        height     :400, 
        title      : "Payments",     
        flexHeight :false,
        flexWidth  :false,
        filterModel: { on: true, mode: "AND", header:true },
    };

    obj.colModel = [
        { title: "ID", width: 10, dataIndx:"p.id"},
        { title : "View", editable : false, width : 10, sortable : false, render : function (ui) {
            var token = ui.rowData['p.token'];
            var url = Routing.generate('admin_payment_show', { "token": token });
            return "<a class='ajax' href='" + url + "'><img class='view' src='/bundles/jariffproject/admin/img/icons/stuttgart-icon-pack/16x16/zoom.png' /></a>";
        }},
        { title : "Edit", editable : false, width : 10, sortable : false, render : function (ui) {
            return "<img class='edit' src='/bundles/jariffproject/admin/img/icons/stuttgart-icon-pack/16x16/edit.png' />";
        }},
        { title: "Admin", width: 100, dataIndx:"a.name", filter: { 
            type : 'textbox', condition : 'contain', listeners : [{ change: function (evt, ui) {
                filter("a.name", $(this).val());
            }}]
        }},
        { title: "Member first name", width: 100, dataIndx:"mp.firstName", filter: { 
            type : 'textbox', condition : 'contain', listeners : [{ change: function (evt, ui) {
                filter("mp.firstName", $(this).val());
            }}]
        }},
        { title: "Member last name", width: 100, dataIndx:"mp.lastName", filter: { 
            type : 'textbox', condition : 'contain', listeners : [{ change: function (evt, ui) {
                filter("mp.lastName", $(this).val());
            }}]
        }},
        { title: "Amount", width: 100, dataIndx:"p.amount"},
        { title: "Date", width: 100, dataIndx:"p.date" },
        { title: "Type", width: 100, dataIndx:"p.type" },
        { title: "Note", width: 100, dataIndx:"p.note" }
    ];
    obj.dataModel = {
        location   : "remote",
        sorting    : "remote",
        paging     : "remote",
        method     : "GET",
        curPage    : 1,
        rPP        : 20,
        sortIndx   : ["p.id"],
        sortDir    : ["down"],
        rPPOptions : [20, 50, 100],
        url        : Routing.generate('admin_payment_json_index'),
        getData    : function (dataJSON) {
            return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: dataJSON.data };
        }
    };


    obj.refresh = function () {
        // $("#grid_paging").find("a.ajax").unbind("click").bind(
        //     "click", function (e) {
        //         e.preventDefault();
        //         var path  = $(this).attr('href');
        //         var title = $(this).attr('title');
        //         History.pushState('ajax',title,path);
        //     }
        // );
        
    };
    var $grid = $("#grid_paging").pqGrid(obj);
    
});    