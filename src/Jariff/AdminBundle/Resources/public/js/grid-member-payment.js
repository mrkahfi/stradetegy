$(document).ready(function () {

        $(document).ready(function () {

            var obj = { 
                width      :1080, 
                height     :400, 
                title      : "Member Payment",     
                flexHeight :false,
                flexWidth  :false
            };

            obj.colModel = [
                { title: "ID", width: 10, dataIndx:"subscription.id"},
                { title: "Active", width: 100, dataIndx:"subscription.active" },
                { title: "Big Picture", width: 100, dataIndx:"subscription.bigPicture" },
                { title: "Big Picture Value", width: 100, dataIndx:"subscription.bigPictureValue" },
                { title: "Date Activation", width: 100, dataIndx:"subscription.dateActivation" },
                { title: "Date Canceled", width: 100, dataIndx:"subscription.cancelDate" },
                { title: "Date Suspended", width: 100, dataIndx:"subscription.dateSuspended" },
                { title: "Download", width: 100, dataIndx:"subscription.download" },
                { title: "Download Value", width: 100, dataIndx:"subscription.downloadValue" },
                { title: "History", width: 100, dataIndx:"subscription.history" },
                { title: "History Value", width: 100, dataIndx:"subscription.historyValue" },
                // { title: "Member", width: 100, dataIndx:"subscription.member" },
                { title: "Number", width: 100, dataIndx:"subscription.number" },
                { title: "Search", width: 100, dataIndx:"subscription.search" },
                { title: "Search Value", width: 100, dataIndx:"subscription.searchValue" },
                { title: "Training", width: 100, dataIndx:"subscription.training" },
                { title: "Owner", width: 100, dataIndx:"subscription.owner" },
                { title: "Sales2", width: 100, dataIndx:"subscription.sales2" }
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
                sortIndx   : ["subscription.id"],
                sortDir    : ["down"],
                rPPOptions : [20, 50, 100],
                url        : Routing.generate('admin_member_subscription_json_index', { "id": id }),
                getData    : function (dataJSON) {
                    return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: dataJSON.data };
                }
            };


            obj.refresh = function () {        
            };
            var $grid = $("#grid_paging").pqGrid(obj);
            
        });    
    
});    