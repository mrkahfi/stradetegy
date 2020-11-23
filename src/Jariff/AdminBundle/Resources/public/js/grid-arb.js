$(document).ready(function () {

        $(document).ready(function () {

            var obj = { 
                width      :1080, 
                height     :400, 
                title      : "Member ARB",     
                flexHeight :false,
                flexWidth  :false
            };

            obj.colModel = [
                { title: "ID", width: 10, dataIndx:"m.id"},
                { title : "Edit", editable : false, width : 10, sortable : false, render : function (ui) {

                    var token = ui.rowData['m.token'];
                    var url = Routing.generate('admin_arb_edit', { "token": token });
                    return "<a class='ajax' href='" + url + "'><img class='view' src='/bundles/jariffproject/admin/img/icons/stuttgart-icon-pack/16x16/edit.png' /></a>";
                }},
                { title: "Charge Date", width: 100, dataIndx:"m.chargeDate" },
                { title: "Amount", width: 100, dataIndx:"m.amount" },
                { title: "Processed", width: 100, dataIndx:"m.processed" },
                { title: "Successful", width: 100, dataIndx:"m.successful" }
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
                url        : Routing.generate('admin_arb_json_index', { "id": id }),
                getData    : function (dataJSON) {
                    return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: dataJSON.data };
                }
            };


            obj.refresh = function () {        
            };
            var $grid = $("#grid_paging").pqGrid(obj);
            
        });    
    
});    