$(document).ready(function () {

        $(document).ready(function () {

            var obj = { 
                width      :1080, 
                height     :400, 
                title      : "Lead History",
                flexHeight :false,
                flexWidth  :false
            };

            obj.colModel = [
                { title: "Id", width: 100, dataIndx:"lh.id" },
                { title: "Admin", width: 100, dataIndx:"lh.admin" },
                { title: "Column", width: 100, dataIndx:"lh.column" },
                { title: "Date", width: 100, dataIndx:"lh.date" },
                { title: "Description", width: 100, dataIndx:"lh.description" },
                { title: "New Value", width: 100, dataIndx:"lh.newValue" },
                { title: "Old Value", width: 100, dataIndx:"lh.oldValue" },
                { title: "Table", width: 100, dataIndx:"lh.table" }
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
                sortIndx   : ["lh.id"],
                sortDir    : ["down"],
                rPPOptions : [20, 50, 100],
                url        : Routing.generate('admin_lead_history_json_index', { "id": id }),
                getData    : function (dataJSON) {
                    return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: dataJSON.data };
                }
            };


            obj.refresh = function () {        
            };
            var $grid = $("#grid_paging").pqGrid(obj);
            
        });    
    
});    