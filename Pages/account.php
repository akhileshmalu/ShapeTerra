<?php
session_start();
if(!$_SESSION['isLogged']) {
    header("location:login.php");
    die();
}										  	//session Started

require_once ("../Resources/Includes/connect.php");          	//Instance of Object class-connection Created
$email = $_SESSION['login_email'];					  			//Session Variable store
$sqlac = " SELECT ID_STATUS,FNAME,LNAME,USER_OU_MEMBERSHIP,OU_TYPE FROM PermittedUsers 
 INNER JOIN Hierarchy ON ID_HIERARCHY = PermittedUsers.USER_OU_MEMBERSHIP where NETWORK_USERNAME ='$email' ";														//Query to Database
$resultac = $mysqli->query($sqlac);                             	// Query Execution
$rowsac = $resultac -> fetch_assoc();								//Fetching to Show on Account page
$ouid = $rowsac['USER_OU_MEMBERSHIP'];
$outype = $rowsac['OU_TYPE'];
/*
 * Sessoin variables.
 */
$_SESSION['login_fname'] = $rowsac['FNAME'];
$_SESSION['login_lname'] = $rowsac['LNAME'];
$_SESSION['login_name'] = $rowsac['LNAME'].", ".$rowsac['FNAME'];
$_SESSION['login_ouid'] = $ouid;
$_SESSION['login_outype'] = $outype;
$_SESSION['login_userid']=$rowsac['ID_STATUS'];

//Menu control for back to dashboard button
//true: Dont show button
//false: show button
$notBackToDashboard = true;

/*
 * SQL TO DISPLAY BLUEPRINTS ON PAGE
 */
if ($outype == "Academic Unit") {
    $sqlbpunit = "SELECT * FROM `broadcast` INNER JOIN PermittedUsers ON PermittedUsers.ID_STATUS = broadcast.AUTHOR WHERE BROADCAST_OU ='$ouid' ORDER BY BROADCAST_AY ASC ; ";
    $resultbpunit = $mysqli->query($sqlbpunit);
} elseif ($outype == "Administration") {
    $sqldistinctay = "SELECT DISTINCT(BROADCAST_AY) FROM `broadcast` INNER JOIN PermittedUsers ON PermittedUsers.ID_STATUS = broadcast.AUTHOR ORDER BY BROADCAST_AY ASC ; ";
    $resultdistinctay = $mysqli->query($sqldistinctay);
}



// Time Setting Check -
// $script_tz = date_default_timezone_get();

// if (strcmp($script_tz, ini_get('date.timezone'))){
//     echo 'Script timezone differs from ini-set timezone.'.ini_get('date.timezone');
// } else {
//     echo 'Script timezone and ini-set timezone match.';
// }

require_once("../Resources/Includes/header.php");
?>

<!--
below headers for task board design purpose
-->
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrapTable.css"/>
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.min.css"/>
<link rel="stylesheet" href="Css/grid.css" title="openJsGrid"/>

<style>
    div.gridWrapper .columns .cell.headerCell {
        background: -webkit-linear-gradient(top, #fff 0%, #e4e4e4 100%);
    }

    #list ul.items li{
        padding-top: 12px;
        padding-bottom: 12px;
        font-size: 14px;
    }

</style>

<!--
  Above Headers are for Task Board design
-->

<?php
require_once("../Resources/Includes/menu.php");
?>


<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title" class="">Home</h1>
    </div>

    <!-- Possible Greeting box -->
    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Hello <?php echo $rowsac['FNAME']; ?>! </h1>
        <p class="status"><span>Org Unit Name: </span> <?php echo $_SESSION['login_ouname']; ?></p>
        <p class="status"><span>User role: </span> <?php echo $rowsmenu['USER_RIGHT']; ?></p>
    </div>


    <div id="main-box" class="col-xs-10 col-xs-offset-1">

        <div id="" class="col-xs-10 col-xs-offset-1">


        </div>
        <?php if ($outype == "Academic Unit") { ?>
            <h1 class="box-title col-xs-12">Select An Academic Year</h1>
            <div class="input-group col-xs-4 card-search">
                <span class="input-group-addon icon" id="basic-addon1">&#xe041;</span>
                <input type="text" class="form-control" class="col-xs-4" id="search-box" placeholder="Search"
                       aria-describedby="basic-addon1">
            </div>
            <!--            <h1 class="box-title">Select an Academic Year</h1>-->
            <!--            <div id="taskboard" class="">-->
            <!--                <table class="taskboard" action="taskboard/accountajax.php" title="TaskBoard">-->
            <!--                    <tr>-->
            <!--                        <th col="BROADCAST_AY" width="125" type="text"-->
            <!--                            href="--><?php //echo '../Pages/bphome.php?ayname={{value}}&ou_abbrev={{columns.OU_ABBREV}}&id={{columns.ID}}'; ?><!--">-->
            <!--                            Academic Year-->
            <!--                        </th>-->
            <!--                        <th col="BROADCAST_DESC" type="text">Description</th>-->
            <!--                        --><?php //if ($outype == "Administration") { ?>
            <!--                            <th col="BROADCAST_STATUS" type="text">Status</th>-->
            <!--                        --><?php //} else { ?>
            <!--                            <th col="BROADCAST_STATUS_OTHERS" type="text">Status</th>-->
            <!--                        --><?php //} ?>
            <!--                        <th col="AUTHOR" type="text">Last Edited On</th>-->
            <!--                        <th col="LastModified" type="text">Last Modified By</th>-->
            <!--                    </tr>-->
            <!--                </table>-->
            <!--            </div>-->

            <!-- Possible new list card style -->


            <?php while ($rowsbpunit = $resultbpunit->fetch_assoc()) : ?>
                <a href="<?php echo '../Pages/bphome.php?ayname=' . $rowsbpunit['BROADCAST_AY'] . '&ou_abbrev=' . $rowsbpunit['OU_ABBREV'] . '&id=' . $rowsbpunit['ID_BROADCAST']; ?>">
                    <div id="" class="col-xs-10 col-xs-offset-1 card">
                        <div id="ay-year" class="col-xs-3">
                            <h1><?php echo $rowsbpunit['BROADCAST_AY']; ?></h1>
                            <p><?php echo $rowsbpunit['BROADCAST_DESC']; ?></p>
                        </div>
                        <div class="col-xs-4 text-center">
                            <p>Last Edited By</p>
                            <h3><?php echo $rowsbpunit['LNAME'] . ", " . $rowsbpunit['FNAME']; ?></h3>
                        </div>
                        <div class="col-xs-2 text-center">
                            <p>Last Edited on</p>
                            <h3><?php echo date("m/d/Y", strtotime($rowsbpunit['LastModified'])); ?></h3>
                        </div>

                        <div class="col-xs-3 text-center">
                            <p>Status</p>
                            <h3><?php echo $rowsbpunit['BROADCAST_STATUS_OTHERS']; ?></h3>
                        </div>
                    </div>
                </a>
            <?php endwhile;
        } elseif ($outype == "Administration") { ?>
            <h1 class="box-title col-xs-12">Select An Academic Year</h1>
            <div class="input-group col-xs-4 card-search">
                <span class="input-group-addon icon" id="basic-addon1">&#xe041;</span>
                <input type="text" class="form-control" class="col-xs-4" id="search-box" placeholder="Search"
                       aria-describedby="basic-addon1">
            </div>
            <?php while ($rowsdistinctay = $resultdistinctay->fetch_assoc()) { ?>

                <a href="#" onclick="return false;">
                    <div id="<?php echo $rowsdistinctay['BROADCAST_AY']; ?>"
                         class="col-xs-11 col-xs-offset-0 card provost-card">
                        <div class="col-xs-4">
                            <h1><?php echo $rowsdistinctay['BROADCAST_AY']; ?><span id="open" class="icon">T</span><span
                                    id="close" class="icon hidden">W</span></h1>

                        </div>
                    </div>
                </a>
                <div id="<?php echo $rowsdistinctay['BROADCAST_AY'] ?>"
                     class="col-xs-10 col-xs-offset-1 provost-dropdown noDisplay">
                    <div id="list">
                        <ul class="list-nav">
                            <li class="col-xs-4">Section</li>
                            <li class="col-xs-3">Last Edited By</li>
                            <li class="col-xs-2">Last Edited On</li>
                            <li class="col-xs-3">Status</li>
                        </ul>
                        <?php
                        $ay = $rowsdistinctay['BROADCAST_AY'];
                        $sqlbpunit = "SELECT * FROM `broadcast` INNER JOIN PermittedUsers ON PermittedUsers.ID_STATUS = broadcast.AUTHOR WHERE BROADCAST_AY = '$ay' ORDER BY BROADCAST_AY ASC ; ";
                        $resultbpunit = $mysqli->query($sqlbpunit);
                        while ($rowsbpunit = $resultbpunit->fetch_assoc()) { ?>
                            <a href="<?php echo '../Pages/bphome.php?ayname=' . $rowsbpunit['BROADCAST_AY'] . '&ou_abbrev=' . $rowsbpunit['OU_ABBREV'] . '&id=' . $rowsbpunit['ID_BROADCAST']; ?>">
                                <ul class="items">
                                    <li class="col-xs-4"><?php echo $rowsbpunit['BROADCAST_DESC']; ?></li>
                                    <li class="col-xs-3"><?php echo $rowsbpunit['LNAME'] . ", " . $rowsbpunit['FNAME']; ?></li>
                                    <li class="col-xs-2"><?php echo date("m/d/Y", strtotime($rowsbpunit['LastModified'])); ?></li>
                                    <li class="col-xs-3"><?php echo $rowsbpunit['BROADCAST_STATUS']; ?></li>
                                </ul>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

        <?php } elseif ($outype == "Service Unit") { ?>

            <h1 class="box-title">Select an Academic Year to Upload Files</h1>
            <div id="taskboard" class="">
                <table class="taskboard" action="taskboard/serviceunitajax.php" title="Service Unit">
                    <tr>
                        <th col="ACAD_YEAR_DESC" width="600" type="text"
                            href="<?php echo '../Pages/fileuploadhome.php?ayname={{value}}'; ?>">
                            Academic Year
                        </th>
                    </tr>
                </table>
            </div>
        <?php } ?>
    </div>
</div>
<?php
require_once("../Resources/Includes/footer.php");
?>
<script src="../Resources/Library/js/search.js"></script>
<script src="../Resources/Library/js/taskboard.js"></script>
<script src="../Resources/Library/js/root.js"></script>
<script src="../Resources/Library/js/grid.js"></script>
<script src="../Resources/Library/js/dashboard.js"></script>

