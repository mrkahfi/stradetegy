/**
 * Product:        Social - Premium Responsive Admin Template
 * Version:        v1.3.1
 * Copyright:      2013 CesarLab.com
 * License:        http://themeforest.net/licenses
 * Live Preview:   http://go.cesarlab.com/SocialAdminTemplate
 * Purchase:       http://go.cesarlab.com/PurchaseSocial
 *
*/

/* Set the defaults for DataTables initialisation
*/

var editRow, fnFilterGlobal, fnGetSelected, restoreRow, saveRow;

$.extend(true, $.fn.dataTable.defaults, {
  sDom: "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
  sPaginationType: "bootstrap",
  oLanguage: {
    sLengthMenu: "_MENU_ records per page"
  }
});

/* Default class modification
*/


$.extend($.fn.dataTableExt.oStdClasses, {
  sWrapper: "dataTables_wrapper form-inline"
});

/* API method to get paging information
*/


$.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
  return {
    iStart: oSettings._iDisplayStart,
    iEnd: oSettings.fnDisplayEnd(),
    iLength: oSettings._iDisplayLength,
    iTotal: oSettings.fnRecordsTotal(),
    iFilteredTotal: oSettings.fnRecordsDisplay(),
    iPage: (oSettings._iDisplayLength === -1 ? 0 : Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength)),
    iTotalPages: (oSettings._iDisplayLength === -1 ? 0 : Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength))
  };
};

/* Bootstrap style pagination control
*/


$.extend($.fn.dataTableExt.oPagination, {
  bootstrap: {
    fnInit: function(oSettings, nPaging, fnDraw) {
      var els, fnClickHandler, oLang;
      oLang = oSettings.oLanguage.oPaginate;
      fnClickHandler = function(e) {
        e.preventDefault();
        if (oSettings.oApi._fnPageChange(oSettings, e.data.action)) {
          return fnDraw(oSettings);
        }
      };
      $(nPaging).addClass("pagination").append("<ul>" + "<li class=\"prev disabled\"><a href=\"#\">&larr; " + oLang.sPrevious + "</a></li>" + "<li class=\"next disabled\"><a href=\"#\">" + oLang.sNext + " &rarr; </a></li>" + "</ul>");
      els = $("a", nPaging);
      $(els[0]).bind("click.DT", {
        action: "previous"
      }, fnClickHandler);
      return $(els[1]).bind("click.DT", {
        action: "next"
      }, fnClickHandler);
    },
    fnUpdate: function(oSettings, fnDraw) {
      var an, i, iEnd, iHalf, iListLength, iStart, ien, j, oPaging, sClass;
      iListLength = 5;
      oPaging = oSettings.oInstance.fnPagingInfo();
      an = oSettings.aanFeatures.p;
      iHalf = Math.floor(iListLength / 2);
      if (oPaging.iTotalPages < iListLength) {
        iStart = 1;
        iEnd = oPaging.iTotalPages;
      } else if (oPaging.iPage <= iHalf) {
        iStart = 1;
        iEnd = iListLength;
      } else if (oPaging.iPage >= (oPaging.iTotalPages - iHalf)) {
        iStart = oPaging.iTotalPages - iListLength + 1;
        iEnd = oPaging.iTotalPages;
      } else {
        iStart = oPaging.iPage - iHalf + 1;
        iEnd = iStart + iListLength - 1;
      }
      i = 0;
      ien = an.length;
      while (i < ien) {
        /* Remove the middle elements
        */

        $("li:gt(0)", an[i]).filter(":not(:last)").remove();
        /* Add the new list items and their event handlers
        */

        j = iStart;
        while (j <= iEnd) {
          sClass = (j === oPaging.iPage + 1 ? "class=\"active\"" : "");
          $("<li " + sClass + "><a href=\"#\">" + j + "</a></li>").insertBefore($("li:last", an[i])[0]).bind("click", function(e) {
            e.preventDefault();
            oSettings._iDisplayStart = (parseInt($("a", this).text(), 10) - 1) * oPaging.iLength;
            return fnDraw(oSettings);
          });
          j++;
        }
        /* Add / remove disabled classes from the static elements
        */

        if (oPaging.iPage === 0) {
          $("li:first", an[i]).addClass("disabled");
        } else {
          $("li:first", an[i]).removeClass("disabled");
        }
        if (oPaging.iPage === oPaging.iTotalPages - 1 || oPaging.iTotalPages === 0) {
          $("li:last", an[i]).addClass("disabled");
        } else {
          $("li:last", an[i]).removeClass("disabled");
        }
        i++;
      }
    }
  }
});

/*
 * TableTools Bootstrap compatibility
 * Required TableTools 2.1+
*/


if ($.fn.DataTable.TableTools) {
  /* Set the classes that TableTools uses to something suitable for Bootstrap
  */

  $.extend(true, $.fn.DataTable.TableTools.classes, {
    container: "DTTT btn-group",
    buttons: {
      normal: "btn",
      disabled: "disabled"
    },
    collection: {
      container: "DTTT_dropdown dropdown-menu",
      buttons: {
        normal: "",
        disabled: "disabled"
      }
    },
    print: {
      info: "DTTT_print_info modal"
    },
    select: {
      row: "active"
    }
  });
  /* Have the collection use a bootstrap compatible dropdown
  */

  $.extend(true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
    collection: {
      container: "ul",
      button: "li",
      liner: "a"
    }
  });
}

/* Table initialisation
*/


fnFilterGlobal = function() {
  $("#editable").dataTable().fnFilter($("#global_filter").val(), null, $("#global_regex")[0].checked, $("#global_smart")[0].checked);
};

/* Get the rows which are currently selected
*/


fnGetSelected = function(oTableLocal) {
  return oTableLocal.$("tr.info");
};

/*
*/


restoreRow = function(oTable, nRow) {
  var aData, i, iLen, jqTds;
  aData = oTable.fnGetData(nRow);
  jqTds = $(">td", nRow);
  i = 0;
  iLen = jqTds.length;
  while (i < iLen) {
    oTable.fnUpdate(aData[i], nRow, i, false);
    i++;
  }
  oTable.fnDraw();
};

editRow = function(oTable, nRow) {
  var aData, jqTds;
  aData = oTable.fnGetData(nRow);
  jqTds = $(">td", nRow);
  jqTds[0].innerHTML = "<input type=\"text\" value=\"" + aData[0] + "\" class=\"span12\">";
  jqTds[1].innerHTML = "<input type=\"text\" value=\"" + aData[1] + "\" class=\"span12\">";
  jqTds[2].innerHTML = "<input type=\"text\" value=\"" + aData[2] + "\" class=\"span12\">";
  jqTds[3].innerHTML = "<input type=\"text\" value=\"" + aData[3] + "\" class=\"span12\">";
  jqTds[4].innerHTML = "<input type=\"text\" value=\"" + aData[4] + "\" class=\"span12\">";
  jqTds[5].innerHTML = "<a class=\"edit\" href=\"\">Save</a>";
};

saveRow = function(oTable, nRow) {
  var jqInputs;
  jqInputs = $("input", nRow);
  oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
  oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
  oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
  oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
  oTable.fnUpdate(jqInputs[4].value, nRow, 4, false);
  oTable.fnUpdate("<a class=\"edit\" href=\"\">Edit</a>", nRow, 5, false);
  oTable.fnDraw();
};

/*
*/


$(document).ready(function() {
  var nEditing, oTable;
  oTable = void 0;
  nEditing = null;
  /*
  */

  /* Init the table
  */

  oTable = $("#editable").dataTable({
    sDom: "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
    sPaginationType: "bootstrap",
    oLanguage: {
      sLengthMenu: "_MENU_ records per page"
    },
    aaSorting: [[1, "asc"]],
    aoColumnDefs: [
      {
        bSortable: false,
        aTargets: [0]
      }
    ]
  });
  $("#add-row").click(function(e) {
    var aiNew, nRow;
    e.preventDefault();
    aiNew = oTable.fnAddData(["", "", "", "", "", "", "<a class=\"edit\" href=\"\">Edit</a>", "<a class=\"delete\" href=\"\">Delete</a>"]);
    nRow = oTable.fnGetNodes(aiNew[0]);
    editRow(oTable, nRow);
    nEditing = nRow;
  });
  $("#editable").on("click", "a.delete", function(e) {
    var nRow;
    e.preventDefault();
    nRow = $(this).parents("tr")[0];
    oTable.fnDeleteRow(nRow);
  });
  $("#editable").on("click", "a.edit", function(e) {
    var nRow;
    e.preventDefault();
    nRow = $(this).parents("tr")[0];
    if (nEditing !== null && nEditing !== nRow) {
      restoreRow(oTable, nEditing);
      editRow(oTable, nRow);
      nEditing = nRow;
    } else if (nEditing === nRow && this.innerHTML === "Save") {
      saveRow(oTable, nEditing);
      nEditing = null;
    } else {
      editRow(oTable, nRow);
      nEditing = nRow;
    }
  });
  $("#global_filter").keyup(fnFilterGlobal);
  $("#global_regex").click(fnFilterGlobal);
  $("#global_smart").click(fnFilterGlobal);
});
