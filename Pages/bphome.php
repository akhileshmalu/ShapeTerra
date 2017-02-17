<?php

session_start();
if(!$_SESSION['isLogged']) {
    header("location:login.php");
    die();
}

require_once ("../Resources/Includes/connect.php");

$message = array();
$errorflag = 0;
@$bpid = $_GET['id'];
$bpayname = $_GET['ayname'];
$ouid = $_SESSION['login_ouid'];
$outype = $_SESSION['login_outype'];
$_SESSION['bpayname'] = $bpayname;
$_SESSION['bpid'] = $bpid;

if ($outype == "Administration") {
    $ouabbrev = $_GET['ou_abbrev'];
    $_SESSION['bpouabbrev'] = $_GET['ou_abbrev'];
} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}

// SQL to display Blueprint Content
try
{
    $sqlbpcontent = "SELECT * FROM `BpContents` INNER JOIN broadcast ON 
BpContents.Linked_BP_ID = broadcast.ID_BROADCAST LEFT JOIN PermittedUsers ON PermittedUsers.ID_STATUS = broadcast
.AUTHOR WHERE OU_ABBREV = :ouabbrev AND BROADCAST_AY=:bpayname ";
    $resultbpcontent = $connection->prepare($sqlbpcontent);
    $resultbpcontent->bindParam(':ouabbrev', $ouabbrev, 2);
    $resultbpcontent->bindParam(':bpayname', $bpayname, 2);
    $resultbpcontent->execute();

    $numbpcontent = $resultbpcontent->rowCount();
}
catch (PDOException $e)
{
//SYSTEM::pLog($e->__toString(),$_SERVER['PHP_SELF']);
    error_log($e->getMessage());
}

try
{
    if ($outype == "Administration" || $outype == "Service Unit") {
        $sqlbroad = "SELECT BROADCAST_AY,OU_NAME,BROADCAST_STATUS,LastModified FROM broadcast INNER JOIN Hierarchy ON 
    broadcast.BROADCAST_OU=Hierarchy.ID_HIERARCHY WHERE BROADCAST_AY=:bpayname AND Hierarchy.OU_ABBREV =:ouabbrev ;";
        $resultbroad = $connection->prepare($sqlbroad);
        $resultbroad->bindParam(':bpayname', $bpayname, 2);
        $resultbroad->bindParam(':ouabbrev', $ouabbrev, 2);

    } else {
        $sqlbroad = "SELECT BROADCAST_AY,OU_NAME, BROADCAST_STATUS_OTHERS,LastModified FROM broadcast INNER JOIN 
    Hierarchy ON broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY WHERE BROADCAST_AY=:bpayname AND BROADCAST_OU =:ouid;";
        $resultbroad = $connection->prepare($sqlbroad);
        $resultbroad->bindParam(':bpayname', $bpayname, 2);
        $resultbroad->bindParam(':ouid', $ouid, 1);
    }
    $resultbroad->execute();
}
catch (PDOException $e)
{
//SYSTEM::pLog($e->__toString(),$_SERVER['PHP_SELF']);
    error_log($e->getMessage());
}
$rowbroad = $resultbroad->fetch(4);


if(isset($_POST['submit_bp'])) {

    $bpayname = $_GET['ayname'];
    $bpid = $_GET['id'];
    try
    {
        $sqlbroadupdate = "UPDATE `broadcast` SET BROADCAST_STATUS='Submitted Draft', 
BROADCAST_STATUS_OTHERS='Submitted Draft' WHERE BROADCAST_AY=:bpayname AND BROADCAST_OU =:ouid; ";
        $resultbroadupdate = $connection->prepare($sqlbroadupdate);
        $resultbroadupdate->bindParam(':bpayname', $bpayname, 2);
        $resultbroadupdate->bindParam(':ouid', $ouid, 1);

        if ($resultbroadupdate->execute()) {
            $message[0] = "Academic BluePrint Draft Submitted Successfully.";
        } else {
            $message[0] = "Academic BluePrint Draft could not be Submitted. Please retry.";
        }
    }
    catch (PDOException $e)
    {
//SYSTEM::pLog($e->__toString(),$_SERVER['PHP_SELF']);
        error_log($e->getMessage());
    }
}


if(isset($_POST['approve'])) {

    $bpid = $_GET['id'];
    $bpayname = $_GET['ayname'];

    try
    {
        $sqlbroadupdate = "UPDATE `broadcast` SET BROADCAST_STATUS='Final', BROADCAST_STATUS_OTHERS='Final' WHERE 
    ID_BROADCAST = :bpid;";
        $resultbroadupdate = $connection->prepare($sqlbroadupdate);
        if ($resultbroadupdate->execute(['bpid' => $bpid])) {
            $message[0] = "Academic BluePrint Draft Submitted Successfully.";
        } else {
            $message[0] = "Academic BluePrint Draft could not be Submitted. Please retry.";
        }
    }
    catch (PDOException $e)
    {
//SYSTEM::pLog($e->__toString(),$_SERVER['PHP_SELF']);
        error_log($e->getMessage());
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
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
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
            <a href="../Resources/Includes/fpdf_extended.php" target="_Blank" class="btn-primary">View PDF</a>
        </div>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Blueprint</h1>
        <div id="list">
            <ul class="list-nav">
                <li class="col-xs-4">Section</li>
                <li class="col-xs-3">Last Edited By</li>
                <li class="col-xs-2">Last Edited On</li>
                <li class="col-xs-3">Status</li>
            </ul>
            <!-- Start the loop to pull from database here -->
            <?php while($rowsbpcontent = $resultbpcontent->fetch(2)) :
                if ($rowsbpcontent['Sr_No'] == '4') { ?>
            </div>
        <h1 class="box-title" style="margin-top: 50px;">Outcomes</h1>
            <div id="list">
                <ul class="list-nav">
                    <li class="col-xs-4">Section</li>
                    <li class="col-xs-3">Last Edited By</li>
                    <li class="col-xs-2">Last Edited On</li>
                    <li class="col-xs-3">Status</li>
                </ul>
        <?php } ?>
            <a href="<?php echo $rowsbpcontent['CONTENT_LINK'].'?linkid='.$rowsbpcontent['ID_CONTENT'] ?>">
                <ul class="items">
                    <li class="col-xs-4"><?php echo $rowsbpcontent['CONTENT_BRIEF_DESC'] ?></li>
                    <li class="col-xs-3"><?php echo $rowsbpcontent['LNAME'].', '.$rowsbpcontent['FNAME']; ?></li>
                    <li class="col-xs-2"><?php echo date("m/d/Y", strtotime($rowsbpcontent['MOD_TIMESTAMP'])); ?></li>
                    <li class="col-xs-3"><?php echo $rowsbpcontent['CONTENT_STATUS'] ?></li>
                </ul>
            </a>
            <?php endwhile; ?>
            </div>


        <!--
        - Old grid below -
        -->

        <!-- <div id="taskboard" style="margin-top: 10px; padding-left: 40px;">
            <table class="bphome" action="taskboard/bphomeajax.php" title="BluePrint Contents">
                <tr>

                    <th col="CONTENT_BRIEF_DESC" href="{{columns.CONTENT_LINK}}?linkid={{columns.ID_CONTENT}}" width="225" type="text">Section</th>
                    <th col="CONTENT_STATUS" width="125" type="text">Status</th>
                    <th col="MOD_TIMESTAMP" width="150" type="text">Last Edited On</th>
                    <th col="BP_AUTHOR"  width="110" type="text">Last Modified</th>

                </tr>
            </table>
        </div> -->

        <form action="<?php echo "bphome.php?ayname=$bpayname&ou_abbrev=$ouabbrev&id=$bpid"; ?>" method="POST">
            <?php if ($_SESSION['login_role'] == 'dean' OR $_SESSION['login_role'] == 'designee') { ?>
            <div>
            <input type="submit" name="submit_bp" value="Submit BluePrint"
                <?php
                $sqlbpcontent .= " AND CONTENT_STATUS = 'Dean Approved' ;";
                $resultcontent = $connection->prepare($sqlbpcontent);
                $resultcontent->bindParam(':ouabbrev', $ouabbrev, 2);
                $resultcontent->bindParam(':bpayname', $bpayname, 2);
                $resultcontent->execute();

                $numrow = $resultcontent->rowCount();
                if ($numrow < $numbpcontent) { echo 'disabled'; } ?>
                   class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
            </div>
            <?php } elseif ($_SESSION['login_role'] == 'provost' &&
                $rowbroad['BROADCAST_STATUS'] == 'Submitted Draft') { ?>
<!--                Provost Controls-->
                <div>
                    <input type="submit" name="approve" value="Approve BluePrint"
                           class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-left">
                    <input type="submit" name="reject" value="Reject BluePrint"
                           class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                </div>
            <?php } ?>
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
