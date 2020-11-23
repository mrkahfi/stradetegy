$(document).ready(function () {

        $(document).ready(function () {

            var obj = { 
                width      :1080, 
                height     :400, 
                title      : "Member Payment History",     
                flexHeight :false,
                flexWidth  :false
            };

            obj.colModel = [
                { title: "Txn ID", width: 10, dataIndx:"m.transactionId"},
                { title: "Amount", width: 100, dataIndx:"m.amount" },
                { title: "Code", width: 100, dataIndx:"m.code" },
                { title: "Response", width: 100, dataIndx:"m.response" },
                { title: "Date", width: 100, dataIndx:"m.date" },
                { title: "Action", width: 100, dataIndx:"m.action" },
                { title: "Description", width: 100, dataIndx:"m.description" },
                { title: "User", width: 100, dataIndx:"m.user" }
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
                url        : Routing.generate('admin_history_json_index', { "id": id }),
                getData    : function (dataJSON) {
                    return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: dataJSON.data };
                }
            };


            obj.refresh = function () {        
            };
            var $grid = $("#grid_paging").pqGrid(obj);
            
        });    
    
});    