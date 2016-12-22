<?php
/*
 * This Page controls Goal Outcomes.
 */

/*
 * Session & Error control Initialization.
 */
session_start();
$error = array();
$errorflag =0;

/*
 * Connection to DataBase.
 */
require_once ("../Resources/Includes/connect.php");

/*
 * Local & Session variable Initialization
 */
$contentlink_id = $_GET['linkid'];
$goal_id=intval($_GET['goal_id']);
$bpayname =$_SESSION['bpayname'];
$ouid = $_SESSION['login_ouid'];
$ouabbrev = $_SESSION['login_ouabbrev'];
$date = date("Y-m-d");
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_userid'];

/*
 * faculty Award Grid ; conditional for provost & other users
 */
if ($ouid == 4) {
    $sqlbroad = "select BROADCAST_AY,BROADCAST_STATUS,LastModified from broadcast where BROADCAST_AY='$bpayname';";
} else{
    $sqlbroad = "select BROADCAST_AY,BROADCAST_STATUS_OTHERS,LastModified from broadcast where BROADCAST_AY='$bpayname' and BROADCAST_OU ='$ouid'; ";
}
$resultbroad = $mysqli->query($sqlbroad);
$rowbroad = $resultbroad->fetch_array(MYSQLI_NUM);

/*
 * Existing values to show for goal outcomes, If exist.
 */
$sqlexgoalout = "select * from BP_UnitGoalOutcomes where ID_UNIT_GOAL = '$goal_id' ";
$resultexgoalout = $mysqli->query($sqlexgoalout);
$rowsexgoalout = $resultexgoalout -> fetch_assoc();



$sqlunitgoal = "select * from BP_UnitGoals where ID_UNIT_GOAL = '$goal_id' ";
$resultunitgoal = $mysqli->query($sqlunitgoal);
$rowsunitgoal = $resultunitgoal -> fetch_assoc();



/*
 * Add Modal Record Addition
 */

if(isset($_POST['save_draft'])){

    $goalstatus = $_POST['goal_status'];
    $goalach = nl2br($_POST['goal_ach']);
    $resutilzed = nl2br($_POST['goal_resutil']);
    $goalconti = nl2br($_POST['goal_conti']);
    $resneed = nl2br($_POST['resoneed']);
    $goalnote = nl2br($_POST['goal_notes']);
    $goalreportstatus = "In progress";
    $contentlink_id = $_GET['linkid'];
    $goal_id=intval($_GET['goal_id']);



    $sqlgoalout = "INSERT INTO BP_UnitGoalOutcomes (ID_UNIT_GOAL, OUTCOMES_AUTHOR, MOD_TIMESTAMP, GOAL_REPORT_STATUS, GOAL_STATUS, GOAL_ACHIEVEMENTS, GOAL_RSRCS_UTLZD, GOAL_CONTINUATION, GOAL_RSRCS_NEEDED, GOAL_NOTES) 
VALUES ('$goal_id','$author','$time','$goalreportstatus','$goalstatus','$goalach','$resutilzed','$goalconti','$resneed','$goalnote'); ";

    $sqlgoalout .="Update  BpContents set CONTENT_STATUS = 'In progress', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

    if($resultgoalout = $mysqli->multi_query($sqlgoalout)) {
        $error[0] = "Goal Outcome Saved.";
    }
    else{
        $error[0] = "Goal Outcome could not be Saved.";
    }

}

if(isset($_POST['approve'])) {

    $goal_id = $_GET['goal_id'];
    $contentlink_id = $_GET['linkid'];
    $goalreportstatus = "Pending approval";
    $sqlgoaloutap = "update BP_UnitGoalOutcomes set GOAL_REPORT_STATUS = '$goalreportstatus' where ID_UNIT_GOAL = '$goal_id'; ";

    $sqlgoaloutap = "Update  BpContents set CONTENT_STATUS = '$goalreportstatus', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

    if($resultgoaloutap = $mysqli->multi_query($sqlgoaloutap)) {
        $error[1] = "Goal Outcome submitted for Approval.";
    }
    else{
        $error[1] = "Goal Outcome could not be Submitted.";
    }

}



require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>


<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<!--<div class="overlay hidden"></div>-->
<?php //if (isset($_POST['save_draft'])) { ?>
<!--    <div class="alert">-->
<!--        <a href="#" class="close end"><span class="icon">9</span></a>-->
<!--        <h1 class="title"></h1>-->
<!--        <p class="description">--><?php //foreach ($error as $value) echo $value; ?><!--</p>-->
<!--        <button type="button" redirect="" class="end btn-primary">Close</button>-->
<!--    </div>-->
<?php //} ?>

<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Blueprint Management</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <div class="col-xs-8">
            <h1 class="box-title"><?php echo $rowbroad[0]; ?></h1>
            <p class="status"><span>Org Unit Name:</span> <?php echo $_SESSION['login_ouname']; ?></p>
            <p class="status"><span>Status:</span> <?php echo $rowbroad[1]; ?></p>
        </div>

<!--        <div class="col-xs-4">-->
<!--            <a href="#" class="btn-primary">Preview</a>-->
<!--        </div>-->



    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Goal Outcome Management</h1>
        <div id="" style="margin-top: 30px;">


            <!--                <div class="form-group" style="border: solid">-->
            <h4 style="border-top: 5px solid #862633; border-bottom: 5px solid #862633;padding:2% 0 2% 0;"><p
                    style="color: grey">Goal : <?php echo $rowsunitgoal['UNIT_GOAL_TITLE']; ?></p></h4>
            <!--                </div>-->

            <form action="<?php echo "goaloutcome.php?goal_id=$goal_id&linkid=$contentlink_id"; ?>" method="POST" class="ajaxform">

                <div class="form-group">
                    <label for="goallink"><h1>Linked to University Goal(s)</h1></label>

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
                <label for ="goalstate" ><h1>Goal Statement </h1></label>
                <div id="goalstate" class="form-group">
                    <textarea  rows="5" cols="25" wrap="hard" class="form-control" readonly><?php echo $rowsunitgoal['GOAL_STATEMENT']; ?></textarea>
                </div>

                <label for ="goalalign" ><h1>Goal Alignment</h1></label>
                <div id="goalalign" class="form-group">
                    <textarea   rows="5" cols="25" wrap="hard" class="form-control" readonly><?php echo $rowsunitgoal['GOAL_ALIGNMENT']; ?></textarea>
                </div>

                <label for ="goalstatus" ><h1>Goal Status</h1></label>
                <div id="goalstatus" class="form-group">
                    <select id="goalstlist" name="goal_status" onchange="control(this);" class="form-control">
                    <?php $sqlgoalstatus ="select * from GoalStatus";
                    $resultgoalstatus = $mysqli->query($sqlgoalstatus);
                    while($rowsgoalstatus = $resultgoalstatus -> fetch_assoc()) :
                    ?>
                        <option value="<?php echo $rowsgoalstatus['ID_STATUS']; ?>"> <?php echo $rowsgoalstatus['STATUS']; ?> </option>
                        <?php  endwhile; ?>
                    </select>
                </div>
                <label for ="goalach" ><h1>Goal Achievement </h1></label>
                <div id="goalach" class="form-group">
                    <textarea id="goalachtext" name="goal_ach" rows="3" cols="25" wrap="hard" class="form-control" ><?php echo $rowsexgoalout['GOAL_ACHIEVEMENTS']; ?></textarea>
                </div>


                <div id="goalresutil" class="form-group hidden">
                    <label for ="goalresutiltext" ><h1>Resources Utilized </h1></label>
                    <textarea id="goalresutiltext" name="goal_resutil" rows="3" cols="25" wrap="hard" class="form-control" ><?php echo $rowsexgoalout['GOAL_RSRCS_UTLZD']; ?></textarea>
                </div>


                <div id="goalconti" class="form-group hidden">
                    <label for ="goalcontitext" ><h1>Goal Continuation </h1></label>
                    <textarea id="goalcontitext" name="goal_conti" rows="3" cols="25" wrap="hard" class="form-control" ><?php echo $rowsexgoalout['GOAL_CONTINUATION']; ?></textarea>
                </div>


                <div id="resoneed" class="form-group hidden">
                    <label for ="resoneedtext" ><h1>Resource Needed </h1></label>
                    <textarea id="resoneedtext" name="resoneed" rows="3" cols="25" wrap="hard" class="form-control" ><?php echo $rowsexgoalout['GOAL_RSRCS_NEEDED']; ?></textarea>
                </div>

                <label for ="notes" ><h1>Notes </h1></label>
                <div id="notes" class="form-group">
                    <textarea name="goal_notes" rows="3" cols="25" wrap="hard" class="form-control" ><?php echo $rowsexgoalout['GOAL_NOTES']; ?></textarea>
                </div>

                <!--                        Reviewer Edit Control-->
                <?php if ($_SESSION['login_right'] != 1): ?>

                <input type="button" id="cancelbtn" value="Cancel & Discard" class="btn-primary cancelbox pull-left">

                <input type="submit" id="approve" name="approve" value="Submit For Approval" class="btn-primary pull-right">
                <input type="submit" id="savebtn" name="save_draft" value="Save Draft" class="btn-secondary pull-right">

                <?php endif; ?>

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
    })
</script>
<script src="../Resources/Library/js/tabchange.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script src="../Resources/Library/js/outcomecntrl.js"></script>

