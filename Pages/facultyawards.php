<?php

$pagename = "bphome";

/*
 * This Page controls Faculty Awards Screen.
 */

/*
 * Session & Error control Initialization.
 */
 require_once ("../Resources/Includes/initalize.php");
 $initalize = new Initialize();
 $initalize->checkSessionStatus();
 $connection = $initalize->connection;
 
require("../Resources/Includes/data.php");
$message = array();
$errorflag = 0;
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

$Facultyawards = new FACULTYAWARDS();

/*
 * faculty Award Grid ; conditional for provost & other users
 */

try {
    if ($ouid == 4) {
        $sqlbroad = "SELECT BROADCAST_AY,OU_NAME,BROADCAST_STATUS,LastModified from broadcast inner join Hierarchy on broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY where BROADCAST_AY= :bpayname and Hierarchy.OU_ABBREV = :ouabbrev;";

        $resultbroad = $connection->prepare($sqlbroad);
        $resultbroad->bindParam(":bpayname", $bpayname, PDO::PARAM_STR);
        $resultbroad->bindParam(":ouabbrev", $ouabbrev, PDO::PARAM_STR);



    } else {
        $sqlbroad = "SELECT BROADCAST_AY,OU_NAME, BROADCAST_STATUS_OTHERS,LastModified from broadcast inner join Hierarchy on broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY where BROADCAST_AY = :bpayname and BROADCAST_OU = :ouid;";

        $resultbroad = $connection->prepare($sqlbroad);
        $resultbroad->bindParam(":bpayname", $bpayname, PDO::PARAM_STR);
        $resultbroad->bindParam(":ouid", $ouid, PDO::PARAM_STR);
    }

    $resultbroad->execute();
    $rowbroad = $resultbroad->fetch(4);

} catch (PDOException $e) {
    error_log($e->getMessage());
    //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
}

/*
 * SQL check Status of Blueprint Content for Edit restrictions
 */
try {
    $sqlbpstatus = "SELECT CONTENT_STATUS FROM `BpContents` WHERE ID_CONTENT = :id";

    $resultbpstatus = $connection->prepare($sqlbpstatus);
    $resultbpstatus->execute(['id'=>$contentlink_id]);
    $rowsbpstatus = $resultbpstatus->fetch(4);

} catch (PDOException $e) {
    error_log($e->getMessage());
    //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
}

/*
 * New award modal Input values : Award type
 */
try {
    $sqlaward = "select * from AwardType;";
    $awardresult = $connection->prepare($sqlaward)->execute();
} catch (PDOException $e) {
    error_log($e->getMessage());
    //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
}

try {
    $sqlawardLoc = "select * from AwardLocation;";
    $awardlocresult = $connection->prepare($sqlawardLoc)->execute();
} catch (PDOException $e) {
    error_log($e->getMessage());
    //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
}


/*
 * Add Modal Record Addition
 */

if(isset($_POST['award_submit'])){

    //  *************************** \\
    //  ********** ERROR ********** \\
    //  ** Can't execute multiple * \\
    //  ** queries in single PDO ** \\
    //  ******** statement ******** \\
    //  *************************** \\

    $awardType = $_POST['awardType'];
    $awardLoc = $_POST['awardLoc'];
    $recipLname = $_POST['recipLname'];
    $recipFname = $_POST['recipFname'];
    $awardTitle = $_POST['awardTitle'];
    $awardOrg = $_POST['awardOrg'];
    $dateAward = $_POST['dateAward'];
    $contentlink_id = $_GET['linkid'];

    $sqlAcFacAward = "INSERT INTO `AC_FacultyAwards`
                        (OU_ABBREV,OUTCOMES_AY,OUTCOMES_AUTHOR,MOD_TIMESTAMP,AWARD_TYPE,AWARD_LOCATION,RECIPIENT_NAME_LAST,
                        RECIPIENT_NAME_FIRST,AWARD_TITLE,AWARDING_ORG,DATE_AWARDED,ID_SORT)
                        VALUES('$ouabbrev','$bpayname','$author','$time','$awardType','$awardLoc','$recipLname','$recipFname',
                        '$awardTitle','$awardOrg','$dateAward','0');";

    $sqlAcFacAward .= "UPDATE `BpContents` set CONTENT_STATUS = 'In progress', BP_AUTHOR = '$author',MOD_TIMESTAMP ='$time'
                        where ID_CONTENT ='$contentlink_id';";

    $sqlAcFacAward .= "UPDATE  `broadcast` set BROADCAST_STATUS = 'In Progress', BROADCAST_STATUS_OTHERS = 'In Progress',
                        AUTHOR= '$author', LastModified ='$time' where ID_BROADCAST = '$bpid'; ";

    if($mysqli->multi_query($sqlAcFacAward)){

        $Data = new Data();
        $Data->initOrderFacultyAwards();
        $message[0] = "Award Added Succesfully.";

    } else {
        $message[0] = "Award Could not be Added.";
    }

}

if(isset($_POST['submit_approve'])) {

    $contentlink_id = $_GET['linkid'];

    try {
        $sqlfacinfo .= "UPDATE `BpContents` SET CONTENT_STATUS = 'Pending Dean Approval', BP_AUTHOR= :author ,MOD_TIMESTAMP =:time  where ID_CONTENT = :contentlink_id;";

        $sqlfacinforesult = $connection->prepare($sqlfacinfo);
        $sqlfacinforesult->bindParam(":author", $author, PDO::PARAM_STR);
        $sqlfacinforesult->bindParam(":time", $time, PDO::PARAM_STR);
        $sqlfacinforesult->bindParam(":contentlink_id", $contentlink_id, PDO::PARAM_STR);

        if ($sqlfacinforesult->execute()) {

            $message[0] = "Faculty Awards submitted Successfully";

        } else {
            $message[0] = "Faculty Awards Could not be submitted. Please Retry.";
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
    }


}

if(isset($_POST['approve'])) {

    $contentlink_id = $_GET['linkid'];

     try {
        $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Approved', BP_AUTHOR = :author, MOD_TIMESTAMP = :time  where ID_CONTENT =:contentlink_id; ";

        $sqlmissionresult = $connection->prepare($sqlmission);
        $sqlmissionresult->bindParam(":author", $author, PDO::PARAM_STR);
        $sqlmissionresult->bindParam(":time", $time, PDO::PARAM_STR);
        $sqlmissionresult->bindParam(":contentlink_id", $contentlink_id, PDO::PARAM_STR);

        if ($sqlmissionresult->execute()) {
            $message[0] = "Faculty Awards Approved Successfully";
        } else {
            $message[0] = "Faculty Awards Could not be Approved. Please Retry.";
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
    }
}

if(isset($_POST['reject'])) {

    $contentlink_id = $_GET['linkid'];

    try {
        $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Rejected', BP_AUTHOR = :author, MOD_TIMESTAMP =:time
        where ID_CONTENT = :contentlink_id; ";

        $sqlmissionresult = $connection->prepare($sqlmission);
        $sqlmissionresult->bindParam(":author", $author, PDO::PARAM_STR);
        $sqlmissionresult->bindParam(":time", $time, PDO::PARAM_STR);
        $sqlmissionresult->bindParam(":contentlink_id", $contentlink_id, PDO::PARAM_STR);

        if ($sqlmissionresult->execute()) {
            $message[0] = "Faculty Awards Rejected Successfully";
        } else {
            $message[0] = "Faculty Awards Could not be Rejected. Please Retry.";
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
    }
}

require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrapTable.css"/>
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

<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<div class="overlay hidden"></div>
<?php if (isset($_POST['award_submit'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" redirect="<?php echo $_SERVER['PHP_SELF'].'?linkid='.$contentlink_id; ?>" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<?php if ( isset($_POST['submit_approve']) OR isset($_POST['approve']) OR isset($_POST['reject'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" redirect="bphome.php?ayname=<?php echo $rowbroad[0]."&id=".$bpid; ?>" class="end btn-primary">Close</button>
    </div>

<?php } ?>

<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Blueprint Home</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <div class="col-xs-8">
            <h1 id="ayname" class="box-title"><?php echo $rowbroad[0]; ?></h1>
            <p class="status"><span>Org Unit Name:</span> <?php echo $rowbroad[1]; ?></p>
            <p id="ouabbrev" class="hidden"><?php echo $ouabbrev; ?></p>
            <p class="status"><span>Status:</span> <?php echo $rowbroad[2]; ?></p>
        </div>

<!--        <div class="col-xs-4">-->
<!--            <a href="#" class="btn-primary">Preview</a>-->
<!--        </div>-->
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <!--                        Reviewer Edit Control-->
        <?php if ($_SESSION['login_right'] != 1): ?>

            <div id="addnew" class="">
                <button id="add-mission" type="button" class="btn-secondary  col-lg-3 col-md-7 col-sm-8 pull-right"
                        data-toggle="modal"
                        data-target="#addawardModal"><span class="icon">&#xe035;</span> Add New Awards
                </button>
            </div>

        <?php endif; ?>
        <h1 class="box-title">Faculty Awards</h1>
        <div id="taskboard" style="margin-top: 10px;">
            <!--<table class="grid" action="taskboard/facultyajax.php" title="Faculty Awards">
                <tr>
                    <th col="AWARD_TYPE" width="100" type="text">Type</th>
                    <th col="AWARD_TITLE" href="<?php echo "facultyawards_detail.php?linkid=".$contentlink_id."&award_id="?>{{columns.ID_FACULTY_AWARDS}}" width="300" type="text">Award</th>
                    <th col="RECIPIENT_NAME" width="200" type="text">Recipient(s)</th>
                                       <th col="" type="text">Actions</th>
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

              $.post("../Resources/Includes/data.php?functionNum=6", function(data) {
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
                    { name: "AWARD_TYPE", title: "Award Type", type: "text", width: "auto"},
                    { name: "AWARD_TITLE", title: "Award Title", itemTemplate: function(value,item){
                      return $("<a>").attr("href", "../Pages/facultyawards_detail.php?award_id="+item.ID_FACULTY_AWARDS+"&linkid="+$.getUrlVar("linkid")).text(value);
                    }, width: "auto" },
                    { name: "RECIPIENT_NAME",  title: "Recipient Name", itemTemplate: function(value,item){
                      return item.RECIPIENT_NAME_FIRST + " " + item.RECIPIENT_NAME_LAST;
                    }, width: "auto"},
                    { name: "MOD_TIMESTAMP", title: "Last Updated", type: "text", width: "auto" }
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
                        $.post("../Resources/Includes/data.php?functionNum=4",{'data':items,'indexes':indexes},function(){

                        })
                      }
                    });
                  }
                });
              });

            </script>
        </div>
        <form action="<?php echo "facultyawards.php?linkid=".$contentlink_id ?>" method="POST" >

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


<!--Modal for Addition of New Awards-->

<div class="modal fade" id="addawardModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Add Faculty Awards</h4>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?php echo "facultyawards.php?linkid=".$contentlink_id; ?>">
                <div class="form-group">

                    <label for="awardtype">Select Award Type:<span
                        style="color: red"><sup>*</sup></span></label>
                    <select  name="awardType" class="form-control" id="awardtype">
                        <option value=""></option>
                        <?php while ($rowsaward = $resultaward->fetch_assoc()): { ?>
                            <option value="<?php echo $rowsaward['AWARD_TYPE']; ?>"> <?php echo $rowsaward['AWARD_TYPE']; ?> </option>
                        <?php } endwhile; ?>
                    </select>

                    <label for="awardLoc">Select Award Location:<span
                        style="color: red"><sup>*</sup></span></label>
                    <select  name="awardLoc" class="form-control" id="awardLoc">
                        <option value=""></option>
                        <?php while ($rowsawardLoc = $resultawardLoc->fetch_assoc()): { ?>
                            <option value="<?php echo $rowsawardLoc['ID_AWARD_LOCATION']; ?>"> <?php echo $rowsawardLoc['AWARD_LOCATION']; ?> </option>
                        <?php } endwhile; ?>
                    </select>

                    <label for="recipLname">Recipient Last Name:<span
                        style="color: red"><sup>*</sup></span></label>
                    <input type="text" class="form-control" name="recipLname" id="recipLname" required>

                    <label for="recipFname">Recipient First Name:<span
                        style="color: red"><sup>*</sup></span></label>
                    <input type="text" class="form-control" name="recipFname" id="recipFname" required>

                    <label for="awardtitle">Award Title / Name:<span
                        style="color: red"><sup>*</sup></span></label>
                    <input type="text" class="form-control" name="awardTitle" id="awardtitle" required>

                    <label for="awardOrg">Awarding Organization:<span
                        style="color: red"><sup>*</sup></span></label>
                    <input type="text" class="form-control" name="awardOrg" id="awardOrg" required>

                     <label for="datetimepicker3">Date Awarded:<span
                        style="color: red"><sup>*</sup></span></label>
                    <div class='input-group date' id='datetimepicker3'>
                        <input type='text' name="dateAward" class="form-control" required>
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    <input type="submit" id="awardbtn" name="award_submit" value="Save" class="btn-primary">
                </div>

            </form>
        </div>
        <div class="modal-footer">
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
