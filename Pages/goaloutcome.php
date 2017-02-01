<?php
/*
 * This Page controls Goal Outcomes.
 */

/*
 * Session & Error control Initialization.
 */
session_start();
if(!$_SESSION['isLogged']) {
    header("location:login.php");
    die();
}
$error = array();
$errorflag =0;
$BackToGoalOutHome = true;

/*
 * Connection to DataBase.
 */
require_once ("../Resources/Includes/connect.php");

/*
 * Local & Session variable Initialization
 */
$bpid = $_SESSION['bpid'];
$contentlink_id = $_GET['linkid'];
$goal_id=$_GET['goal_id'];
$bpayname =$_SESSION['bpayname'];
$ouid = $_SESSION['login_ouid'];

if ($ouid == 4) {
    $ouabbrev = $_SESSION['bpouabbrev'];
} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}

$date = date("Y-m-d");
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_userid'];

/*
 * faculty Award Grid ; conditional for provost & other users
 */
if ($ouid == 4) {
    $sqlbroad = "select BROADCAST_AY,OU_NAME,BROADCAST_STATUS,LastModified from broadcast inner join Hierarchy on broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY where BROADCAST_AY='$bpayname' and Hierarchy.OU_ABBREV ='$ouabbrev';";
} else{
    $sqlbroad = "select BROADCAST_AY,OU_NAME, BROADCAST_STATUS_OTHERS,LastModified from broadcast inner join Hierarchy on broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY where BROADCAST_AY='$bpayname' and BROADCAST_OU ='$ouid'; ";
}
$resultbroad = $mysqli->query($sqlbroad);
$rowbroad = $resultbroad->fetch_array(MYSQLI_NUM);

/*
 * Existing values to show for goal outcomes, If exist.
 */
$sqlexgoalout = "select * from BP_UnitGoalOutcomes where ID_UNIT_GOAL = '$goal_id' ";
$resultexgoalout = $mysqli->query($sqlexgoalout);
$rowsexgoalout = $resultexgoalout -> fetch_assoc();

/*
 * values to show for goals, If exist.
 */
$sqlunitgoal = "select * from BP_UnitGoals where ID_UNIT_GOAL = '$goal_id' ";
$resultunitgoal = $mysqli->query($sqlunitgoal);
$rowsunitgoal = $resultunitgoal -> fetch_assoc();

// Value Set for Goal ViewPoints;
$goalviewpoint = array(
    'Looking Back',
    'Real Time',
    'Looking Ahead'
);


/*
 * Add Modal Record Addition
 */

if(isset($_POST['savedraft'])) {

    $goalstatus = $_POST['goal_status'];
    $goalach = mynl2br($_POST['goal_ach']);
    $resutilzed = mynl2br($_POST['goal_resutil']);
    $goalconti = mynl2br($_POST['goal_conti']);
    $resneed = mynl2br($_POST['resoneed']);
    $goalincomplan = mynl2br($_POST['goal_plan_incomp']);
    $goalupcominplan = mynl2br($_POST['goal_plan_upcoming']);
    $goalreportstatus = "In Progress";
    $contentlink_id = $_GET['linkid'];
    $goal_id = $_GET['goal_id'];



    $sqlgoalout = "INSERT INTO `BP_UnitGoalOutcomes` (ID_UNIT_GOAL, OUTCOMES_AUTHOR, MOD_TIMESTAMP, GOAL_REPORT_STATUS, GOAL_STATUS, GOAL_ACHIEVEMENTS, GOAL_RSRCS_UTLZD, GOAL_CONTINUATION, GOAL_RSRCS_NEEDED, GOAL_PLAN_INCOMPLT, GOAL_UPCOMING_PLAN )
VALUES ('$goal_id','$author','$time','$goalreportstatus','$goalstatus','$goalach','$resutilzed','$goalconti','$resneed','$goalincomplan','$goalupcominplan')
ON DUPLICATE KEY UPDATE `ID_UNIT_GOAL` = VALUES(`ID_UNIT_GOAL`), OUTCOMES_AUTHOR = VALUES(`OUTCOMES_AUTHOR`), MOD_TIMESTAMP = VALUES(`MOD_TIMESTAMP`),
GOAL_REPORT_STATUS = VALUES(`GOAL_REPORT_STATUS`), GOAL_STATUS = VALUES(`GOAL_STATUS`), GOAL_ACHIEVEMENTS = VALUES(`GOAL_ACHIEVEMENTS`), GOAL_RSRCS_UTLZD = VALUES(`GOAL_RSRCS_UTLZD`),
GOAL_CONTINUATION = VALUES(`GOAL_CONTINUATION`), GOAL_RSRCS_NEEDED = VALUES(`GOAL_RSRCS_NEEDED`); ";

    $sqlgoalout .= "UPDATE `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR= '$author', MOD_TIMESTAMP ='$time' where ID_CONTENT ='$contentlink_id';";

    $sqlgoalout .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress', BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= '$author', LastModified ='$time' where ID_BROADCAST = '$bpid'; ";

    if($resultgoalout = $mysqli->multi_query($sqlgoalout)) {
        $error[0] = "Goal Outcome Saved.";
    } else{
        $error[0] = "Goal Outcome could not be Saved.";
    }

}

if(isset($_POST['submit_approve'])) {

    $goal_id = $_GET['goal_id'];
    $contentlink_id = $_GET['linkid'];
    $goalreportstatus = "Pending Approval";
    $sqlgoaloutap .= "update `BP_UnitGoalOutcomes` set GOAL_REPORT_STATUS = '$goalreportstatus' where ID_UNIT_GOAL = '$goal_id'; ";

    //$sqlgoaloutap .= "Update `BpContents` set CONTENT_STATUS = 'Pending Dean Approval', BP_AUTHOR= '$author', MOD_TIMESTAMP ='$time' where ID_CONTENT ='$contentlink_id';";

    if($resultgoaloutap = $mysqli->multi_query($sqlgoaloutap)) {
        $error[1] = "Goal Outcome submitted for Approval.";
    } else {
        $error[1] = "Goal Outcome could not be Submitted.";
    }

}

//Dean Approve or Reject

if(isset($_POST['approve'])) {

    $contentlink_id = $_GET['linkid'];
    $sqlmission = "update `BP_UnitGoalOutcomes` set GOAL_REPORT_STATUS = 'Dean Approved' where ID_UNIT_GOAL = '$goal_id'; ";
    if ($mysqli->query($sqlmission)) {
        $error[0] = "Goal Outcome Approved Successfully";
    } else {
        $error[0] = "Goal Outcome Could not be Approved. Please Retry.";
    }
}

if(isset($_POST['reject'])) {

    $contentlink_id = $_GET['linkid'];
    $sqlmission = "update `BP_UnitGoalOutcomes` set GOAL_REPORT_STATUS = 'Dean Rejected' where ID_UNIT_GOAL = '$goal_id'; ";
    if ($mysqli->query($sqlmission)) {
        $error[0] = "Goal Outcome Rejected Successfully";
    } else {
        $error[0] = "Goal Outcome Could not be Rejected. Please Retry.";
    }
}



require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>


<!--<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />-->

<div class="overlay hidden"></div>
<?php if (isset($_POST['savedraft']) OR isset($_POST['submit_approve']) OR isset($_POST['approve']) OR isset($_POST['reject']) ) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" redirect="<?php echo "goaloutcomeshome.php?linkid=".$contentlink_id; ?>" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 class="box-title" id="title">Blueprint Management</h1>
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
        <h1 class="box-title">Goal : <?php echo $rowsunitgoal['UNIT_GOAL_TITLE']; ?></h1>
        <div id="" style="margin-top: 30px;">



            <form action="<?php echo "goaloutcome.php?goal_id=" . $goal_id . "&linkid=" . $contentlink_id; ?>"
                onsubmit="if($('#goalstlist').val() == 0) { alert('Please select Goal Status'); return false; }"  method="POST">

                <div class="form-group">
                    <label for="goallink"><h3>Linked to University Goal(s)</h3></label>

                    <?php
                    $sqlug = "Select * from BP_UnitGoals A   join UniversityGoals B where find_in_set(ID_UNIV_GOAL,LINK_UNIV_GOAL)>0 and A.ID_UNIT_GOAL = '$goal_id'; ";
                    $resultug = $mysqli->query($sqlug);
                    while ($rowsug = $resultug->fetch_assoc()): { ?>
                        <div class="checkbox" id="goallink">
                            <span class="icon">S</span><label style="color: grey"><?php echo $rowsug['GOAL_TITLE']; ?>
                            </label>

                        </div>
                    <?php } endwhile; ?>

                </div>

                <label for ="goalstate" ><h3>Goal Statement </h3></label>
                <div id="goalstate" class="form-group form-indent">
                    <textarea  rows="5" cols="25" wrap="hard" class="form-control" readonly><?php echo mybr2nl($rowsunitgoal['GOAL_STATEMENT']);?></textarea>
                </div>

                <label for ="goalalign" ><h3>Goal Alignment</h3></label>
                <div id="goalalign" class="form-group form-indent">
                    <textarea   rows="5" cols="25" wrap="hard" class="form-control" readonly><?php echo mybr2nl($rowsunitgoal['GOAL_ALIGNMENT']); ?></textarea>
                </div>

                <label for ="goalview" ><h3>Goal Viewpoint in Report</h3></label>
                <div id="goalview" class="form-group">
                    <select id="viewpoint" type="text" class="form-control form-indent" readonly>
                        <option value="0">-- select an option --</option>
                        <?php foreach ($goalviewpoint as $item) { ?>
                            <option
                                value="<?php echo $item ?>" <?php if ($rowsunitgoal['GOAL_VIEWPOINT'] == $item) {
                                echo " selected = selected";
                            } ?>><?php echo $item ?></option>
                        <?php } ?>
                    </select>
                </div>

                <label for ="goalstatus" ><h3>Goal Status</h3></label>
                <div id="goalstatus" class="form-group form-indent">
                    <select id="goalstlist" name="goal_status"  class="form-control" style="padding: 0px; background-color: #fff !important;">
                        <option value="0"> -- select an option -- </option>
                        <?php $sqlgoalstatus ="select * from GoalStatus";
                    $resultgoalstatus = $mysqli->query($sqlgoalstatus);
                    while($rowsgoalstatus = $resultgoalstatus -> fetch_assoc()) :?>
                        <option value="<?php echo $rowsgoalstatus['ID_STATUS']; ?>"
                            <?php if($rowsgoalstatus['ID_STATUS'] == $rowsexgoalout['GOAL_STATUS']) echo " selected = selected"; ?>> <?php echo $rowsgoalstatus['STATUS']; ?> </option>
                        <?php  endwhile; ?>
                    </select>
                </div>
                <label for ="goalach" ><h3>Goal Achievement </h3></label>
                <div id="goalach" class="form-group form-indent">
                    <textarea id="goalachtext" name="goal_ach" rows="3" cols="25" wrap="hard" class="form-control" ><?php echo mybr2nl($rowsexgoalout['GOAL_ACHIEVEMENTS']); ?></textarea>
                </div>

                <label for ="goalresutil" ><h3>Resources Utilized </h3></label>
                <div id="goalresutil" class="form-group form-indent hidden">
                    <textarea id="goalresutiltext" name="goal_resutil" rows="3" cols="25" wrap="hard" class="form-control" ><?php echo mybr2nl($rowsexgoalout['GOAL_RSRCS_UTLZD']); ?></textarea>
                </div>

                <label id ="goalcontilable" ><h3>Goal Continuation </h3></label>
                <div id="goalconti" class="form-group form-indent hidden">
                    <textarea id="goalcontitext" name="goal_conti" rows="3" cols="25" wrap="hard" class="form-control" ><?php echo mybr2nl($rowsexgoalout['GOAL_CONTINUATION']); ?></textarea>
                </div>

                <label for ="goalplanincomp" ><h3>Goal Plans for Incomplete Goal</h3></label>
                <div id="goalplanincomp" class="form-group form-indent">
                    <textarea name="goal_plan_incomp" rows="3" cols="25" wrap="hard" class="form-control" ><?php echo mybr2nl($rowsexgoalout['GOAL_PLAN_INCOMPLT']); ?></textarea>
                </div>

                <label for ="goalplanupcom" ><h3>Goal Upcoming Plans </h3></label>
                <div id="notes" class="form-group form-indent">
                    <textarea name="goal_plan_upcoming" rows="3" cols="25" wrap="hard" class="form-control" ><?php echo mybr2nl($rowsexgoalout['GOAL_UPCOMING_PLAN']); ?></textarea>
                </div>

                <label id = "resoneedlable" ><h3>Resource Needed </h3></label>
                <div id="resoneed" class="form-group form-indent hidden">
                    <textarea id="resoneedtext" name="resoneed" rows="3" cols="25" wrap="hard" class="form-control" ><?php echo mybr2nl($rowsexgoalout['GOAL_RSRCS_NEEDED']); ?></textarea>
                </div>


                <!--                      Edit Control-->

                <?php if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') { ?>

                    <button id="save" type="submit" name="savedraft"
                            class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                        Save Draft
                    </button>
                    <input type="button" id="cancelbtn" value="Cancel & Discard"
                           class="btn-primary cancelbpbox pull-left">
                    <button type="submit" id="submit_approve" name="submit_approve"
                            class="btn-primary pull-right">Submit For Approval
                    </button>

                <?php } elseif ($_SESSION['login_role'] == 'dean' OR $_SESSION['login_role'] == 'designee') { ?>

                    <button id="save" type="submit" name="savedraft"
                            class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                        Save Draft
                    </button>

                    <?php if ($rowsexgoalout['GOAL_REPORT_STATUS'] == 'Pending Approval'): ?>
                        <input type="submit" id="approve" name="approve" value="Approve"
                               class="btn-primary pull-right">

                        <input type="submit" id="reject" name="reject" value="Reject"
                               class="btn-primary pull-right">
                    <?php endif;
                } ?>
            </form>
        </div>
    </div>
</div>

<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>

<!--Calender Bootstrap inclusion for date picker INPUT-->
<!--<script src="../Resources/Library/js/tabchange.js"></script>-->
<script>
    $('#viewpoint option:not(:selected)').prop('disabled', true);
</script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script src="../Resources/Library/js/alert.js"></script>
<script src="../Resources/Library/js/outcomecntrl.js"></script>
