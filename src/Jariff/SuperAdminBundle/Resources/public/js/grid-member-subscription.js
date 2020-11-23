$(document).ready(function () {

        $(document).ready(function () {

            var obj = { 
                width      :1080, 
                height     :400, 
                title      : "Member Subscription",     
                flexHeight :false,
                flexWidth  :false
            };

            obj.colModel = [
                { title: "ID", width: 10, dataIndx:"subscription.id"},
                { title : "Active", editable : false, width : 10, sortable : false, render : function (ui) {
                    if(ui.rowData['subscription.active']){
                        return "<img class='view' src='/bundles/jariffproject/admin/img/icons/stuttgart-icon-pack/16x16/check.png'/>";
                    } else {
                        return "<img class='view' src='/bundles/jariffproject/admin/img/icons/stuttgart-icon-pack/16x16/busy.png'/>";
                    }
                }},
                { title: "Total", width: 100, dataIndx:"subscription.total" },
                { title: "Discount", width: 100, dataIndx:"subscription.discount" },
                { title: "Custom Discount", width: 100, dataIndx:"subscription.customDiscount" },
                { title: "Everything Plan", width: 100, dataIndx:"subscription.everythingPlan" },
                { title: "Big Picture", width: 100, dataIndx:"subscription.bigPicture" },
                { title: "Big Picture Value", width: 100, dataIndx:"subscription.bigPictureValue" },
                { title: "Download", width: 100, dataIndx:"subscription.download" },
                { title: "Download Value", width: 100, dataIndx:"subscription.downloadValue" },
                { title: "History", width: 100, dataIndx:"subscription.history" },
                { title: "History Value", width: 100, dataIndx:"subscription.historyValue" },
                { title: "Search", width: 100, dataIndx:"subscription.search" },
                { title: "Search Value", width: 100, dataIndx:"subscription.searchValue" },
                { title: "Date Activation", width: 100, dataIndx:"subscription.dateActivation" },
                { title: "Date Canceled", width: 100, dataIndx:"subscription.cancelDate" },
                { title: "Date Suspended", width: 100, dataIndx:"subscription.dateSuspended" },
                { title: "Number", width: 100, dataIndx:"subscription.number" },
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