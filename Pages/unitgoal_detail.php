<?php


/*
 * This Page controls Faculty Awards Screen.
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
$BackToGoal = true;

/*
 * Connection to DataBase.
 */
require_once ("../Resources/Includes/connect.php");

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

if ($ouid == 4) {
    $ouabbrev = $_SESSION['bpouabbrev'];
} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}


/*
 * BluePrint Header Info; conditional for provost & other users
 */
if ($ouid == 4) {
    $sqlbroad = "select BROADCAST_AY,OU_NAME,BROADCAST_STATUS,LastModified from broadcast inner join Hierarchy on broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY where BROADCAST_AY='$bpayname' and Hierarchy.OU_ABBREV ='$ouabbrev';";
} else{
    $sqlbroad = "select BROADCAST_AY,OU_NAME, BROADCAST_STATUS_OTHERS,LastModified from broadcast inner join Hierarchy on broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY where BROADCAST_AY='$bpayname' and BROADCAST_OU ='$ouid'; ";
}
$resultbroad = $mysqli->query($sqlbroad);
$rowbroad = $resultbroad->fetch_array(MYSQLI_NUM);

/*
 * SQL for pre-existing Goal Value
 */
$sqlexvalue = "SELECT * FROM `BP_UnitGoals` WHERE ID_UNIT_GOAL = '$goal_id' ;";
$resultexvalue = $mysqli->query($sqlexvalue);
$rowsexvalue = $resultexvalue->fetch_assoc();

// SQL for Goal Status for Edit control
$sqlgoalstatus = "SELECT GOAL_STATUS FROM BP_UnitGoals WHERE ID_UNIT_GOAL = '$goal_id';";
$resultgoalstatus = $mysqli->query($sqlgoalstatus);
$rowsgoalstatus = $resultgoalstatus->fetch_array(MYSQLI_NUM);

//SQL to check goal level 'goal approval' by Dean to action content status 'Goal Approval'
// Checks if given goal is last goal to approve ; if yes then create sql to change content status of Goal Overview module.
$sqlgoalchk = "SELECT * FROM BP_UnitGoals WHERE OU_ABBREV = '$ouabbrev' AND UNIT_GOAL_AY='$bpayname' ";
$resultgoalchk = $mysqli->query($sqlgoalchk);
$numrow = $resultgoalchk->num_rows;

//AND GOAL_STATUS = 'Dean Approved';
/*
 * Add UNIT GOAL Modal
 */

if(isset($_POST['goal_submit'])) {
    $contentlink_id = $_GET['linkid'];
    $goal_id = $_GET['goal_id'];
    $goaltitle = $_POST['goaltitle'];

    $unigoallink = $_POST['goallink'];
    foreach ($unigoallink as $value) {
        $unigoallinkname .= $value . ",";
    }
    $goalstatement = mynl2br($_POST['goalstatement']);
    $goalalignment = mynl2br($_POST['goalalignment']);

    $sqlunitgoal = "UPDATE `BP_UnitGoals` SET GOAL_AUTHOR = '$author', MOD_TIMESTAMP ='$time',GOAL_STATUS = 'In Progress', UNIT_GOAL_TITLE = '$goaltitle', LINK_UNIV_GOAL = '$unigoallinkname', GOAL_STATEMENT = '$goalstatement', GOAL_ALIGNMENT = '$goalalignment' WHERE ID_UNIT_GOAL = '$goal_id' ;";

    $sqlunitgoal .= "Update  `BpContents` set CONTENT_STATUS = 'In Progress', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

    $sqlunitgoal .= "Update  `broadcast` set BROADCAST_STATUS = 'In Progress',BROADCAST_STATUS_OTHERS = 'In Progress',  AUTHOR= '$author',LastModified ='$time' where ID_BROADCAST = '$bpid'; ";

    if($mysqli->multi_query($sqlunitgoal)) {

        $error[0] = "Unit goals Updated Succesfully.";

    } else {
        $error[0] = "Unit goals could not be Updated.";
    }


}

if(isset($_POST['new_goal_submit'])) {
    $contentlink_id = $_GET['linkid'];

    $goaltitle = $_POST['goaltitle'];

    $unigoallink = $_POST['goallink'];
    foreach ($unigoallink as $value) {
        $unigoallinkname .= $value . ",";
    }
    $goalstatement = mynl2br($_POST['goalstatement']);
    $goalalignment = mynl2br($_POST['goalalignment']);

    $sqlunitgoal = "INSERT INTO `BP_UnitGoals` (OU_ABBREV, GOAL_AUTHOR, MOD_TIMESTAMP, UNIT_GOAL_AY, UNIT_GOAL_TITLE, LINK_UNIV_GOAL, GOAL_STATEMENT, GOAL_ALIGNMENT) 
VALUES ('$ouabbrev','$author','$time','$bpayname','$goaltitle','$unigoallinkname','$goalstatement','$goalalignment');";

    $sqlunitgoal .= "Update  `BpContents` set CONTENT_STATUS = 'In Progress', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

    $sqlunitgoal .= "Update  `broadcast` set BROADCAST_STATUS = 'In Progress',BROADCAST_STATUS_OTHERS = 'In Progress',  AUTHOR= '$author',LastModified ='$time' where ID_BROADCAST = '$bpid'; ";


    if($mysqli->multi_query($sqlunitgoal)) {

        $error[0] = "Unit goals Updated Succesfully.";

    } else {
        $error[0] = "Unit goals could not be Updated.";
    }

}

if(isset($_POST['submit_for_approval'])) {
    $contentlink_id = $_GET['linkid'];
    $goal_id = $_GET['goal_id'];
    $sqlunitgoal = "UPDATE `BP_UnitGoals` SET GOAL_STATUS = 'Pending Dean Approval', GOAL_AUTHOR = '$author', MOD_TIMESTAMP ='$time'  where ID_UNIT_GOAL ='$goal_id'; ";
    $sqlunitgoal .= "Update `BpContents` set CONTENT_STATUS = 'Pending Dean Approval', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";
    if ($mysqli->multi_query($sqlunitgoal)) {
        $error[0] = "Unit Goals Submitted for Approval Successfully";
    } else {
        $error[0] = "Unit Goals Could not be Submitted for Approval. Please Retry.";
    }
}
if(isset($_POST['approve'])) {
    $goal_id = $_GET['goal_id'];
    $contentlink_id = $_GET['linkid'];
    $sqlunitgoal = "UPDATE `BP_UnitGoals` SET GOAL_STATUS = 'Dean Approved', GOAL_AUTHOR = '$author', MOD_TIMESTAMP ='$time'  where ID_UNIT_GOAL ='$goal_id'; ";

    //check if goal is last goal to approve which should change status of goal overview module to "dean approved".
    $sqlgoalchk .= "AND GOAL_STATUS = 'Dean Approved';";
    $resultgoalchk = $mysqli->query($sqlgoalchk);
    $numrowapprove = $resultgoalchk->num_rows;

    if ($numrow-1 == $numrowapprove) {
        $sqlunitgoal .= "Update `BpContents` set CONTENT_STATUS = 'Dean Approved', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";
    }

    if ($mysqli->multi_query($sqlunitgoal)) {
        $error[0] = "Unit Goals Approved Successfully";
    } else {
        $error[0] = "Unit Goals Could not be Approved. Please Retry.";
    }
}

if(isset($_POST['reject'])) {
    $goal_id = $_GET['goal_id'];
    $sqlunitgoal = "UPDATE `BP_UnitGoals` SET GOAL_STATUS = 'Dean Rejected', GOAL_AUTHOR = '$author', MOD_TIMESTAMP ='$time'  where ID_UNIT_GOAL ='$goal_id'; ";
    if ($mysqli->query($sqlunitgoal)) {
        $error[0] = "Unit Goals Rejected Successfully";
    } else {
        $error[0] = "Unit Goals Could not be Rejected. Please Retry.";
    }
}

require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<!--<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrapTable.css"/>-->
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.min.css"/>
<link rel="stylesheet" href="Css/grid.css" title="openJsGrid"/>
<script src="../Resources/Library/js/root.js"></script>
<script src="../Resources/Library/js/grid.js"></script>


<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>

<div class="overlay hidden"></div>
<?php if (isset($_POST['goal_submit']) or isset($_POST['new_goal_submit']) or isset($_POST['submit_for_approval']) or isset($_POST['approve']) or isset($_POST['reject'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" redirect="<?php echo "unitgoaloverview.php?linkid=".$contentlink_id ?>" class="end btn-primary">Close</button>
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
        <form action="<?php echo $_SERVER['PHP_SELF'].'?linkid='.$contentlink_id.'&goal_id='.$goal_id; ?>" method="POST" >

                <div class="form-group">
                    <h3>Goal Title<span
                            style="color: red"><sup>*</sup></span></h3>
                    <input type="text" class="form-control form-indent" name="goaltitle" id="goaltitle" value="<?php echo $rowsexvalue['UNIT_GOAL_TITLE'] ?>" required>
                </div>
                <div class="form-group">
                    <h3>Linked to University Goals (select all that apply)</h3>

                    <?php
                    $sqlug = "SELECT * FROM UniversityGoals;";
                    $resultug = $mysqli->query($sqlug);
                    while ($rowsug = $resultug->fetch_assoc()) { ?>
                        <div class="checkbox form-indent" id="goallink">
                            <label><input type="checkbox" name="goallink[]"
                                          class="checkBoxClass"
                                          value="<?php echo $rowsug['ID_UNIV_GOAL']; ?>"
                                          <?php
                                          $goals_in_db = explode(',', $rowsexvalue['LINK_UNIV_GOAL']);
                                          foreach ($goals_in_db as $items) {

                                              if (!strcmp($items,$rowsug['ID_UNIV_GOAL'])) {
                                                  echo " checked";
                                              }
                                          } ?> ><?php echo $rowsug['GOAL_TITLE']; ?>
                            </label>

                        </div>
                    <?php } ?>

                </div>
                <div class="form-group">
                    <h3>College/School Goal Statement<span
                            style="color: red"><sup>*</sup></span></h3>

                    <textarea rows="5" class="form-control form-indent" name="goalstatement" id="goalstatement"
                              required><?php echo mybr2nl($rowsexvalue['GOAL_STATEMENT']); ?></textarea>
                </div>
                <div class="form-group">

                    <h3>Describe how this Goal Align with your Mission, Vision & Values<span
                            style="color: red"><sup>*</sup></span></h3>
                    <textarea rows="5" class="form-control form-indent" name="goalalignment" id="goalalignment"
                              required><?php echo mybr2nl($rowsexvalue['GOAL_ALIGNMENT']); ?></textarea>
                </div>

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
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
<script src="../Resources/Library/js/tabAlert.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script src="../Resources/Library/js/taskboard.js"></script>

