$(document).ready(function () {

        $(document).ready(function () {

            var obj = { 
                width      :1080, 
                height     :400, 
                title      : "Member Email History",     
                flexHeight :false,
                flexWidth  :false
            };

            obj.colModel = [
                { title: "Id", width: 50, dataIndx:"m.id" },
                { title : "Edit", editable : false, width : 10, sortable : false, render : function (ui) {
                    if (ui.rowData["m.dateSend"] == "---"){
                        var url = Routing.generate('admin_member_email_edit', { "id": ui.rowData["m.id"] });
                        return "<a class='ajax' href='" + url + "'><img class='view' src='/bundles/jariffproject/admin/img/icons/stuttgart-icon-pack/16x16/edit.png' /></a>";
                    } else {
                        return "---";
                    }
                }},
                { title : "Send", editable : false, width : 10, sortable : false, render : function (ui) {
                    if (ui.rowData["m.dateSend"] == "---"){
                        var url = Routing.generate('admin_member_email_send', { "id": ui.rowData["m.id"] });
                        return "<a class='ajax' href='" + url + "'><img class='view' src='/bundles/jariffproject/admin/img/icons/stuttgart-icon-pack/16x16/email.png' /></a>";
                    } else {
                        return "sent";
                    }
                }},
                { title: "Message", width: 300, dataIndx:"m.altbody" },
                { title: "Email", width: 100, dataIndx:"m.address" },
                { title: "Date Create", width: 100, dataIndx:"m.dateCreate" },
                { title: "Date Send", width: 100, dataIndx:"m.dateSend" },
                { title: "Subject", width: 100, dataIndx:"m.subject" },
                { title: "View Date", width: 100, dataIndx:"m.viewDate" },
                { title: "View Ip Address", width: 100, dataIndx:"m.viewIpAddress" },
                { title: "View Os", width: 100, dataIndx:"m.viewOs" }
            ];

            // karena ada generate routing di each row table, jadinya js nya harus full js bukan dari twig / server
            var id = $("#grid_paging").attr('data-id');

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
                url        : Routing.generate('admin_member_email_json_index', { "id": id }),
                getData    : function (dataJSON) {
                    return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: dataJSON.data };
                }
            };


            obj.refresh = function () {        
            };
            var $grid = $("#grid_paging").pqGrid(obj);
            
        });    
    
});    