$(function() {

    var $grid = $(".taskboard").grid({

        title : "",
        page : 1,
        showPager : true,
        editing : false,
        deleting :false,
        nRowsShowing : 20,
        width: 900,
        rowNumbers: true,
        checkboxes: false,
        cellTypes : {
            "hashBang": function(value, columnOpts, grid) {
                console.log(value, columnOpts, grid);
                return {
                    cellClass: "",
                    cellValue: "/#!/"+value
                }
            }
        }
    }).on("loadComplete",function(e, grid) {
        console.log("loadComplete", grid);
    }).on("cellClick",function(e, $cell,rowData) {
        //console.log("cell",$cell,rowData);
    }).on("rowCheck",function(e, $checkbox, rowData) {
        //console.log("rowCheck",$checkbox, rowData);
    }).on("rowClick",function(e, $rows,rowData) {
        //console.log("rowClick",$rows,rowData);
    }).on("save",function(e, row, res) {
        //console.log("save",row,res);
    });

    var $grid = $(".grid").grid({

        title : "",
        page : 1,
        showPager : false,
        editing : false,
        deleting :false,
        nRowsShowing : 20,
        width: 900,
        rowNumbers: true,
        checkboxes: false,
        cellTypes : {
            "hashBang": function(value, columnOpts, grid) {
                console.log(value, columnOpts, grid);
                return {
                    cellClass: "",
                    cellValue: "/#!/"+value
                }
            }
        }
    }).on("loadComplete",function(e, grid) {
        //console.log("loadComplete", grid);
    }).on("cellClick",function(e, $cell,rowData) {
        //console.log("cell",$cell,rowData);

    }).on("rowCheck",function(e, $checkbox, rowData) {
        //console.log("rowCheck",$checkbox, rowData);
    }).on("rowClick",function(e, $rows,rowData) {
        console.log("rowClick",$rows,rowData);
        var id =$rows.attr('data-row');

        // $($rows).css("background","black").filter('.grid-row-'+id).css("background","rgba(0,0,0,0.05)");

       $(".grid-row-"+id).toggleClass('row-hover','row-hoverOut');


    }).on("save",function(e, row, res) {
        //console.log("save",row,res);
    });

});