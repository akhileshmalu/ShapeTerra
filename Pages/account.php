<?php
session_start();										  	//session Started
require_once ("../Resources/Includes/connect.php");          	//Instance of Object class-connection Created
$email = $_SESSION['login_email'];					  			//Session Variable store
$sqlac = " SELECT ID_STATUS,FNAME,LNAME,USER_OU_MEMBERSHIP,OU_TYPE FROM PermittedUsers INNER JOIN Hierarchy ON ID_HIERARCHY = PermittedUsers.USER_OU_MEMBERSHIP where NETWORK_USERNAME ='$email' ";														//Query to Database
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

        <?php if ($outype == "Academic Unit" || $outype == "Administration") { ?>

    <!-- Possible new list card style -->

    <div id="" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title col-xs-12">Select An Academic Year</h1>

        <div class="input-group col-xs-4 card-search">
          <span class="input-group-addon icon" id="basic-addon1">&#xe041;</span>
          <input type="text" class="form-control" class="col-xs-4" id="search-box" placeholder="Search" aria-describedby="basic-addon1"></input>
        </div>
        
    </div>

    <a href="#">
        <div id="" class="col-xs-10 col-xs-offset-1 card">
            <div id="ay-year" class="col-xs-3">
                <h1>AY2014-2015</h1>
                <p>CEC Academic BluePrint</p>
            </div>
            <div class="col-xs-3 text-center">
                <p>Last Edited By</p>
                <h3>Blake Finn</h3>
            </div>
            <div class="col-xs-3 text-center">
                <p>Last Edited on</p>
                <h3>12/22/2016</h3>
            </div>

            <div class="col-xs-3 text-center">
                <p>Status</p>
                <h3>Initiated By Provost</h3>
            </div>
        </div>
    </a>
    <a href="#">
        <div id="" class="col-xs-10 col-xs-offset-1 card">
            <div id="ay-year" class="col-xs-3">
                <h1>AY2016-2017</h1>
                <p>CEC Academic BluePrint</p>
            </div>
            <div class="col-xs-3 text-center">
                <p>Last Edited By</p>
                <h3>John Doe</h3>
            </div>
            <div class="col-xs-3 text-center">
                <p>Last Edited on</p>
                <h3>1/19/2017</h3>
            </div>

            <div class="col-xs-3 text-center">
                <p>Status</p>
                <h3>Completed</h3>
            </div>
        </div>
    </a>
    <a href="#">
        <div id="" class="col-xs-10 col-xs-offset-1 card">
            <div id="ay-year" class="col-xs-3">
                <h1>AY2018-2019</h1>
                <p>COE Academic BluePrint</p>
            </div>
            <div class="col-xs-3 text-center">
                <p>Last Edited By</p>
                <h3>Jane Girl</h3>
            </div>
            <div class="col-xs-3 text-center">
                <p>Last Edited on</p>
                <h3>2/4/2018</h3>
            </div>

            <div class="col-xs-3 text-center">
                <p>Status</p>
                <h3>Approved</h3>
            </div>
        </div>
    </a>
    <a href="#">
        <div id="" class="col-xs-10 col-xs-offset-1 card">
            <div id="ay-year" class="col-xs-3">
                <h1>AY2014-2015</h1>
                <p>CEC Academic BluePrint</p>
            </div>
            <div class="col-xs-3 text-center">
                <p>Last Edited By</p>
                <h3>Blake Finn</h3>
            </div>
            <div class="col-xs-3 text-center">
                <p>Last Edited on</p>
                <h3>12/22/2016</h3>
            </div>

            <div class="col-xs-3 text-center">
                <p>Status</p>
                <h3>Initiated By Provost</h3>
            </div>
        </div>
    </a>
    <a href="#">
        <div id="" class="col-xs-10 col-xs-offset-1 card">
            <div id="ay-year" class="col-xs-3">
                <h1>AY2014-2015</h1>
                <p>CEC Academic BluePrint</p>
            </div>
            <div class="col-xs-3 text-center">
                <p>Last Edited By</p>
                <h3>Blake Finn</h3>
            </div>
            <div class="col-xs-3 text-center">
                <p>Last Edited on</p>
                <h3>12/22/2016</h3>
            </div>

            <div class="col-xs-3 text-center">
                <p>Status</p>
                <h3>Initiated By Provost</h3>
            </div>
        </div>
    </a>
        
<!--<script>-->
<!--    $(function () {-->
<!--        var that = $(".taskboard");-->
<!--        console.log(that);-->
<!--        that.find('[col]').each(function (index, value) {-->
<!--            var that = $(this),-->
<!--                col = that.attr('col');-->
<!--            console.log(col);-->
<!--            //           value = that.val();-->
<!---->
<!--        });-->
<!--    });-->

            <?php }
            if ($outype == "Service Unit") {?>

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


    <?php
    require_once("../Resources/Includes/footer.php");
    ?>
    <script src="../Resources/Library/js/search.js"></script>
    <script src="../Resources/Library/js/taskboard.js"></script>
    <script src="../Resources/Library/js/root.js"></script>
    <script src="../Resources/Library/js/grid.js"></script>

