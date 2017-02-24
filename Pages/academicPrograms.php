<?php

/*
 * This Page controls Academic Program Module
 */
require_once("../Resources/Includes/Initialize.php");
$initalize = new Initialize();
$initalize->checkSessionStatus();
require_once ("../Resources/Includes/BpContents.php");

$message = array();
$errorflag =0;
$bpid = $_SESSION ['bpid'];
$contentlink_id = $_GET['linkid'];
$author = $_SESSION['login_userid'];
$ouid = $_SESSION['login_ouid'];
$bpayname = $_SESSION['bpayname'];
$time = date('Y-m-d H:i:s');

//Menu control Back button
$BackToDashboard = true;

if ($ouid == 4) {
    $ouabbrev = $_SESSION['bpouabbrev'];
} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}

//Object for Academic Program table
$AcademicProgram = new ACADEMICPROGRAM();

// Blueprint Status information on title box
$resultbroad = $AcademicProgram->BlueprintStatusDisplay();
$rowbroad = $resultbroad->fetch(PDO::FETCH_BOTH);

// Values for placeholders
$resultExValue = $AcademicProgram->PlaceHolderValue();
$rowsExValue = $resultExValue->fetch(2);


//  SQL check Status of Blueprint Content for Edit restrictions
$resultbpstatus = $AcademicProgram->GetStatus();
$rowsbpstatus = $resultbpstatus->fetch(2);

if (isset($_POST['savedraft'])) {
    $message[0] = $AcademicProgram->SaveDraft();
}

if(isset($_POST['submit_approve'])) {
    $message[0] = "Academic Program";
    $message[0].= $AcademicProgram->SubmitApproval();

}

if(isset($_POST['approve'])) {
    $message[0] = "Academic Program";
    $message[0].= $AcademicProgram->Approve();
}

if (isset($_POST['reject'])) {
    $message[0] = "Academic Program";
    $message[0].= $AcademicProgram->Reject();
}

require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<div class="overlay hidden"></div>
<?php if (isset($_POST['savedraft']) or isset($_POST['submit_approve']) or isset($_POST['approve']) or isset
    ($_POST['reject'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" redirect="bphome.php?ayname=<?php echo $rowbroad[0]."&id=".$bpid; ?>" class="end
        btn-primary">Close</button>
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
        <h1 class="box-title">Academic Programs</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?linkid=' . $contentlink_id; ?>" method="POST"
              enctype="multipart/form-data">
            <h3>Program Rankings</h3>
            <div class="form-group form-indent">
                <p class="status">List any academic programs that were nationally ranked during the Academic Year
                    . For each, provide the formal name of the academic program followed by the name of the
                    organization that issued the ranking, the date of notification, effective date range, and
                    notes. </p>
                <textarea name="programranking" rows="6" cols="25" wrap="hard" class="form-control"
                          required><?php echo $initalize->mybr2nl($rowsExValue['PROGRAM_RANKINGS']); ?></textarea>
            </div>
            <h3>Instructional Modalities</h3>
            <div class="form-group form-indent">
                <p class="status">List and describe innovations and changes to Instructional Modalities in your
                    unit's programmatic and course offerings that were enacted during the Academic Year. </p>
                <textarea name="instructionalmodalities" rows="6" cols="25" wrap="hard" class="form-control"
                ><?php echo $initalize->mybr2nl($rowsExValue['INSTRUCT_MODALITIES']); ?></textarea>
            </div>
            <h3>Program Launches</h3>
            <div class="form-group form-indent">
                <p class="status">
                    <small>List any Academic Programs that were newly launched during the Academic
                        Year; those that have received required approvals but which have not yet enrolled
                        students should not be included. For each, list the formal name of the academic program
                        and the responsible department.
                    </small>
                </p>
                <textarea name="launch" rows="6" cols="25" wrap="hard" class="form-control"><?php echo $initalize->mybr2nl
                    ($rowsExValue['PROGRAM_LAUNCHES']); ?></textarea>
            </div>
            <h3>Program Terminations</h3>
            <div class="form-group form-indent">
                <p class="status">
                    <small>List any Academic Programs that were newly terminated or discontinued
                        during the Academic Year as follows: for each clearly indicate whether the decision to
                        terminate was made during the Academic Year or whether the program ceased having any
                        enrolled students during the Academic Year.
                    </small>
                </p>
                <textarea name="programterminators" rows="6" cols="25" wrap="hard" class="form-control"><?php
                    echo $initalize->mybr2nl($rowsExValue['PROGRAM_TERMINATIONS']); ?></textarea>
            </div>
            <h3>Supplemental Info</h3>
            <div id="suppinfo" class="form-group form-indent">
                <p class="status">
                    <small>Optional. If available, you may attach a single PDF document formatted
                        to 8.5 x 11 dimensions, to provide additional detail on Academic Programs for the
                        Academic Year.
                    </small>
                </p>
                <label for="supinfofile">Select File</label>
                <input id="supinfofile" type="file" name="supinfo" onchange="selectorfile(this)"
                       class="form-control">
            </div>

            <!--                      Edit Control-->

            <?php if (($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') AND
                ($rowsbpstatus['CONTENT_STATUS'] == 'In Progress' OR $rowsbpstatus['CONTENT_STATUS'] == 'Dean Rejected'
                    OR $rowsbpstatus['CONTENT_STATUS'] == 'Not Started')
            ) { ?>
                <button id="save" type="submit" name="savedraft"
                        class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                    Save Draft
                </button>
                <input type="button" id="cancelbtn" value="Cancel & Discard" class="btn-primary cancelbpbox pull-left">
                <button type="submit" id="submit_approve" name="submit_approve"
                        class="btn-primary pull-right">Submit For Approval
                </button>

            <?php } elseif ($_SESSION['login_role'] == 'dean' OR $_SESSION['login_role'] == 'designee') { ?>

                <input id="save" type="submit" name="savedraft"
                       class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right" value="Save Draft">

                <input type="button" id="cancelbtn" value="Cancel & Discard" class="btn-primary cancelbpbox
                    pull-left">
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
