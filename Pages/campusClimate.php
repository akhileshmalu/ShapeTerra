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
$message = array();
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
$sqlexvalue = "SELECT * FROM `AC_CampusClimateInclusion` where OU_ABBREV = '$ouabbrev' AND ID_CLIMATE_INCLUSION in (select max(ID_CLIMATE_INCLUSION) from AC_CampusClimateInclusion where OUTCOMES_AY = '$bpayname' group by OU_ABBREV); ";
$resultexvalue = $mysqli->query($sqlexvalue);
$rowsexvalue = $resultexvalue -> fetch_assoc();

/*
 * SQL check Status of Blueprint Content for Edit restrictions
 */
$sqlbpstatus = "SELECT CONTENT_STATUS FROM `BpContents` WHERE ID_CONTENT = '$contentlink_id';";
$resultbpstatus = $mysqli->query($sqlbpstatus);
$rowsbpstatus = $resultbpstatus->fetch_assoc();

if (isset($_POST['savedraft'])) {

    $climate = mynl2br($_POST['climate']);

    if ($_FILES['supinfo']['tmp_name'] != "") {
        $target_dir = "../uploads/campus_climate/";
        $target_file = $target_dir . basename($_FILES["supinfo"]["name"]);
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        $imagedimension = getimagesize($_FILES["supinfo"]["name"]);


        if ($imageFileType != "pdf") {
            $message[1] = "Sorry, only PDF files are allowed.";
            $errorflag = 1;

        } else {
            if (move_uploaded_file($_FILES["supinfo"]["tmp_name"], $target_file)) {
                // $error[0] = "The file " . basename($_FILES["supinfo"]["name"]) . " has been uploaded.";
                $supinfopath = $target_file;
            } else {
                $message[2] = "Sorry, there was an error uploading your file.";
            }
        }
    }
    if ($errorflag != 1) {

        $sqlclimate = "INSERT INTO `AC_CampusClimateInclusion` (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP, CLIMATE_INCLUSION, SUPPL_CLIMATE_INCLUSION)
VALUES ('$ouabbrev','$bpayname','$author','$time','$climate','$supinfopath');";

        $sqlclimate .= "Update `BpContents` set CONTENT_STATUS = 'In Progress', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

        $sqlclimate .= "Update `broadcast` set BROADCAST_STATUS = 'In Progress', BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= '$author', LastModified ='$time' where ID_BROADCAST = '$bpid'; ";

        if ($mysqli->multi_query($sqlclimate)) {

            $message[0] = "Campus & Climate Info Added Succesfully.";
        } else {
            $message[3] = "Campus & Climate Info could not be added.";
        }
    }

}

if(isset($_POST['submit_approval'])) {

    $contentlink_id = $_GET['linkid'];

    $sqlfacinfo .= "UPDATE `BpContents` SET CONTENT_STATUS = 'Pending Dean Approval', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

    if ($mysqli->query($sqlfacinfo)) {

        $message[0] = "Campus & Climate Info submitted Successfully";

    } else {
        $message[0] = "Campus & Climate Info Could not be submitted. Please Retry.";
    }

}

if(isset($_POST['approve'])) {

    $contentlink_id = $_GET['linkid'];
    $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Approved', BP_AUTHOR = '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";
    if ($mysqli->query($sqlmission)) {
        $message[0] = "Campus & Climate Info Approved Successfully";
    } else {
        $message[0] = "Campus & Climate Info Could not be Approved. Please Retry.";
    }
}

if(isset($_POST['reject'])) {

    $contentlink_id = $_GET['linkid'];
    $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Rejected', BP_AUTHOR = '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";
    if ($mysqli->query($sqlmission)) {
        $message[0] = "Campus & Climate Info Rejected Successfully";
    } else {
        $message[0] = "Campus & Climate Info Could not be Rejected. Please Retry.";
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
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" redirect="bphome.php?ayname=<?php echo $rowbroad[0] . "&id=" . $bpid; ?>"
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
        <h1 class="box-title">Campus Climate &amp; Inclusion</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . "?linkid=" . $contentlink_id; ?>" method="POST"
              enctype="multipart/form-data">
            <h3>Campus Climate &amp; Inclusion</h3>
            <div class="form-group form-indent">
                <p class="status">Describe activities your unit conducted within the Academic Year that were designed to
                    improve campus climate and inclusion. The response may be brief or up to several paragraphs. If you
                    have extensive information or narrative to include, please provide a brief synopsis here, and upload
                    a Supplemental Info PDF in the provided space below.</p>
                <textarea name="climate" rows="6" cols="25" wrap="hard" class="form-control"
                          required><?php echo mybr2nl($rowsexvalue['CLIMATE_INCLUSION']); ?></textarea>
            </div>
            <h3>Supplemental Info</h3>
            <div id="suppinfo" class="form-group form-indent">
                <p class="status">
                    <small> Optional. You may attach a single PDF document formatted to 8.5 x 11 dimensions, to provide
                        additional detail on Campus Climate and Inclusion efforts of your Academic Unit during the
                        Academic Year.
                    </small>
                </p>
                <label for="supinfofile">Select File</label>
                <input id="supinfofile" type="file" name="supinfo" onchange="selectorfile(this)" class="form-control">
            </div>


            <!--                      Edit Control-->

            <?php if (($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') AND ($rowsbpstatus['CONTENT_STATUS'] == 'In Progress' OR $rowsbpstatus['CONTENT_STATUS'] == 'Dean Rejected' OR $rowsbpstatus['CONTENT_STATUS'] == 'Not Started')) { ?>
                <button id="save" type="submit" name="savedraft"
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

        var doc, image;
        var filename = $(selected).val();
        var extention = $(selected).val().substr(filename.lastIndexOf('.') + 1).toLowerCase();
        var allowedext = ['pdf'];

        if (filename.length > 0) {
            if (allowedext.indexOf(extention) !== -1) {
                alert(filename.substr(12) + " is selected.");
            } else {
                alert('Invalid file Format. Only ' + allowedext.join(', ') + ' are allowed.');
                $(selected).val('');
            }
        }
    }
</script>
<script src="../Resources/Library/js/tabAlert.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script src="../Resources/Library/js/outcomecntrl.js"></script>
