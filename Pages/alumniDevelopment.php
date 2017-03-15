<?php

require_once ("../Resources/Includes/BpContents.php");
$alumniDeveopment = new ALUMNIDEVELOPMENT();
$alumniDeveopment->checkSessionStatus();


$message = array();
$errorflag = 0;
$bpid = $_SESSION ['bpid'];
$contentlink_id = $_GET['linkid'];
$author = $_SESSION['login_userid'];
$ouid = $_SESSION['login_ouid'];
$bpayname= $_SESSION['bpayname'];


//Menu button ctrl for back button
$BackToDashboard = true;

if ($ouid == 4) {
    $ouabbrev = $_SESSION['bpouabbrev'];
} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}

//object for Alumni Dev Table

// Blueprint Status information on title box
$resultbroad = $alumniDeveopment->BlueprintStatusDisplay();
$rowbroad = $resultbroad->fetch(PDO::FETCH_BOTH);

// Values for placeholders
$resultExValue = $alumniDeveopment->PlaceHolderValue();
$rowsExValue = $resultExValue->fetch(2);


//  SQL check Status of Blueprint Content for Edit restrictions
$resultbpstatus = $alumniDeveopment->GetStatus();
$rowsbpstatus = $resultbpstatus->fetch(2);


if (isset($_POST['savedraft'])) {
    $message[0] = $alumniDeveopment->SaveDraft();
}

if(isset($_POST['submit_approve'])) {

    $message[0] = $alumniDeveopment->SaveDraft();
    $message[0] = "Alumni Development";
    $message[0].= $alumniDeveopment->SubmitApproval();
}

if(isset($_POST['approve'])) {
    $message[0] = "Alumni Development";
    $message[0].= $alumniDeveopment->Approve();
}

if(isset($_POST['reject'])) {
    $message[0] = "Alumni Development";
    $message[0].= $alumniDeveopment->Reject();
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
        <h1 class="box-title">Alumni &amp; Development</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . "?linkid=" . $contentlink_id; ?>" method="POST"
              enctype="multipart/form-data">
            <h3>Alumni<span
                        style="color: red"><sup>*</sup></span></h3>
            <div class="form-group form-indent">
                <p class="status">Describe your unit's substantial activities, engagements, and initiatives with alumni
                    during the Academic Year. Focus should be on relationships and activities with alumni; development
                    with non-alumni and fundraising are collected separately. </p>
                <textarea name="alumni" rows="6" cols="25" wrap="hard" class="form-control"
                          required><?php echo $alumniDeveopment->mybr2nl($rowsExValue['AC_UNIT_ALUMNI']); ?></textarea>
            </div>
            <h3>Development, Fundraising, and Gifts<span
                        style="color: red"><sup>*</sup></span></h3>
            <div class="form-group form-indent">
                <p class="status">Describe your unit's substantial development initiatives and outcomes during the
                    Academic Year.</p>
                <textarea name="development" rows="6" cols="25" wrap="hard"
                          class="form-control"><?php echo $alumniDeveopment->mybr2nl($rowsExValue['AC_UNIT_DEVT_FUND_GIFTS']); ?></textarea>
            </div>
            <h3>Supplemental Info</h3>
            <div id="suppinfo" class="form-group form-indent">
                <p class="status">
                    <small>Optional. If available, you may attach a single PDF document formatted to 8.5 x 11
                        dimensions, to provide additional detail on Alumni and Development for the Academic Year.
                    </small>
                </p>
                <label for="supinfofile">Select File</label>
                <input id="supinfofile" type="file" name="supinfo" onchange="selectorfile(this)" class="form-control">
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
