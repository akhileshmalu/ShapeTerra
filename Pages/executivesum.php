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
$sqlexvalue = "SELECT * FROM `AC_ExecSum` where OU_ABBREV = '$ouabbrev' AND ID_EXECUTIVE_SUMMARY in (select max(ID_EXECUTIVE_SUMMARY) from AC_ExecSum where OUTCOMES_AY = '$bpayname' group by OU_ABBREV); ";
$resultexvalue = $mysqli->query($sqlexvalue);
$rowsexvalue = $resultexvalue -> fetch_assoc();

/*
 * SQL check Status of Blueprint Content for Edit restrictions
 */
$sqlbpstatus = "SELECT CONTENT_STATUS FROM `BpContents` WHERE ID_CONTENT = '$contentlink_id';";
$resultbpstatus = $mysqli->query($sqlbpstatus);
$rowsbpstatus = $resultbpstatus->fetch_assoc();

if (isset($_POST['savedraft'])) {

    $collname = $_POST['college-school-input'];
    $deanname = $_POST['deans-name-input'];
    $deantitle = $_POST['deans-title-input'];
    $introduction = mynl2br($_POST['introduction-input']);
    $highlights = mynl2br($_POST['highlights-input']);

    if ($_FILES['deans-portrait-logo']['tmp_name'] != "") {
        $target_dir = "../uploads/exec_summary/";
        $target_file_port = $target_dir . basename($_FILES["deans-portrait-logo"]["name"]);
        $deansPortraitLogoTmpDir = $_FILES["deans-portrait-logo"]["name"];
        $portsize = getimagesize($_FILES["deans-portrait-logo"]["name"]);


        if (exif_imagetype($deansPortraitLogoTmpDir) == IMAGETYPE_GIF || exif_imagetype($deansPortraitLogoTmpDir) == IMAGETYPE_JPEG || exif_imagetype($deansPortraitLogoTmpDir) == IMAGETYPE_PNG) {

            if ($portsize[0] == 250 && $portsize[1] == 250) {
                if (move_uploaded_file($_FILES["deans-portrait-logo"]["tmp_name"], $target_file_port)) {
                    $deansPortraitLogopath = $target_file_port;
                } else {
                    $error[1] = "Sorry, there was an error uploading your file.";
                }
            } else {
                $error[1] = "Only 250 X 250 pixel files are allowed.";
                $errorflag = 1;
            }

        } else {
            $error[1] = "Sorry, only GIF, JPEG or PNG files are allowed.";
            $errorflag = 1;
        }
    }
    if ($_FILES['deans-signature-logo']['tmp_name'] != "") {
        $target_dir = "../uploads/exec_summary/";
        $target_file_sign = $target_dir . basename($_FILES["deans-signature-logo"]["name"]);
        $deansPortraitSignTmpDir = $_FILES["deans-signature-logo"]["name"];
        $signsize = getimagesize($_FILES["deans-signature-logo"]["name"]);


        if (exif_imagetype($deansPortraitSignTmpDir) == IMAGETYPE_GIF || exif_imagetype($deansPortraitSignTmpDir) == IMAGETYPE_JPEG || exif_imagetype($deansPortraitSignTmpDir) == IMAGETYPE_PNG) {

            if ($signsize[0] == 250 && $signsize[1] == 75) {
                if (move_uploaded_file($_FILES["deans-signature-logo"]["tmp_name"], $target_file_sign)) {
                    $deansPortraitSignpath = $target_file_sign;
                } else {
                    $error[2] = "Sorry, there was an error uploading your file.";
                }
            } else {
                $error[1] = "Only 250 X 75 pixel files are allowed.";
                $errorflag = 1;
            }

        } else {
            $error[1] = "Sorry, only GIF, JPEG or PNG files are allowed.";
            $errorflag = 1;
        }
    }
    if ($_FILES['deans-college-school-logo']['tmp_name'] != "") {
        $target_dir = "../uploads/exec_summary/";
        $target_file_sch_logo = $target_dir . basename($_FILES["deans-college-school-logo"]["name"]);
        $deansSchoolLogoTmpDir = $_FILES["deans-college-school-logo"]["name"];
//        $imagedimension = getimagesize($_FILES["deans-college-school-logo"]["name"]);


        if (exif_imagetype($deansSchoolLogoTmpDir) == IMAGETYPE_GIF || exif_imagetype($deansSchoolLogoTmpDir) == IMAGETYPE_JPEG || exif_imagetype($deansSchoolLogoTmpDir) == IMAGETYPE_PNG){

            if (move_uploaded_file($_FILES["deans-college-school-logo"]["tmp_name"], $target_file_sch_logo)) {
                $deansSchLogopath = $target_file_sch_logo;
            } else {
                $error[3] = "Sorry, there was an error uploading your file.";
            }

        } else {
            $error[1] = "Sorry, only GIF, JPEG or PNG files are allowed.";
            $errorflag = 1;
        }
    }


    if ($errorflag != 1) {

        $sqlexecsum = "INSERT INTO `AC_ExecSum` (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP, UNIT_NAME, DEAN_NAME_PRINT, DEAN_TITLE, DEAN_PORTRAIT, DEAN_SIGNATURE, COMPANION_LOGO, INTRODUCTION, HIGHLIGHTS_NARRATIVE)
VALUES ('$ouabbrev','$bpayname','$author','$time','$collname','$deanname','$deantitle','$deansPortraitLogopath','$deansPortraitSignpath','$deansSchLogopath','$introduction','$highlights');";

        $sqlexecsum .= "Update  `BpContents` set CONTENT_STATUS = 'In progress', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

        $sqlexecsum .= "Update  `broadcast` set BROADCAST_STATUS = 'In Progress', BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= '$author', LastModified ='$time' where ID_BROADCAST = '$bpid'; ";

        if ($mysqli->multi_query($sqlexecsum)) {

            $error[0] = "Executive Summary Info Added Succesfully.";
        } else {
            $error[3] = "Executive Summary Info could not be added.";
        }
    }

}

if(isset($_POST['submit_approval'])) {

    $contentlink_id = $_GET['linkid'];

    $sqlfacinfo .= "UPDATE `BpContents` SET CONTENT_STATUS = 'Pending Dean Approval', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

    if ($mysqli->query($sqlfacinfo)) {

        $error[0] = "Executive Summary Info submitted Successfully";

    } else {
        $error[0] = "Executive Summary Info Could not be submitted. Please Retry.";
    }

}

if(isset($_POST['approve'])) {

    $contentlink_id = $_GET['linkid'];
    $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Approved', BP_AUTHOR = '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";
    if ($mysqli->query($sqlmission)) {
        $error[0] = "Executive Summary Info Approved Successfully";
    } else {
        $error[0] = "Executive Summary Info Could not be Approved. Please Retry.";
    }
}

if(isset($_POST['reject'])) {

    $contentlink_id = $_GET['linkid'];
    $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Rejected', BP_AUTHOR = '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";
    if ($mysqli->query($sqlmission)) {
        $error[0] = "Executive Summary Info Rejected Successfully";
    } else {
        $error[0] = "Executive Summary Info Could not be Rejected. Please Retry.";
    }
}




require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="Css/templateTabs.css" rel="stylesheet" type="text/css"/>
<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.min.css"/>

<div class="overlay hidden"></div>
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
        <h1 id="title">Add Data Element</h1>
    </div>

    <div id="list" class="col-lg-2 col-md-4 col-xs-4">
        <ul class="tabs-nav">
            <li class="tab1 active">1. Details</li>
            <li class="tab2 disabled">2. Intro & Highlights</li>
        </ul>
    </div>
    <div id="main-box" class="col-lg-8 col-xs-offset-1 col-md-8 col-xs-8">
        <form action="<?php echo $_SERVER['PHP_SELF'].'?linkid='.$contentlink_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group tab1 active" id="actionlist">
                <h1>Details</h1>
                <div class="col-xs-12">

                    <h3>College/School Name</h3>
                    <div id="college-school" class="form-group form-indent">
                        <p class="status">
                            <small>Provide the formal name of the College/School exactly as you want it to appear.</small>
                        </p>
                        <input id="college-school-input" name="college-school-input" type="text" class="form-control" value="<?php echo $rowsexvalue["UNIT_NAME"]; ?>" required>
                    </div>

                    <h3>Dean's Name</h3>
                    <div id="deans-name" class="form-group form-indent">
                        <p class="status">
                            <small>Provide the formal name of the Dean exactly as you want it to appear.</small>
                        </p>
                        <input id="deans-name-input" name="deans-name-input" type="text" class="form-control" value="<?php echo $rowsexvalue["DEAN_NAME_PRINT"]; ?>" required>
                    </div>
                    <h3>Deans Title</h3>
                    <div id="deans-title" class="form-group form-indent">
                        <p class="status">
                            <small>Provide the full, formal title of the Dean exactly as you would like it to appear.</small>
                        </p>
                        <input id="deans-title-input" name="deans-title-input" type="text" class="form-control" value="<?php echo $rowsexvalue["DEAN_TITLE"]; ?>" required>
                    </div>
                    <h3>Deans Portrait</h3>
                    <div id="deans-portrait" class="form-group form-indent">
                        <p class="status">
                            <small>Optional. Upload a high resolution portrait photo of the Dean, exactly as you want it to
                                appear. Image should be sized to 250 x 250 pixels in JPG, GIF, or PNG format. Note: if you do
                                not include a portrait, the official Univerisity Seal will be printed in its place.
                                <small>
                        </p>
                        <input id="deans-portrait-logo" name="deans-portrait-logo" type="file" class="form-control">
                    </div>
                    <h3>Deans Dean's Signature</h3>
                    <div id="deans-signature" class="form-group form-indent">
                        <p class="status">
                            <small>Optional. Upload a high resolution image of the Dean's signature, exactly as you want it to
                                appear. Image should be sized to 250 x 75 pixels in JPG, GIF, or PNG format.
                            </small>
                        </p>
                        <input id="deans-signature-logo" name="deans-signature-logo" type="file" class="form-control">
                    </div>
                    <h3>Deans College/School Companion Logo</h3>
                    <div id="deans-college-school" class="form-group form-indent">
                        <p class="status">
                            <small>Upload the official Columbia Campus Colleges and Schools Companion Logo, congruent with
                                instructions provided at http://sc.edu/toolbox/companion_Logos.php.
                            </small>
                        </p>
                        <input id="deans-college-school-logo" name="deans-college-school-logo" type="file" class="form-control">
                    </div>

                    <button id="next-tab" type="button"
                            class="btn-primary col-lg-5 col-md-7 col-sm-8 pull-right changeTab3">Continue
                    </button>
                    <button id="cancel" type="button"
                            class="btn-secondary col-lg-3 col-md-5 col-sm-6 pull-left cancelbox">Cancel
                    </button>
                </div>
            </div>

            <div class="form-group hidden tab2" id="actionlist">
                <h1>Intro & Highlight</h1>

                <div class="col-lg-12 col-sm-10 col-xs-12">
                    <h3>Introduction</h3>
                    <div id="introduction" class="form-group form-indent">
                        <p class="status"><small>Provide a brief narrative introduction of no more than 1,000 characters. This text will form the narrative introduction to the annual Outcomes Report and you may choose to follow it with highlights using the feature provider below. In the Introduction, please use only plain text.</small></p>
                        <textarea rows="5" cols="25" maxlength="1000" id="introduction-input" name="introduction-input" class="form-control" required><?php echo mybr2nl($rowsexvalue["INTRODUCTION"]); ?></textarea>
                    </div>
                    <h3>Highlights</h3>
                    <div id="highlights" class="form-group form-indent">
                        <p class="status"><small>Provide a narrative that highlights accomplishments, awards, or other outcomes. You should elaborate on these highlights elsewhere in your outcomes reporting. Content is restricted to 2,500 characters (including spaces).</small></p>
                        <textarea rows="5" cols="25" maxlength="2500" id="highlights-input" name="highlights-input" type="textarea" class="form-control"><?php echo mybr2nl($rowsexvalue["HIGHLIGHTS_NARRATIVE"]); ?></textarea>
                    </div>

                    <!--                      Edit Control-->

                    <?php if (($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead' ) AND ($rowsbpstatus['CONTENT_STATUS']=='In Progress' OR $rowsbpstatus['CONTENT_STATUS']=='Dean Rejected' OR $rowsbpstatus['CONTENT_STATUS']=='Not Started') ) { ?>
                        <button id="save" type="submit" name="savedraft"
                                class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                            Save Draft
                        </button>
                        <input type="button" id="cancelbtn" value="Cancel & Discard" class="btn-primary cancelbox pull-left">
                        <button type="submit" id="submit_approve" name="submit_approve"
                                class="btn-primary pull-right">Submit For Approval</button>

                    <?php } elseif ($_SESSION['login_role'] == 'dean' OR $_SESSION['login_role'] == 'designee') { ?>

                        <button id="save" type="submit" name="savedraft"
                                class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                            Save Draft
                        </button>
                        <input type="button" id="cancelbtn" value="Cancel & Discard" class="btn-primary cancelbox pull-left">
                        <?php if($rowsbpstatus['CONTENT_STATUS'] == 'Pending Dean Approval'): ?>
                            <input type="submit" id="approve" name="approve" value="Approve" class="btn-primary pull-right">
                            <input type="submit" id="reject" name="reject" value="Reject" class="btn-primary pull-right">
                        <?php endif; } ?>

                </div>
            </div>
        </form>
    </div>
</div>


<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>
<script src="../Resources/Library/js/tabChange.js"></script>
<script>
    $('.cancelbox').on("click", function () {
        var choice = confirm("Are you sure you want to cancel");
        if (choice == true) {
            var ayname = <?php echo json_encode($bpayname); ?> , ouabbrev = <?php echo json_encode($ouabbrev); ?>;
            $(window).attr('location', 'bphome.php?ayname=' + ayname + '&ou_abbrev=' + ouabbrev)
        }
    });
</script>

