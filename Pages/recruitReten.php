
<?php

/*
 * This Page controls Initiatives & Observations.
 */
require_once ("../Resources/Includes/BpContents.php");
$recruitNretention = new RECRUITRETENTION();
$recruitNretention->checkSessionStatus();

$message = array();
$errorflag = 0;
$BackToDashboard = true;

$bpid = $_SESSION ['bpid'];
$contentlink_id = $_GET['linkid'];
$author = $_SESSION['login_userid'];
$ouid = $_SESSION['login_ouid'];
$bpayname = $_SESSION['bpayname'];


if ($ouid == 4) {
    $ouabbrev = $_SESSION['bpouabbrev'];
} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}

//  Blueprint Status information on title box
$resultbroad = $recruitNretention->BlueprintStatusDisplay();
$rowbroad = $resultbroad->fetch(4);


// Values for placeholders
$resultexvalue = $recruitNretention->PlaceHolderValue();
$rowsExValue = $resultexvalue->fetch(4);

// SQL check Status of Blueprint Content for Edit restrictions
$resultbpstatus = $recruitNretention->GetStatus();
$rowsbpstatus = $resultbpstatus->fetch(2);

if (isset($_POST['savedraft'])) {
    $message[0] = $recruitNretention->SaveDraft();
}

if(isset($_POST['submit_approve'])) {
    $message[0] = "Recruitment and retention";
    $message[0].= $recruitNretention->SubmitApproval();
}

if(isset($_POST['approve'])) {
    $message[0] = "Recruitment and retention";
    $message[0].= $recruitNretention->Approve();
}

if(isset($_POST['reject'])) {
    $message[0] = "Recruitment and retention";
    $message[0].= $recruitNretention->Reject();
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
        <h1 class="box-title">Recruit Retention</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . "?linkid=" . $contentlink_id; ?>" method="POST">
            
            <h3>Student Recruitment Efforts<span
                        style="color: red"><sup>*</sup></span></h3>
            <div class="form-group form-indent">
                <p class="status">List and describe your unit's efforts at recruiting students into your programs.  Please provide specific actions. </p>
                <textarea name="recruitment" rows="6" cols="25" wrap="hard"
                          class="form-control" required><?php echo $recruitNretention->mybr2nl($rowsExValue['STUDENT_RECRUITMENT_EFFORTS']); ?></textarea>
            </div>
            <h3>Student Retention Efforts<span
                        style="color: red"><sup>*</sup></span></h3>
            <div class="form-group form-indent">
                <p class="status">
                    <small>List and describe your unit's efforts at retaining the current students in your programs.  Please provide specific actions.
                    </small>
                </p>
                <textarea name="retention" rows="6" cols="25" wrap="hard"
                          class="form-control" required><?php echo $recruitNretention->mybr2nl($rowsExValue['STUDENT_RETENTION_EFFORTS']); ?></textarea>
                
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
</script>
<script src="../Resources/Library/js/tabAlert.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script src="../Resources/Library/js/outcomecntrl.js"></script>
