<?php

require_once("../Resources/Includes/Initialize.php");
$initialize = new Initialize();
$initialize->checkSessionStatus();
$connection = $initialize->connection;

$time = date('Y-m-d H:i:s');
$message = array();
$errorflag = 0;

$sqlbroad = "";
$ou = array();
$broad_id = 0;
$author = $_SESSION['login_userid'];
$first = TRUE;

$userid = $_GET['id_user'];

/*
 * Query to show Non terminated Organization Unit as on date.
 */
$sqlou = "SELECT * FROM Hierarchy WHERE OU_ABBREV != 'UNAFFIL' AND OU_DATE_END IS NULL AND OU_TYPE ='Academic Unit';";
$resultou = $connection->prepare($sqlou);
$resultou->execute();


/*
 * Query to show Academic years for Initiating Blue Print.
 */

$sqlay = "SELECT * FROM AcademicYears ORDER BY ID_ACAD_YEAR ASC;";
$resultay = $connection->prepare($sqlay);
$resultay->execute();

// To provide Global access to Sys Admin of Provost Unit <Inter Unit Role Change>
$globalAdminRoles = $_SESSION['login_outype'] == 'Administration' ?"'Provost'":
    "'Provost','System Administrator','System Developer'";

$sqlrole = "SELECT * FROM UserRoles WHERE USER_ROLE NOT IN ($globalAdminRoles);";
$resultrole = $connection->prepare($sqlrole);
$resultrole->execute();

// Place holder

$sqlExValue = "SELECT ID_STATUS,CONCAT(FNAME,space(1) ,LNAME) AS USERFULLNAME,
NETWORK_USERNAME, USER_ROLE, USER_OU_MEMBERSHIP  FROM `PermittedUsers` A INNER JOIN Hierarchy B ON B.ID_HIERARCHY = A
.USER_OU_MEMBERSHIP 
INNER JOIN UserRoles C ON C.ID_USER_ROLE = A.SYS_USER_ROLE WHERE ID_STATUS = :id_user ORDER BY ID_STATUS ASC";
$resultExValue = $initialize->connection->prepare($sqlExValue);
$resultExValue->bindParam(':id_user', $userid, 2);
$resultExValue->execute();
$rowsExValue = $resultExValue->fetch(4);

if(isset($_POST['modifyUser'])) {

    $userid = $_GET['id_user'];
    $username = $initialize->test_input($_POST['username']);
    $role = $_POST['role'];
    $ouid = $_SESSION['login_outype'] == 'Administration'? $_POST['ouname']:$_SESSION['login_ouid'];

    try {
        $sql = "UPDATE PermittedUsers SET SYS_USER_ROLE = :role , USER_OU_MEMBERSHIP = :ouid 
WHERE ID_STATUS =:id_user ;";
        $result = $initialize->connection->prepare($sql);
        $result->bindParam(':id_user', $userid, 2);
        $result->bindParam(':ouid', $ouid, 2);
        $result->bindParam(':role', $role, 2);
        if ($result->execute()) {
            $message[0] = "User: $username Modified successfully.";
        } else {
            $message[0] = "User: $username could not be Modified.";
        }

    } catch (PDOException $e) {
        $e->getMessage();
    }
}

if(isset($_POST['delUser'])) {

    $userid = $_GET['id_user'];
    $username = $initialize->test_input($_POST['username']);
    $role = $_POST['role'];
    $ouid = $_SESSION['login_outype'] == 'Administration'? $_POST['ouname']:$_SESSION['login_ouid'];

    try {
        $sql = "UPDATE PermittedUsers SET DATE_TERMINATE = '$time', USER_STATUS = '-1' WHERE ID_STATUS =:id_user ;";
        $result = $initialize->connection->prepare($sql);
        $result->bindParam(':id_user', $userid, 2);
        if ($result->execute()) {
            $message[0] = "User: $username Terminated successfully.";
        } else {
            $message[0] = "User: $username could not be Terminated.";
        }

    } catch (PDOException $e) {
        $e->getMessage();
    }
}


require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>

<div class="overlay hidden"></div>
<?php if (isset($_POST['modifyUser']) or isset($_POST['delUser'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" redirect="<?php echo 'addUser.php'; ?>" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Team Lead Dashboard</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <form action="<?php echo $_SERVER['PHP_SELF'].'?id_user='.$userid; ?>" method="POST" class="col-xs-12">
            <h2>1. Hello Username</h2>
            <input type="text" name="username" placeholder="Network Username" value="<?php
            echo $rowsExValue['NETWORK_USERNAME']?>"
                   class="form-control" disabled>
            <h2>2. Modify User Roles </h2>
            <div>
                <select name="role" class="form-control" id="roles"
                        style="padding: 0px !important; background-color: #fff !important;">
                    <?php while ($rowsrole = $resultrole->fetch(4)): { ?>
                        <option value="<?php echo $rowsrole[0]; ?>"
                        <?php
                        if ($rowsrole[1] == $rowsExValue['USER_ROLE']) {
                            echo ' selected = selected';
                        }
                        ?>
                        ><?php echo $rowsrole[1]; ?></option>
                    <?php } endwhile; ?>
                </select>
            </div>
            <br/>
            <?php if ($_SESSION['login_outype'] == 'Administration'): ?>
                <h2>3. Select Organization Unit</h2>
                <select name="ouname" class="form-group">
                    <option value="0" selected>--Select A Unit--</option>
                    <?php while ($rowsou = $resultou->fetch(4)) { ?>
                        <option value="<?php echo $rowsou[0]; ?>"
                            <?php
                            if ($rowsou[0] == $rowsExValue['USER_OU_MEMBERSHIP']) {
                                echo ' selected = selected';
                            }
                            ?>
                        ><?php echo $rowsou[1]; ?></option>
                    <?php } ?>
                </select>
            <?php endif; ?>
            <input type="submit" name="modifyUser" value="Modify User" class="btn-primary pull-right">

            <input type="submit" name="delUser" value="Delete User" class="btn-primary pull-right">
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
