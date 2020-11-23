$(document).ready(function () {

        $(document).ready(function () {

            var obj = { 
                width      :1080, 
                height     :400, 
                title      : "Member Invoice",     
                flexHeight :false,
                flexWidth  :false
            };

            obj.colModel = [
                { title: "ID", width: 100, dataIndx:"invoice.id"},
                { title: "Number", width: 100, dataIndx:"invoice.number"},
                { title: "Amount", width: 100, dataIndx:"invoice.amount"},
                { title: "Type", editable : false, width : 100, sortable : false, render : function (ui) {
                    if(ui.rowData['subscription.type'] = 'outstanding'){
                        var invoice_id = ui.rowData['invoice.id'];
                        var url = Routing.generate('admin_member_payment_new', { "id": invoice_id });
                        return "Outstanding <a href='" + url + "'><img class='view' src='/bundles/jariffproject/admin/img/icons/stuttgart-icon-pack/16x16/cost.png' /></a>";
                    } else {
                        return 'Paid';
                    }
                }},
                { title: "Payment", width: 100, dataIndx:"invoice.payment"},
                { title: "Bill To Name", width: 100, dataIndx:"invoice.billToName"},
                { title: "Bill To Adress", width: 100, dataIndx:"invoice.billToAdress"},
                { title: "Bill To Email", width: 100, dataIndx:"invoice.billToEmail"},
                { title: "Bill To Phone", width: 100, dataIndx:"invoice.billToPhone"},
                { title: "Date", width: 100, dataIndx:"invoice.date"},
                { title: "Description", width: 100, dataIndx:"invoice.description"},
                { title: "Sales", width: 100, dataIndx:"invoice.sales"},
                { title: "Subscription", width: 100, dataIndx:"invoice.subscription"}
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
                sortIndx   : ["invoice.id"],
                sortDir    : ["down"],
                rPPOptions : [20, 50, 100],
                url        : Routing.generate('admin_member_invoice_json_index', { "id": id }),
                getData    : function (dataJSON) {
                    return { curPage: dataJSON.curPage, totalRecords: dataJSON.totalRecords, data: dataJSON.data };
                }
            };


            obj.refresh = function () {        
            };
            var $grid = $("#grid_paging").pqGrid(obj);
            
        });    
    
});    