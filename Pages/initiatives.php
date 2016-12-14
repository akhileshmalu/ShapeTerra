<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

/*
 * This Page controls Initiatives & Observations.
 */

session_start();
$error = array();
$errorflag =0;

require_once ("../Resources/Includes/connect.php");

$contentlink_id = $_GET['linkid'];
$name = $_SESSION['login_name'];
$ouid = $_SESSION['login_ouid'];
$bpayname= $_SESSION['bpayname'];
$ouabbrev = $_SESSION['login_ouabbrev'];

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


$sqlexvalue = "SELECT * from AC_InitObsrv where OU_ABBREV='$ouabbrev' and OUTCOMES_AY='$bpayname' ";
$resultexvalue = $mysqli->query($sqlexvalue);
$rowsexvalue = $resultexvalue -> fetch_assoc();


if (isset($_POST['savedraft'])) {

    $contentlink_id = $_GET['linkid'];
    $ugexplearn = nl2br($_POST['ugexplearning']);
    $gradexplearn = nl2br($_POST['gradexplearning']);
    $afford = nl2br($_POST['afford']);
    $reputation = nl2br($_POST['reputation']);
    $coolstuff = nl2br($_POST['coolstuff']);
    $challenges = nl2br($_POST['challenges']);




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
        $sqlinitiatives = "INSERT INTO AC_InitObsrv (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP, EXPERIENTIAL_LEARNING_UGRAD, EXPERIENTIAL_LEARNING_GRAD, AFFORDABILITY, REPUTATION_ENHANCE,COOL_STUFF, CHALLENGES,AC_SUPPL_INITIATIVES_OBSRV)
 VALUES ('$ouabbrev','$bpayname','$name','$time','$ugexplearn','$gradexplearn','$afford','$reputation','$coolstuff','$challenges','$supinfopath')
 ON DUPLICATE KEY UPDATE 
`OU_ABBREV` = VALUES(`OU_ABBREV`),`OUTCOMES_AY` = VALUES(`OUTCOMES_AY`),`OUTCOMES_AUTHOR` = VALUES(`OUTCOMES_AUTHOR`),`MOD_TIMESTAMP` = VALUES(`MOD_TIMESTAMP`),`EXPERIENTIAL_LEARNING_UGRAD` = VALUES(`EXPERIENTIAL_LEARNING_UGRAD`),
`EXPERIENTIAL_LEARNING_GRAD` =VALUES(`EXPERIENTIAL_LEARNING_GRAD`), `AFFORDABILITY`=VALUES(`AFFORDABILITY`),`REPUTATION_ENHANCE`=VALUES(`REPUTATION_ENHANCE`),`COOL_STUFF`=VALUES(`COOL_STUFF`),`CHALLENGES`=VALUES(`CHALLENGES`),`AC_SUPPL_INITIATIVES_OBSRV`=VALUES(`AC_SUPPL_INITIATIVES_OBSRV`);";

        $sqlinitiatives .="Update  BpContents set CONTENT_STATUS = 'In progress', BP_AUTHOR= '$name',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

        if ($mysqli->multi_query($sqlinitiatives)) {

            $error[0] = "Initiatives & Observations Updated Succesfully.";
        } else {
            $error[3] = "Initiatives & Observations could not be Updated.";
        }
    }
}


if(isset($_POST['submit_approval'])) {

    $contentlink_id = $_GET['linkid'];

    $sqlinitiatives .= "Update  BpContents set CONTENT_STATUS = 'Pending approval',, BP_AUTHOR= '$name',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

    if ($mysqli->query($sqlinitiatives)) {

        $error[0] = "Initiatives & Observation submitted Successfully";

    } else {
        $error[0] = "Initiatives & Observation Could not be submitted. Please Retry.";
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
            <h1 class="box-title"><?php echo $rowbroad[0]; ?></h1>
            <p class="status"><span>Org Unit Name:</span> <?php echo $_SESSION['login_ouname']; ?></p>
            <p class="status"><span>Status:</span> <?php echo $rowbroad[1]; ?></p>
        </div>




    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Initiatives & Observations</h1>
        <div id="" style="margin-top: 10px;">
            <form action="initiatives.php?linkid=<?php echo $contentlink_id;?>" method="POST" enctype="multipart/form-data">
                <label for ="explearning" ><h1>Experiential Learning: </h1></label>
                <div id="explearning" class="form-group">
                    <p><small><em>Describe your unit's initiatives, improvements, challenges, and progress with Experiential Learning at each level during the Academic Year (as applicable).</em></small></p>


                    <div  class="form-group" style="padding-left: 30px;">
                        <label for="undergrad"><h3>Undergraduate</h3></label>
                    <textarea id="undergrad" name="ugexplearning" rows="6" cols="25" wrap="hard" class="form-control"  required><?php echo $rowsexvalue['EXPERIENTIAL_LEARNING_UGRAD']; ?></textarea>
                    </div>

                    <div  class="form-group" style="padding-left: 30px;">
                        <label for="graduate"><h3>Graduate</h3></label>
                        <textarea id="graduate" name="gradexplearning" rows="6" cols="25" wrap="hard" class="form-control" ><?php echo $rowsexvalue['EXPERIENTIAL_LEARNING_GRAD']; ?></textarea>
                    </div>

                </div>

                <label for ="afford" ><h1>Affordability</h1></label>
                <div id="afford" class="form-group">
                    <p><small><em>Describe your unit's assessment of affordability and efforts to address affordability during the Academic Year.</em></small></p>

                        <textarea  name="afford" rows="6" cols="25" wrap="hard" class="form-control" ><?php echo $rowsexvalue['AFFORDABILITY']; ?></textarea>


                </div>

                <label for ="reputation" ><h1>Reputation Enhancement</h1></label>
                <div id="reputation" class="form-group">
                    <p><small><em>Describe innovations, happy accidents, good news, etc. that occurred within your unit during the Academic Year, not noted elsewhere in your reporting.
                            </em></small></p>

                    <textarea  name="reputation" rows="6" cols="25" wrap="hard" class="form-control" ><?php echo $rowsexvalue['REPUTATION_ENHANCE']; ?></textarea>

                </div>

                <label for ="coolstuff" ><h1>Cool Stuff</h1></label>
                <div id="coolstuff" class="form-group">
                    <p><small><em>Describe your unit's assessment of affordability and efforts to address affordability during the Academic Year.
                            </em></small></p>

                    <textarea  name="coolstuff" rows="6" cols="25" wrap="hard" class="form-control" ><?php echo $rowsexvalue['COOL_STUFF']; ?></textarea>

                </div>

                <label for ="challenge" ><h1>Challenges</h1></label>
                <div id="challenge" class="form-group">
                    <p><small><em>Describe challenges and resource needs you anticipate for the current and upcoming Academic Years, not noted elsewhere in your reporting - or which merit additional attention.
                            </em></small></p>

                    <textarea  name="challenges" rows="6" cols="25" wrap="hard" class="form-control" ><?php echo $rowsexvalue['CHALLENGES']; ?></textarea>

                </div>

                <label for ="suppinfo" ><h1>Supplemental Info</h1></label>
                <div id="suppinfo" class="form-group">
                    <p><small><em>Optional.  If available, you may attach a single PDF document formatted to 8.5 x 11 dimensions, to provide additional detail on Initiatives & Observations for the Academic Year.
                            </em></small></p>

                    <label for="supinfofile">Select File
                    </label>
                    <input id="supinfofile" type="file" name="supinfo" onchange="selectorfile(this)" class="form-control">
                </div>

                <input type="button" id="cancelbtn" value="Cancel & Discard" class="btn-primary cancelbox pull-left">
                <input type="submit" id="approve" name="submit_approval" value="Submit For Approval" class="btn-primary pull-right">
                <input type="submit" id="savebtn" name="savedraft" value="Save Draft" class="btn-secondary pull-right">
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
    })
</script>
<script src="../Resources/Library/js/tabchange.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script src="../Resources/Library/js/outcomecntrl.js"></script>
<script>
    function selectorfile(selected) {
        var b = $(selected).val().substr(12);
        alert(b + " is selected.");
    }
</script>
