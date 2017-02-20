<?php

/*
 * This Page controls Initiatives & Observations.
 */

require_once ("../Resources/Includes/initalize.php");
$initalize = new Initialize();
$initalize->checkSessionStatus();

require_once ("../Resources/Includes/BpContents.php");

$message = array();
$errorflag = 0;
$bpid = $_SESSION ['bpid'];
$bpayname = $_SESSION['bpayname'];
$contentlink_id = $_GET['linkid'];

$BackToDashboard = true;

$ouid = $_SESSION['login_ouid'];
if ($ouid == 4) {
    $ouabbrev = $_SESSION['bpouabbrev'];
} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}

//Object for executive Summary Table
$CampusClimate = new EXECUTIVESUMCLASS();


//  Blueprint Status information on title box
$resultbroad = $CampusClimate->BlueprintStatusDisplay();
$rowbroad = $resultbroad->fetch(4);


// Values for placeholders
$resultexvalue = $CampusClimate->PlaceHolderValue();
$rowsExValue = $resultexvalue->fetch(4);

// SQL check Status of Blueprint Content for Edit restrictions
$resultbpstatus = $CampusClimate->GetStatus();

$rowsbpstatus = $resultbpstatus->fetch(2);


if (isset($_POST['savedraft'])) {

    $message[0] = $CampusClimate->SaveDraft();

}

if (isset($_POST['submit_approve'])) {
    $message[0] = "Executive Summary";
    $message[0].= $CampusClimate->SubmitApproval();

}

if (isset($_POST['approve'])) {
    $message[0] = "Executive Summary";
    $message[0].= $CampusClimate->Approve();
}

if (isset($_POST['reject'])) {
    $message[0] = "Executive Summary";
    $message[0].= $CampusClimate->Reject();


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
<?php if (isset($_POST['savedraft']) or isset($_POST['submit_approve']) or isset($_POST['approve']) or
    isset($_POST['reject'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" redirect="bphome.php?ayname=<?php echo $bpayname."&id=".$bpid; ?>"
                class="end btn-primary">Close</button>
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
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?linkid=' . $contentlink_id; ?>" method="POST"
              enctype="multipart/form-data">
            <div class="form-group tab1 active" id="actionlist">
                <h1>Details</h1>
                <div class="col-xs-12">

                    <h3>College/School Name</h3>
                    <div id="college-school" class="form-group form-indent">
                        <p class="status">
                            <small>Provide the formal name of the College/School exactly as you want it to appear.
                            </small>
                        </p>
                        <input id="college-school-input" name="college-school-input" type="text" class="form-control"
                               value="<?php echo $rowsExValue["UNIT_NAME"]; ?>" required>
                    </div>

                    <h3>Dean's Name</h3>
                    <div id="deans-name" class="form-group form-indent">
                        <p class="status">
                            <small>Provide the formal name of the Dean exactly as you want it to appear.</small>
                        </p>
                        <input id="deans-name-input" name="deans-name-input" type="text" class="form-control"
                               value="<?php echo $rowsExValue["DEAN_NAME_PRINT"]; ?>" required>
                    </div>
                    <h3>Dean's Title</h3>
                    <div id="deans-title" class="form-group form-indent">
                        <p class="status">
                            <small>Provide the full, formal title of the Dean exactly as you would like it to appear.
                            </small>
                        </p>
                        <input id="deans-title-input" name="deans-title-input" type="text" class="form-control"
                               value="<?php echo $rowsExValue["DEAN_TITLE"]; ?>" required>
                    </div>
                    <h3>Dean's Portrait</h3>
                    <div id="deans-portrait" class="form-group form-indent">
                        <p class="status">
                            <small>Optional. Upload a high resolution portrait photo of the Dean, exactly as you want it
                                to
                                appear. Image should be sized to 250 x 250 pixels in JPG, GIF, or PNG format. Note: if
                                you do
                                not include a portrait, the official Univerisity Seal will be printed in its place.
                                <small>
                        </p>
                        <input id="deans-portrait-logo" name="deans-portrait-logo" type="file"
                               onchange="selectorfile(this)" class="form-control">
                    </div>
                    <h3>Dean's Signature</h3>
                    <div id="deans-signature" class="form-group form-indent">
                        <p class="status">
                            <small>Optional. Upload a high resolution image of the Dean's signature, exactly as you want
                                it to
                                appear. Image should be sized to 250 x 75 pixels in JPG, GIF, or PNG format.
                            </small>
                        </p>
                        <input id="deans-signature-logo" name="deans-signature-logo" type="file"
                               onchange="selectorfile(this)" class="form-control">
                    </div>
                    <h3>College/School Companion Logo</h3>
                    <div id="deans-college-school" class="form-group form-indent">
                        <p class="status">
                            <small>Upload the official Columbia Campus Colleges and Schools Companion Logo, congruent
                                with
                                instructions provided at http://sc.edu/toolbox/companion_Logos.php.
                            </small>
                        </p>
                        <input id="deans-college-school-logo" name="deans-college-school-logo" type="file"
                               onchange="selectorfile(this)" class="form-control">
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
                        <p class="status">
                            <small>Provide a brief narrative introduction of no more than 725 characters (including
                                spaces). This text will form the narrative introduction to the annual Outcomes Report
                                and you may choose to follow it with highlights using the feature provider below. In the
                                Introduction, please use only plain text.
                            </small>
                        </p>
                        <textarea rows="5" cols="25" maxlength="725" id="introduction-input" name="introduction-input"
                                  type="textarea" class="form-control wordCount"
                                  required><?php echo $initalize->mybr2nl($rowsExValue["INTRODUCTION"]); ?></textarea>
                    </div>
                    <h3>Highlights</h3>
                    <div id="highlights" class="form-group form-indent">
                        <p class="status">
                            <small>Provide a narrative that highlights accomplishments, awards, or other outcomes. You
                                should elaborate on these highlights elsewhere in your outcomes reporting. Content is
                                restricted to 525 characters (including spaces).
                            </small>
                        </p>
                        <textarea rows="5" cols="25" maxlength="525" id="highlights-input" name="highlights-input"
                                  type="textarea"
                                  class="form-control wordCount"
                        ><?php echo $initalize->mybr2nl($rowsExValue["HIGHLIGHTS_NARRATIVE"]); ?></textarea>
                    </div>

                    <!--                      Edit Control-->

                    <?php

                    if (($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') AND
                        ($rowsbpstatus['CONTENT_STATUS'] == 'In Progress' OR $rowsbpstatus['CONTENT_STATUS'] == 'Dean
                         Rejected' OR $rowsbpstatus['CONTENT_STATUS'] == 'Not Started')) { ?>
                        <button id="save" type="submit" name="savedraft" class="btn-primary  pull-right">
                            Save Draft
                        </button>
                        <input type="button" id="cancelbtn" value="Cancel & Discard"
                               class="btn-primary cancelbox pull-left">
                        <button type="submit" id="submit_approve" name="submit_approve" class="btn-primary pull-right">
                            Submit For Approval
                        </button>

                    <?php } elseif ($_SESSION['login_role'] == 'dean' OR $_SESSION['login_role'] == 'designee') { ?>

                        <button id="save" type="submit" name="savedraft" class="btn-primary  pull-right">
                            Save Draft
                        </button>
                        <input type="button" id="cancelbtn" value="Cancel & Discard"
                               class="btn-primary cancelbox pull-left">
                        <?php if ($rowsbpstatus['CONTENT_STATUS'] == 'Pending Dean Approval'): ?>
                            <input type="submit" id="approve" name="approve" value="Approve"
                                   class="btn-primary pull-right">
                            <input type="submit" id="reject" name="reject" value="Reject"
                                   class="btn-primary pull-right">
                        <?php endif;
                    } ?>
                </div>
            </div>
        </form>
    </div>
</div>


<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>
<!--<script src="../Resources/Library/js/ckeditor.js" type="text/javascript"></script>-->
<script src="../Resources/Library/js/tabChange.js"></script>
<script>
    $('.cancelbox').on("click", function () {
        var choice = confirm("Are you sure you want to cancel");
        if (choice == true) {
            var ayname = <?php echo json_encode($bpayname); ?> , ouabbrev = <?php echo json_encode($ouabbrev); ?>;
            $(window).attr('location', 'bphome.php?ayname=' + ayname + '&ou_abbrev=' + ouabbrev)
        }
    });
    function selectorfile(selected) {
        var doc, image;
        var filename = $(selected).val();
        var extention = $(selected).val().substr(filename.lastIndexOf('.') + 1).toLowerCase();
        var allowedext = ['gif', 'jpg', 'png'];

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
