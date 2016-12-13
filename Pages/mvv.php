<?php

/*
 * This Page controls Mission Vision & Value Screen.
 */

/*
 * Session & Error control Initialization.
 */
session_start();
$error = array();
$errorflag = 0;

/*
 * Connection to DataBase.
 */
require_once("../Resources/Includes/connect.php");

/*
 * Local & Session variable Initialization
 */

$bpayname = $_SESSION['bpayname'];
$ouid = $_SESSION['login_ouid'];
$ouabbrev = $_SESSION['login_ouabbrev'];
$date = date("Y-m-d");
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_email'];


$prevbpid = stringtoid($bpayname);
$prevbpayname = idtostring($prevbpid - 101);

if ($ouid == 4) {
    $sqlbroad = "select BROADCAST_AY,BROADCAST_STATUS,LastModified from broadcast where BROADCAST_AY='$bpayname';";
} else {
    $sqlbroad = "select BROADCAST_AY,BROADCAST_STATUS_OTHERS,LastModified from broadcast where BROADCAST_AY='$bpayname' and BROADCAST_OU ='$ouid'; ";
}
$resultbroad = $mysqli->query($sqlbroad);
$rowbroad = $resultbroad->fetch_array(MYSQLI_NUM);


/*
 * Query to Select Previous Year Mission , Visoin, Value Statement for Specific Org Unit.
 */
$sqlmission = "select * from BP_MissionVisionValues where UNIT_MVV_AY ='$prevbpayname' and OU_ABBREV ='$ouabbrev';";
$resultmission = $mysqli->query($sqlmission);
$rowsmission = $resultmission->fetch_assoc();


$sqllastmission = "SELECT max(ID_UNIT_MVV) FROM BP_MissionVisionValues";
$resultlastmission = $mysqli->query($sqllastmission);
$rowslastmission = $resultlastmission->fetch_array(MYSQLI_NUM);

$id = intval($rowslastmission[0]) + 1;

if (isset($_POST['mission_submit'])) {

    $missionstatement = mynl2br($_POST['missionstatement']);



    $sqlmission = "INSERT INTO BP_MissionVisionValues (ID_UNIT_MVV,OU_ABBREV,MVV_AUTHOR, MOD_TIMESTAMP, UNIT_MVV_AY, MISSION_STATEMENT) 
VALUES ('$id','$ouabbrev','$author','$time','$bpayname','$missionstatement')
ON DUPLICATE KEY UPDATE `ID_UNIT_MVV` = VALUES(`ID_UNIT_MVV`),
`OU_ABBREV` = VALUES(`OU_ABBREV`),`MVV_AUTHOR` = VALUES(`MVV_AUTHOR`),`MOD_TIMESTAMP` = VALUES(`MOD_TIMESTAMP`),`UNIT_MVV_AY` = VALUES(`UNIT_MVV_AY`),
`MISSION_STATEMENT` =VALUES(`MISSION_STATEMENT`);";


    if ($mysqli->query($sqlmission)) {

        $error[0] = "Mission Updated Successfully";

    } else {
        $error[0] = "Mission Could not be Updated. Please Retry.";
    }


}

if (isset($_POST['vision_submit'])) {


    $visionstatement = mynl2br($_POST['visionstatement']);


    $sqlmission = "INSERT INTO BP_MissionVisionValues (ID_UNIT_MVV,OU_ABBREV,MVV_AUTHOR, MOD_TIMESTAMP, UNIT_MVV_AY,VISION_STATEMENT ) 
VALUES ('$id','$ouabbrev','$author','$time','$bpayname','$visionstatement')
ON DUPLICATE KEY UPDATE `ID_UNIT_MVV` = VALUES(`ID_UNIT_MVV`),
`OU_ABBREV` = VALUES(`OU_ABBREV`),`MVV_AUTHOR` = VALUES(`MVV_AUTHOR`),`MOD_TIMESTAMP` = VALUES(`MOD_TIMESTAMP`),`UNIT_MVV_AY` = VALUES(`UNIT_MVV_AY`),
`VISION_STATEMENT` =VALUES(`VISION_STATEMENT`);";


    if ($mysqli->query($sqlmission)) {

        $error[0] = "Vision Updated Successfully";

    } else {
        $error[0] = "Vision Could not be Updated. Please Retry.";
    }


}
if (isset($_POST['value_submit'])) {

    $valuestatement = mynl2br($_POST['valuestatement']);


    $sqlmission = "INSERT INTO BP_MissionVisionValues (ID_UNIT_MVV,OU_ABBREV,MVV_AUTHOR, MOD_TIMESTAMP, UNIT_MVV_AY, VALUES_STATEMENT) 
VALUES ('$id','$ouabbrev','$author','$time','$bpayname','$valuestatement')
ON DUPLICATE KEY UPDATE `ID_UNIT_MVV` = VALUES(`ID_UNIT_MVV`),
`OU_ABBREV` = VALUES(`OU_ABBREV`),`MVV_AUTHOR` = VALUES(`MVV_AUTHOR`),`MOD_TIMESTAMP` = VALUES(`MOD_TIMESTAMP`),`UNIT_MVV_AY` = VALUES(`UNIT_MVV_AY`),
`VALUES_STATEMENT` =VALUES(`VALUES_STATEMENT`);";


    if ($mysqli->query($sqlmission)) {

        $error[0] = "Values Updated Successfully";

    } else {
        $error[0] = "Values Could not be Updated. Please Retry.";
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
<link href="Css/templateTabs.css" rel="stylesheet" type="text/css"/>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>

<!--<div class="overlay hidden"></div>-->
<?php //if (isset($_POST['submit']) ) { ?>
<!--    <div class="alert">-->
<!--        <a href="#" class="close end"><span class="icon">9</span></a>-->
<!--        <h1 class="title"></h1>-->
<!--        <p class="description">--><?php //foreach ($error as $value) echo $value; ?><!--</p>-->
<!--        <button type="button" redirect="" class="end btn-primary">Close</button>-->
<!--    </div>-->
<?php //} ?>

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
        <!-- Nav tabs -->
        <ul class="nav nav-pills" role="tablist">
            <li role="presentation" class="active"><a href="#mission" aria-controls="mission" role="tab"
                                                      data-toggle="pill">Mission</a></li>
            <li role="presentation"><a href="#vision" aria-controls="vision" role="tab" data-toggle="pill">Vision</a>
            </li>
            <li role="presentation"><a href="#values" aria-controls="values" role="tab" data-toggle="pill">Values</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">


            <div role="tabpanel" class="tab-pane active" id="mission">
                <form action="mvv.php" method="POST" class="ajaxform">
                    <p>
                        <small><em>Instruction: Enter your BluePrint content for the Academic Year indicated above.The
                                components below are highest level statements of
                                what <?php echo $_SESSION['login_ouname']; ?> considers foundation to your goals &
                                related outcomes.</em></small>
                    </p>

                    <label class="col-xs-12" for="missiontitle">Mission Statement</label>

                    <div class="col-xs-12">

                        <textarea rows="5" cols="25" wrap="hard" class="form-control" name="missionstatement"
                                  id="missiontitle"
                                  required><?php echo $rowsmission['MISSION_STATEMENT']; ?></textarea>

                        <button type="submit" name="mission_submit"
                                class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                            Submit Mission
                        </button>
                    </div>
                </form>
            </div>


            <div role="tabpanel" class="tab-pane" id="vision">

                <form action="mvv.php" method="POST" class="ajaxform">

                        <label class="col-xs-12" for="visiontitle">Vision Statement</label>

                        <div class="col-xs-12">
                            <textarea rows="5" cols="25" wrap="hard" class="form-control" name="visionstatement"
                                      id="visiontitle"
                                      required><?php echo $rowsmission['VISION_STATEMENT']; ?></textarea>

                            <button type="submit" name="vision_submit"
                                    class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                                Submit Vision
                            </button>
                        </div>

                </form>
            </div>


            <div role="tabpanel" class="tab-pane" id="values">
                <form action="mvv.php" method="POST" class="ajaxform">

                        <label class="col-xs-12" for="visiontitle">Value Statement</label>

                        <div class="col-xs-12">

                        <textarea rows="5" cols="25" wrap="hard" class="form-control" name="valuestatement"
                                  id="valuetitle"
                                  required><?php echo $rowsmission['VALUES_STATEMENT']; ?></textarea>

                            <button type="submit" name="value_submit"
                                    class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right">
                                Submit Value
                            </button>
                        </div>
                </form>
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
    $('.nav a').click(function (e) {
        e.preventDefault();
        $(this).tab('show')
    })
</script>
<script src="../Resources/Library/js/tabchange.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script src="../Resources/Library/js/taskboard.js"></script>
<script src="../Resources/Library/js/content.js"></script>

