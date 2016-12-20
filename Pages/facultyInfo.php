<?php

$pagename = "bphome";

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

/*
 * This Page controls Academic Faculty Info.
 */

session_start();
$error = array();
$errorflag =0;

require_once ("../Resources/Includes/connect.php");

$author = $_SESSION['login_userid'];
$ouid = $_SESSION['login_ouid'];
$bpayname= $_SESSION['bpayname'];
$ouabbrev = $_SESSION['login_ouabbrev'];
$contentlink_id = $_GET['linkid'];

$fcdev=null;
$createact=null;
$time = date('Y-m-d H:i:s');


if ($ouid == 4) {
    $sqlbroad = "select BROADCAST_AY,BROADCAST_STATUS,LastModified from broadcast where BROADCAST_AY='$bpayname';";
} else{
    $sqlbroad = "select BROADCAST_AY,BROADCAST_STATUS_OTHERS,LastModified from broadcast where BROADCAST_AY='$bpayname' and BROADCAST_OU ='$ouid'; ";
}
$resultbroad = $mysqli->query($sqlbroad);
$rowbroad = $resultbroad->fetch_array(MYSQLI_NUM);


$sqlexvalue = "SELECT * from AC_FacultyInfo where OU_ABBREV='$ouabbrev' and OUTCOMES_AY='$bpayname' ";
$resultexvalue = $mysqli->query($sqlexvalue);
$rowsexvalue = $resultexvalue -> fetch_assoc();


if (isset($_POST['savedraft'])) {

    $facdev = nl2br($_POST['factextarea']);

    $createact = nl2br($_POST['cractivity']);

    $contentlink_id = $_GET['linkid'];


//    if ($_FILES["supinfo"]["error"] > 0) {
//        $error[0] = "Return Code: No Input " . $_FILES["supinfo"]["error"] . "<br />";
//        $errorflag = 1;
//
//    } else {
//        $target_dir = "../../user"."/".$name."/";

    if ($_FILES['supinfo']['tmp_name'] !="") {
        $target_dir = "../uploads/facultyInfo/";
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
                $error[2] = "Sorry, there was an error uploading your file.";
            }
        }
    }

    if ($errorflag != 1) {
        $sqlfacinfo = "INSERT INTO AC_FacultyInfo (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP, FACULTY_DEVELOPMENT, CREATIVE_ACTIVITY, AC_SUPPL_FACULTY)
 VALUES ('$ouabbrev','$bpayname','$author','$time','$facdev','$createact','$supinfopath') ON DUPLICATE KEY UPDATE
 `OU_ABBREV` = VALUES(`OU_ABBREV`),`OUTCOMES_AY` = VALUES(`OUTCOMES_AY`),`OUTCOMES_AUTHOR` = VALUES(`OUTCOMES_AUTHOR`),`MOD_TIMESTAMP` = VALUES(`MOD_TIMESTAMP`),
 `FACULTY_DEVELOPMENT` = VALUES(`FACULTY_DEVELOPMENT`),`CREATIVE_ACTIVITY` = VALUES(`CREATIVE_ACTIVITY`),`AC_SUPPL_FACULTY` = VALUES(`AC_SUPPL_FACULTY`)
 ;";

        $sqlfacinfo .= "Update  BpContents set CONTENT_STATUS = 'In progress', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";



        if ($mysqli->multi_query($sqlfacinfo)) {

            $error[0] = "Faculty Info Added Succesfully.";
        } else {
            $error[3] = "Faculty Info could not be added.";
        }
    }
}


if(isset($_POST['submit_approval'])) {

    $contentlink_id = $_GET['linkid'];

    $sqlfacinfo .= "Update  BpContents set CONTENT_STATUS = 'Pending approval', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

    if ($mysqli->query($sqlfacinfo)) {

        $error[0] = "Faculty Information submitted Successfully";


    } else {
        $error[0] = "Faculty Information Could not be submitted. Please Retry.";
    }


}


require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<div class="overlay hidden"></div>
<?php if (isset($_POST['savedraft'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" redirect="<?php echo "facultyInfo.php?ayname=".$rowbroad[0]."&linkid=".$contentlink_id; ?> " class="end btn-primary">Close</button>
    </div>
<?php } ?>
<?php if (isset($_POST['submit_approval'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" redirect="<?php echo "bphome.php?ayname=".$rowbroad[0]; ?> " class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Blueprint Home</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <div class="col-xs-8">
            <h1 class="box-title"><?php echo $rowbroad[0]; ?></h1>
            <p class="status"><span>Org Unit Name:</span> <?php echo $_SESSION['login_ouname']; ?></p>
            <p class="status"><span>Status:</span> <?php echo $rowbroad[1]; ?></p>
        </div>




    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Faculty information</h1>
        <div id="" style="margin-top: 10px;">
            <form action="<?php echo "facultyInfo.php?linkid=".$contentlink_id; ?>" method="POST" enctype="multipart/form-data">
            <label for ="facdev" ><h1>Faculty Development: </h1></label>
            <div id="facdev" class="form-group">
                <label for="factextarea"><small><em>Optional. List and describe your unit's efforts at faculty development during the Academic Year, including investments, activities, incentives, objectives, and outcomes.
                    You may paste text from other applications by copying from the source document and hitting Ctrl + V (Windows) or Cmd + V (Mac)</em></small></label>
                <textarea id="factextarea" name="factextarea" rows="5" cols="25" wrap="hard" class="form-control" ><?php echo $rowsexvalue['FACULTY_DEVELOPMENT']; ?></textarea>
            </div>

            <label for ="createact" ><h1>Creative Activity</h1></label>
            <div id="createact" class="form-group">
                <label for="cractivity"><small><em>Optional.  List and describe significant artistic, creative, and performance activities of faculty in your unit during the Academic Year.  List by each individual's last name, first name, name of activity, and date (month and year are sufficient).
                    You may paste text from other applications by copying from the source document and hitting Ctrl + V (Windows) or Cmd + V (Mac).</em></small>
                </label>
                <textarea id="cractivity" name="cractivity" rows="5" cols="25" wrap="hard" class="form-control"><?php echo $rowsexvalue['CREATIVE_ACTIVITY']; ?></textarea>
            </div>

            <label for ="suppfacinfo" ><h1>Supplemental Faculty Info</h1></label>
            <div id="suppfacinfo" class="form-group">
                <label for="supinfo"><small><em>Optional.  You may attach a single PDF document, formatted to 8.5 x 11 dimensions, to provide additional detail on Faculty for the Academic Year.  This document will appear as an Appendix in the Draft Report and Final Report.</em></small>
                </label>
                <input id="supinfo" type="file" name="supinfo" onchange="selectorfile(this)" class="form-control">
            </div>

            <input type="button" value="Cancel & Discard" class="btn-primary cancelbox pull-left">
            <input id="approve" type="submit" name="submit_approval" value="Submit For Approval" class="btn-primary pull-right">
            <input id="save" type="submit" name="savedraft" value="Save Draft" class="btn-secondary pull-right" onclick="$('#approve').removeAttr('disabled');$('#save').addClass('hidden');">
            </form>
        </div>
    </div>

</div>


<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>

<!--Calender Bootstrap inclusion for date picker INPUT-->

<script src="../Resources/Library/js/tabAlert.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>

<script type="text/javascript">

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    function selectorfile(selected) {
        var b = $(selected).val().substr(12);
        alert(b + " is selected.");
    };


    $('.cancelbox').on("click", function () {
        var choice = confirm("Are you sure you want to cancel");
        if (choice == true) {
            var ayname = <?php echo json_encode($bpayname); ?> , ouabbrev = <?php echo json_encode($ouabbrev); ?>;
            $(window).attr('location', 'bphome.php?ayname=' + ayname + '&ou_abbrev=' + ouabbrev)
        }
    });

</script>