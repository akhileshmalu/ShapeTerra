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
$contentlink_id = $_GET['linkid'];
$bpayname =$_SESSION['bpayname'];
$ouid = $_SESSION['login_ouid'];


if ($ouid == 4) {
    $ouabbrev = $_SESSION['bpouabbrev'];
} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}

$date = date("Y-m-d");
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_userid'];

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
 * SQL check Status of Blueprint Content for Edit restrictions
 */
$sqlbpstatus = "SELECT CONTENT_STATUS FROM BpContents WHERE ID_CONTENT = '$contentlink_id';";
$resultbpstatus = $mysqli->query($sqlbpstatus);
$rowsbpstatus = $resultbpstatus->fetch_assoc();


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

<div class="overlay hidden"></div>
<?php if (isset($_POST['submit'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
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
        <h1 class="box-title">Goal Management &amp; Outcomes</h1>
        <p>Below are listed all goals for your unit.  Click any item by its Goal Title in order to edit or compose outcomes.  The response options provided for each goal are determined by the Goal Viewpoint you selected when the goal was entered in the system.</p>
        <div id="taskboard" style="margin-top: 10px;">
            <!--<table class="grid" action="taskboard/goaloutcomeajax.php" title="Unit Goals">
                <tr>
                    <th col="UNIT_GOAL_TITLE" href="<?php echo "../Pages/goaloutcome.php?goal_id={{columns.ID_UNIT_GOAL}}&linkid=".$contentlink_id ?>" width="300" type="text">Goal Title</th>
                    <th col="GOAL_REPORT_STATUS" width="150" type="text">Report Status</th>
                    <th col="MOD_TIMESTAMP" width="150" type="text">Last Edited On</th>
                    <th col="AUTHOR" width="150" type="text">Last Modified By</th>
                </tr>
            </table>-->
            <div id="jsGrid"></div>
            <div id="table-status"></div>
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

              $.post("../Resources/Includes/data.php?functionNum=3", function(statusArray) {
                if (statusArray != ""){
                  statusArray = $.parseJSON(statusArray);
                }

                $.post("../Resources/Includes/data.php?functionNum=1", function(data) {
                  data = $.parseJSON(data);
                  $("#jsGrid").jsGrid({
                    width: "100%",
                    height: "400px",
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
                        return $("<a>").attr("href", "../Pages/goaloutcome.php?goal_id="+item.ID_UNIT_GOAL+"&linkid="+$.getUrlVar("linkid")).text(value);
                      }, width: "auto" },
                      { title: "Goal Status", itemTemplate: function(value,item){
                        var status;
                        for (var i = 0; i < statusArray.length; i++){
                          if (statusArray[i][0] == item.ID_UNIT_GOAL){
                            status = statusArray[i][3];
                          }
                        }
                        return status;
                      }, width: "auto"},
                      { name: "GOAL_STATEMENT",  title: "Goal", type: "text", width: "auto"},
                      { name: "MOD_TIMESTAMP", title: "Last Updated", itemTemplate: function(value,item){
                        var timestamp;
                        for (var i = 0; i < statusArray.length; i++){
                          if (statusArray[i][0] == item.ID_UNIT_GOAL){
                            timestamp = statusArray[i][2];
                          }
                        }
                        return timestamp;
                      }, width: "auto" }
                    ],
                    onRefreshed: function() {
                      var $gridData = $("#jsGrid .jsgrid-grid-body tbody");
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
                            console.log(indexes);
                            $("#table-status").html("Table Saved.");
                          })
                        }
                      });
                    }
                  });
                });
              });

            </script>
        </div>

        <form action="<?php echo $_SERVER['PHP_SELF']."?linkid=".$contentlink_id ?>" method="POST" >

            <!--                        Edit Control-->
            <?php if (($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead' ) AND ($rowsbpstatus['CONTENT_STATUS']=='In Progress' OR $rowsbpstatus['CONTENT_STATUS']=='Dean Rejected' OR $rowsbpstatus['CONTENT_STATUS']=='Not Started') ) { ?>

                <input type="submit" id="approve" name="submit_approve" value="Submit For Approval" class="btn-primary pull-right" >

            <?php } elseif ($_SESSION['login_role'] == 'dean' OR $_SESSION['login_role'] == 'designee') {
                if($rowsbpstatus['CONTENT_STATUS'] == 'Pending Dean Approval') { ?>
                    <input type="submit" id="approve" name="approve" value="Approve"
                           class="btn-primary pull-right">
                    <input type="submit" id="reject" name="reject" value="Reject"
                           class="btn-primary pull-right">
                <?php }
            } ?>
        </form>
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
