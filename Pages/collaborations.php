<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

/*
 * This Page controls Initiatives & Observations.
 */

require_once("../Resources/Includes/Initialize.php");
$initalize = new Initialize();
$initalize->checkSessionStatus();

$message = array();
$errorflag = 0;
$BackToDashboard = true;

require_once ("../Resources/Includes/BpContents.php");

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

//Object for Campus Climate Table
$BpContent = new COLLABORATION();

//  Blueprint Status information on title box
$resultbroad = $BpContent->BlueprintStatusDisplay();
$rowbroad = $resultbroad->fetch(4);


// Values for placeholders
$resultexvalue = $BpContent->PlaceHolderValue();
$rowsExValue = $resultexvalue->fetch(4);

// SQL check Status of Blueprint Content for Edit restrictions
$resultbpstatus = $BpContent->GetStatus();
$rowsbpstatus = $resultbpstatus->fetch(2);

if (isset($_POST['savedraft'])) {
    $message[0] = $BpContent->SaveDraft();
}

if(isset($_POST['submit_approve'])) {
    $message[0] = "Collaboration";
    $message[0].= $BpContent->SubmitApproval();
}

if(isset($_POST['approve'])) {
    $message[0] = "Collaboration";
    $message[0].= $BpContent->Approve();
}

if(isset($_POST['reject'])) {
    $message[0] = "Collaboration";
    $message[0].= $BpContent->Reject();
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
        <h1 class="box-title">Collaborations</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . "?linkid=" . $contentlink_id; ?>" method="POST" enctype="multipart/form-data">
            <h3>Internal Collaborations </h3>
            <div class="form-group form-indent">
                <p class="status">List your Academic Unit's most significant academic collaborations and multidisciplinary efforts that are internal to the University.  Details should be omitted; list by name only. </p>
                <textarea name="internalcollaborators" rows="6" cols="25" wrap="hard" class="form-control"
                          required><?php echo $initalize->mybr2nl($rowsExValue['COLLAB_INTERNAL']); ?></textarea>
            </div>
            <h3>External Collaborations</h3>
            <div class="form-group form-indent">
                <p class="status">List your Academic Unit's most significant academic collaborations and multidisciplinary efforts that are external to the University.  Details should be omitted; list by name only. </p>
                <textarea name="externalcollaborators" rows="6" cols="25" wrap="hard"
                          class="form-control"><?php echo $initalize->mybr2nl($rowsExValue['COLLAB_EXTERNAL']); ?></textarea>
            </div>
            <h3>Other Collaborations</h3>
            <div class="form-group form-indent">
                <p class="status">
                    <small>List your Academic Unit's most significant academic collaborations and multidisciplinary efforts that are not otherwise accounted for as Internal or External Collaborations. Details should be omitted; list by name only.
                    </small>
                </p>
                <textarea name="othercollaborators" rows="6" cols="25" wrap="hard"
                          class="form-control"><?php echo $initalize->mybr2nl($rowsExValue['COLLAB_OTHER']); ?></textarea>
            </div>

            <h3>Supplemental Info</h3>
            <div id="suppfacinfo" class="form-group form-indent">
                <p class="status"><small>Optional.  If available, you may attach a single PDF document formatted to 8.5 x 11 dimensions, to provide additional detail on Collaborations for the Academic Year.</small></p>
                <input id="supinfo" type="file" name="supinfo" onchange="selectorfile(this)" class="form-control">
            </div>

            <!--                      Edit Control-->

            <?php if (($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') AND ($rowsbpstatus['CONTENT_STATUS'] == 'In Progress' OR $rowsbpstatus['CONTENT_STATUS'] == 'Dean Rejected' OR $rowsbpstatus['CONTENT_STATUS'] == 'Not Started')) { ?>
                <button id="save" type="submit" name="savedraft"
                        onclick="//$('#approve').removeAttr('disabled');$('#save').addClass('hidden');"
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
