<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

/*
 * This Page controls Initiatives & Observations.
 */

require_once ("../Resources/Includes/initialize.php");
require_once ("../Resources/Includes/BpContents.php");
$initalize = new Initialize();
$initalize->checkSessionStatus();

$message = array();
$errorflag =0;
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

$Initiatives = new INITIATIVES();

//  Blueprint Status information on title box
$resultbroad = $Initiatives->BlueprintStatusDisplay();
$rowbroad = $resultbroad->fetch(4);


// Values for placeholders
$resultexvalue = $Initiatives->PlaceHolderValue();
$rowsexvalue = $resultexvalue->fetch(4);

// SQL check Status of Blueprint Content for Edit restrictions
$resultbpstatus = $Initiatives->GetStatus();
$rowsbpstatus = $resultbpstatus->fetch(4);


if (isset($_POST['savedraft'])) {
    $message[0] = $Initiatives->SaveDraft();
}


if(isset($_POST['submit_approve'])) {
    $message[0] = "Initiatives & Observations";
    $message[0].= $Initiatives->SubmitApproval();
}

if(isset($_POST['approve'])) {

    $message[0] = "Initiatives & Observations";
    $message[0].= $Initiatives->Approve();
}

if(isset($_POST['reject'])) {

    $message[0] = "Initiatives & Observations";
    $message[0].= $Initiatives->Reject();
}


require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<div class="overlay hidden"></div>
<?php if (isset($_POST['submit_approve'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" redirect="bphome.php?ayname=<?php echo $rowbroad[0]; ?>" class="end btn-primary">Close</button>
    </div>
<?php } ?>
<?php if (isset($_POST['savedraft'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
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
            <h1 id="ayname" class="box-title"><?php echo $rowbroad[0]; ?></h1>
            <p class="status"><span>Org Unit Name:</span> <?php echo $rowbroad[1]; ?></p>
            <p id="ouabbrev" class="hidden"><?php echo $ouabbrev;  ?></p>
            <p class="status"><span>Status:</span> <?php echo $rowbroad[2]; ?></p>
        </div>
    </div>
    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Initiatives & Observations</h1>
        <div id="" style="margin-top: 10px;">
            <form action="initiatives.php?linkid=<?php echo $contentlink_id;?>" method="POST" enctype="multipart/form-data">
                <label for ="explearning" ><h1 class="box-title">Experiential Learning: </h1></label>
                <div id="explearning" class="form-group">
                    <p class="status"><small>Describe your unit's initiatives, improvements, challenges, and progress with Experiential Learning at each level during the Academic Year (as applicable).</small></p>
                    <h3>Undergraduate</h3>
                    <div class="form-group form-indent">
                      <textarea id="undergrad" name="ugexplearning" rows="6" cols="25" wrap="hard" class="form-control"  required><?php echo $initalize->mybr2nl($rowsexvalue['EXPERIENTIAL_LEARNING_UGRAD']); ?></textarea>
                      <div class="checkbox">
                        <label for="optionalCheck">
                            <input type="checkbox" name="optionalCheck" id="ugexplearning"/> No response to this item
                        </label>
                    </div>
                    </div>
                    <h3>Graduate</h3>
                    <div class="form-group form-indent">
                        <textarea id="graduate" name="gradexplearning" rows="6" cols="25" wrap="hard" class="form-control" ><?php echo $initalize->mybr2nl($rowsexvalue['EXPERIENTIAL_LEARNING_GRAD']); ?></textarea>
                        <div class="checkbox">
                            <label for="optionalCheck">
                                <input type="checkbox" name="optionalCheck" id="gradexplearning"/> No response to this item
                            </label>
                        </div>
                    </div>
                </div>
                <h3>Affordability</h3>
                <div id="" class="form-group form-indent">
                    <p class="status"><small>Describe your unit's assessment of affordability and efforts to address affordability during the Academic Year.</small></p>
                    <textarea  name="afford" rows="6" cols="25" wrap="hard" class="form-control" ><?php echo $initalize->mybr2nl($rowsexvalue['AFFORDABILITY']); ?></textarea>
                    <div class="checkbox">
                        <label for="optionalCheck">
                            <input type="checkbox" name="optionalCheck" id="afford"/> No response to this item
                        </label>
                    </div>
                </div>
                <h3>Reputation Enhancement</h3>
                <div id="reputation" class="form-group form-indent">
                    <p class="status"><small>Describe innovations, happy accidents, good news, etc. that occurred within your unit during the Academic Year, not noted elsewhere in your reporting.</small></p>
                    <textarea  name="reputation" rows="6" cols="25" wrap="hard" class="form-control" ><?php echo $initalize->mybr2nl($rowsexvalue['REPUTATION_ENHANCE']); ?></textarea>
                    <div class="checkbox">
                        <label for="optionalCheck">
                            <input type="checkbox" name="optionalCheck" id="reputation"/> No response to this item
                        </label>
                    </div>
                </div>
                <h3>Challenges</h3>
                <div id="challenge" class="form-group form-indent">
                    <p class="status"><small>Describe challenges and resource needs you anticipate for the current and upcoming Academic Years, not noted elsewhere in your reporting - or which merit additional attention.</small></p>
                    <textarea  name="challenges" rows="6" cols="25" wrap="hard" class="form-control" ><?php echo $initalize->mybr2nl($rowsexvalue['CHALLENGES']); ?></textarea>
                    <div class="checkbox">
                        <label for="optionalCheck">
                            <input type="checkbox" name="optionalCheck" id="challenges"/> No response to this item
                        </label>
                    </div>
                </div>
                <h3>Supplemental Info</h3>
                <div id="suppinfo" class="form-group form-indent">
                    <p class="status"><small>Optional.  If available, you may attach a single PDF document formatted to 8.5 x 11 dimensions, to provide additional detail on Initiatives & Observations for the Academic Year.</small></p>
                    <label for="supinfofile">Select File</label>
                    <input id="supinfofile" type="file" name="supinfo" onchange="selectorfile(this)" class="form-control">
                </div>
                <!--                        Reviewer Edit Control
                <?php //if ($_SESSION['login_right'] != 1): ?>

                <input type="button" id="cancelbtn" value="Cancel & Discard" class="btn-primary cancelbpbox pull-left">
                <input type="submit" id="approve" name="submit_approval" value="Submit For Approval" class="btn-primary pull-right">
                <input type="submit" id="savebtn" name="savedraft" value="Save Draft" class="btn-secondary pull-right">

                <?php //endif; ?>
                -->

                <!--                      Edit Control-->

                <?php if (($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead' ) AND ($rowsbpstatus['CONTENT_STATUS']=='In Progress' OR $rowsbpstatus['CONTENT_STATUS']=='Dean Rejected' OR $rowsbpstatus['CONTENT_STATUS']=='Not Started') ) { ?>
                    <button id="save" type="submit" name="savedraft"
                            onclick="//$('#approve').removeAttr('disabled');$('#save').addClass('hidden');"
                            class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                        Save Draft
                    </button>
                    <input type="button" id="cancelbtn" value="Cancel & Discard" class="btn-primary cancelbpbox pull-left">
                    <button type="submit" id="submit_approve" name="submit_approve"
                            class="btn-primary pull-right">Submit For Approval</button>

                <?php } elseif ($_SESSION['login_role'] == 'dean' OR $_SESSION['login_role'] == 'designee') { ?>

                    <button id="save" type="submit" name="savedraft"
                            class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                        Save Draft
                    </button>

                    <?php if($rowsbpstatus['CONTENT_STATUS'] == 'Pending Dean Approval'): ?>
                        <input type="submit" id="approve" name="approve" value="Approve" class="btn-primary pull-right">
                        <input type="submit" id="reject" name="reject" value="Reject" class="btn-primary pull-right">
                    <?php endif; } ?>

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
