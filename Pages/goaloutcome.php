<?php
/*
 * This Page controls Goal Outcomes.
 */

 require_once ("../Resources/Includes/initialize.php");
 $initalize = new Initialize();
 $initalize->checkSessionStatus();


//require_once ("../Resources/Includes/connect.php");
require_once ("../Resources/Includes/BpContents.php");

$message = array();
$errorflag =0;
$BackToGoalOutHome = true;
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


$goaloutcome = new GOALOUTCOME();

// Blueprint Status information on title box
$resultbroad = $goaloutcome->BlueprintStatusDisplay();
$rowbroad = $resultbroad->fetch(PDO::FETCH_BOTH);



/*
 * Existing values to show for goal outcomes, If exist.
 */
$resultexgoalout = $goaloutcome->OutcomePlaceholders();
$rowsexgoalout = $resultexgoalout->fetch(4);

/*
 * values to show for goals, If exist.
 */
// try {
//     $sqlunitgoal = "select * from BP_UnitGoals where ID_UNIT_GOAL = :goal_id ";
//     $resultunitgoal = $connection -> prepare($sqlunitgoal);
//     $resultunitgoal -> bindParam(":goal_id", $goal_id, PDO::PARAM_INT);
//     $resultunitgoal -> execute();

//     $rowsunitgoal = $resultunitgoal->fetch(4);
// } catch(PDOException $e) {
//     error_log($e->getMessage());
//     //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
// }

$resultunitgoal = $goaloutcome->PlaceHolderValue();
$rowsunitgoal = $resultunitgoal->fetch(4);

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

    $message[0] = $goaloutcome->SaveDraft();

}

if(isset($_POST['submit_approve'])) {

    $message[0] = "Goal Outcome";
    $message[0].= $goaloutcome->SubmitApproval();

}

//Dean Approve or Reject

if(isset($_POST['approve'])) {

    $message[0] = "Goal Outcome";
    $message[0].= $goaloutcome->Approve();

}

if(isset($_POST['reject'])) {

    $message[0] = "Goal Outcome";
    $message[0].= $goaloutcome->Reject();

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
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" onclick="$redirect = $('.alert button').attr('redirect');
		$(window).attr('location',$redirect)" redirect="<?php echo "goaloutcomeshome.php?linkid=".$contentlink_id; ?>" class="end btn-primary">Close</button>
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
                    $sqlug = "Select * from BP_UnitGoals A   join UniversityGoals B where find_in_set(ID_UNIV_GOAL,LINK_UNIV_GOAL)>0 and A.ID_UNIT_GOAL = :goal_id; ";

                    $resultug = $goaloutcome->connection->prepare($sqlug);
                    $resultug -> bindParam(":goal_id", $goal_id, PDO::PARAM_INT);

                    $resultug->execute();
                    while ($rowsug = $resultug->fetch(4)): { ?>
                        <div class="checkbox" id="goallink">
                            <span class="icon">S</span><label style="color: grey"><?php echo $rowsug['GOAL_TITLE']; ?>
                            </label>

                        </div>
                    <?php } endwhile; ?>

                </div>


                <div id="goalstate" class="form-group col-xs-12">
                    <h3>Goal Statement </h3>
                    <textarea  rows="5" cols="25" wrap="hard" class="form-control form-indent" disabled readonly><?php echo $initalize->mybr2nl($rowsunitgoal['GOAL_STATEMENT']);?></textarea>
                </div>


                <div id="goalalign" class="form-group col-xs-12">
                    <h3>Goal Alignment</h3>
                    <textarea   rows="5" cols="25" wrap="hard" class="form-control form-indent" disabled readonly><?php echo $initalize->mybr2nl($rowsunitgoal['GOAL_ALIGNMENT']); ?></textarea>
                </div>

<!--                <label for ="goalview" ></label>-->
                <div id="goalview" class="form-group col-xs-12">
                    <h3>Goal Viewpoint in Report</h3>
                    <input type="text" class="form-control form-indent" value="<?php echo $rowsunitgoal['GOAL_VIEWPOINT']; ?>" readonly disabled>
                </div>

<!--                <label for ="goalstatus" ></label>-->
                <div id="goalstatus" class="form-group col-xs-12">
                    <h3>Goal Status</h3>
                    <select id="goalstlist" name="goal_status"  class="form-control form-indent" style="padding: 0px; background-color: #fff !important;">
                        <option value="0"> -- select an option -- </option>
                        <?php $selectedViewPoint = $rowsunitgoal['GOAL_VIEWPOINT'];
                        try {
                            $sqlgoalstatus ="select * from GoalStatus where STATUS_VIEWPOINT = :selectedViewPoint; ";
                            $resultgoalstatus = $goaloutcome->connection->prepare($sqlgoalstatus);
                            $resultgoalstatus->bindParam(':selectedViewPoint', $selectedViewPoint,2);
                            $resultgoalstatus->execute();
                        } catch(PDOException $e) {
                            error_log($e->getMessage());
                        }
                    while($rowsgoalstatus = $resultgoalstatus -> fetch(2)) :?>
                        <option value="<?php echo $rowsgoalstatus['ID_STATUS']; ?>"
                            <?php if($rowsgoalstatus['ID_STATUS'] == $rowsexgoalout['GOAL_STATUS']) echo " selected = selected"; ?>> <?php echo $rowsgoalstatus['STATUS']; ?> </option>
                        <?php  endwhile; ?>
                    </select>
                </div>
<!--                <label for ="goalach" ></label>-->
                <div id="goalachcont" class="form-group col-xs-12 hidden">
                    <h3>Goal Achievement </h3>
                    <textarea id="goalachtext" name="goal_ach" rows="3" cols="25" wrap="hard" class="form-control form-indent" ><?php echo $initalize->mybr2nl($rowsexgoalout['GOAL_ACHIEVEMENTS']); ?></textarea>
                </div>

<!--                <label for ="goalresutil" ></label>-->
                <div id="goalresutilcont" class="form-group col-xs-12 hidden">
                    <h3>Resources Utilized </h3>
                    <textarea id="goalresutiltext" name="goal_resutil" rows="3" cols="25" wrap="hard" class="form-control form-indent" ><?php echo $initalize->mybr2nl($rowsexgoalout['GOAL_RSRCS_UTLZD']); ?></textarea>
                </div>

<!--                <label id ="goalcontilable" ></label>-->
                <div id="goalconticont" class="form-group col-xs-12 hidden">
                    <h3>Goal Continuation </h3>
                    <textarea id="goalcontitext" name="goal_conti" rows="3" cols="25" wrap="hard" class="form-control form-indent" ><?php echo $initalize->mybr2nl($rowsexgoalout['GOAL_CONTINUATION']); ?></textarea>
                </div>

<!--                <label for ="goalplanincomp" ></label>-->
                <div id="goalincompcont" class="form-group col-xs-12 hidden">
                    <h3>Goal Plans for Incomplete Goal</h3>
                    <textarea id="goalincomptext" name="goal_plan_incomp" rows="3" cols="25" wrap="hard" class="form-control form-indent" ><?php echo $initalize->mybr2nl($rowsexgoalout['GOAL_PLAN_INCOMPLT']); ?></textarea>
                </div>

<!--                <label for ="goalplanupcom" ></label>-->
                <div id="goalupcomincont" class="form-group col-xs-12 hidden">
                    <h3>Goal Upcoming Plans </h3>
                    <textarea id="goalupcomintext" name="goal_plan_upcoming" rows="3" cols="25" wrap="hard" class="form-control form-indent" ><?php echo $initalize->mybr2nl($rowsexgoalout['GOAL_UPCOMING_PLAN']); ?></textarea>
                </div>

<!--                <label id = "resoneedlable" ></label>-->
                <div id="resoneedcont" class="form-group col-xs-12 hidden">
                    <h3>Resource Needed </h3>
                    <textarea id="resoneedtext" name="resoneed" rows="3" cols="25" wrap="hard" class="form-control form-indent" ><?php echo $initalize->mybr2nl($rowsexgoalout['GOAL_RSRCS_NEEDED']); ?></textarea>
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
