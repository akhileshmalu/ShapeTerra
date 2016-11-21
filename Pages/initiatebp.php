<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

/*
 * This Page controls Intiation of Academic BluePrint module.
 */

$cur  = date('Y-m-d H:i:s');
session_start();
$error = array();
$errorflag =0;

$sqlbroad ="";
$ou=array();


require_once ("../Resources/Includes/connect.php");
$sqlou = "Select * from Hierarchy where OU_DATE_END >= '$cur'";
$resultou = $mysqli->query($sqlou);

$sqlay = "Select * from AcademicYears";
$resultay = $mysqli->query($sqlay);

if(isset($_POST['submit'])) {
    if(empty($_POST['AY'])){
        $error[0]= "Please select Academic Year.";
        $errorflag = 1;
    }

    if(!isset($_POST['ou_name'])){
        $error[1]= "Please select Organizational Unit.";
        $errorflag = 1;
    }

    if($errorflag!=1){

        $ou = $_POST['ou_name'];
        $ay = $_POST['AY'];

        /*
         * Broadcast status
         *  -  Initiated by Provost : Provost opened Academic Year
         *  -  Approved by Administrator of Unit
         * -   In Progress : Acad Contributor Started confirmation
         *  -  Complete : Acad Contributor Finished confirmation
         */

        foreach ($ou as $value) {
            list($ouid, $ouabbrev) = explode(",", $value);

            $sqlbroadcheck = "select * from broadcast where BROADCAST_AY='$ay' and find_in_set('$ouid',BROADCAST_OU)>0 ";
            $resultbroadcheck = $mysqli->query($sqlbroadcheck);
            $rowbroadcheck = $resultbroadcheck->num_rows;
            if ($rowbroadcheck >= 1) {
                $error[1] = "You have already Initiated BluePrint for Org Unit: " . $ouabbrev . " for year : " . $ay;
                $errorflag = 1;
            } else {

                $broadcaststatus = "Initiated by Provost";
                $broadcastmsg = $ouabbrev . " BluePrint for Academic Year - " . $ay;
                $sqlbroad .= "INSERT INTO broadcast(BROADCAST_OU,BROADCAST_DESC,OPEN_CONFIRMGOALS,BROADCAST_STATUS,BROADCAST_AY) VALUES ('$ouid','$broadcastmsg','Y','$broadcaststatus','$ay');";
            }
        }
        if ($errorflag != 1) {
            if ($mysqli->multi_query($sqlbroad)) {
                $error[0] = "Academic BluePrint Successfully Initiated.";
            } else {
                $error [0] = "Academic BluePrint Could not be initiated.";
            }
        }
    }
}



require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

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
        <h1 id="title">Initiate Academic BluePrint</h1>
    </div>
    <div id="list" class="col-lg-2 col-md-4 col-xs-4">
        <ul class="tabs-nav">
            <li class="year active">1. Select Academic Year</li>
            <li class="unit disabled">2. Select Organization Unit</li>
        </ul>
    </div>

    <div id="form" class="col-lg-9 col-md-8 col-xs-8">
        <form action="" method="POST">
            <div class="year active" id="actionlist">
                <div class="col-lg-5 col-md-9 col-xs-12" id="table-container">
                    <div class="form-group">
                        <label for="AYgoal">Please select Academic Year:</label>
                        <select  name="AY" class="form-control" id="AYgoal">
                            <option value=""></option>
                            <?php while ($rowsay = $resultay->fetch_array(MYSQLI_NUM)): { ?>
                                <option value="<?php echo $rowsay[1]; ?>"> <?php echo $rowsay[1]; ?> </option>
                            <?php } endwhile; ?>
                        </select>
                    </div>

                </div>
                <button id="next-tab" type="button" class="btn-primary col-xs-offset-12 col-lg-3 col-md-7 col-sm-8 pull-right changeTab"> Next Tab
                   </button>
            </div>

            <div class="unit hidden" id="actionlist">
                <label for="ouname">Please Select Organization Unit(s)</label><br/>
                <div class="checkbox" id="ouname">
                <label><input type="checkbox" id="ckbCheckAll" >Select All Organization Units</label>
                </div>
                    <?php while ($rowsou = $resultou->fetch_array(MYSQLI_NUM)): { ?>
                    <div class="checkbox" id="ouname">
                        <label><input type="checkbox" name="ou_name[]"
                                  class="checkBoxClass" value="<?php echo $rowsou[0].",".$rowsou[2]; ?>"><?php echo $rowsou[1]; ?></label>
                    </div>
                <?php } endwhile; ?>
                <input type="submit" name="submit" value="Submit" class="btn-primary pull-left">
            </div>
        </form>
    </div>
</div>


<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>

<!--Calender Bootstrap inclusion for date picker INPUT-->
<script src="../Resources/Library/js/tabchange.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
