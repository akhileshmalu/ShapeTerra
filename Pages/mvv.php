<?php

/*
 * This Page controls Mission Vision & Value Screen.
 */

require_once ("../Resources/Includes/initalize.php");
$initalize = new Initialize();
$initalize->checkSessionStatus();

$message = array();
$errorflag = 0;
$BackToDashboard = true;


/*
 * Connection to DataBase.
 */

  require_once ("../Resources/Includes/BpContents.php");

/*
 * Local & Session variable Initialization
 */
$bpid = $_SESSION['bpid'];
$contentlink_id = $_GET['linkid'];
$bpayname = $_SESSION['bpayname'];

$prevbpid = $initalize->stringtoid($bpayname);
$prevbpayname = $initalize->idtostring($prevbpid - 101);
$ouid = $_SESSION['login_ouid'];
$outype = $_SESSION['login_outype'];

$date = date("Y-m-d");
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_userid'];


if ($outype == "Administration" OR $outype == "Service Unit" ) {
    $ouabbrev = $_SESSION['bpouabbrev'];
} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}


//Object for executive Summary Table
$mvv = new MVV();


//  Blueprint Status information on title box
$resultbroad = $mvv->BlueprintStatusDisplay();
$rowbroad = $resultbroad->fetch(4);

// Values for placeholders
$resultexvalue = $mvv->PlaceHolderValue();
$rowsmission = $resultexvalue->fetch(4);

// SQL check Status of Blueprint Content for Edit restrictions
$resultbpstatus = $mvv->GetStatus();
$rowsbpstatus = $resultbpstatus->fetch(2);

if (isset($_POST['submit'])) {

    $message[0] = $mvv->SaveDraft();

}

if (isset($_POST['submit_approve'])) {
    $message[0] = "Mission, Vision, & Values";
    $message[0].= $mvv->SubmitApproval();

}

if (isset($_POST['approve'])) {
    $message[0] = "Mission, Vision, & Values";
    $message[0].= $mvv->Approve();
}

if (isset($_POST['reject'])) {
    $message[0] = "Mission, Vision, & Values";
    $message[0].= $mvv->Reject();


}

require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrapTable.css"/>
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.min.css"/>
<link rel="stylesheet" href="Css/grid.css" title="openJsGrid"/>
<script src="../Resources/Library/js/root.js"></script>
<script src="../Resources/Library/js/grid.js"></script>
<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>
<link href="Css/templateTabs.css" rel="stylesheet" type="text/css"/>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>

<?php if (isset($_POST['submit']) || isset($_POST['submit_approve']) || isset($_POST['approve'])  ) { ?>
    <div class="overlay hidden"></div>
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
        <h1 id="title">Blueprint Home</h1>
    </div>
    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 id="ayname" class="box-title"><?php echo $rowbroad[0]; ?></h1>
        <p class="status"><span>Status:</span> <?php echo $rowbroad[2]; ?></p>
        <p id="ouabbrev" class="hidden"><?php echo $ouabbrev; ?></p>
        <p class="status"><span>Last Modified:</span> <?php echo date("F j, Y, g:i a", strtotime($rowbroad[3])); ?></p>
    </div>
    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Mission, Vision & Values</h1>
        <div id="headtitle">
            <form action="<?php echo $_SERVER['PHP_SELF'] . "?linkid=" . $contentlink_id ?>" method="POST"
                  class="mission">
                <div class="mission-status-alert hidden text-center">
                    <h1>Mission Updated Successfully</h1>
                    <a href="bphome.php?ayname=<?php echo $rowbroad[0]; ?>" class="btn-secondary pull-left">Back To
                        Dashboard</a>
                    <a href="#" class="mission-next-tab btn-primary" onclick="return false;">Next Tab</a>
                </div>
                <p class="status">
                    <small><em>Instruction: Enter your BluePrint content for the Academic Year indicated above.The
                            components below are highest level statements of
                            what <?php echo $_SESSION['login_ouname']; ?> considers foundation to your goals &
                            related outcomes.</em></small>
                </p>
                <h3>Mission Statement<span
                        style="color: red"><sup>*</sup></span></h3>
                <div class="col-xs-12 form-group form-indent">
                    <textarea rows="5" cols="25" wrap="hard" class="form-control wordCount" name="missionstatement"
                              id="missiontitle" maxlength="1000"
                              required><?php echo $initalize->mybr2nl($rowsmission['MISSION_STATEMENT']); ?></textarea>
                </div>

                <h3>Last Updated:<span
                        style="color: red"><sup>*</sup></span></h3>
                <div class="form-group col-xs-12 form-indent">
                    <div class='input-group date col-xs-4' id='datetimepicker1'>
                        <input type='text' name="misupdate" value="<?php echo $rowsmission['MISSION_UPDATE_DATE']; ?>" class="form-control" required>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                <h3>Vision Statement</h3>
                <div class="col-xs-12 form-group form-indent">
                    <textarea rows="5" cols="25" wrap="hard" class="form-control wordCount" name="visionstatement" maxlength="1000"
                              id="visiontitle"><?php echo $initalize->mybr2nl($rowsmission['VISION_STATEMENT']); ?></textarea>
                </div>

                <h3>Last Updated:</h3>
                <div class="form-group col-xs-12 form-indent">
                    <div class='input-group date col-xs-4' id='datetimepicker2'>
                        <input type='text' name="visupdate" value="<?php echo $rowsmission['VISION_UPDATE_DATE']; ?>" class="form-control">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                <h3>Values Statement</h3>
                <div class="col-xs-12 form-group form-indent">
                    <textarea rows="5" cols="25" wrap="hard" class="form-control wordCount" name="valuestatement" maxlength="1000"
                              id="valuetitle"><?php echo $initalize->mybr2nl($rowsmission['VALUES_STATEMENT']); ?></textarea>
                </div>

                <h3>Last Updated:</h3>
                <div class="col-xs-12 form-indent">
                    <div class='input-group date col-xs-4' id='datetimepicker3'>
                        <input type='text' name="valupdate" value="<?php echo $rowsmission['VALUE_UPADTE_DATE']; ?>" class="form-control">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>


                <!--Edit Control-->
                <?php if (($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') AND ($rowsbpstatus['CONTENT_STATUS'] == 'In Progress' OR $rowsbpstatus['CONTENT_STATUS'] == 'Dean Rejected' OR $rowsbpstatus['CONTENT_STATUS'] == 'Not Started')) { ?>
                    <button id="save" type="submit" name="submit"
                            class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                        Save Draft
                    </button>
                    <button type="submit" id="submit_approve" name="submit_approve" <?php if ($rowsbpstatus['CONTENT_STATUS'] == 'Not Started') echo 'disabled'; ?> class="btn-primary pull-right">
                        Submit For Approval
                    </button>
                <?php } elseif ($_SESSION['login_role'] == 'dean' OR $_SESSION['login_role'] == 'designee') { ?>
                    <button id="save" type="submit" name="submit"
                            onclick="//$('#approve').removeAttr('disabled');$('#save').addClass('hidden');"
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
</div>
<?php require_once("../Resources/Includes/footer.php"); //Include Footer ?>
<!--Calender Bootstrap inclusion for date picker INPUT-->
<script type="text/javascript">
    $('.nav a').click(function (e) {
        e.preventDefault();
        $(this).tab('show')
    })
</script>
<script src="../Resources/Library/js/tabAlert.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script src="../Resources/Library/js/taskboard.js"></script>
<script src="../Resources/Library/js/content.js"></script>
