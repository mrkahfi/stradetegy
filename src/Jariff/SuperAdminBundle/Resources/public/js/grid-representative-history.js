$(document).ready(function () {

        $(document).ready(function () {

            var obj = { 
                width      :1080, 
                height     :400, 
                title      : "Member Representative History",     
                flexHeight :false,
                flexWidth  :false
            };

            obj.colModel = [
                { title: "Id", width: 100, dataIndx:"r.id" },
                { title: "Admin", width: 100, dataIndx:"r.admin" },
                { title: "Date", width: 100, dataIndx:"r.date" },
                { title: "Description", width: 100, dataIndx:"r.description" },
                { title: "Result", width: 100, dataIndx:"r.result" },
                { title: "Subscription", width: 100, dataIndx:"r.subscription" },
                { title: "Status", width: 100, dataIndx:"r.status" },
                { title: "Type", width: 100, dataIndx:"r.type" }
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
                sortIndx   : ["r.id"],
                sortDir    : ["down"],
                rPPOptions : [20, 50, 100],
                url        : Routing.generate('admin_member_representative_history_json_index', { "id": id }),
                getData    : function (dataJSON) {
                    return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: dataJSON.data };
                }
            };


            obj.refresh = function () {        
            };
            var $grid = $("#grid_paging").pqGrid(obj);
            
        });    
    
});    