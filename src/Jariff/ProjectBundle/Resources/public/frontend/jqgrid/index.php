<!DOCTYPE html>

<html lang="en">
<head>
    <!-- The jQuery library is a prerequisite for all jqSuite products -->
<!--    <script type="text/ecmascript" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.1.1.min.js"></script>-->
    <!-- We support more than 40 localizations -->
    <script type="text/ecmascript" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.1.1.min.js"></script>
    <script type="text/ecmascript" src="js/i18n/grid.locale-en.js"></script>
    <!-- This is the Javascript file of jqGrid -->
    <script type="text/ecmascript" src="js/jquery.jqGrid.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.4/jquery-ui.min.js" type="text/javascript"></script>

    <script type="text/ecmascript" src="plugins/ui.multiselect.js"></script>
    <!-- This is the localization file of the grid controlling messages, labels, etc.
    <!-- A link to a jQuery UI ThemeRoller theme, more than 22 built-in and many more custom -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <!-- The link to the CSS that the grid needs -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid-bootstrap.css" />

    <link rel="stylesheet" type="text/css" media="screen" href="plugins/ui.multiselect.css" />
    <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.11.1/themes/start/jquery-ui.css" media="screen" />
    <script>
        $.jgrid.defaults.width = 780;
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <meta charset="utf-8" />
    <title>jqGrid Loading Data - Million Rows from a REST service</title>
</head>
<body>
<div style="margin-left:20px">

    <table id="jqGrid" style="width: 800px"></table>
    <div id="jqGridPager"></div>

    <a href="#" class="j-test">Klik me</a>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        $("#jqGrid").jqGrid({
            url: 'http://trirand.com/blog/phpjqgrid/examples/jsonp/getjsonp.php?callback=?&qwery=longorders',
            mtype: "GET",
            styleUI : 'Bootstrap',
            datatype: "jsonp",
            colModel: [
                { label: 'OrderID', name: 'OrderID', key: true, width: 75 },
                { label: 'Customer ID', name: 'CustomerID', width: 150 },
                { label: 'Order Date', name: 'OrderDate', width: 150 },
                { label: 'Freight', name: 'Freight', width: 150 },
                { label:'Ship Name', name: 'ShipName', width: 150 }
            ],

            viewrecords: true,
            height: 250,
            width: 1000,
            rowNum: 20,
            shrinkToFit: true,
            altRows: true,
            pager: "#jqGridPager"
        });

        $('.j-test').on('click',function(){
            jQuery("#jqGrid").jqGrid('columnChooser');
        })
    });

</script>


</body>
</html>