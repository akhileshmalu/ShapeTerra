<?php


/*
 * This Page controls Faculty Awards Screen.
 */

/*
 * Session & Error control Initialization.
 */
require_once("../Resources/Includes/BpContents.php");
$goaloutcomehome = new BPCONTENTS();
$goaloutcomehome->checkSessionStatus();

$message = array();
$errorflag = 0;
$BackToDashboard = true;

/*
 * Local & Session variable Initialization
 */
$contentlink_id = $_GET['linkid'];
$bpayname = $_SESSION['bpayname'];
$ouid = $_SESSION['login_ouid'];


if ($ouid == 4) {
    $ouabbrev = $_SESSION['bpouabbrev'];
} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}

$date = date("Y-m-d");
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_userid'];


// Blueprint Status information on title box
$resultbroad = $goaloutcomehome->BlueprintStatusDisplay();
$rowbroad = $resultbroad->fetch(PDO::FETCH_BOTH);


// SQL check Status of Blueprint Content for Edit restrictions
$resultbpstatus = $goaloutcomehome->GetStatus();
$rowsbpstatus = $resultbpstatus->fetch(2);


require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<!--<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrapTable.css"/>-->
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.min.css"/>
<link rel="stylesheet" href="Css/grid.css" title="openJsGrid"/>
<script src="../Resources/Library/js/root.js"></script>
<script src="../Resources/Library/js/grid.js"></script>

<!--Temp-->
<script
    src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js"
    integrity="sha256-oTyWrNiP6Qftu4vs2g0RPCKr3g1a6QTlITNgoebxRc4="
    crossorigin="anonymous"></script>
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css"/>
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>

<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>

<div class="overlay hidden"></div>
<?php if (isset($_POST['submit'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Blueprint Home</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title"><?php echo $rowbroad[0]; ?></h1>
        <p class="status"><span>Org Unit Name:</span> <?php echo $rowbroad[1]; ?></p>
        <p class="status"><span>Status:</span> <?php echo $rowbroad[2]; ?></p>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Goal Outcomes</h1>
        <p>Below are listed all goals for your unit.  Click any item by its Goal Title in order to edit or compose
            outcomes.  The response options provided for each goal are determined by the Goal Viewpoint you selected
            when the goal was entered in the system.</p>
        <div id="taskboard" style="margin-top: 10px;">
            <!--            <table class="grid" action="taskboard/goaloutcomeajax.php" title="Unit Goals">-->
            <!--                <tr>-->
            <!--                    <th col="UNIT_GOAL_TITLE" href="-->
            <?php //echo "../Pages/goaloutcome.php?goal_id={{columns.ID_UNIT_GOAL}}&linkid=".$contentlink_id ?><!--" width="300" type="text">Goal Title</th>-->
            <!--                    <th col="GOAL_REPORT_STATUS" width="150" type="text">Report Status</th>-->
            <!--                    <th col="MOD_TIMESTAMP" width="150" type="text">Last Edited On</th>-->
            <!--                    <th col="AUTHOR" width="150" type="text">Last Modified By</th>-->
            <!--                </tr>-->
            <!--            </table>-->
            <h3 style="padding: 5px;">Looking Back</h3>
            <div id="jsGridBack"></div>
            <h3 style="padding: 5px;">Real Time</h3>
            <div id="jsGridReal"></div>
            <h3 style="padding: 5px;">Looking Ahead</h3>
            <div id="jsGridAhead"></div>
            <script>

                var status;

                $.extend({
                    getUrlVars: function () {
                        var vars = [], hash;
                        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                        for (var i = 0; i < hashes.length; i++) {
                            hash = hashes[i].split('=');
                            vars.push(hash[0]);
                            vars[hash[0]] = hash[1];
                        }
                        return vars;
                    },
                    getUrlVar: function (name) {
                        return $.getUrlVars()[name];
                    }
                });

                $.post("../Resources/Includes/data.php?functionNum=5", function (peopleArray) {
                    if (peopleArray != "") {
                        peopleArray = $.parseJSON(peopleArray);
                    }
                    $.post("../Resources/Includes/data.php?functionNum=3", function (statusArray) {
                        if (statusArray != "") {
                            statusArray = $.parseJSON(statusArray);
                        }

                        $.post("../Resources/Includes/data.php?functionNum=1&viewpoint=back", function (data) {
                            data = $.parseJSON(data);
                            $("#jsGridBack").jsGrid({
                                width: "100%",
                                height: "300px",
                                sorting: false,
                                paging: true,
                                data: data,
                                rowClass: function (item, itemIndex) {
                                    return "client-" + itemIndex;
                                },
                                controller: {
                                    loadData: function () {
                                        return db.clients.slice(0, 15);
                                    }

                                },
                                fields: [
                                    {name: "ID_SORT", title: "#", type: "text", width: "20px"},
                                    {
                                        name: "UNIT_GOAL_TITLE",
                                        title: "Goal Title",
                                        itemTemplate: function (value, item) {
                                            return $("<a>").attr("href", "../Pages/goaloutcome.php?goal_id=" + item.ID_UNIT_GOAL + "&linkid=" + $.getUrlVar("linkid")).text(value);
                                        },
                                        width: "auto"
                                    },
                                    {
                                        title: "Goal Reporting Status", itemTemplate: function (value, item) {
                                        var status;
                                        for (var i = 0; i < statusArray.length; i++) {
                                            if (statusArray[i][0] == item.ID_UNIT_GOAL) {
                                                status = statusArray[i][3];
                                            }

                                        }
                                        return status;
                                    }, width: "auto"
                                    },
                                    {
                                        name: "MOD_TIMESTAMP",
                                        title: "Last Updated",
                                        itemTemplate: function (value, item) {
                                            var timestamp;
                                            for (var i = 0; i < statusArray.length; i++) {
                                                if (statusArray[i][0] == item.ID_UNIT_GOAL) {
                                                    timestamp = statusArray[i][2];
                                                }
                                            }
                                            return timestamp;
                                        },
                                        width: "auto"
                                    },
                                    {
                                        name: "AUTHOR", title: "Author", itemTemplate: function (value, item) {
                                        var status;
                                        for (var i = 0; i < peopleArray.length; i++) {
                                            if (peopleArray[i][0] == item.GOAL_AUTHOR) {
                                                person = peopleArray[i][11] + ", " + peopleArray[i][10];
                                            }
                                        }
                                        return person;
                                    }, width: "auto"
                                    }
                                ],
                                onRefreshed: function () {
                                    var $gridData = $("#jsGridBack .jsgrid-grid-body tbody");
                                    $gridData.sortable({
                                        update: function (e, ui) {
                                            var clientIndexRegExp = /\s*client-(\d+)\s*/;
                                            var indexes = $.map($gridData.sortable("toArray", {attribute: "class"}), function (classes) {
                                                return clientIndexRegExp.exec(classes)[1];
                                            });
                                            var items = $.map($gridData.find("tr"), function (row) {
                                                return $(row).data("JSGridItem");
                                            });
                                            $.post("../Resources/Includes/data.php?functionNum=2", {
                                                'data': items,
                                                'indexes': indexes
                                            }, function () {

                                            })
                                        }
                                    });
                                }
                            });
                        });
                    });
                });

                $.post("../Resources/Includes/data.php?functionNum=5", function (peopleArray) {
                    if (peopleArray != "") {
                        peopleArray = $.parseJSON(peopleArray);
                    }
                    $.post("../Resources/Includes/data.php?functionNum=3", function (statusArray) {
                        if (statusArray != "") {
                            statusArray = $.parseJSON(statusArray);
                        }

                        $.post("../Resources/Includes/data.php?functionNum=1&viewpoint=real", function (data) {
                            data = $.parseJSON(data);
                            $("#jsGridReal").jsGrid({
                                width: "100%",
                                height: "300px",
                                sorting: false,
                                paging: true,
                                data: data,
                                rowClass: function (item, itemIndex) {
                                    return "client-" + itemIndex;
                                },
                                controller: {
                                    loadData: function () {
                                        return db.clients.slice(0, 15);
                                    }

                                },
                                fields: [
                                    {name: "ID_SORT", title: "#", type: "text", width: "20px"},
                                    {
                                        name: "UNIT_GOAL_TITLE",
                                        title: "Goal Title",
                                        itemTemplate: function (value, item) {
                                            return $("<a>").attr("href", "../Pages/goaloutcome.php?goal_id=" + item.ID_UNIT_GOAL + "&linkid=" + $.getUrlVar("linkid")).text(value);
                                        },
                                        width: "auto"
                                    },
                                    {
                                        title: "Goal Reporting Status", itemTemplate: function (value, item) {
                                        var status;
                                        for (var i = 0; i < statusArray.length; i++) {
                                            if (statusArray[i][0] == item.ID_UNIT_GOAL) {
                                                status = statusArray[i][3];
                                            }

                                        }
                                        return status;
                                    }, width: "auto"
                                    },
                                    {
                                        name: "MOD_TIMESTAMP",
                                        title: "Last Updated",
                                        itemTemplate: function (value, item) {
                                            var timestamp;
                                            for (var i = 0; i < statusArray.length; i++) {
                                                if (statusArray[i][0] == item.ID_UNIT_GOAL) {
                                                    timestamp = statusArray[i][2];
                                                }
                                            }
                                            return timestamp;
                                        },
                                        width: "auto"
                                    },
                                    {
                                        name: "AUTHOR", title: "Author", itemTemplate: function (value, item) {
                                        var status;
                                        for (var i = 0; i < peopleArray.length; i++) {
                                            if (peopleArray[i][0] == item.GOAL_AUTHOR) {
                                                person = peopleArray[i][11] + ", " + peopleArray[i][10];
                                            }
                                        }
                                        return person;
                                    }, width: "auto"
                                    }
                                ],
                                onRefreshed: function () {
                                    var $gridData = $("#jsGridReal .jsgrid-grid-body tbody");
                                    $gridData.sortable({
                                        update: function (e, ui) {
                                            var clientIndexRegExp = /\s*client-(\d+)\s*/;
                                            var indexes = $.map($gridData.sortable("toArray", {attribute: "class"}), function (classes) {
                                                return clientIndexRegExp.exec(classes)[1];
                                            });
                                            var items = $.map($gridData.find("tr"), function (row) {
                                                return $(row).data("JSGridItem");
                                            });
                                            $.post("../Resources/Includes/data.php?functionNum=2", {
                                                'data': items,
                                                'indexes': indexes
                                            }, function () {

                                            })
                                        }
                                    });
                                }
                            });
                        });
                    });
                });

                $.post("../Resources/Includes/data.php?functionNum=5", function (peopleArray) {
                    if (peopleArray != "") {
                        peopleArray = $.parseJSON(peopleArray);
                    }
                    $.post("../Resources/Includes/data.php?functionNum=3", function (statusArray) {
                        if (statusArray != "") {
                            statusArray = $.parseJSON(statusArray);
                        }

                        $.post("../Resources/Includes/data.php?functionNum=1&viewpoint=ahead", function (data) {
                            data = $.parseJSON(data);
                            $("#jsGridAhead").jsGrid({
                                width: "100%",
                                height: "300px",
                                sorting: false,
                                paging: true,
                                data: data,
                                rowClass: function (item, itemIndex) {
                                    return "client-" + itemIndex;
                                },
                                controller: {
                                    loadData: function () {
                                        return db.clients.slice(0, 15);
                                    }

                                },
                                fields: [
                                    {name: "ID_SORT", title: "#", type: "text", width: "20px"},
                                    {
                                        name: "UNIT_GOAL_TITLE",
                                        title: "Goal Title",
                                        itemTemplate: function (value, item) {
                                            return $("<a>").attr("href", "../Pages/goaloutcome.php?goal_id=" + item.ID_UNIT_GOAL + "&linkid=" + $.getUrlVar("linkid")).text(value);
                                        },
                                        width: "auto"
                                    },
                                    {
                                        title: "Goal Reporting Status", itemTemplate: function (value, item) {
                                        var status;
                                        for (var i = 0; i < statusArray.length; i++) {
                                            if (statusArray[i][0] == item.ID_UNIT_GOAL) {
                                                status = statusArray[i][3];
                                            }

                                        }
                                        return status;
                                    }, width: "auto"
                                    },
                                    {
                                        name: "MOD_TIMESTAMP",
                                        title: "Last Updated",
                                        itemTemplate: function (value, item) {
                                            var timestamp;
                                            for (var i = 0; i < statusArray.length; i++) {
                                                if (statusArray[i][0] == item.ID_UNIT_GOAL) {
                                                    timestamp = statusArray[i][2];
                                                }
                                            }
                                            return timestamp;
                                        },
                                        width: "auto"
                                    },
                                    {
                                        name: "AUTHOR", title: "Author", itemTemplate: function (value, item) {
                                        var status;
                                        for (var i = 0; i < peopleArray.length; i++) {
                                            if (peopleArray[i][0] == item.GOAL_AUTHOR) {
                                                person = peopleArray[i][11] + ", " + peopleArray[i][10];
                                            }
                                        }
                                        return person;
                                    }, width: "auto"
                                    }
                                ],
                                onRefreshed: function () {
                                    var $gridData = $("#jsGridAhead .jsgrid-grid-body tbody");
                                    $gridData.sortable({
                                        update: function (e, ui) {
                                            var clientIndexRegExp = /\s*client-(\d+)\s*/;
                                            var indexes = $.map($gridData.sortable("toArray", {attribute: "class"}), function (classes) {
                                                return clientIndexRegExp.exec(classes)[1];
                                            });
                                            var items = $.map($gridData.find("tr"), function (row) {
                                                return $(row).data("JSGridItem");
                                            });
                                            $.post("../Resources/Includes/data.php?functionNum=2", {
                                                'data': items,
                                                'indexes': indexes
                                            }, function () {

                                            })
                                        }
                                    });
                                }
                            });
                        });
                    });
                });
            </script>
        </div>
    </div>
</div>


<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>

<!--Calender Bootstrap inclusion for date picker INPUT-->
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
<script src="../Resources/Library/js/tabAlert.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script src="../Resources/Library/js/taskboard.js"></script>
