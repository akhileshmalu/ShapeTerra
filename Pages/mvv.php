<?php

/*
 * This Page controls Mission Vision & Value Screen.
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
$errorflag = 0;
$BackToDashboard = true;


/*
 * Connection to DataBase.
 */
require_once("../Resources/Includes/connect.php");

/*
 * Local & Session variable Initialization
 */
$bpid = $_SESSION['bpid'];
$contentlink_id = $_GET['linkid'];
$bpayname = $_SESSION['bpayname'];
$prevbpid = stringtoid($bpayname);
$prevbpayname = idtostring($prevbpid - 101);
$ouid = $_SESSION['login_ouid'];
$outype = $_SESSION['login_outype'];

$date = date("Y-m-d");
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_userid'];


if ($outype == "Administration" OR $outype == "Service Unit" ) {
    $ouabbrev = $_SESSION['bpouabbrev'];
} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}

// Display Information of Main-box basis roles
if ($outype == "Administration" || $outype == "Service Unit" ) {
    $sqlbroad = "SELECT BROADCAST_AY,OU_NAME,BROADCAST_STATUS,LastModified FROM broadcast INNER JOIN Hierarchy ON broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY WHERE BROADCAST_AY='$bpayname' AND Hierarchy.OU_ABBREV ='$ouabbrev';";
} else{
    $sqlbroad = "SELECT BROADCAST_AY,OU_NAME, BROADCAST_STATUS_OTHERS,LastModified FROM broadcast INNER JOIN Hierarchy ON broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY WHERE BROADCAST_AY='$bpayname' AND BROADCAST_OU ='$ouid'; ";
}
$resultbroad = $mysqli->query($sqlbroad);
$rowbroad = $resultbroad->fetch_array(MYSQLI_NUM);

/*
 * Query to Select Previous Year Mission , Visoin, Value Statement for Specific Org Unit.
 */

$sqlmission = "SELECT * FROM BP_MissionVisionValues where OU_ABBREV = '$ouabbrev' AND ID_UNIT_MVV in (select max(ID_UNIT_MVV) from BP_MissionVisionValues where UNIT_MVV_AY IN ('$bpayname','$prevbpayname') group by OU_ABBREV)";
//$sqlmission = "select * from BP_MissionVisionValues where (UNIT_MVV_AY ='$prevbpayname' or UNIT_MVV_AY ='$bpayname') and OU_ABBREV ='$ouabbrev' ORDER BY UNIT_MVV_AY DESC;";
$resultmission = $mysqli->query($sqlmission);
$rowsmission = $resultmission->fetch_assoc();


/*
 * SQL check Status of Blueprint Content for Edit restrictions
 */
$sqlbpstatus = "SELECT CONTENT_STATUS FROM BpContents WHERE ID_CONTENT = '$contentlink_id';";
$resultbpstatus = $mysqli->query($sqlbpstatus);
$rowsbpstatus = $resultbpstatus->fetch_assoc();

if (isset($_POST['submit'])) {

    $missionstatement = mynl2br($_POST['missionstatement']);
    $missionupdatedate = $_POST['misupdate'];
    $visionstatement = mynl2br($_POST['visionstatement']);
    $visionupdatedate = $_POST['visupdate'];
    $valuestatement = mynl2br($_POST['valuestatement']);
    $valueupdatedate = $_POST['valupdate'];
    $contentlink_id = $_GET['linkid'];


    $sqlmission = "INSERT INTO `BP_MissionVisionValues` (OU_ABBREV,MVV_AUTHOR, MOD_TIMESTAMP, UNIT_MVV_AY, MISSION_STATEMENT, MISSION_UPDATE_DATE, VISION_STATEMENT,VISION_UPDATE_DATE,VALUES_STATEMENT,VALUE_UPADTE_DATE)
VALUES ('$ouabbrev','$author','$time','$bpayname','$missionstatement','$missionupdatedate','$visionstatement','$visionupdatedate','$valuestatement','$valueupdatedate');";

    $sqlmission .= "Update  `BpContents` set CONTENT_STATUS = 'In Progress', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";

    $sqlmission .= "Update  `broadcast` set BROADCAST_STATUS = 'In Progress',BROADCAST_STATUS_OTHERS = 'In Progress',  AUTHOR= '$author',LastModified ='$time'
where ID_BROADCAST = '$bpid'; ";

    if ($mysqli->multi_query($sqlmission)) {
        $error[0] =  "Mission Updated Successfully";
    } else {
        $error[0] =   "Mission Could not be Updated. Please Retry.";
    }

}

if(isset($_POST['submit_approve'])) {

    $contentlink_id = $_GET['linkid'];
       $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Pending Dean Approval', BP_AUTHOR= '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";
       if ($mysqli->query($sqlmission)) {
           $error[0] = "Mission, Vision & Values submitted Successfully";
       } else {
           $error[0] = "Mission, Vision & Values Could not be submitted. Please Retry.";
       }
}

if(isset($_POST['approve'])) {

    $contentlink_id = $_GET['linkid'];
    $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Approved', BP_AUTHOR= '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";
    if ($mysqli->query($sqlmission)) {
        $error[0] = "Mission, Vision & Values Approved Successfully";
    } else {
        $error[0] = "Mission, Vision & Values Could not be Approved. Please Retry.";
    }
}

if(isset($_POST['reject'])) {

    $contentlink_id = $_GET['linkid'];
    $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Rejected', BP_AUTHOR= '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";
    if ($mysqli->query($sqlmission)) {
        $error[0] = "Mission, Vision & Values Rejected Successfully";
    } else {
        $error[0] = "Mission, Vision & Values Could not be Rejected. Please Retry.";
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
<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>
<link href="Css/templateTabs.css" rel="stylesheet" type="text/css"/>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>

<?php if (isset($_POST['submit']) || isset($_POST['submit_approve']) || isset($_POST['approve'])  ) { ?>
    <div class="overlay hidden"></div>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" redirect="bphome.php?ayname=<?php echo $rowbroad[0]."&id=".$bpid; ?>" class="end btn-primary">Close</button>
    </div>
<?php } ?>
<div class="hr"></div>
<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Blueprint Home</h1>
    </div>
    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 id="ayname" class="box-title"><?php echo $rowbroad[0]; ?></h1>
        <p class="status"><span>Status:</span> <?php echo $rowbroad[2]; ?></p>
        <p id="ouabbrev" class="hidden"><?php echo $ouabbrev; ?></p>
        <p class="status"><span>Last Modified:</span> <?php echo date("F j, Y, g:i a", strtotime($rowbroad[3])); ?></p>
    </div>
    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Mission, Vision & Values</h1>
        <div id="headtitle">
            <form action="<?php echo $_SERVER['PHP_SELF'] . "?linkid=" . $contentlink_id ?>" method="POST"
                  class="mission">
                <div class="mission-status-alert hidden text-center">
                    <h1>Mission Updated Successfully</h1>
                    <a href="bphome.php?ayname=<?php echo $rowbroad[0]; ?>" class="btn-secondary pull-left">Back To
                        Dashboard</a>
                    <a href="#" class="mission-next-tab btn-primary" onclick="return false;">Next Tab</a>
                </div>
                <p class="status">
                    <small><em>Instruction: Enter your BluePrint content for the Academic Year indicated above.The
                            components below are highest level statements of
                            what <?php echo $_SESSION['login_ouname']; ?> considers foundation to your goals &
                            related outcomes.</em></small>
                </p>
                <h3>Mission Statement<span
                        style="color: red"><sup>*</sup></span></h3>
                <div class="col-xs-12 form-group form-indent">
                    <textarea rows="5" cols="25" wrap="hard" class="form-control wordCount" name="missionstatement"
                              id="missiontitle" maxlength="1000"
                              required><?php echo mybr2nl($rowsmission['MISSION_STATEMENT']); ?></textarea>
                    <div class="form-group col-xs-4">
                        <p><h3>Last Updated:</h3></p>
                        <div class='input-group date' id='datetimepicker1'>
                            <input type='text' name="misupdate" value="<?php echo $rowsmission['MISSION_UPDATE_DATE']; ?>" class="form-control" required>
                            <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                        </div>
                    </div>
                </div>
                <h3>Vision Statement</h3>
                <div class="col-xs-12 form-group form-indent">
                    <textarea rows="5" cols="25" wrap="hard" class="form-control wordCount" name="visionstatement" maxlength="1000"
                              id="visiontitle"><?php echo mybr2nl($rowsmission['VISION_STATEMENT']); ?></textarea>
                    <div class="form-group col-xs-4">
                        <p><h3>Last Updated:</h3></p>
                        <div class='input-group date' id='datetimepicker2'>
                            <input type='text' name="visupdate" value="<?php echo $rowsmission['VISION_UPDATE_DATE']; ?>" class="form-control">
                            <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                        </div>
                    </div>
                </div>
                <h3>Values Statement</h3>
                <div class="col-xs-12 form-group form-indent">
                    <textarea rows="5" cols="25" wrap="hard" class="form-control wordCount" name="valuestatement" maxlength="1000"
                              id="valuetitle"><?php echo mybr2nl($rowsmission['VALUES_STATEMENT']); ?></textarea>

                    <div class="form-group col-xs-4">
                        <p><h3>Last Updated:</h3></p>
                        <div class='input-group date' id='datetimepicker3'>
                            <input type='text' name="valupdate" value="<?php echo $rowsmission['VALUE_UPADTE_DATE']; ?>" class="form-control">
                            <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                        </div>
                    </div>
                </div>
                <!--Edit Control-->
                <?php if (($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') AND ($rowsbpstatus['CONTENT_STATUS'] == 'In Progress' OR $rowsbpstatus['CONTENT_STATUS'] == 'Dean Rejected' OR $rowsbpstatus['CONTENT_STATUS'] == 'Not Started')) { ?>
                    <button id="save" type="submit" name="submit"
                            class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                        Save Draft
                    </button>
                    <button type="submit" id="submit_approve" name="submit_approve" <?php if ($rowsbpstatus['CONTENT_STATUS'] == 'Not Started') echo 'disabled'; ?> class="btn-primary pull-right">
                        Submit For Approval
                    </button>
                <?php } elseif ($_SESSION['login_role'] == 'dean' OR $_SESSION['login_role'] == 'designee') { ?>
                    <button id="save" type="submit" name="submit"
                            onclick="//$('#approve').removeAttr('disabled');$('#save').addClass('hidden');"
                            class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                        Save Draft
                    </button>
                    <?php if ($rowsbpstatus['CONTENT_STATUS'] == 'Pending Dean Approval'): ?>
                        <input type="submit" id="approve" name="approve" value="Approve" class="btn-primary pull-right">
                        <input type="submit" id="reject" name="reject" value="Reject" class="btn-primary pull-right">
                    <?php endif;
                } ?>
            </form>
        </div>
    </div>
</div>
<?php require_once("../Resources/Includes/footer.php"); //Include Footer ?>
<!--Calender Bootstrap inclusion for date picker INPUT-->
<script type="text/javascript">
    $('.nav a').click(function (e) {
        e.preventDefault();
        $(this).tab('show')
    })
</script>
<script src="../Resources/Library/js/tabAlert.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script src="../Resources/Library/js/taskboard.js"></script>
<script src="../Resources/Library/js/content.js"></script>
