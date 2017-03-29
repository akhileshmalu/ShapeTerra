<?php

/*
 * This Page controls Mission Vision & Value Screen.
 */
require_once ("../Resources/Includes/BpContents.php");
$mvv = new MVV();
$mvv->checkSessionStatus();

$message = array();
$errorflag = 0;
$BackToDashboard = true;



/*
 * Local & Session variable Initialization
 */
$bpid = $_SESSION['bpid'];
$contentlink_id = $_GET['linkid'];
$bpayname = $_SESSION['bpayname'];

$prevbpid = $mvv->stringtoid($bpayname);
$prevbpayname = $mvv->idtostring($prevbpid - 101);
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

//  Blueprint Status information on title box
$resultbroad = $mvv->BlueprintStatusDisplay();
$rowbroad = $resultbroad->fetch(4);

// Values for placeholders
$resultexvalue = $mvv->PlaceHolderValue();
$rowsmission = $resultexvalue->fetch(4);

// SQL check Status of Blueprint Content for Edit restrictions
$resultbpstatus = $mvv->GetStatus();
$rowsbpstatus = $resultbpstatus->fetch(2);

if (isset($_POST['savedraft'])) {
    $message[0] = $mvv->SaveDraft();
}

if (isset($_POST['submit_approve'])) {

    // Also Save current changes and then send for approval.
    $message[0] = $mvv->SaveDraft();
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

<?php if (isset($_POST['savedraft']) || isset($_POST['submit_approve']) || isset($_POST['approve'])  ) { ?>
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
                              required><?php echo $mvv->mybr2nl($rowsmission['MISSION_STATEMENT']); ?></textarea>
                </div>

                <h3>Last Updated:<span
                        style="color: red"><sup>*</sup></span></h3>
                <div class="form-group col-xs-12 form-indent">
                    <div class='input-group date col-xs-4' id='datetimepicker1'>
                        <input type='text' name="misupdate"
                               value="<?php
                               if(!empty($rowsmission['MISSION_UPDATE_DATE']))
                               echo date("m/d/Y", strtotime($rowsmission['MISSION_UPDATE_DATE']));
                               ?>"
                               class="form-control" required>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                <h3>Vision Statement</h3>
                <div class="col-xs-12 form-group form-indent">
                    <textarea rows="5" cols="25" wrap="hard" class="form-control wordCount" name="visionstatement" maxlength="1000"
                             <?php
                             if($rowsmission['VISION_NO_RESPONSE']){
                                 echo ' disabled';
                             }
                             ?>
                              id="visiontitle"><?php echo $mvv->mybr2nl($rowsmission['VISION_STATEMENT']); ?></textarea>
                    <div class="checkbox">
                        <label for="optionalCheck">
                            <input type="checkbox" name="visNoResponse" class="optionalCheck"
                                   <?php
                                   if($rowsmission['VISION_NO_RESPONSE']){
                                       echo ' checked';
                                   }
                                   ?>
                                   id="visionstatement"/> No
                            response to
                            this item
                        </label>
                    </div>
                </div>

                <h3>Last Updated:</h3>
                <div class="form-group col-xs-12 form-indent">
                    <div class='input-group date col-xs-4' id='datetimepicker2'>
                        <input type='text' name="visupdate"
                               value="<?php
                               if(!empty($rowsmission['VISION_UPDATE_DATE']))
                               echo date("m/d/Y", strtotime($rowsmission['VISION_UPDATE_DATE']));
                               ?>"
                               class="form-control">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>

                    </div>
                </div>
                <h3>Values Statement</h3>
                <div class="col-xs-12 form-group form-indent">
                    <textarea rows="5" cols="25" wrap="hard" class="form-control wordCount" name="valuestatement" maxlength="1000"
                              id="valuetitle"><?php echo $mvv->mybr2nl($rowsmission['VALUES_STATEMENT']); ?></textarea>
                    <div class="checkbox">
                        <label for="optionalCheck">
                            <input type="checkbox" name="valNoResponse" class="optionalCheck" id="valuestatement"/> No
                            response
                            to
                            this item
                        </label>
                    </div>
                </div>

                <h3>Last Updated:</h3>
                <div class="col-xs-12 form-indent">
                    <div class='input-group date col-xs-4' id='datetimepicker3'>
                        <input type='text' name="valupdate"
                               value="<?php
                               if(!empty($rowsmission['VALUE_UPADTE_DATE']))
                               echo date("m/d/Y", strtotime($rowsmission['VALUE_UPDATE_DATE']));
                               ?>"
                               class="form-control">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>


                <!--Edit Control-->
                <?php require_once ("../Resources/Includes/control.php") ?>

            </form>
        </div>
    </div>
</div>
<?php require_once("../Resources/Includes/footer.php"); //Include Footer ?>
<!--Calender Bootstrap inclusion for date picker INPUT-->
<!-- <script type="text/javascript">
    $('.nav a').click(function (e) {
        e.preventDefault();
        $(this).tab('show')
    })
</script> -->

<script type="text/javascript" src="../Resources/Library/js/tabAlert.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="../Resources/Library/js/calender.js"></script>
<script type="text/javascript" src="../Resources/Library/js/chkbox.js"></script>
<script type="text/javascript" src="../Resources/Library/js/taskboard.js"></script>
<script type="text/javascript" src="../Resources/Library/js/content.js"></script>
