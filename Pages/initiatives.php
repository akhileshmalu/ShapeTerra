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
$sqlexvalue = "SELECT * from `AC_InitObsrv` where OU_ABBREV='$ouabbrev' and OUTCOMES_AY='$bpayname' ";
$resultexvalue = $mysqli->query($sqlexvalue);
$rowsexvalue = $resultexvalue -> fetch_assoc();

/*
 * SQL check Status of Blueprint Content for Edit restrictions
 */
$sqlbpstatus = "SELECT CONTENT_STATUS FROM `BpContents` WHERE ID_CONTENT = '$contentlink_id';";
$resultbpstatus = $mysqli->query($sqlbpstatus);
$rowsbpstatus = $resultbpstatus->fetch_assoc();


if (isset($_POST['savedraft'])) {

    $contentlink_id = $_GET['linkid'];
    $ugexplearn = mynl2br($_POST['ugexplearning']);
    $gradexplearn = mynl2br($_POST['gradexplearning']);
    $afford = mynl2br($_POST['afford']);
    $reputation = mynl2br($_POST['reputation']);
    $coolstuff = mynl2br($_POST['coolstuff']);
    $challenges = mynl2br($_POST['challenges']);

//    if ($_FILES["supinfo"]["error"] > 0) {
//        $error[0] = "Return Code: No Input " . $_FILES["supinfo"]["error"] . "<br />";
//        $errorflag = 1;
//
//    } else {

if ($_FILES['supinfo']['tmp_name'] !="") {
    $target_dir = "../uploads/initiatives/";
    $target_file = $target_dir . basename($_FILES["supinfo"]["name"]);
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);


    if ($imageFileType != "pdf") {
        $error[1] = "Sorry, only PDf files are allowed.";
        $errorflag = 1;

    } else {
        if (move_uploaded_file($_FILES["supinfo"]["tmp_name"], $target_file)) {
            // $error[0] = "The file " . basename($_FILES["supinfo"]["name"]) . " has been uploaded.";
            $supinfopath = $target_file;
        } else {
            $error[1] = "Sorry, there was an error uploading your file.";
            $errorflag = 1;
        }
    }
}

    if ($errorflag != 1) {
        $sqlinitiatives = "INSERT INTO `AC_InitObsrv` (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP, EXPERIENTIAL_LEARNING_UGRAD, EXPERIENTIAL_LEARNING_GRAD, AFFORDABILITY, REPUTATION_ENHANCE,COOL_STUFF, CHALLENGES,AC_SUPPL_INITIATIVES_OBSRV)
 VALUES ('$ouabbrev','$bpayname','$author','$time','$ugexplearn','$gradexplearn','$afford','$reputation','$coolstuff','$challenges','$supinfopath')
 ON DUPLICATE KEY UPDATE `OU_ABBREV` = VALUES(`OU_ABBREV`),`OUTCOMES_AY` = VALUES(`OUTCOMES_AY`),`OUTCOMES_AUTHOR` = VALUES(`OUTCOMES_AUTHOR`),`MOD_TIMESTAMP` = VALUES(`MOD_TIMESTAMP`),`EXPERIENTIAL_LEARNING_UGRAD` = VALUES(`EXPERIENTIAL_LEARNING_UGRAD`),
`EXPERIENTIAL_LEARNING_GRAD` =VALUES(`EXPERIENTIAL_LEARNING_GRAD`), `AFFORDABILITY`=VALUES(`AFFORDABILITY`),`REPUTATION_ENHANCE`=VALUES(`REPUTATION_ENHANCE`),`COOL_STUFF`=VALUES(`COOL_STUFF`),`CHALLENGES`=VALUES(`CHALLENGES`),`AC_SUPPL_INITIATIVES_OBSRV`= VALUES(`AC_SUPPL_INITIATIVES_OBSRV`);";

        $sqlinitiatives .= "Update  `BpContents` set CONTENT_STATUS = 'In Progress', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";
        $sqlinitiatives .= "Update  `broadcast` set BROADCAST_STATUS = 'In Progress', BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR = '$author', LastModified = '$time' where ID_BROADCAST = '$bpid'; ";

        if ($mysqli->multi_query($sqlinitiatives)) {

            $error[0] = "Initiatives & Observations Updated Succesfully.";
        } else {
            $error[3] = "Initiatives & Observations could not be Updated.";
        }
    }
}


if(isset($_POST['submit_approval'])) {

    $contentlink_id = $_GET['linkid'];

    $sqlinitiatives .= " Update BpContents set CONTENT_STATUS = 'Pending Dean Approval', BP_AUTHOR = '$author', MOD_TIMESTAMP = '$time' where ID_CONTENT ='$contentlink_id';";

    if ($mysqli->query($sqlinitiatives)) {

        $error[0] = "Initiatives & Observation submitted Successfully";

    } else {
        $error[0] = "Initiatives & Observation Could not be submitted. Please Retry.";
    }

}

if(isset($_POST['approve'])) {

    $contentlink_id = $_GET['linkid'];
    $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Approved', BP_AUTHOR= '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";
    if ($mysqli->query($sqlmission)) {
        $error[0] = "Initiatives Approved Successfully";
    } else {
        $error[0] = "Initiatives Could not be Approved. Please Retry.";
    }
}

if(isset($_POST['reject'])) {

    $contentlink_id = $_GET['linkid'];
    $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Rejected', BP_AUTHOR= '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";
    if ($mysqli->query($sqlmission)) {
        $error[0] = "Initiatives Rejected Successfully";
    } else {
        $error[0] = "Initiatives Could not be Rejected. Please Retry.";
    }
}


require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<div class="overlay hidden"></div>
<?php if (isset($_POST['submit_approval'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" redirect="bphome.php?ayname=<?php echo $rowbroad[0]; ?>" class="end btn-primary">Close</button>
    </div>
<?php } ?>
<?php if (isset($_POST['savedraft'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" redirect="<?php echo "initiatives.php?ayname=".$rowbroad[0]."&linkid=".$contentlink_id; ?>" class="end btn-primary">Close</button>
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
            <p id="ouabbrev" class="hidden"><?php echo $ouabbrev;  ?></p>
            <p class="status"><span>Status:</span> <?php echo $rowbroad[2]; ?></p>
        </div>
    </div>
    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Initiatives & Observations</h1>
        <div id="" style="margin-top: 10px;">
            <form action="initiatives.php?linkid=<?php echo $contentlink_id;?>" method="POST" enctype="multipart/form-data">
                <label for ="explearning" ><h1>Experiential Learning: </h1></label>
                <div id="explearning" class="form-group">
                    <p class="status"><small>Describe your unit's initiatives, improvements, challenges, and progress with Experiential Learning at each level during the Academic Year (as applicable).</small></p>
                    <h3>Undergraduate</h3>
                    <div class="form-group form-indent">
                      <textarea id="undergrad" name="ugexplearning" rows="6" cols="25" wrap="hard" class="form-control"  required><?php echo mybr2nl($rowsexvalue['EXPERIENTIAL_LEARNING_UGRAD']); ?></textarea>
                    </div>
                    <h3>Graduate</h3>
                    <div class="form-group form-indent">
                        <textarea id="graduate" name="gradexplearning" rows="6" cols="25" wrap="hard" class="form-control" ><?php echo mybr2nl($rowsexvalue['EXPERIENTIAL_LEARNING_GRAD']); ?></textarea>
                    </div>
                </div>
                <h3>Affordability</h3>
                <div id="afford" class="form-group form-indent">
                    <p class="status"><small>Describe your unit's assessment of affordability and efforts to address affordability during the Academic Year.</small></p>
                    <textarea  name="afford" rows="6" cols="25" wrap="hard" class="form-control" ><?php echo mybr2nl($rowsexvalue['AFFORDABILITY']); ?></textarea>
                </div>
                <h3>Reputation Enhancement</h3>
                <div id="reputation" class="form-group form-indent">
                    <p class="status"><small>Describe innovations, happy accidents, good news, etc. that occurred within your unit during the Academic Year, not noted elsewhere in your reporting.</small></p>
                    <textarea  name="reputation" rows="6" cols="25" wrap="hard" class="form-control" ><?php echo mybr2nl($rowsexvalue['REPUTATION_ENHANCE']); ?></textarea>
                </div>
                <h3>Cool Stuff</h3>
                <div id="coolstuff" class="form-group form-indent">
                    <p class="status"><small>Describe your unit's assessment of affordability and efforts to address affordability during the Academic Year.</small></p>
                    <textarea  name="coolstuff" rows="6" cols="25" wrap="hard" class="form-control" ><?php echo mybr2nl($rowsexvalue['COOL_STUFF']); ?></textarea>
                </div>
                <h3>Challenges</h3>
                <div id="challenge" class="form-group form-indent">
                    <p class="status"><small>Describe challenges and resource needs you anticipate for the current and upcoming Academic Years, not noted elsewhere in your reporting - or which merit additional attention.</small></p>
                    <textarea  name="challenges" rows="6" cols="25" wrap="hard" class="form-control" ><?php echo mybr2nl($rowsexvalue['CHALLENGES']); ?></textarea>
                </div>
                <h3>Supplemental Info</h3>
                <div id="suppinfo" class="form-group form-indent">
                    <p class="status"><small>Optional.  If available, you may attach a single PDF document formatted to 8.5 x 11 dimensions, to provide additional detail on Initiatives & Observations for the Academic Year.</small></p>
                    <label for="supinfofile">Select File</label>
                    <input id="supinfofile" type="file" name="supinfo" onchange="selectorfile(this)" class="form-control">
                </div>
                <!--                        Reviewer Edit Control
                <?php //if ($_SESSION['login_right'] != 1): ?>

                <input type="button" id="cancelbtn" value="Cancel & Discard" class="btn-primary cancelbpbox pull-left">
                <input type="submit" id="approve" name="submit_approval" value="Submit For Approval" class="btn-primary pull-right">
                <input type="submit" id="savebtn" name="savedraft" value="Save Draft" class="btn-secondary pull-right">

                <?php //endif; ?>
                -->

                <!--                      Edit Control-->

                <?php if (($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead' ) AND ($rowsbpstatus['CONTENT_STATUS']=='In Progress' OR $rowsbpstatus['CONTENT_STATUS']=='Dean Rejected' OR $rowsbpstatus['CONTENT_STATUS']=='Not Started') ) { ?>
                    <button id="save" type="submit" name="savedraft"
                            onclick="//$('#approve').removeAttr('disabled');$('#save').addClass('hidden');"
                            class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                        Save Draft
                    </button>
                    <input type="button" id="cancelbtn" value="Cancel & Discard" class="btn-primary cancelbpbox pull-left">
                    <button type="submit" id="submit_approve" name="submit_approve"
                            class="btn-primary pull-right">Submit For Approval</button>

                <?php } elseif ($_SESSION['login_role'] == 'dean' OR $_SESSION['login_role'] == 'designee') { ?>

                    <button id="save" type="submit" name="savedraft"
                            class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                        Save Draft
                    </button>

                    <?php if($rowsbpstatus['CONTENT_STATUS'] == 'Pending Dean Approval'): ?>
                        <input type="submit" id="approve" name="approve" value="Approve" class="btn-primary pull-right">
                        <input type="submit" id="reject" name="reject" value="Reject" class="btn-primary pull-right">
                    <?php endif; } ?>

            </form>
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
