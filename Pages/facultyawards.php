<?php

$pagename = "bphome";

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
$errorflag = 0;
$BackToDashboard = true;

/*
 * Connection to DataBase.
 */
require_once ("../Resources/Includes/connect.php");

/*
 * Local & Session variable Initialization
 */
$bpid = $_SESSION['bpid'];
$contentlink_id = $_GET['linkid'];
$bpayname = $_SESSION['bpayname'];
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
 * SQL check Status of Blueprint Content for Edit restrictions
 */
$sqlbpstatus = "SELECT CONTENT_STATUS FROM BpContents WHERE ID_CONTENT = '$contentlink_id';";
$resultbpstatus = $mysqli->query($sqlbpstatus);
$rowsbpstatus = $resultbpstatus->fetch_assoc();

/*
 * New award modal Input values
 */
$sqlaward = "select * from AwardType;";
$resultaward = $mysqli->query($sqlaward);



/*
 * Add Modal Record Addition
 */

if(isset($_POST['award_submit'])){

    $awardType = $_POST['awardType'];
    $recipLname = $_POST['recipLname'];
    $recipFname = $_POST['recipFname'];
    $awardTitle = $_POST['awardTitle'];
    $awardOrg = $_POST['awardOrg'];
    $dateAward = $_POST['dateAward'];
    $contentlink_id = $_GET['linkid'];

    $sqlAcFacAward = "INSERT INTO `AC_FacultyAwards`
(OU_ABBREV,OUTCOMES_AY,OUTCOMES_AUTHOR,MOD_TIMESTAMP,AWARD_TYPE,RECIPIENT_NAME_LAST,RECIPIENT_NAME_FIRST,AWARD_TITLE,AWARDING_ORG,DATE_AWARDED)
VALUES('$ouabbrev','$bpayname','$author','$time','$awardType','$recipLname','$recipFname','$awardTitle','$awardOrg','$dateAward');";

    $sqlAcFacAward .= "Update `BpContents` set CONTENT_STATUS = 'In progress', BP_AUTHOR = '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

    $sqlAcFacAward .= "Update  `broadcast` set BROADCAST_STATUS = 'In Progress', BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= '$author', LastModified ='$time' where ID_BROADCAST = '$bpid'; ";

    if($mysqli->multi_query($sqlAcFacAward)){

        $error[0] = "Award Added Succesfully.";

    } else {
        $error[0] = "Award Could not be Added.";
    }

}

if(isset($_POST['submit_approve'])) {

    $contentlink_id = $_GET['linkid'];

    $sqlcreatebp .= "Update  `BpContents` set CONTENT_STATUS = 'Pending Dean Approval', BP_AUTHOR= '$author',MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id';";

    if ($mysqli->query($sqlcreatebp)) {

        $error[0] = "Faculty Awards submitted Successfully";

    } else {
        $error[0] = "Faculty Awards Could not be submitted. Please Retry.";
    }


}

if(isset($_POST['approve'])) {

    $contentlink_id = $_GET['linkid'];
    $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Approved', BP_AUTHOR= '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";
    if ($mysqli->query($sqlmission)) {
        $error[0] = "Faculty Awards Approved Successfully";
    } else {
        $error[0] = "Faculty Awards Could not be Approved. Please Retry.";
    }
}

if(isset($_POST['reject'])) {

    $contentlink_id = $_GET['linkid'];
    $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Rejected', BP_AUTHOR= '$author', MOD_TIMESTAMP ='$time'  where ID_CONTENT ='$contentlink_id'; ";
    if ($mysqli->query($sqlmission)) {
        $error[0] = "Faculty Awards Rejected Successfully";
    } else {
        $error[0] = "Faculty Awards Could not be Rejected. Please Retry.";
    }
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



<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<div class="overlay hidden"></div>
<?php if (isset($_POST['award_submit']) OR isset($_POST['submit_approve']) OR isset($_POST['approve']) OR isset($_POST['reject'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" redirect="bphome.php?ayname=<?php echo $rowbroad[0]; ?>" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Blueprint Home</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <div class="col-xs-8">
            <h1 id="ayname" class="box-title"><?php echo $rowbroad[0]; ?></h1>
            <p class="status"><span>Org Unit Name:</span> <?php echo $rowbroad[1]; ?></p>
            <p id="ouabbrev" class="hidden"><?php echo $ouabbrev;?></p>
            <p class="status"><span>Status:</span> <?php echo $rowbroad[2]; ?></p>
        </div>
        
<!--        <div class="col-xs-4">-->
<!--            <a href="#" class="btn-primary">Preview</a>-->
<!--        </div>-->
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <!--                        Reviewer Edit Control-->
        <?php if ($_SESSION['login_right'] != 1): ?>

            <div id="addnew" class="">
                <button id="add-mission" type="button" class="btn-secondary  col-lg-3 col-md-7 col-sm-8 pull-right"
                        data-toggle="modal"
                        data-target="#addawardModal"><span class="icon">&#xe035;</span> Add New Awards
                </button>
            </div>

        <?php endif; ?>
        <h1 class="box-title">Faculty Awards</h1>
        <div id="taskboard" style="margin-top: 10px;">
            <table class="grid" action="taskboard/facultyajax.php" title="Faculty Awards">
                <tr>
                    <th col="AWARD_TYPE" width="100" type="text">Type</th>
                    <th col="AWARD_TITLE" href="<?php echo "facultyawards_detail.php?linkid=".$contentlink_id."&award_id="?>{{columns.ID_FACULTY_AWARDS}}" width="300" type="text">Award</th>
                    <th col="RECIPIENT_NAME" width="200" type="text">Recipient(s)</th>
<!--                                        <th col="" type="text">Actions</th>-->
                </tr>
            </table>
        </div>
        <form action="<?php echo "facultyawards.php?linkid=".$contentlink_id ?>" method="POST" >

            <!--                        Edit Control-->
            <?php if (($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead' ) AND ($rowsbpstatus['CONTENT_STATUS']=='In Progress' OR $rowsbpstatus['CONTENT_STATUS']=='Dean Rejected' OR $rowsbpstatus['CONTENT_STATUS']=='Not Started') ) { ?>

                <input type="submit" id="approve" name="submit_approve" value="Submit For Approval" class="btn-primary pull-right" >

            <?php } elseif ($_SESSION['login_role'] == 'dean' OR $_SESSION['login_role'] == 'designee') {
                if($rowsbpstatus['CONTENT_STATUS'] == 'Pending Dean Approval') { ?>
                    <input type="submit" id="approve" name="approve" value="Approve"
                           class="btn-primary pull-right">
                    <input type="submit" id="reject" name="reject" value="Reject"
                           class="btn-primary pull-right">
                <?php }
            } ?>
        </form>

    </div>
</div>


<!--Modal for Addition of New Awards-->

<div class="modal fade" id="addawardModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Add Faculty Awards</h4>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?php echo "facultyawards.php?linkid=".$contentlink_id; ?>" class="ajaxform">
                <div class="form-group">

                    <label for="awardtype">Select Award Type:</label>
                    <select  name="awardType" class="form-control" id="awardtype">
                        <option value=""></option>
                        <?php while ($rowsaward = $resultaward->fetch_assoc()): { ?>
                            <option value="<?php echo $rowsaward['AWARD_TYPE']; ?>"> <?php echo $rowsaward['AWARD_TYPE']; ?> </option>
                        <?php } endwhile; ?>
                    </select>

                    <label for="recipLname">Recipient Last Name:</label>
                    <input type="text" class="form-control" name="recipLname" id="recipLname" required>

                    <label for="recipFname">Recipient First Name:</label>
                    <input type="text" class="form-control" name="recipFname" id="recipFname" required>

                    <label for="awardtitle">Award Title / Name:</label>
                    <input type="text" class="form-control" name="awardTitle" id="awardtitle" required>

                    <label for="awardOrg">Awarding Organization:</label>
                    <input type="text" class="form-control" name="awardOrg" id="awardOrg" required>

                     <label for="datetimepicker3">Date Awarded:</label>
                    <div class='input-group date' id='datetimepicker3'>
                        <input type='text' name="dateAward" class="form-control" required>
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    <input type="submit" id="awardbtn" name="award_submit" value="Save" class="btn-primary">
                </div> 

            </form>
        </div>
        <div class="modal-footer">
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
<script src="../Resources/Library/js/tabAlert.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script src="../Resources/Library/js/taskboard.js"></script>
