<?php


/*
 * This Page controls Faculty Awards Screen.
 */

/*
 * Session & Error control Initialization.
 */
session_start();
if(!$_SESSION['isLogged']) {
    header("location:login.php");
    die();
}
$error = array();
$errorflag =0;
$BackToDashboard = true;

/*
 * Connection to DataBase.
 */
require_once ("../Resources/Includes/connect.php");

/*
 * Local & Session variable Initialization
 */
$bpid = $_SESSION['bpid'];
$contentlink_id = $_GET['linkid'];
$bpayname =$_SESSION['bpayname'];
$ouid = $_SESSION['login_ouid'];
$date = date("Y-m-d");
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_userid'];

if ($ouid == 4) {
    $ouabbrev = $_SESSION['bpouabbrev'];
} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}

/*
 * SQL check Status of Blueprint Content for Edit restrictions
 */
$sqlbpstatus = "SELECT CONTENT_STATUS FROM BpContents WHERE ID_CONTENT = '$contentlink_id';";
$resultbpstatus = $mysqli->query($sqlbpstatus);
$rowsbpstatus = $resultbpstatus->fetch_assoc();

/*
 * faculty Award Grid ; conditional for provost & other users
 */
if ($ouid == 4) {
    $sqlbroad = "select BROADCAST_AY,OU_NAME,BROADCAST_STATUS,LastModified from broadcast inner join Hierarchy on broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY where BROADCAST_AY='$bpayname' and Hierarchy.OU_ABBREV ='$ouabbrev';";
} else{
    $sqlbroad = "select BROADCAST_AY,OU_NAME, BROADCAST_STATUS_OTHERS,LastModified from broadcast inner join Hierarchy on broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY where BROADCAST_AY='$bpayname' and BROADCAST_OU ='$ouid'; ";
}
$resultbroad = $mysqli->query($sqlbroad);
$rowbroad = $resultbroad->fetch_array(MYSQLI_NUM);



/*
 * Add UNIT GOAL Modal
 */

//if(isset($_POST['goal_submit'])) {
//    $contentlink_id = $_GET['linkid'];
//    $goaltitle = $_POST['goaltitle'];
//
//    $unigoallink = $_POST['goallink'];
//    foreach ($unigoallink as $value) {
//        $unigoallinkname .= $value . ",";
//    }
//    $goalstatement = mynl2br($_POST['goalstatement']);
//    $goalalignment = mynl2br($_POST['goalalignment']);
//
//
//    $sqlcreatebp .= "INSERT INTO `BP_UnitGoals` ( OU_ABBREV, GOAL_AUTHOR, MOD_TIMESTAMP, UNIT_GOAL_AY, UNIT_GOAL_TITLE, LINK_UNIV_GOAL, GOAL_STATEMENT, GOAL_ALIGNMENT) VALUES ('$ouabbrev','$author','$time','$bpayname','$goaltitle','$unigoallinkname','$goalstatement','$goalalignment');";
//
//    $sqlcreatebp .= "Update  `BpContents` set CONTENT_STATUS = 'In Progress', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";
//
//    $sqlcreatebp .= "Update  `broadcast` set BROADCAST_STATUS = 'In Progress',BROADCAST_STATUS_OTHERS = 'In Progress',  AUTHOR= '$author',LastModified ='$time' where ID_BROADCAST = '$bpid'; ";
//
//    if($mysqli->multi_query($sqlcreatebp)) {
//
//        $error[0] = "Unit goals added Succesfully.";
//
//    } else {
//        $error[0] = "Unit goals could not be added.";
//    }
//
//
//}

//if(isset($_POST['submit_approve'])) {
//
//    $contentlink_id = $_GET['linkid'];
//
//    $sqlcreatebp .= "Update  `BpContents` set CONTENT_STATUS = 'Pending Dean Approval', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";
//
//    if ($mysqli->query($sqlcreatebp)) {
//
//        $error[0] = "Unit goals submitted Successfully";
//
//    } else {
//        $error[0] = "Unit goals Could not be submitted. Please Retry.";
//    }
//
//
//}
//
//if(isset($_POST['approve'])) {
//
//    $contentlink_id = $_GET['linkid'];
//    $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Approved', BP_AUTHOR= '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";
//    if ($mysqli->query($sqlmission)) {
//        $error[0] = "Unit Goals Approved Successfully";
//    } else {
//        $error[0] = "Unit Goals Could not be Approved. Please Retry.";
//    }
//}
//
//if(isset($_POST['reject'])) {
//
//    $contentlink_id = $_GET['linkid'];
//    $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Rejected', BP_AUTHOR= '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";
//    if ($mysqli->query($sqlmission)) {
//        $error[0] = "Unit Goals Rejected Successfully";
//    } else {
//        $error[0] = "Unit Goals Could not be Rejected. Please Retry.";
//    }
//}


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
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>

<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>

<!--<div class="overlay hidden"></div>-->
<?php //if (isset($_POST['goal_submit'])) { ?>
<!--    <div class="alert">-->
<!--        <a href="#" class="close end"><span class="icon">9</span></a>-->
<!--        <h1 class="title"></h1>-->
<!--        <p class="description">--><?php //foreach ($error as $value) echo $value; ?><!--</p>-->
<!--        <button type="button" redirect="--><?php //echo "unitgoaloverview.php?linkid=".$contentlink_id ?><!--" class="end btn-primary">Close</button>-->
<!--    </div>-->
<?php //} ?>

<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Blueprint Home</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 id="ayname" class="box-title"><?php echo $rowbroad[0]; ?></h1>
        <p class="status"><span>Org Unit Name:</span> <?php echo $rowbroad[1]; ?></p>
        <p id="ouabbrev" class="hidden"><?php echo $ouabbrev;?></p>
        <p class="status"><span>Status:</span> <?php echo $rowbroad[2]; ?></p>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <!--                        Reviewer Edit Control-->
        <?php if ($_SESSION['login_right'] != 1): ?>
            <div>
<!--                <button id="add-mission" type="button" class="btn-secondary  col-lg-3 col-md-7 col-sm-8 pull-right"-->
<!--                        data-toggle="modal"-->
<!--                        data-target="#addawardModal"><span class="icon">&#xe035;</span> Add New Goal-->
<!--                </button>-->
                <a id="add-mission" href="<?php echo "unitgoal_detail.php?linkid=".$contentlink_id."&goal_id=0"?>" class="btn-secondary  col-lg-3 col-md-7 col-sm-8 pull-right"><span class="icon">&#xe035;</span> Add New Goal
                </a>

            </div>
        <?php endif; ?>
        <h1 class="box-title">Unit Goals</h1>


        <p>Below is a summary of your Unit Goals.</p>
<!--        <div id="taskboard" style="margin-top: 10px;">-->
            <!--<table class="grid" action="taskboard/unitgoalajax.php" title="Unit Goals">
                <tr>
                    <th col="UNIT_GOAL_TITLE" width="200" href="<?php echo "unitgoal_detail.php?linkid=".$contentlink_id."&goal_id="?>{{columns.ID_UNIT_GOAL}}" type="text">Goal Title</th>
                    <th col="STATUS" width="150" type="text">Status</th>
                    <th col="MOD_TIMESTAMP" width="150" type="text">Last Edited On</th>
                    <th col="AUTHOR" width="150" type="text">Last Modified By</th>

                </tr>
            </table>-->
            <p class="status"><em><small>To change the order in which the goals are displayed, clicking and hold the goal you wish to move, and drag it up or down, releasing in the appropriate location.  The item will move as intended.  To update the number of the goal accordingly, please Refresh the page.</small></em></p>
            <h3 style="padding: 5px;">Looking Back</h3>
            <div id="jsGridBack"></div>
            <h3 style="padding: 5px;">Real Time</h3>
            <div id="jsGridReal"></div>
            <h3 style="padding: 5px;">Looking Ahead</h3>
            <div id="jsGridAhead"></div>
            <script>

              var status;

              $.extend({
                getUrlVars: function(){
                  var vars = [], hash;
                  var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                  for(var i = 0; i < hashes.length; i++)
                  {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                  }
                  return vars;
                },
                getUrlVar: function(name){
                  return $.getUrlVars()[name];
                }
              });

              $.post("../Resources/Includes/data.php?functionNum=5", function(peopleArray) {
                if (peopleArray != ""){
                  peopleArray = $.parseJSON(peopleArray);
                }

                $.post("../Resources/Includes/data.php?functionNum=1&viewpoint=back", function(data) {
                  data = $.parseJSON(data);
                  $("#jsGridBack").jsGrid({
                    width: "100%",
                    height: "300px",
                    sorting: true,
                    paging: true,
                    data: data,
                    rowClass: function(item, itemIndex) {
                      return "client-" + itemIndex;
                    },
                    controller: {
                      loadData: function() {
                        return db.clients.slice(0, 15);
                      }
                    },
                    fields: [
                      { name: "ID_SORT", title: "#", type: "text", width: "20px" },
                      { name: "UNIT_GOAL_TITLE", title: "Goal Title", itemTemplate: function(value,item){
                        return $("<a>").attr("href", "../Pages/unitgoal_detail.php?goal_id="+item.ID_UNIT_GOAL+"&linkid="+$.getUrlVar("linkid")).text(value);
                      }, width: "auto" },
                      { name: "GOAL_STATUS", title: "Status", type: "text", width: "auto" },
                      { name: "MOD_TIMESTAMP", title: "Last Updated", type: "text", width: "auto" },
                      { name: "AUTHOR", title: "Author", itemTemplate: function(value,item){
                        var status;
                        for (var i = 0; i < peopleArray.length; i++){
                          if (peopleArray[i][0] == item.GOAL_AUTHOR){
                            person = peopleArray[i][11] + ", " +  peopleArray[i][10];
                          }
                        }
                        return person;
                      }, width: "auto" }
                    ],
                    onRefreshed: function() {
                      var $gridData = $("#jsGridBack .jsgrid-grid-body tbody");
                      $gridData.sortable({
                        update: function(e, ui) {
                          var clientIndexRegExp = /\s*client-(\d+)\s*/;
                          var indexes = $.map($gridData.sortable("toArray", { attribute: "class" }), function(classes) {
                              return clientIndexRegExp.exec(classes)[1];
                          });
                          var items = $.map($gridData.find("tr"), function(row) {
                              return $(row).data("JSGridItem");
                          });
                          $.post("../Resources/Includes/data.php?functionNum=2",{'data':items,'indexes':indexes},function(){

                          })
                        }
                      });
                    }
                  });
                });
              });

              $.post("../Resources/Includes/data.php?functionNum=5", function(peopleArray) {
                if (peopleArray != ""){
                  peopleArray = $.parseJSON(peopleArray);
                }

                $.post("../Resources/Includes/data.php?functionNum=1&viewpoint=real", function(data) {
                  data = $.parseJSON(data);
                  $("#jsGridReal").jsGrid({
                    width: "100%",
                    height: "300px",
                    sorting: true,
                    paging: true,
                    data: data,
                    rowClass: function(item, itemIndex) {
                      return "client-" + itemIndex;
                    },
                    controller: {
                      loadData: function() {
                        return db.clients.slice(0, 15);
                      }
                    },
                    fields: [
                      { name: "ID_SORT", title: "#", type: "text", width: "20px" },
                      { name: "UNIT_GOAL_TITLE", title: "Goal Title", itemTemplate: function(value,item){
                        return $("<a>").attr("href", "../Pages/unitgoal_detail.php?goal_id="+item.ID_UNIT_GOAL+"&linkid="+$.getUrlVar("linkid")).text(value);
                      }, width: "auto" },
                      { name: "GOAL_STATUS", title: "Status", type: "text", width: "auto" },
                      { name: "MOD_TIMESTAMP", title: "Last Updated", type: "text", width: "auto" },
                      { name: "AUTHOR", title: "Author", itemTemplate: function(value,item){
                        var status;
                        for (var i = 0; i < peopleArray.length; i++){
                          if (peopleArray[i][0] == item.GOAL_AUTHOR){
                            person = peopleArray[i][11] + ", " +  peopleArray[i][10];
                          }
                        }
                        return person;
                      }, width: "auto" }
                    ],
                    onRefreshed: function() {
                      var $gridData = $("#jsGridReal .jsgrid-grid-body tbody");
                      $gridData.sortable({
                        update: function(e, ui) {
                          var clientIndexRegExp = /\s*client-(\d+)\s*/;
                          var indexes = $.map($gridData.sortable("toArray", { attribute: "class" }), function(classes) {
                              return clientIndexRegExp.exec(classes)[1];
                          });
                          var items = $.map($gridData.find("tr"), function(row) {
                              return $(row).data("JSGridItem");
                          });
                          $.post("../Resources/Includes/data.php?functionNum=2",{'data':items,'indexes':indexes},function(){

                          })
                        }
                      });
                    }
                  });
                });
              });

              $.post("../Resources/Includes/data.php?functionNum=5", function(peopleArray) {
                if (peopleArray != ""){
                  peopleArray = $.parseJSON(peopleArray);
                }

                $.post("../Resources/Includes/data.php?functionNum=1&viewpoint=ahead", function(data) {
                  data = $.parseJSON(data);
                  $("#jsGridAhead").jsGrid({
                    width: "100%",
                    height: "300px",
                    sorting: true,
                    paging: true,
                    data: data,
                    rowClass: function(item, itemIndex) {
                      return "client-" + itemIndex;
                    },
                    controller: {
                      loadData: function() {
                        return db.clients.slice(0, 15);
                      }
                    },
                    fields: [
                      { name: "ID_SORT", title: "#", type: "text", width: "20px" },
                      { name: "UNIT_GOAL_TITLE", title: "Goal Title", itemTemplate: function(value,item){
                        return $("<a>").attr("href", "../Pages/unitgoal_detail.php?goal_id="+item.ID_UNIT_GOAL+"&linkid="+$.getUrlVar("linkid")).text(value);
                      }, width: "auto" },
                        { name: "GOAL_STATUS", title: "Status", type: "text", width: "auto" },
                      { name: "MOD_TIMESTAMP", title: "Last Updated", type: "text", width: "auto" },
                      { name: "AUTHOR", title: "Author", itemTemplate: function(value,item){
                        var status;
                        for (var i = 0; i < peopleArray.length; i++){
                          if (peopleArray[i][0] == item.GOAL_AUTHOR){
                            person = peopleArray[i][11] + ", " +  peopleArray[i][10];
                          }
                        }
                        return person;
                      }, width: "auto" }
                    ],
                    onRefreshed: function() {
                      var $gridData = $("#jsGridAhead .jsgrid-grid-body tbody");
                      $gridData.sortable({
                        update: function(e, ui) {
                          var clientIndexRegExp = /\s*client-(\d+)\s*/;
                          var indexes = $.map($gridData.sortable("toArray", { attribute: "class" }), function(classes) {
                              return clientIndexRegExp.exec(classes)[1];
                          });
                          var items = $.map($gridData.find("tr"), function(row) {
                              return $(row).data("JSGridItem");
                          });
                          $.post("../Resources/Includes/data.php?functionNum=2",{'data':items,'indexes':indexes},function(){

                          })
                        }
                      });
                    }
                  });
                });
              });

            </script>
<!--        </div>-->

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
