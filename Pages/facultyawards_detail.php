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
$award_id = $_GET['award_id'];
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
try {
    if ($ouid == 4) {
        $sqlbroad = "SELECT BROADCAST_AY,OU_NAME,BROADCAST_STATUS,LastModified from broadcast inner join Hierarchy on broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY where BROADCAST_AY= :bpayname and Hierarchy.OU_ABBREV = :ouabbrev;";

        $resultbroad = $connection->prepare($sqlbroad);
        $resultbroad->bindParam(":bpayname", $bpayname, PDO::PARAM_STR);
        $resultbroad->bindParam(":ouabbrev", $ouabbrev, PDO::PARAM_STR);
        
        

    } else {
        $sqlbroad = "SELECT BROADCAST_AY,OU_NAME, BROADCAST_STATUS_OTHERS,LastModified from broadcast inner join Hierarchy on broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY where BROADCAST_AY = :bpayname and BROADCAST_OU = :ouid;";

        $resultbroad = $connection->prepare($sqlbroad);
        $resultbroad->bindParam(":bpayname", $bpayname, PDO::PARAM_STR);
        $resultbroad->bindParam(":ouid", $ouid, PDO::PARAM_STR);
    }

    $resultbroad->execute();
    $rowbroad = $resultbroad->fetch(4);
} catch (PDOException $e) {
    error_log($e->getMessage());
    //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
}

/*
 * SQL check Status of Blueprint Content for Edit restrictions
 */
try {
    $sqlbpstatus = "SELECT CONTENT_STATUS FROM `BpContents` WHERE ID_CONTENT = :id";

    $resultbpstatus = $connection->prepare($sqlbpstatus);
    $resultbpstatus->bindParam(":id", $contentlink_id, PDO::PARAM_INT);
    $resultbpstatus->execute();

    $rowsbpstatus = $resultbpstatus->fetch(4); 
} catch (PDOException $e) {
    error_log($e->getMessage());
    //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
}

/*
 * New award modal Input values
 */
$sqlaward = "select * from AwardType;";
$awardresult = $connection->prepare($sqlaward)->execute();


$sqlawardLoc = "select * from AwardLocation;";
$awardlocresult = $connection->prepare($sqlawardLoc)->execute();



/*
 * SQL for pre-existing Awards Value
 */
$sqlexvalue = "SELECT * FROM AC_FacultyAwards WHERE ID_FACULTY_AWARDS = :id ;";
$rowsexvalue = $connection->prepare($sqlexvalue);
$rowsexvalue->bindParam(":id", $contentlink_id, PDO::PARAM_INT);
$rowsexvalue->execute();

/*
 * Add Modal Record Addition
 */

if(isset($_POST['award_submit'])){

    //  *************************** \\
    //  ********** ERROR ********** \\
    //  ** Can't execute multiple * \\
    //  ** queries in single PDO ** \\
    //  ******** statement ******** \\
    //  *************************** \\

    $awardType = $_POST['awardType'];
    $awardLoc = $_POST['awardLoc'];
    $recipLname = $_POST['recipLname'];
    $recipFname = $_POST['recipFname'];
    $awardTitle = $_POST['awardTitle'];
    $awardOrg = $_POST['awardOrg'];
    $dateAward = $_POST['dateAward'];
    $contentlink_id = $_GET['linkid'];

    $sqlAcFacAward = "UPDATE `AC_FacultyAwards` SET OUTCOMES_AUTHOR = '$author',MOD_TIMESTAMP =
                '$time',AWARD_TYPE = '$awardType', AWARD_LOCATION = '$awardLoc', RECIPIENT_NAME_LAST = '$recipLname',
                RECIPIENT_NAME_FIRST = '$recipFname', AWARD_TITLE = '$awardTitle', AWARDING_ORG = '$awardOrg',DATE_AWARDED ='$dateAwar'
                WHERE ID_FACULTY_AWARDS = '$award_id' ;";

    if($mysqli->query($sqlAcFacAward)){

        $error[0] = "Award Updated Succesfully.";

    } else {
        $error[0] = "Award Could not be Updated.";
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
            <p id="ouabbrev" class="hidden"><?php echo $ouabbrev; ?></p>
            <p class="status"><span>Status:</span> <?php echo $rowbroad[2]; ?></p>
        </div>

    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">

        <form action="<?php echo "facultyawards_detail.php?linkid=".$contentlink_id."&award_id=".$award_id; ?>" method="POST" >

            <div class="form-group">

                <label for="awardtype">Select Award Type:</label>
                <select name="awardType" class="form-control" id="awardtype">
                    <option value=""></option>
                    <?php while ($rowsaward = $resultaward->fetch_assoc()): { ?>
                        <option
                            value="<?php echo $rowsaward['AWARD_TYPE']; ?>"
                        <?php if($rowsaward['AWARD_TYPE'] == $rowsexvalue['AWARD_TYPE']) echo " selected = selected"; ?>>
                            <?php echo $rowsaward['AWARD_TYPE']; ?> </option>
                    <?php } endwhile; ?>
                </select>

                <label for="awardLoc">Select Award Location:</label>
                <select name="awardLoc" class="form-control" id="awardLoc">
                    <option value=""></option>
                    <?php while ($rowsawardLoc = $resultawardLoc->fetch_assoc()): { ?>
                        <option
                            value="<?php echo $rowsawardLoc['ID_AWARD_LOCATION'];?>"<?php if($rowsawardLoc['ID_AWARD_LOCATION'] == $rowsexvalue['AWARD_LOCATION']) { echo " selected = selected"; } ?>><?php echo $rowsawardLoc['AWARD_LOCATION']; ?></option>
                    <?php } endwhile; ?>
                </select>

                <label for="recipLname">Recipient Last Name:</label>
                <input type="text" class="form-control" name="recipLname" value="<?php echo $rowsexvalue['RECIPIENT_NAME_LAST'] ?>" id="recipLname" required>

                <label for="recipFname">Recipient First Name:</label>
                <input type="text" class="form-control" name="recipFname" value="<?php echo $rowsexvalue['RECIPIENT_NAME_FIRST'] ?>" id="recipFname" required>

                <label for="awardtitle">Award Title / Name:</label>
                <input type="text" class="form-control" name="awardTitle" value="<?php echo $rowsexvalue['AWARD_TITLE'] ?>" id="awardtitle" required>

                <label for="awardOrg">Awarding Organization:</label>
                <input type="text" class="form-control" name="awardOrg" value="<?php echo $rowsexvalue['AWARDING_ORG'] ?>" id="awardOrg" required>

                <label for="datetimepicker3">Date Awarded:</label>
                <div class='input-group date' id='datetimepicker3'>
                    <input type='text' name="dateAward" value="<?php echo $rowsexvalue['DATE_AWARDED'] ?>" class="form-control" required>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                </div>

                <?php if ($_SESSION['login_role'] != 'reviewer'): ?>
                <input type="submit" id="awardbtn" name="award_submit" value="Save" class="btn-primary">
                <?php endif; ?>

            </div>

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
