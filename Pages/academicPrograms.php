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
 *  Main Title Box  ; conditional for provost & other users
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
$sqlexvalue = "SELECT * FROM `AC_Programs` where OU_ABBREV = '$ouabbrev' AND ID_AC_PROGRAMS in (select max(ID_AC_PROGRAMS) from AC_Programs where OUTCOMES_AY = '$bpayname' group by OU_ABBREV)";
$resultexvalue = $mysqli->query($sqlexvalue);
$rowsexvalue = $resultexvalue -> fetch_assoc();

/*
 * SQL check Status of Blueprint Content for Edit restrictions
 */
$sqlbpstatus = "SELECT CONTENT_STATUS FROM `BpContents` WHERE ID_CONTENT = '$contentlink_id';";
$resultbpstatus = $mysqli->query($sqlbpstatus);
$rowsbpstatus = $resultbpstatus->fetch_assoc();

if (isset($_POST['savedraft'])) {
    $programranking = mynl2br($_POST['programranking']);
    $instructionalmodalities = mynl2br($_POST['instructionalmodalities']);
    $launch = mynl2br($_POST['launch']);
    $programterminators = mynl2br($_POST['programterminators']);

    if ($_FILES['supinfo']['tmp_name'] != "") {
        $target_dir = "../uploads/ac_programs/";
        $target_file = $target_dir . basename($_FILES["supinfo"]["name"]);
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        $imagedimension = getimagesize($_FILES["supinfo"]["name"]);


        if ($imageFileType != "pdf") {
            $error[1] = "Sorry, only PDf files are allowed.";
            $errorflag = 1;

        } else {
            if (move_uploaded_file($_FILES["supinfo"]["tmp_name"], $target_file)) {
                // $error[0] = "The file " . basename($_FILES["supinfo"]["name"]) . " has been uploaded.";
                $supinfopath = $target_file;
            } else {
                $error[2] = "Sorry, there was an error uploading your file.";
            }
        }
    }
    if ($errorflag != 1) {

        $sqlacprogramme = "INSERT INTO AC_Programs (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP, PROGRAM_RANKINGS, INSTRUCT_MODALITIES, PROGRAM_LAUNCHES, PROGRAM_TERMINATIONS, AC_SUPPL_PROGRAMS) 
VALUES ('$ouabbrev','$bpayname','$author','$time','$programranking','$instructionalmodalities','$launch','$programterminators','$supinfopath');";

        $sqlacprogramme .= "Update  `BpContents` set CONTENT_STATUS = 'In Progress', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

        $sqlacprogramme .= "Update  `broadcast` set BROADCAST_STATUS = 'In Progress', BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= '$author', LastModified ='$time' where ID_BROADCAST = '$bpid'; ";

        if ($mysqli->multi_query($sqlacprogramme)) {

            $error[0] = "Academic Program Info Added Succesfully.";
        } else {
            $error[3] = "Academic Program Info could not be added.";
        }
    }

}

if(isset($_POST['submit_approval'])) {

    $contentlink_id = $_GET['linkid'];

    $sqlfacinfo .= "UPDATE `BpContents` SET CONTENT_STATUS = 'Pending Dean Approval', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

    if ($mysqli->query($sqlfacinfo)) {

        $error[0] = "Academic Program Info submitted Successfully";

    } else {
        $error[0] = "Academic Program Info Could not be submitted. Please Retry.";
    }

}

if(isset($_POST['approve'])) {

    $contentlink_id = $_GET['linkid'];
    $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Approved', BP_AUTHOR = '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";
    if ($mysqli->query($sqlmission)) {
        $error[0] = "Academic Program Info Approved Successfully";
    } else {
        $error[0] = "Academic Program Info Could not be Approved. Please Retry.";
    }
}

if(isset($_POST['reject'])) {

    $contentlink_id = $_GET['linkid'];
    $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Rejected', BP_AUTHOR = '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";
    if ($mysqli->query($sqlmission)) {
        $error[0] = "Academic Program Info Rejected Successfully";
    } else {
        $error[0] = "Academic Program Info Could not be Rejected. Please Retry.";
    }
}

require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<div class="overlay hidden"></div>
<?php //if (isset($_POST['submit_approval'])) { ?>
<!--    <div class="alert">-->
<!--        <a href="#" class="close end"><span class="icon">9</span></a>-->
<!--        <h1 class="title"></h1>-->
<!--        <p class="description">--><?php //foreach ($error as $value) echo $value; ?><!--</p>-->
<!--        <button type="button" redirect="bphome.php?ayname=--><?php //echo $rowbroad[0]; ?><!--" class="end btn-primary">Close</button>-->
<!--    </div>-->
<?php //} ?>
<?php if (isset($_POST['savedraft']) or isset($_POST['submit_approval']) or isset($_POST['approve']) or isset($_POST['reject'])) { ?>
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
        <h1 class="box-title">Academic Programs</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'].'?linkid='.$contentlink_id; ?>" method="POST" enctype="multipart/form-data">
            <h3>Program Rankings</h3>
                <div class="form-group form-indent">
                    <p class="status">List any academic programs that were nationally ranked during the Academic Year.  For each, provide the formal name of the academic program followed by the name of the organization that issued the ranking, the date of notification, effective date range, and notes. </p>  
                    <textarea name="programranking" rows="6" cols="25" wrap="hard" class="form-control"  required><?php echo mybr2nl($rowsexvalue['PROGRAM_RANKINGS']); ?></textarea>
                </div>
            <h3>Instructional Modalities</h3>
                <div class="form-group form-indent">
                    <p class="status">List and describe innovations and changes to Instructional Modalities in your unit's programmatic and course offerings that were enacted during the Academic Year.  </p> 
                    <textarea name="instructionalmodalities" rows="6" cols="25" wrap="hard" class="form-control" ><?php echo mybr2nl($rowsexvalue['INSTRUCT_MODALITIES']); ?></textarea>
                </div>
            <h3>Program Launches</h3>
                <div class="form-group form-indent">
                    <p class="status"><small>List any Academic Programs that were newly launched during the Academic Year; those that have received required approvals but which have not yet enrolled students should not be included. For each, list the formal name of the academic program and the responsible department. </small></p>
                    <textarea  name="launch" rows="6" cols="25" wrap="hard" class="form-control" ><?php echo mybr2nl($rowsexvalue['PROGRAM_LAUNCHES']); ?></textarea>
                </div>
            <h3>Program Terminations</h3>
                <div class="form-group form-indent">
                    <p class="status"><small>List any Academic Programs that were newly terminated or discontinued during the Academic Year as follows: for each clearly indicate whether the decision to terminate was made during the Academic Year or whether the program ceased having any enrolled students during the Academic Year.  </small></p>
                    <textarea  name="programterminators" rows="6" cols="25" wrap="hard" class="form-control" ><?php echo mybr2nl($rowsexvalue['PROGRAM_TERMINATIONS']); ?></textarea>
                </div>
            <h3>Supplemental Info</h3>
                <div id="suppinfo" class="form-group form-indent">
                    <p class="status"><small>Optional.  If available, you may attach a single PDF document formatted to 8.5 x 11 dimensions, to provide additional detail on Academic Programs for the Academic Year. </small></p>
                    <label for="supinfofile">Select File</label>
                    <input id="supinfofile" type="file" name="supinfo" onchange="selectorfile(this)" class="form-control">
                </div>

                <!--                      Edit Control-->

                <?php if (($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead' ) AND ($rowsbpstatus['CONTENT_STATUS']=='In Progress' OR $rowsbpstatus['CONTENT_STATUS']=='Dean Rejected' OR $rowsbpstatus['CONTENT_STATUS']=='Not Started') ) { ?>
                    <button id="save" type="submit" name="savedraft"
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
                    <input type="button" id="cancelbtn" value="Cancel & Discard" class="btn-primary cancelbpbox pull-left">
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
