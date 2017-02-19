<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
/*
 * This Page controls Academic BluePrint Home.
 */
require_once ("../Resources/Includes/initalize.php");
$initalize = new Initialize();
$initalize->checkSessionStatus();
$connection = $initalize->connection;

$message = array();
$errorflag =0;
require_once ("../Resources/Includes/connect.php");
$FUayname = $_GET['ayname'];
$ouid = $_SESSION['login_ouid'];
$outype = $_SESSION['login_outype'];
$_SESSION['FUayname'] = $FUayname;
$notBackToDashboard = true;
$sqlbroad = "select BROADCAST_AY,OU_NAME, BROADCAST_STATUS_OTHERS,LastModified from broadcast inner join Hierarchy on broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY where BROADCAST_AY='$bpayname' and BROADCAST_OU ='$ouid'; ";
$resultbroad = $mysqli->query($sqlbroad);
$rowbroad = $resultbroad->fetch_array(MYSQLI_NUM);
//Menu control for back to dashboard button
//true: Dont show button
//false: show button
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

<!---->
<!--<div class="overlay hidden"></div>-->
<?php //if (isset($_POST['submit_bp'])) { ?>
<!--    <div class="alert">-->
<!--        <a href="#" class="close end"><span class="icon">9</span></a>-->
<!--        <h1 class="title"></h1>-->
<!--        <p class="description">--><?php //foreach ($error as $value) echo $value; ?><!--</p>-->
<!--        <button type="button" redirect="account.php" class="end btn-primary">Close</button>-->
<!--    </div>-->
<?php //} ?>

<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">File Upload Service</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <div class="col-xs-8">
            <h1 class="box-title"><?php echo $FUayname; ?> </h1>
            <p class="status"><span>Org Unit Name: </span> <?php echo $_SESSION['login_ouname']; ?></p>
            <!--            <p class="status"><span>User role: </span> --><?php //echo $rowsmenu['USER_RIGHT']; ?><!--</p>-->
        </div>

    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">

        <h1 class="box-title">Select File Type for Action</h1>
        <label for ="fileupload">The overview below indicates the current status of each file type expected for the above Academic Year. Click any file type to  upload new or view content of previously uploaded files.</label>
        <div id="fileupload" style="margin-top: 10px; padding-left: 40px;">
            <table class="fileupload" action="taskboard/fileuploadstatusajax.php" title="File Upload Contents">
                <tr>

                    <th col="NAME_UPLOADFILE" href="{{columns.LINK_UPLOADFILE}}?linkid={{columns.ID_UPLOADFILE}}"  width="225" type="text">Section</th>
                    <th col="STATUS_UPLOADFILE" width="125" type="text">Status</th>
                    <th col="MOD_TIMESTAMP" width="150" type="text">Last Edited On</th>
                    <th col="FILE_AUTHOR"  width="130" type="text">Last Modified By</th>

                </tr>
            </table>
        </div>
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
