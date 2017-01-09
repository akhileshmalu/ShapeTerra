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
$outype = $_SESSION['login_outype'];
$_SESSION['bpayname'] = $bpayname;

if ($outype == "Administration" || $outype == "Service Unit" ) {
    $ouabbrev = $_GET['ou_abbrev'];
    $_SESSION['bpouabbrev'] = $_GET['ou_abbrev'];

} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}


if ($outype == "Administration" || $outype == "Service Unit" ) {
    $sqlbroad = "select BROADCAST_AY,OU_NAME,BROADCAST_STATUS,LastModified from broadcast inner join Hierarchy on broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY where BROADCAST_AY='$bpayname' and Hierarchy.OU_ABBREV ='$ouabbrev';";
} else{
    $sqlbroad = "select BROADCAST_AY,OU_NAME, BROADCAST_STATUS_OTHERS,LastModified from broadcast inner join Hierarchy on broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY where BROADCAST_AY='$bpayname' and BROADCAST_OU ='$ouid'; ";
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
            <p class="status"><span>Org Unit Name:</span> <?php echo $rowbroad[1]; ?></p>
            <p class="status"><span>Status:</span> <?php echo $rowbroad[2]; ?></p>
        </div>

        <div class="col-xs-4">
            <a href="pdfscript.php?ayname=<?php echo $bpayname; ?>" class="btn-primary">Preview</a>
        </div>
    </div>

    <div id="main-box" class="information col-xs-10 col-xs-offset-1">
        <div class="col-xs-8">
            <h1 class="box-title"><span class="plus">+</span><span class="minus hidden">-</span> Information</h1>
            <p class="hidden">Information here</p>
        </div>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">BluePrint Contents</h1>
        <div id="taskboard" style="margin-top: 10px; padding-left: 40px;">
            <table class="bphome" action="taskboard/bphomeajax.php" title="BluePrint Contents">
                <tr>

                    <th col="CONTENT_BRIEF_DESC" href="{{columns.CONTENT_LINK}}?linkid={{columns.ID_CONTENT}}" width="225" type="text">Section</th>
                    <th col="CONTENT_STATUS" width="125" type="text">Status</th>
                    <th col="MOD_TIMESTAMP" width="150" type="text">Last Edited On</th>
                    <th col="BP_AUTHOR"  width="110" type="text">Last Modified</th>

                </tr>
            </table>
        </div>
        <form action="<?php echo "bphome.php?ayname=$bpayname&ouname=$ouid"; ?>" method="POST">
        <div>
            <input type="submit" name="submit_bp" value="Submit BluePrint" class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
        </div>
        </form>
    </div>


</div>


<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>


<script src="../Resources/Library/js/tabAlert.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script src="../Resources/Library/js/taskboard.js"></script>

