<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

/*
 * This Page controls Initiatives & Observations.
 */

session_start();
if(!$_SESSION['isLogged']) {
    header("location:login.php");
    die();
}
$error = array();
$errorflag =0;
$BackToDashboard = true;

require_once ("../Resources/Includes/connect.php");

$bpid = $_SESSION ['bpid'];
$contentlink_id = $_GET['linkid'];
$author = $_SESSION['login_userid'];
$ouid = $_SESSION['login_ouid'];
$bpayname= $_SESSION['bpayname'];


if ($ouid == 4) {
    $ouabbrev = $_SESSION['bpouabbrev'];
} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}


$time = date('Y-m-d H:i:s');

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
 * Values for placeholders
 */
$sqlexvalue = "SELECT * FROM `AC_Collaborations` where OU_ABBREV = '$ouabbrev' AND ID_COLLABORATIONS in (select max(ID_COLLABORATIONS) from AC_Collaborations where OUTCOMES_AY = '$bpayname' group by OU_ABBREV); ";
$resultexvalue = $mysqli->query($sqlexvalue);
$rowsexvalue = $resultexvalue -> fetch_assoc();

/*
 * SQL check Status of Blueprint Content for Edit restrictions
 */
$sqlbpstatus = "SELECT CONTENT_STATUS FROM `BpContents` WHERE ID_CONTENT = '$contentlink_id';";
$resultbpstatus = $mysqli->query($sqlbpstatus);
$rowsbpstatus = $resultbpstatus->fetch_assoc();

if (isset($_POST['savedraft'])) {
    $internalcollaborators = mynl2br($_POST['internalcollaborators']);
    $externalcollaborators = mynl2br($_POST['externalcollaborators']);
    $othercollaborators = mynl2br($_POST['othercollaborators']);

    $sqlcollob = "INSERT INTO `AC_Collaborations` (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP, COLLAB_INTERNAL, COLLAB_EXTERNAL, COLLAB_OTHER)
VALUES ('$ouabbrev','$bpayname','$author','$time','$internalcollaborators','$externalcollaborators','$othercollaborators');";

    $sqlcollob .= "Update `BpContents` set CONTENT_STATUS = 'In Progress', BP_AUTHOR= '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

    $sqlcollob .= "Update `broadcast` set BROADCAST_STATUS = 'In Progress', BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR = '$author', LastModified ='$time' where ID_BROADCAST = '$bpid'; ";

    if ($mysqli->multi_query($sqlcollob)) {

        $error[0] = "Academic Collaborations Info Added Succesfully.";
    } else {
        $error[3] = "Academic Collaborations Info could not be added.";
    }

}

if(isset($_POST['submit_approval'])) {

    $contentlink_id = $_GET['linkid'];

    $sqlcollob = "UPDATE `BpContents` SET CONTENT_STATUS = 'Pending Dean Approval', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

    if ($mysqli->query($sqlcollob)) {

        $error[0] = "Academic Collaborations Info submitted Successfully";

    } else {
        $error[0] = "Academic Collaborations Info Could not be submitted. Please Retry.";
    }

}

if(isset($_POST['approve'])) {

    $contentlink_id = $_GET['linkid'];
    $sqlcollob = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Approved', BP_AUTHOR = '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";
    if ($mysqli->query($sqlcollob)) {
        $error[0] = "Academic Collaborations Info Approved Successfully";
    } else {
        $error[0] = "Academic Collaborations Info Could not be Approved. Please Retry.";
    }
}

if(isset($_POST['reject'])) {

    $contentlink_id = $_GET['linkid'];
    $sqlcollob = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Rejected', BP_AUTHOR = '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";
    if ($mysqli->query($sqlcollob)) {
        $error[0] = "Academic Collaborations Info Rejected Successfully";
    } else {
        $error[0] = "Academic Collaborations Info Could not be Rejected. Please Retry.";
    }
}

require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<div class="overlay hidden"></div>
<?php if (isset($_POST['submit_approval']) or isset($_POST['savedraft']) or isset($_POST['approve']) or isset($_POST['reject'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" redirect="<?php echo "bphome.php?ayname=" . $rowbroad[0] . "&id=" . $bpid; ?>"
                class="end btn-primary">Close
        </button>
    </div>
<?php } ?>
<div class="hr"></div>
<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Blueprint Management</h1>
    </div>
    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <div class="col-xs-8">
            <h1 id="ayname" class="box-title"><?php echo $rowbroad[0]; ?></h1>
            <p class="status"><span>Org Unit Name:</span> <?php echo $rowbroad[1]; ?></p>
            <p id="ouabbrev" class="hidden"><?php echo $ouabbrev; ?></p>
            <p class="status"><span>Status:</span> <?php echo $rowbroad[2]; ?></p>
        </div>
    </div>
    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Collaborations</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . "?linkid=" . $contentlink_id; ?>" method="POST">
            <h3>Internal Collaborators</h3>
            <div class="form-group form-indent">
                <p class="status">List up to five of your Academic Unit's most significant academic collaborations and
                    multidisciplinary efforts that are internal to the University. Details should be omitted; list by
                    name only. Please note that items written up under the topic Notable Engagements should preferably
                    not be included here.</p>
                <textarea name="internalcollaborators" rows="6" cols="25" wrap="hard" class="form-control"
                          required><?php echo mybr2nl($rowsexvalue['COLLAB_INTERNAL']); ?></textarea>
            </div>
            <h3>External Collaborators</h3>
            <div class="form-group form-indent">
                <p class="status">List up to five of your Academic Unit's most significant academic collaborations and
                    multidisciplinary efforts that are external to the University. Details should be omitted; list by
                    name only. Please note that items written up under the topic Notable Engagements should preferably
                    not be included here.</p>
                <textarea name="externalcollaborators" rows="6" cols="25" wrap="hard"
                          class="form-control"><?php echo mybr2nl($rowsexvalue['COLLAB_EXTERNAL']); ?></textarea>
            </div>
            <h3>Other Collaborators</h3>
            <div class="form-group form-indent">
                <p class="status">
                    <small>List up to five of your Academic Unit's most significant academic collaborations and
                        multidisciplinary efforts that are not otherwise accounted for as Internal or External
                        Collaborations. Details should be omitted; list by name only. Please note that items written up
                        under the topic Notable Engagements should preferably not be included here.
                    </small>
                </p>
                <textarea name="othercollaborators" rows="6" cols="25" wrap="hard"
                          class="form-control"><?php echo mybr2nl($rowsexvalue['COLLAB_OTHER']); ?></textarea>
            </div>


            <!--                      Edit Control-->

            <?php if (($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') AND ($rowsbpstatus['CONTENT_STATUS'] == 'In Progress' OR $rowsbpstatus['CONTENT_STATUS'] == 'Dean Rejected' OR $rowsbpstatus['CONTENT_STATUS'] == 'Not Started')) { ?>
                <button id="save" type="submit" name="savedraft"
                        onclick="//$('#approve').removeAttr('disabled');$('#save').addClass('hidden');"
                        class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                    Save Draft
                </button>
                <input type="button" id="cancelbtn" value="Cancel & Discard" class="btn-primary cancelbpbox pull-left">
                <button type="submit" id="submit_approve" name="submit_approve"
                        class="btn-primary pull-right">Submit For Approval
                </button>

            <?php } elseif ($_SESSION['login_role'] == 'dean' OR $_SESSION['login_role'] == 'designee') { ?>

                <button id="save" type="submit" name="savedraft"
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



<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>

<!--Calender Bootstrap inclusion for date picker INPUT-->
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    function selectorfile(selected) {
        var b = $(selected).val().substr(12);
        alert(b + " is selected.");
    }
</script>
<script src="../Resources/Library/js/tabAlert.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script src="../Resources/Library/js/outcomecntrl.js"></script>
