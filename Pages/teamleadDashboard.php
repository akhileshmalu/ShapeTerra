<?php

/*
 * This Page controls Intiation of Academic BluePrint module.
 */

require_once("../Resources/Includes/Initialize.php");
$diversityPersonnel = new Initialize();
$diversityPersonnel->checkSessionStatus();
$connection = $diversityPersonnel->connection;

$time = date('Y-m-d H:i:s');
$message = array();
$errorflag = 0;

$sqlbroad = "";
$ou = array();
$broad_id = 0;
$author = $_SESSION['login_userid'];
$first = TRUE;

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

$sqlrights = "SELECT * FROM UserRoles";
$resultrights = $connection->prepare($sqlrights);
$resultrights->execute();


require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>

<div class="overlay hidden"></div>
<?php if (isset($_POST['submit'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" redirect="<?php echo "account.php"; ?>" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Team Lead Dashboard</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <form action="" method="POST" class="col-xs-4">
            <h2>1. Enter Network Username</h2>
            <input type="text" placeholder="Network Username" class="form-control"></input>
            <h2>2. Select User Rights </h2>
            <div class="col-xs-12">
                <select name="AY" class="form-control" id="AYname"
                        style="padding: 0px !important; background-color: #fff !important;">
                    <option value=""></option>
                    <?php while ($rowsrights = $resultrights->fetch(4)): { ?>
                        <option value="<?php echo $rowsay[1]; ?>"><?php echo $rowsrights[1]; ?></option>
                    <?php } endwhile; ?>
                </select>
            </div>
            <br/>
            <h2>3. Select Organization Unit</h2>
            <div class="checkbox" id="ouname">
                <label><input type="checkbox" id="ckbCheckAll">All Active Academic Units </label>
            </div>
            <?php while ($rowsou = $resultou->fetch(4)): { ?>
                <div class="checkbox" id="ouname">
                    <label><input type="checkbox" name="ou_name[]"
                                  class="checkBoxClass"
                                  value="<?php echo $rowsou[0] . "," . $rowsou[2]; ?>"><?php echo $rowsou[1]; ?></label>
                </div>
            <?php } endwhile; ?>
            <input type="submit" name="submit" value="Add User" class="btn-primary pull-right col-xs-12">
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
