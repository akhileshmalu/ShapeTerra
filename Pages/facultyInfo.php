<?php

$pagename = "bphome";

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

/*
 * This Page controls Academic Faculty Info.
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

$bpid = $_SESSION['bpid'];
$author = $_SESSION['login_userid'];
$ouid = $_SESSION['login_ouid'];
$bpayname= $_SESSION['bpayname'];
$contentlink_id = $_GET['linkid'];

if ($ouid == 4) {
    $ouabbrev = $_SESSION['bpouabbrev'];
} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}

$fcdev=null;
$createact=null;
$time = date('Y-m-d H:i:s');

try {
    if ($ouid == 4) {
        $sqlbroad = "SELECT BROADCAST_AY,OU_NAME,BROADCAST_STATUS,LastModified from broadcast inner join Hierarchy on broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY where BROADCAST_AY= :bpayname and Hierarchy.OU_ABBREV = :ouabbrev;";

        $resultbroad = $connection->prepare($sqlbroad);
        $resultbroad->bindParam(":bpayname", $bpayname, PDO::PARAM_STR);
        $resultbroad->bindParam(":ouabbrev", $ouabbrev, PDO::PARAM_STR);
        
        

    } else{
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

try {
    $sqlexvalue = "SELECT * FROM `AC_FacultyInfo` where OU_ABBREV = :ouabbrev AND ID_FACULTY_INFO in (select max(ID_FACULTY_INFO) from AC_FacultyInfo where OUTCOMES_AY = :bpayname group by OU_ABBREV); ";

    $resultsexvalue = $connection->prepare($sqlexvalue);
    $resultsexvalue->bindParam(":ouabbrev", $ouabbrev, PDO::PARAM_STR);
    $resultsexvalue->bindParam(":bpayname", $bpayname, PDO::PARAM_STR);
    $resultsexvalue->execute();

    $rowsexvalue = $resultsexvalue->fetch(4); 

} catch(PDOException $e) {
    error_log($e->getMessage());
    //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
}

/*
 * SQL check Status of Blueprint Content for Edit restrictions
 */
try {
    $sqlbpstatus = "SELECT CONTENT_STATUS FROM `BpContents` WHERE ID_CONTENT = :id";

    $resultbpstatus = $connection->prepare($sqlbpstatus);
    $resultbpstatus->bindParam(":id", $contentlink_id, PDO::PARAM_INT);
    $resultbpstatus->execute();

    $rowsbpstatus = $resultbpstatus->fetch(4); 

} catch(PDOException $e) {
    error_log($e->getMessage());
    //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
}


if (isset($_POST['savedraft'])) {

    $facdev = mynl2br($_POST['factextarea']);

    $createact = mynl2br($_POST['cractivity']);

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

    //  *************************** \\
    //  ********** ERROR ********** \\
    //  ** Can't execute multiple * \\
    //  ** queries in single PDO ** \\
    //  ******** statement ******** \\
    //  *************************** \\

        $sqlfacinfo = "INSERT INTO `AC_FacultyInfo` (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP, FACULTY_DEVELOPMENT, CREATIVE_ACTIVITY, AC_SUPPL_FACULTY)
 VALUES ('$ouabbrev','$bpayname','$author','$time','$facdev','$createact','$supinfopath');";

        $sqlfacinfo .= "Update  `BpContents` set CONTENT_STATUS = 'In Progress', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

        $sqlfacinfo .= "Update  `broadcast` set BROADCAST_STATUS = 'In Progress', BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= '$author', LastModified ='$time' where ID_BROADCAST = '$bpid'; ";

        if ($mysqli->multi_query($sqlfacinfo)) {

            $error[0] = "Faculty Info Added Succesfully.";
        } else {
            $error[3] = "Faculty Info could not be added.";
        }

    }
}


if(isset($_POST['submit_approval'])) {

    $contentlink_id = $_GET['linkid'];

    try {
        $sqlfacinfo .= "UPDATE `BpContents` SET CONTENT_STATUS = 'Pending Dean Approval', BP_AUTHOR= :author ,MOD_TIMESTAMP = :time  where ID_CONTENT =:contentlink_id;";

        $sqlfacinforesult = $connection->prepare($sqlfacinfo);
        $sqlfacinforesult->bindParam(":author", $author, PDO::PARAM_INT);
        $sqlfacinforesult->bindParam(":time", $time, PDO::PARAM_INT);
        $sqlfacinforesult->bindParam(":contentlink_id", $contentlink_id, PDO::PARAM_INT);

        if ($sqlfacinforesult->execute()) {
            $error[0] = "Faculty Information submitted Successfully";
        } else {
            $error[0] = "Faculty Information Could not be submitted. Please Retry.";
        }

    } catch (PDOException $e){
        error_log($e->getMessage());
        //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
    }
}

if(isset($_POST['approve'])) {

    try {
        $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Approved', BP_AUTHOR= :author ,MOD_TIMESTAMP = :time  where ID_CONTENT =:contentlink_id;";

        $sqlmissionresult = $connection->prepare($sqlmission);
        $sqlmissionresult->bindParam(":author", $author, PDO::PARAM_INT);
        $sqlmissionresult->bindParam(":time", $time, PDO::PARAM_INT);
        $sqlmissionresult->bindParam(":contentlink_id", $contentlink_id, PDO::PARAM_INT);

        if ($sqlmissionresult->execute()) {
            $error[0] = "Faculty Information Approved Successfully";
        } else {
            $error[0] = "Faculty Information Could not be Approved. Please Retry.";
        }

    } catch (PDOException $e) {
        error_log($e->getMessage());
        //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
    }
}

if(isset($_POST['reject'])) {

    try {
        $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Rejected', BP_AUTHOR= :author ,MOD_TIMESTAMP = :time  where ID_CONTENT =:contentlink_id;";

        $sqlmissionresult = $connection->prepare($sqlmission);
        $sqlmissionresult->bindParam(":author", $author, PDO::PARAM_INT);
        $sqlmissionresult->bindParam(":time", $time, PDO::PARAM_INT);
        $sqlmissionresult->bindParam(":contentlink_id", $contentlink_id, PDO::PARAM_INT);

        if ($sqlmissionresult->execute()) {
            $error[0] = "Faculty Information Rejected Successfully";
        } else {
            $error[0] = "Faculty Information Could not be Rejected. Please Retry.";
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

<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<div class="overlay hidden"></div>
<?php if (isset($_POST['submit_approval']) or isset($_POST['savedraft']) or isset($_POST['approve']) or isset($_POST['reject'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" redirect="<?php echo "bphome.php?ayname=".$rowbroad[0]."&id=".$bpid; ?> " class="end btn-primary">Close</button>
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
    </div>
    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Faculty information</h1>
            <form action="<?php echo "facultyInfo.php?linkid=".$contentlink_id; ?>" method="POST" enctype="multipart/form-data">
            <h3>Faculty Development: </h3>
            <div id="facdev" class="form-group form-indent">
                <p class="status"><small>Optional. List and describe your unit's efforts at faculty development during the Academic Year, including investments, activities, incentives, objectives, and outcomes.
                    You may paste text from other applications by copying from the source document and hitting Ctrl + V (Windows) or Cmd + V (Mac)</small></p>
                <textarea id="factextarea" name="factextarea" rows="5" cols="25" wrap="hard" class="form-control" >
                <?php 
                    echo mybr2nl($rowsexvalue['FACULTY_DEVELOPMENT']); 
                ?>
                    
                </textarea>
            </div>
            <h3>Other Activity</h3>
            <div id="createact" class="form-group form-indent">
                <p class="status"><small>Optional.  List and describe significant artistic, creative, and performance activities of faculty in your unit during the Academic Year.  List by each individual's last name, first name, name of activity, and date (month and year are sufficient).
                    You may paste text from other applications by copying from the source document and hitting Ctrl + V (Windows) or Cmd + V (Mac).</small></p>

                <textarea id="cractivity" name="cractivity" rows="5" cols="25" wrap="hard" class="form-control">
                <?php 
                    echo mybr2nl($rowsexvalue['CREATIVE_ACTIVITY']); 
                ?>        
                </textarea>
            </div>

            
            
            <h3>Supplemental Faculty Info</h3>
            <div id="suppfacinfo" class="form-group form-indent">
                <p class="status"><small>Optional.  You may attach a single PDF document, formatted to 8.5 x 11 dimensions, to provide additional detail on Faculty for the Academic Year.  This document will appear as an Appendix in the Draft Report and Final Report.</small></p>
                <input id="supinfo" type="file" name="supinfo" onchange="selectorfile(this)" class="form-control">
            </div>


                <!--                      Edit Control-->

                <?php 

                if (($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead' ) AND ($rowsbpstatus['CONTENT_STATUS']=='In progress' OR $rowsbpstatus['CONTENT_STATUS']=='Dean Rejected' OR $rowsbpstatus['CONTENT_STATUS']=='Not Started') ) { ?>

                    <button id="save" type="submit" name="savedraft"
                            class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                        Save Draft
                    </button>
                    <input type="button" id="cancelbtn" value="Cancel & Discard" class="btn-secondary cancelbpbox pull-left">
                    <button type="submit" id="submit_approve" name="submit_approve"
                            class="btn-primary pull-right">Submit For Approval</button>

                <?php } elseif ($_SESSION['login_role'] == 'dean' OR $_SESSION['login_role'] == 'designee') { ?>

                    <button id="save" type="submit" name="savedraft"
                            class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                        Save Draft
                    </button>

                    <?php if($rowsbpstatus['CONTENT_STATUS'] == 'Pending Dean Approval'): ?>
                        <input type="submit" id="approve" name="approve" value="Approve"
                               class="btn-primary pull-right">

                        <input type="submit" id="reject" name="reject" value="Reject"
                               class="btn-primary pull-right">

                    <?php endif; } ?>

            </form>

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


    $('.cancelbox').on("click", function () {
        var choice = confirm("Are you sure you want to cancel");
        if (choice == true) {
            var ayname = <?php echo json_encode($bpayname); ?> , ouabbrev = <?php echo json_encode($ouabbrev); ?>;
            $(window).attr('location', 'bphome.php?ayname=' + ayname + '&ou_abbrev=' + ouabbrev)
        }
    });

</script>
