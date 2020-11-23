$(document).ready(function () {

    function filter(dataIndx, value) {
        $grid.pqGrid("filter", {
            data: [{ dataIndx: dataIndx, value: value}]
        });
    }

    var obj = {
        width      :1200,
        height     :400,
        title      : "Member",
        flexHeight :false,
        flexWidth  :false,
        filterModel: { on: true, mode: "AND", header:true },
    };

    obj.colModel = [
        { title: "ID", width: 10, dataIndx:"id"},
        { title : "View", editable : false, width : 10, sortable : false, render : function (ui) {
            var id = ui.rowData['id'];
            var url = Routing.generate('admin_member_profile_show', { "id": id });
            return "<a class='ajax' href='" + url + "'><img class='view' src='/bundles/jariffproject/admin/img/icons/stuttgart-icon-pack/16x16/zoom.png' /></a>";
        }},
        { title : "Edit", editable : false, width : 10, sortable : false, render : function (ui) {
            return "<img class='edit' src='/bundles/jariffproject/admin/img/icons/stuttgart-icon-pack/16x16/edit.png' />";
        }},
        { title: "Number", width: 100, dataIndx:"m.number", filter: {
            type : 'textbox', condition : 'contain', listeners : [{ change: function (evt, ui) {
                filter("m.number", $(this).val());
            }}]
        }},
        { title: "Status", width: 100, dataIndx:"m.status", filter: {
            type : 'textbox', condition : 'contain', listeners : [{ change: function (evt, ui) {
                filter("m.status", $(this).val());
            }}]
        }},
        { title: "Company", width: 100, dataIndx:"mc.name", filter: {
            type : 'textbox', condition : 'contain', listeners : [{ change: function (evt, ui) {
                filter("mc.name", $(this).val());
            }}]
        }},
        { title: "First name", width: 100, dataIndx:"mp.firstName", filter: {
            type : 'textbox', condition : 'contain', listeners : [{ change: function (evt, ui) {
                filter("mp.firstName", $(this).val());
            }}]
        }},
        { title: "Last name", width: 100, dataIndx:"mp.lastName", filter: {
            type : 'textbox', condition : 'contain', listeners : [{ change: function (evt, ui) {
                filter("mp.lastName", $(this).val());
            }}]
        }},
        { title: "Email", width: 200, dataIndx:"m.email", filter: {
            type : 'textbox', condition : 'contain', listeners : [{ change: function (evt, ui) {
                filter("m.email", $(this).val());
            }}]
        }},
        { title: "Last login date", width: 100, dataIndx:"m.lastLoginDate" },
    ];

    obj.dataModel = {
        location   : "remote",
        sorting    : "remote",
        paging     : "remote",
        method     : "GET",
        curPage    : 1,
        rPP        : 20,
        sortIndx   : ["m.id"],
        sortDir    : ["down"],
        rPPOptions : [20, 50, 100],
        url        : Routing.generate('admin_member_json_index'),
        getData    : function (dataJSON) {
            return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: dataJSON.data };
        }
    };

    obj.refresh = function () {
    };
    
    var $grid = $("#grid_paging").pqGrid(obj);
});