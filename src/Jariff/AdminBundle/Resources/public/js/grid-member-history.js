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
                { title: "Id", width: 100, dataIndx:"m.id" },
                { title: "Admin", width: 100, dataIndx:"m.admin" },
                { title: "Column", width: 100, dataIndx:"m.column" },
                { title: "Date", width: 100, dataIndx:"m.date" },
                { title: "Description", width: 100, dataIndx:"m.description" },
                { title: "Member", width: 100, dataIndx:"m.member" },
                { title: "New Value", width: 100, dataIndx:"m.newValue" },
                { title: "Old Value", width: 100, dataIndx:"m.oldValue" },
                { title: "Table", width: 100, dataIndx:"m.table" }
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
                url        : Routing.generate('admin_member_history_json_index', { "id": id }),
                getData    : function (dataJSON) {
                    return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: dataJSON.data };
                }
            };


            obj.refresh = function () {        
            };
            var $grid = $("#grid_paging").pqGrid(obj);
            
        });    
    
});    