<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

/*
 * This Page controls Academic BluePrint Home.
 */

session_start();
$error = array();
$errorflag =0;

require_once ("../Resources/Includes/connect.php");

$bpayname = $_GET['ayname'];
$ouid = $_SESSION['login_ouid'];
$_SESSION['bpayname'] = $bpayname;


if ($ouid == 4) {
    $sqlbroad = "select BROADCAST_AY,BROADCAST_STATUS,LastModified from broadcast where BROADCAST_AY='$bpayname';";
} else{
    $sqlbroad = "select BROADCAST_AY,BROADCAST_STATUS_OTHERS,LastModified from broadcast where BROADCAST_AY='$bpayname' and BROADCAST_OU ='$ouid'; ";
}
$resultbroad = $mysqli->query($sqlbroad);
$rowbroad = $resultbroad->fetch_array(MYSQLI_NUM);

//Menu control for back to dashboard button
//true: Dont show button
//false: show button
$notBackToDashboard = true;


if(isset($_POST['submit_bp'])) {

    $status = "Pending approval";
    $bpayname = $_GET['ayname'];
    $ouid = $_GET['ouid'];

    $sqlbroadupdate = "update broadcast set BROADCAST_STATUS='$status',BROADCAST_STATUS_OTHERS='$status'where BROADCAST_AY='$bpayname' and BROADCAST_OU ='$ouid';  ";

    if($mysqli->query($sqlbroadupdate)){
        $error[0] = "Academic BluePrint Submitted Successfully.";

    } else {
        $error[0] = "Academic BluePrint could not be Submitted. Please retry.";
    }

}




require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrapTable.css"/>-->
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.min.css"/>
<link rel="stylesheet" href="Css/grid.css" title="openJsGrid"/>
<script src="../Resources/Library/js/root.js"></script>
<script src="../Resources/Library/js/grid.js"></script>




<div class="overlay hidden"></div>
<?php if (isset($_POST['submit_bp'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" redirect="account.php" class="end btn-primary">Close</button>
    </div>
<?php } ?>

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

        <div class="col-xs-4">
            <a href="#" class="btn-primary">Preview</a>
        </div>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">BluePrint Contents</h1>
        <div id="" style="margin-top: 10px; padding-left: 40px;">
            <table class="grid" action="taskboard/bphomeajax.php" title="BluePrint Contents">
                <tr>
                    <th col="CONTENT_BRIEF_DESC" href="{{columns.CONTENT_LINK}}?linkid={{columns.ID_CONTENT}}" width="300" type="text">Type</th>
                    <th col="CONTENT_STATUS" width="150" type="text">Award</th>
                    <th col="MOD_TIMESTAMP" width="150" type="text">Last Edited On</th>
                    <th col="BP_AUTHOR" width="150" type="text">Last Modified By</th>
                    <!--                                        <th col="" type="text">Actions</th>-->
                </tr>
            </table>
        </div>
        <form action="<?php echo "bphome.php?ayname=$bpayname&ouname=$ouid"; ?>" method="POST">
        <div>
            <input type="submit" name="submit_bp" value="Submit BluePrint" class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
        </div>
        </form>
    </div>


<!--    <div id="main-box" class="col-xs-5 col-xs-offset-1">-->
<!--        <h1 class="box-title">Tasks</h1>-->
<!--        <ul class="task-list">-->
<!--            <li><a href="mvv.php"><span class="icon">&#xe01c;</span> Create BluePrint</a></li>-->
<!--            <li><a href="unitgoaloverview.php"><span class="icon">&#xe01c;</span> Goal Overview & Management</a></li>-->
<!--            <li><a href="goaloutcomeshome.php"><span class="icon">&#xe01c;</span> Goal Outcomes Summary</a></li>-->
<!--            <li><a href="facultyawards.php"><span class="icon">&#xe01c;</span> Faculty Awards</a></li>-->
<!--            <li><a href="facultyInfo.php"><span class="icon">&#xe01c;</span> Faculty Info</a></li>-->
<!--            <li><a href="initiatives.php"><span class="icon">&#xe01c;</span> Initiatives & Observations</a></li>-->
<!--        </ul>-->
<!--    </div>-->

<!--    <div id="main-box" class="col-xs-4 col-xs-offset-1 ">-->
<!--        <h1 class="box-title">Completed Tasks</h1>-->
<!--        <ul class="task-completed-list">-->
<!--            <li><a><span class="icon">S</span> Task 4</a></li>-->
<!--            <li><a><span class="icon">S</span> Task 5</a></li>-->
<!--        </ul>-->
<!--    </div>-->

</div>


<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>


<script src="../Resources/Library/js/tabchange.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script src="../Resources/Library/js/taskboard.js"></script>

