<?php

$pagename = "bphome";

/*
 * This Page controls Faculty Awards Screen.
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

$bpayname =$_SESSION['bpayname'];
$ouid = $_SESSION['login_ouid'];
$ouabbrev = $_SESSION['login_ouabbrev'];
$date = date("Y-m-d");
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_email'];

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

    $sqlAcFacAward = "INSERT INTO AC_FacultyAwards
(OU_ABBREV,OUTCOMES_AY,OUTCOMES_AUTHOR,MOD_TIMESTAMP,AWARD_TYPE,RECIPIENT_NAME_LAST,RECIPIENT_NAME_FIRST,AWARD_TITLE,AWARDING_ORG,DATE_AWARDED)
VALUES('$ouabbrev','$bpayname','$author','$time','$awardType','$recipLname','$recipFname','$awardTitle','$awardOrg','$dateAward');";
    if($mysqli->query($sqlAcFacAward)){

        $error[0] = "Award Added Succesfully.";

    } else {
        $error[0] = "Award Could not be Added.";
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



<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<div class="overlay hidden"></div>
<?php if (isset($_POST['submit'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Blueprint Home</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title"><?php echo $rowbroad[0]; ?></h1>
        <p class="status"><span>Status:</span> <?php echo $rowbroad[1]; ?></p>
        <p class="status"><span>Last Modified:</span> <?php echo date("F j, Y, g:i a", strtotime($rowbroad[2])); ?></p>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Faculty Awards</h1>
        <div id="" style="margin-top: 10px;">
            <table class="grid" action="taskboard/facultyajax.php" title="Faculty Awards">
                <tr>
                    <th col="AWARD_TYPE" width="100" type="text">Type</th>
                    <th col="AWARD_TITLE" width="300" type="text">Award</th>
                    <th col="RECIPIENT_NAME" width="200" type="text">Recipient(s)</th>
<!--                                        <th col="" type="text">Actions</th>-->
                </tr>
            </table>
        </div>
        <div id="addnew" class="">
            <button id="add-mission" type="button" class="btn-secondary  col-lg-3 col-md-7 col-sm-8 pull-left"
                    data-toggle="modal"
                    data-target="#addawardModal"><span class="icon">&#xe035;</span> Add New Awards
            </button>
        </div>
    </div>
</div>


<!--Modal for Addition of New Awards-->

<div class="modal fade" id="addawardModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <!--    <div class="modal-dialog" role="dialog">-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Add Faculty Awards</h4>
        </div>
        <div class="modal-body">
            <form method="POST" action="facultyawards.php" class="ajaxform">
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

                    <input type="submit" id="awardbtn" name="award_submit" value="Save"
                           class="btn-primary btn-sm">
                </div>
            </form>
            <div class="modal-footer">
            </div>
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
<script src="../Resources/Library/js/taskboard.js"></script>
