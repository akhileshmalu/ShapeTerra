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

    $sqlunitgoal = "UPDATE `BP_UnitGoals` SET GOAL_AUTHOR = '$author', MOD_TIMESTAMP ='$time',
 UNIT_GOAL_TITLE = '$goaltitle', LINK_UNIV_GOAL = $unigoallinkname, GOAL_STATEMENT = '$goalstatement',
  GOAL_ALIGNMENT = '$goalalignment' WHERE ID_UNIT_GOAL = '$goal_id' ;";

    if($mysqli->multi_query($sqlunitgoal)) {

        $error[0] = "Unit goals Updated Succesfully.";

    } else {
        $error[0] = "Unit goals could not be Updated.";
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
<?php if (isset($_POST['goal_submit'])) { ?>
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

                <input type="submit" id="goalbtn" name="goal_submit" value="Save" class="btn-primary">

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

