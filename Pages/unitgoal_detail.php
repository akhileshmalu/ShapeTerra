<?php


/*
 * This Page controls Faculty Awards Screen.
 */


require_once ("../Resources/Includes/BpContents.php");
//$UnitGoalDetail = new UNITGOALDETAIL();
$Bpcontent = new BPCONTENTS();
$Bpcontent->checkSessionStatus();
$connection = $Bpcontent->connection;

$message = array();
$errorflag =0;
$BackToGoal = true;

/*

 * Connection to DataBase.
 */


/*
 * Local & Session variable Initialization
 */
$bpid = $_SESSION['bpid'];
$contentlink_id = $_GET['linkid'];
$goal_id = $_GET['goal_id'];
$bpayname =$_SESSION['bpayname'];
$ouid = $_SESSION['login_ouid'];
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_userid'];
$unigoallinkname = null;

if ($ouid == 4) {
    $ouabbrev = $_SESSION['bpouabbrev'];
} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}



//  Blueprint Status information on title box
$resultbroad = $Bpcontent->BlueprintStatusDisplay();
$rowbroad = $resultbroad->fetch(4);

//$mysqli = $Bpcontent->mysqli;
/*
 * SQL for pre-existing Goal Value
 */
try {
    $sqlexvalue = "SELECT * FROM `BP_UnitGoals` WHERE ID_UNIT_GOAL = :goal_id ;";
    $resultexvalue = $Bpcontent->connection->prepare($sqlexvalue);
    $resultexvalue->bindParam(':goal_id', $goal_id, 1);
    $resultexvalue->execute();
} catch (PDOException $e) {
    error_log($e->getMessage());
}

$rowsexvalue = $resultexvalue->fetch(2);

// SQL for Goal Status for Edit control

try {
    $sqlgoalstatus = "SELECT GOAL_STATUS FROM BP_UnitGoals WHERE ID_UNIT_GOAL = :goal_id;";
    $resultgoalstatus = $Bpcontent->connection->prepare($sqlgoalstatus);
    $resultgoalstatus->bindParam(':goal_id', $goal_id, 1);
    $resultgoalstatus->execute();

} catch (PDOException $e) {
    error_log($e->getMessage());
}
$rowsgoalstatus = $resultgoalstatus->fetch(4);

//SQL to check goal level 'goal approval' by Dean to action content status 'Goal Approval'
// Checks if given goal is last goal to approve ; if yes then create sql to change content status of Goal Overview module.
try {
    $sqlgoalchk = "SELECT * FROM BP_UnitGoals WHERE OU_ABBREV = :ouabbrev AND UNIT_GOAL_AY= :bpayname ";
    $resultgoalchk = $Bpcontent->connection->prepare($sqlgoalchk);
    $resultgoalchk->bindParam(':ouabbrev', $ouabbrev, 2);
    $resultgoalchk->bindParam(':bpayname', $bpayname, 2);
    $resultgoalchk->execute();

} catch (PDOException $e) {
    error_log($e->getMessage());
}

$numrow = $resultgoalchk->rowCount();

// Value Set for Goal ViewPoints;
$goalviewpoint = array(
    'Looking Back',
    'Real Time',
    'Looking Ahead'
);

/*
 * Add UNIT GOAL Modal
 */

if(isset($_POST['goal_submit'])) {
    $Bpcontent->time = date('Y-m-d H:i:s');
    $contentlink_id = $_GET['linkid'];
    $goal_id = $_GET['goal_id'];
    $goaltitle = $Bpcontent->mynl2br($_POST['goaltitle']);

    $unigoallink = $_POST['goallink'];
    foreach ($unigoallink as $value) {
        $unigoallinkname .= $value . ",";
    }
    $goalstatement = $Bpcontent->mynl2br($_POST['goalstatement']);
    $goalalignment = $Bpcontent->mynl2br($_POST['goalalignment']);
    $goalview = $_POST['goal_viewpoint'];
    $goalaction = $Bpcontent->mynl2br($_POST['goal_action']);
    $noRespAction = $_POST['noRespActionPlan'];
    $goalresNeed = $Bpcontent->mynl2br($_POST['goal_resNeedLookAhead']);
    $noRespResNeed = $_POST['noRespResNeed'];
    $goalnotes = $Bpcontent->mynl2br($_POST['goal_notes']);
    $noRespNotes = $_POST['noRespNotes'];

    try {

        $sqlunitgoal = "UPDATE `BP_UnitGoals` SET GOAL_AUTHOR = :author, MOD_TIMESTAMP = :timeStampmod, 
GOAL_STATUS = 'In Progress', UNIT_GOAL_TITLE = :goaltitle, LINK_UNIV_GOAL = :unigoallinkname,
GOAL_VIEWPOINT = :goalview, GOAL_STATEMENT = :goalstatement, GOAL_ALIGNMENT = :goalalignment, 
GOAL_ACTION_PLAN = :goalaction, GOAL_ACTION_PLAN_NO_RESP = :noRespAction,GOAL_RSRCS_NEEDED_LOOKAHEAD = :resneed, 
GOAL_RSRCS_NEEDED_NO_RESP = :noRespResNeed, GOAL_NOTES = :goalnotes, GOAL_NOTES_NO_RESP = :noRespNotes
 WHERE ID_UNIT_GOAL =:goal_id ;";

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {
            $sqlunitgoal .= "UPDATE `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR= :author,
            MOD_TIMESTAMP = :timeStampmod WHERE ID_CONTENT =:contentlink_id ;";

            $sqlunitgoal .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress',
BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= :author, LastModified = :timeStampmod WHERE ID_BROADCAST = :bpid ; ";
        }

        $resultunitgoal = $Bpcontent->connection->prepare($sqlunitgoal);

        $resultunitgoal->bindParam(":author", $Bpcontent->author, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":timeStampmod", $Bpcontent->time, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":goaltitle", $goaltitle, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":unigoallinkname", $unigoallinkname, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":goalview", $goalview, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":goalstatement", $goalstatement, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":goalalignment", $goalalignment, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":goalaction", $goalaction, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":noRespAction", $noRespAction, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":resneed", $goalresNeed, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":noRespResNeed", $noRespResNeed, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":goalnotes", $goalnotes, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":noRespNotes", $noRespNotes, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":goal_id",$goal_id,2);

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

            $resultunitgoal->bindParam(":author", $Bpcontent->author, PDO::PARAM_STR);
            $resultunitgoal->bindParam(":timeStampmod", $Bpcontent->time, PDO::PARAM_STR);
            $resultunitgoal->bindParam(':contentlink_id', $contentlink_id, PDO::PARAM_STR);
            $resultunitgoal->bindParam(":author", $Bpcontent->author, PDO::PARAM_STR);
            $resultunitgoal->bindParam(":timeStampmod", $Bpcontent->time, PDO::PARAM_STR);
            $resultunitgoal->bindParam(':bpid', $Bpcontent->bpid, PDO::PARAM_STR);
        }

        if ($resultunitgoal->execute()) {

            if($goalview != $rowsexvalue['GOAL_VIEWPOINT']) {
                require_once ("../Resources/Includes/Data.php");
                $data = new Data();
                $data->setUpdateViewPointOrder($goal_id,$goalview);
            }

            $message[0] = "Unit goals Updated Succesfully.";
        } else {
            $message[0] = "Unit goals could not be Updated.";
        }


    } catch (PDOException $e) {
        error_log($e->getMessage());
    }

}

if(isset($_POST['new_goal_submit'])) {
    $contentlink_id = $_GET['linkid'];
    $Bpcontent->time = date('Y-m-d H:i:s');
    $goaltitle = $Bpcontent->mynl2br($_POST['goaltitle']);

    $unigoallink = $_POST['goallink'];
    foreach ($unigoallink as $value) {
        $unigoallinkname .= $value . ",";
    }
    $goalstatement = $Bpcontent->mynl2br($_POST['goalstatement']);
    $goalalignment = $Bpcontent->mynl2br($_POST['goalalignment']);
    $goalview = $_POST['goal_viewpoint'];
    $goalaction = $Bpcontent->mynl2br($_POST['goal_action']);
    $noRespAction = $_POST['noRespActionPlan'];
    $goalresNeed = $Bpcontent->mynl2br($_POST['goal_resNeedLookAhead']);
    $noRespResNeed = $_POST['noRespResNeed'];
    $goalnotes = $Bpcontent->mynl2br($_POST['goal_notes']);
    $noRespNotes = $_POST['noRespNotes'];

    try {

        $sqlunitgoal = "INSERT INTO `BP_UnitGoals` (OU_ABBREV, GOAL_AUTHOR, MOD_TIMESTAMP, UNIT_GOAL_AY, 
UNIT_GOAL_TITLE, LINK_UNIV_GOAL, GOAL_VIEWPOINT, GOAL_STATEMENT, GOAL_ALIGNMENT, GOAL_ACTION_PLAN,
GOAL_ACTION_PLAN_NO_RESP, GOAL_RSRCS_NEEDED_LOOKAHEAD, GOAL_RSRCS_NEEDED_NO_RESP,GOAL_NOTES,GOAL_NOTES_NO_RESP) VALUES 
(:ouabbrev, :author, :timestampmod, :bpayname, :goaltitle, :unigoallinkname, :goalview, :goalstatement,:goalalignment,
 :goalaction, :noRespAction, :resneed, :noRespResNeed, :goalnotes,:noRespNotes);";

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {
            $sqlunitgoal .= "UPDATE `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR= :author,
            MOD_TIMESTAMP = :timestampmod WHERE ID_CONTENT =:contentlink_id ;";

            $sqlunitgoal .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress',
BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= :author, LastModified = :timestampmod WHERE ID_BROADCAST = :bpid ; ";
        }

        $resultunitgoal = $Bpcontent->connection->prepare($sqlunitgoal);

        $resultunitgoal->bindParam(":ouabbrev", $Bpcontent->ouabbrev, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":author", $Bpcontent->author, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":timestampmod", $Bpcontent->time, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":bpayname", $Bpcontent->bpayname, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":goaltitle", $goaltitle, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":unigoallinkname", $unigoallinkname, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":goalview", $goalview, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":goalstatement", $goalstatement, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":goalalignment", $goalalignment, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":goalaction", $goalaction, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":noRespAction", $noRespAction, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":resneed", $goalresNeed, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":noRespResNeed", $noRespResNeed, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":goalnotes", $goalnotes, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":noRespNotes", $noRespNotes, PDO::PARAM_STR);

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

            $resultunitgoal->bindParam(":author", $Bpcontent->author, PDO::PARAM_STR);
            $resultunitgoal->bindParam(":timestampmod", $Bpcontent->time, PDO::PARAM_STR);
            $resultunitgoal->bindParam(':contentlink_id', $contentlink_id, PDO::PARAM_STR);
            $resultunitgoal->bindParam(":author", $Bpcontent->author, PDO::PARAM_STR);
            $resultunitgoal->bindParam(":timestampmod", $Bpcontent->time, PDO::PARAM_STR);
            $resultunitgoal->bindParam(':bpid', $Bpcontent->bpid, PDO::PARAM_STR);
        }

        if ($resultunitgoal->execute()) {
            require_once ("../Resources/Includes/Data.php");
            $data = new Data();
            $data->initOrderGoals();

            $message[0] = "Unit goals Updated Succesfully.";
        } else {
            $message[0] = "Unit goals could not be Updated.";
        }


    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}

if(isset($_POST['submit_for_approval'])) {

    $Bpcontent->time = date('Y-m-d H:i:s');
    $contentlink_id = $_GET['linkid'];
    $goal_id = $_GET['goal_id'];

    try {
        $sqlunitgoal = "UPDATE `BP_UnitGoals` SET GOAL_STATUS = 'Pending Dean Approval', GOAL_AUTHOR = :author, 
        MOD_TIMESTAMP = :timeStampmod  where ID_UNIT_GOAL = :goal_id;";
        $sqlunitgoal .= "Update `BpContents` set CONTENT_STATUS = 'Pending Dean Approval', BP_AUTHOR= :author,
        MOD_TIMESTAMP =:timeStampmod  where ID_CONTENT = :contentlink_id;";

        $resultunitgoal = $Bpcontent->connection->prepare($sqlunitgoal);

        $resultunitgoal->bindParam(":author", $Bpcontent->author, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":timeStampmod", $Bpcontent->time, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":goal_id", $goal_id, PDO::PARAM_INT);
        $resultunitgoal->bindParam(":author", $Bpcontent->author, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":timeStampmod", $Bpcontent->time, PDO::PARAM_STR);
        $resultunitgoal->bindParam(':contentlink_id', $contentlink_id, PDO::PARAM_STR);

        if ($resultunitgoal->execute()) {

            $message[0] = "Unit Goals Submitted for Approval Successfully";
        } else {
            $message[0] = "Unit Goals Could not be Submitted for Approval. Please Retry.";
        }

    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}

if(isset($_POST['approve'])) {
    $Bpcontent->time = date('Y-m-d H:i:s');
    $goal_id = $_GET['goal_id'];
    $contentlink_id = $_GET['linkid'];

    try {

        $sqlunitgoal = "UPDATE `BP_UnitGoals` SET GOAL_STATUS = 'Dean Approved', 
GOAL_AUTHOR = :author, MOD_TIMESTAMP = :timeStampmod  where ID_UNIT_GOAL =:goal_id; ";

        //check if goal is last goal to approve which should change status of goal overview module to "dean approved".
        $sqlgoalchk .= "AND GOAL_STATUS = 'Dean Approved';";

        $resultgoalchk = $Bpcontent->connection->prepare($sqlgoalchk);
        $resultgoalchk->bindParam(':ouabbrev', $ouabbrev, 2);
        $resultgoalchk->bindParam(':bpayname', $bpayname, 2);
        $resultgoalchk->execute();
        $numrowapprove = $resultgoalchk->rowCount();

        if ($numrow-1 == $numrowapprove) {
            $sqlunitgoal .= "Update `BpContents` set CONTENT_STATUS = 'Dean Approved', BP_AUTHOR= :author,
            MOD_TIMESTAMP =:timeStampmod  where ID_CONTENT =:contentlink_id;";
        }

        $resultunitgoal = $Bpcontent->connection->prepare($sqlunitgoal);

        $resultunitgoal->bindParam(":author", $Bpcontent->author, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":timeStampmod", $Bpcontent->time, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":goal_id", $goal_id, PDO::PARAM_INT);

        if ($numrow-1 == $numrowapprove) {
            $resultunitgoal->bindParam(":author", $Bpcontent->author, PDO::PARAM_STR);
            $resultunitgoal->bindParam(":timestampmod", $Bpcontent->time, PDO::PARAM_STR);
            $resultunitgoal->bindParam(':contentlink_id', $contentlink_id, PDO::PARAM_STR);
        }

        if ($resultunitgoal->execute()) {

            $message[0] = "Unit Goals Approved Successfully";
        } else {
            $message[0] = "Unit Goals Could not be Approved. Please Retry.";
        }

    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}

if(isset($_POST['reject'])) {

    $goal_id = $_GET['goal_id'];

    try {
        $sqlunitgoal = "UPDATE `BP_UnitGoals` SET GOAL_STATUS = 'Dean Rejected', GOAL_AUTHOR = :author, 
        MOD_TIMESTAMP =:timeStampmod  where ID_UNIT_GOAL =:goal_id; ";

        $resultunitgoal = $Bpcontent->connection->prepare($sqlunitgoal);

        $resultunitgoal->bindParam(":author", $Bpcontent->author, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":timeStampmod", $Bpcontent->time, PDO::PARAM_STR);
        $resultunitgoal->bindParam(":goal_id", $goal_id, PDO::PARAM_INT);

        if ($resultunitgoal->execute()) {

            $message[0] = "Unit Goals Rejected Successfully";
        } else {
            $message[0] = "Unit Goals Could not be Rejected. Please Retry.";
        }

    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}

require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<!--<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrapTable.css"/>-->
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.min.css"/>


<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>
<!--<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>-->

<div class="overlay hidden"></div>
<?php if (isset($_POST['goal_submit']) or isset($_POST['new_goal_submit']) or isset($_POST['submit_for_approval']) or isset($_POST['approve']) or isset($_POST['reject'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" onclick="$redirect = $('.alert button').attr('redirect');
		$(window).attr('location',$redirect)" redirect="<?php echo "unitgoaloverview.php?linkid=".$contentlink_id
        ?>" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Blueprint Home</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 id="ayname" class="box-title"><?php echo $rowbroad[0]; ?></h1>
        <p class="status"><span>Org Unit Name:</span> <?php echo $rowbroad[1]; ?></p>
        <p id="ouabbrev" class="hidden"><?php echo $ouabbrev;?></p>
        <p class="status"><span>Status:</span> <?php echo $rowbroad[2]; ?></p>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">

        <h1 class="box-title">Unit Goal</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'].'?linkid='.$contentlink_id.'&goal_id='.$goal_id; ?>"
              onsubmit="if($('#viewpoint').val() == 0) { alert('Please select Goal view point.'); return false; }" method="POST" >

            <div class="form-group">
                <h3>Goal Title<span
                        style="color: red"><sup>*</sup></span></h3>
                <p class="status">
                    <small>Provide a succinct title for the goal, max 150 characters.</small>
                </p>
<!--                <input type="text" class="form-control form-indent wordCount" style="width: 90%" name="goaltitle" id="goaltitle" maxlength="150"-->
<!--                       value="--><?php //echo $rowsexvalue['UNIT_GOAL_TITLE'] ?><!--" required>-->
                <textarea  class="form-control form-indent wordCount" rows="2" col="50" style="width: 95%" name="goaltitle" id="goaltitle" maxlength="150" required><?php echo $Bpcontent->mybr2nl($rowsexvalue['UNIT_GOAL_TITLE']); ?></textarea>
            </div>
            <div class="form-group">
                <h3>Linked to University Goals (select all that apply)</h3>
                <p class="status">
                    <small>Linking to one University Goal is preferred; however, linking is not madatory and you may choose to link to more than one University Goal.</small>
                </p>
                <?php
                $sqlug = "SELECT * FROM UniversityGoals ORDER BY ID_UNIV_GOAL ASC;";
                $resultug = $Bpcontent->connection->prepare($sqlug);
                $resultug->execute();
                $rowsug = $resultug->fetchAll(2);
                $rowCount = $resultug->rowCount();
                $counter = 0;
                while ($counter<$rowCount) { ?>
                    <div class="checkbox form-indent" id="goallink">
                        <label><input type="checkbox" name="goallink[]"
                                      class="checkBoxClass"
                                      value="<?php echo $rowsug[$counter]['ID_UNIV_GOAL']; ?>"
                                <?php
                                $goals_in_db = explode(',', $rowsexvalue['LINK_UNIV_GOAL']);
                                foreach ($goals_in_db as $items) {

                                    if (!strcmp($items, $rowsug[$counter]['ID_UNIV_GOAL'])) {
                                        echo " checked";
                                    }
                                } ?> ><?php echo $rowsug[$counter]['GOAL_TITLE']; ?>
                        </label>
                    </div>
                <?php
                    $counter++;
                } ?>
            </div>
            <div id="goalview" class="form-group">
                <h3>Goal Viewpoint in Report<span
                        style="color: red"><sup>*</sup></span></h3>
                <p class="status">
                    <small>Select how this goal should be presented in the Outcomes Report for this Academic Year.  'Looking Back' will guide you through reporting goal outcomes.  'Real time' will guide you through reporting progress and status of goals for the current Academic Year. 'Looking Ahead' will guide you to provide details on new goals for the upcoming Academic Year.</small>
                </p>
                <select id="viewpoint"  name="goal_viewpoint" style="width: 30%" class="form-control form-indent" required>
                    <option value="0">-- select an option --</option>
                    <?php foreach ($goalviewpoint as $item) { ?>
                        <option
                            value="<?php echo $item ?>" <?php if ($rowsexvalue['GOAL_VIEWPOINT'] == $item) {
                            echo " selected = selected";
                        } ?>><?php echo $item ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <h3>College/School Goal Statement<span
                        style="color: red"><sup>*</sup></span></h3>
                <p class="status">
                    <small>Provide a full statement of the Goal. </small>
                </p>
                <textarea rows="5" class="form-control form-indent" style="width: 95%" name="goalstatement" id="goalstatement"
                          required><?php echo $Bpcontent->mybr2nl($rowsexvalue['GOAL_STATEMENT']); ?></textarea>
            </div>

            <div class="form-group">
                <h3>Describe how this Goal Align with your Mission, Vision & Values<span
                        style="color: red"><sup>*</sup></span></h3>
                <p class="status">
                    <small>Explain how this Goal aligns to your unit's Mission, Vision, and Values.</small>
                </p>
                <textarea rows="5" class="form-control form-indent" style="width: 95%" name="goalalignment" id="goalalignment"
                          required><?php echo $Bpcontent->mybr2nl($rowsexvalue['GOAL_ALIGNMENT']); ?></textarea>
            </div>

            <div  class="form-group">
                <h3>Goal Action Plan</h3>
                <p class="status">
                    <small>Describe your general plan to achieve this goal over the life of the goal.</small>
                </p>
                <textarea name="goal_action" rows="5" cols="25" wrap="hard" style="width: 95%" class="form-control
                form-indent" ><?php echo $Bpcontent->mybr2nl($rowsexvalue['GOAL_ACTION_PLAN']); ?></textarea>
                <div class="checkbox">
                    <label for="optionalCheck">
                        <input type="checkbox" name="noRespActionPlan" class="optionalCheck"
                            <?php
                            if($rowsexvalue['GOAL_ACTION_PLAN_NO_RESP']) {
                                echo ' checked';
                            }
                            ?>
                               id="goal_action"/> No response to this item
                    </label>
                </div>
            </div>

            <div id="resNeedLookAhead"  class="form-group hidden">
                <h3>Resources Needed - Looking Ahead</h3>
                <p class="status">
                    <small>Describe budgetary, personnel, and other resources needed to to undertake the Goal.
                        Note whether those resources are in place and sufficient.</small>
                </p>
                <textarea name="goal_resNeedLookAhead" rows="3" cols="25" wrap="hard" style="width: 95%" class="form-control
                form-indent" ><?php echo $Bpcontent->mybr2nl($rowsexvalue['GOAL_RSRCS_NEEDED_LOOKAHEAD']); ?></textarea>
                <div class="checkbox">
                    <label for="optionalCheck">
                        <input type="checkbox" name="noRespResNeed" class="optionalCheck"
                            <?php
                            if($rowsexvalue['GOAL_RSRCS_NEEDED_NO_RESP']) {
                                echo ' checked';
                            }
                            ?>
                               id="goal_resNeedLookAhead"/> No response to this
                        item
                    </label>
                </div>
            </div>

            <div id="notes" class="form-group">
                <h3>Notes </h3>
                <p class="status">
                    <small>Provide any relevant notes about this forward-looking goal.</small>
                </p>
                <textarea name="goal_notes" rows="3" cols="25" wrap="hard" style="width: 95%" class="form-control
                form-indent" ><?php echo $Bpcontent->mybr2nl($rowsexvalue['GOAL_NOTES']); ?></textarea>
                <div class="checkbox">
                    <label for="optionalCheck">
                        <input type="checkbox" name="noRespNotes" class="optionalCheck"
                            <?php
                            if($rowsexvalue['GOAL_NOTES_NO_RESP']) {
                                echo ' checked';
                            }
                            ?>
                               id="goal_notes"/> No response to this item
                    </label>
                </div>

            </div>

<!--            Edit control (Specific to Page) -->

            <?php if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') { ?>
                <input type="submit" id="goalbtn" name="<?php if ($goal_id != 0) {
                    echo 'goal_submit';
                } else {
                    echo 'new_goal_submit';
                } ?>" value="Save" class="btn-primary">
                <?php if ($rowsgoalstatus[0] <> 'Pending Dean Approval') { ?>
                <input type="submit" name="submit_for_approval" value="Submit for Approval"
                       class="btn-primary" <?php if ($goal_id == 0) echo 'disabled'; ?>>
            <?php } } elseif ($_SESSION['login_role'] == 'dean' OR $_SESSION['login_role'] == 'designee') { ?>
                <input type="submit" id="goalbtn" name="<?php if ($goal_id != 0) {
                    echo 'goal_submit';
                } else {
                    echo 'new_goal_submit';
                } ?>" value="Save" class="btn-primary">
                <?php if ($rowsgoalstatus[0] == 'Pending Dean Approval') { ?>
                    <input type="submit" id="approve" name="approve" value="Approve"
                           class="btn-primary pull-right">
                    <input type="submit" id="reject" name="reject" value="Reject"
                           class="btn-primary pull-right">

                <?php }
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
        $('[data-toggle="tooltip"]').tooltip();

        // On Load function for control viewpoint Look ahead teax check
        var goalviewpoint = $("#viewpoint option:selected").text();
        lookaheadcontrol(goalviewpoint);

    });

    $('#viewpoint').change(function (){
        lookaheadcontrol(this.value);
    });

    function lookaheadcontrol(goalviewpoint) {
        goalviewpoint == "Looking Ahead"? $('#resNeedLookAhead').removeClass("hidden"): $('#resNeedLookAhead')
            .addClass("hidden");
    }
</script>
