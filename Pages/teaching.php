<?php

/*
 * This Page controls Teaching.
 */

require_once ("../Resources/Includes/BpContents.php");
$teaching = new TEACHING();
$teaching->checkSessionStatus();

$message = array();
$errorflag = 0;
$BackToDashboard = true;


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

//  Blueprint Status information on title box
$resultbroad = $teaching->BlueprintStatusDisplay();
$rowbroad = $resultbroad->fetch(4);

// Values for placeholders
$resultexvalue = $teaching->PlaceHolderValue("AC_Teaching","ID_FACULTY_INFO");
$rowsExValue = $resultexvalue->fetch(4);

// SQL check Status of Blueprint Content for Edit restrictions
$resultbpstatus = $teaching->GetStatus();
$rowsbpstatus = $resultbpstatus->fetch(2);

if (isset($_POST['savedraft'])) {
    $message[0] = $teaching->SaveDraft();
}

if(isset($_POST['submit_approve'])) {
    $message[0] = $teaching->SaveDraft();
    $message[0] = "Teaching";
    $message[0].= $teaching->SubmitApproval();
}

if(isset($_POST['approve'])) {
    $message[0] = "Teaching";
    $message[0].= $teaching->Approve();
}

if(isset($_POST['reject'])) {
    $message[0] = "Teaching";
    $message[0].= $teaching->Reject();
}


require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<div class="overlay hidden"></div>
<?php if (isset($_POST['submit_approve']) or isset($_POST['savedraft']) or isset($_POST['approve']) or isset($_POST['reject'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
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
        <h1 class="box-title">Teaching</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . "?linkid=" . $contentlink_id; ?>" method="POST" enctype="multipart/form-data">
            
            <h3>Faculty to Student Ratio<span
                        style="color: red"><sup>*</sup></span></h3>
            <div class="form-group form-indent">
                <p class="status">Please calculate and provide the ratio in the space provided based on the formula: <br /> (Total FT Students + 1/3PT Students) / (Total FT Instructional Faculty +1/3 PT instructional Faculty)+Staff who teach. </p>
                <textarea name="FACULTY_STUDENT_RATIO" rows="1" cols="25" wrap="hard"
                          class="form-control" required><?php echo $teaching->mybr2nl($rowsExValue['FACULTY_STUDENT_RATIO']); ?></textarea>
            </div>
            <h3></h3>
            <div class="form-group form-indent">
                <p class="status">
                    <small>What does the quantitative data reflect on the faculty student ratio?  Do you agree with the data?  Why or why not?  Please describe your plans for the future to impact this ratio. 
                    </small>
                </p>
                <textarea name="FACULTY_STUDENT_RATIO_NARRTV" rows="6" cols="25" wrap="hard"
                          class="form-control" required><?php echo $teaching->mybr2nl($rowsExValue['FACULTY_STUDENT_RATIO_NARRTV']); ?></textarea>
                
            </div>

            <!--                      Edit Control-->
            <?php require_once ("../Resources/Includes/control.php"); ?>

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
