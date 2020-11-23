$(document).ready(function () {

    function filter(dataIndx, value) {
        $grid.pqGrid("filter", {
            data: [{ dataIndx: dataIndx, value: value}]
        });
    }

    var obj = { 
        width      :980, 
        height     :400, 
        title      : "Pending members",     
        flexHeight :false,
        flexWidth  :false,
        filterModel: { on: true, mode: "AND", header:true },
    };

    obj.colModel = [
        { title: "ID", width: 10, dataIndx:"id"},
        { title : "View", editable : false, width : 10, sortable : false, render : function (ui) {
            var token = ui.rowData.token;
            var url = Routing.generate('admin_pending_show', { "token": token });
            return "<a class='ajax' href='" + url + "'><img class='view' src='/bundles/jariffproject/admin/img/icons/stuttgart-icon-pack/16x16/zoom.png' /></a>";
        }},
        { title : "Edit", editable : false, width : 10, sortable : false, render : function (ui) {
            var token = ui.rowData.token;
            var url = Routing.generate('admin_pending_edit', { "token": token });
            return "<a class='ajax' href='" + url + "'><img class='view' src='/bundles/jariffproject/admin/img/icons/stuttgart-icon-pack/16x16/edit.png' /></a>";
        }},
        { title : "Activate", editable : false, width : 10, sortable : false, render : function (ui) {
            var token = ui.rowData.token;
            var url = Routing.generate('admin_pending_activate', { "token": token });
            return "<a class='ajax' href='" + url + "'><img class='view' src='/bundles/jariffproject/admin/img/icons/stuttgart-icon-pack/16x16/check.png' /></a>";
        }},
        { title: "Email", width: 100, dataIndx:"email", filter: { 
            type : 'textbox', condition : 'contain', listeners : [{ change: function (evt, ui) {
                filter("email", $(this).val());
            }}]
        }},
        { title: "", width: 10, dataIndx:"salutation" },
        { title: "First Name", width: 100, dataIndx:"firstName", filter: { 
            type : 'textbox', condition : 'contain', listeners : [{ change: function (evt, ui) {
                filter("firstName", $(this).val());
            }}]
        }},
        { title: "Last Name", width: 100, dataIndx:"lastName", filter: { 
            type : 'textbox', condition : 'contain', listeners : [{ change: function (evt, ui) {
                filter("lastName", $(this).val());
            }}]
        }},
        { title: "Company Name", width: 200, dataIndx:"companyName" },
        { title: "Country", width: 100, dataIndx:"country" },
        { title: "Phone", width: 100, dataIndx:"phone" },
        { title: "Date Registration", width: 100, dataIndx:"dateRegistration" },
        { title: "Payment", width: 100, dataIndx:"payment" },
        { title: "Cc Type", width: 100, dataIndx:"ccType" },
        { title: "Number", width: 100, dataIndx:"number" },
        { title: "Expired", width: 100, dataIndx:"expired" },
        { title: "Secure Code", width: 100, dataIndx:"secureCode" },
        { title: "Everything Plan", width: 10, dataIndx:"everythingPlan" },
        { title: "Custom Plan", width: 10, dataIndx:"customPlan" },
        { title: "History", width: 100, dataType: "integer", align: "right", dataIndx:"history" },
        { title: "Search", width: 100, dataIndx:"search" },
        { title: "Download", width: 100, dataIndx:"download" },
        { title: "Big Picture", width: 10, dataIndx:"bigPicture" },
        { title: "Payment Term", width: 100, dataIndx:"paymentTerm" },
        { title: "Month", width: 100, dataIndx:"month" }
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
        url        : Routing.generate('admin_pending_json_index'),
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