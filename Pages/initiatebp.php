<?php

/*
 * This Page controls Intiation of Academic BluePrint module.
 */

session_start();
$error = array();
$errorflag =0;
$ouname="";
$ou=array();

$ayname="";
$ay=array();


require_once ("../Resources/Includes/connect.php");
$sqlou = "Select * from Hierarchy";
$resultou = $mysqli->query($sqlou);

$sqlay = "Select * from AcademicYears";
$resultay = $mysqli->query($sqlay);

if(isset($_POST['submit'])) {
    if(!isset($_POST['AY'])){
        $error[0]= "Please select Academic Year.";
        $errorflag = 1;
    }

    if(!isset($_POST['ou_name'])){
        $error[1]= "Please select Organizational Unit.";
        $errorflag = 1;
    }

    if($errorflag!=1){

        $ou = $_POST['ou_name'];
        foreach ($ou as $value) {
            $ouname .= $value . ",";
        }

        $ay = $_POST['AY'];
        foreach ($ay as $value) {
            $iday .=stringtoid($value);
            $ayname .= $value . ",";
        }

        /*
         * Broadcast status
         *  -  Initiated : Provost opened Academic Year
         *  -  In Progress : Contributor Started confirmation
         *  -  Complete : Contributor Finished confirmation
         */

        $broadcaststatus = "Initiated";
        $broadcastmsg= "BluePrint ".$broadcaststatus." for Academic Year(s) ".$ayname;

        $sql= "INSERT INTO broadcast(BROADCAST_OU,BROADCAST_DESC,OPEN_CONFIRMGOALS,BROADCAST_STATUS,BROADCAST_AY) VALUES ('$ouname','$broadcastmsg','Y','$broadcaststatus','$iday');";

        if($mysqli->query($sql)){
            $error[0] = "Academic BluePrint Successfully Initiated.";
        } else {
            $error [0] = "Academic BluePrint Could not be initiated.";
        }
    }
}



require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="Css/templateTabs.css" rel="stylesheet" type="text/css" />
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<div class="hr"></div>
<form action="" method="POST">
    <div id="main-content" class="col-xs-10">
        <h1 id="title">Initiate Academic BluePrint</h1>

        <ul id="tabs" class="nav nav-pills" id="menu-secondary" role="tablist">
            <li class="active"><a href="#add">Select Academic Year</a></li>
            <li><a href="#view">Select Organization Unit</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active fade in" id="add">
                <div class="col-xs-4" id="table-container">
                    <div class="form-group">
                        <label for="AYgoal">Please select Academic Year:</label>
                        <select multiple="multiple" name="AY[]" class="form-control" id="AYgoal">
                            <option value=""></option>
                            <?php while ($rowsay = $resultay->fetch_array(MYSQLI_NUM)): { ?>
                                <option value="<?php echo $rowsay[1]; ?>"> <?php echo $rowsay[1]; ?> </option>
                            <?php } endwhile; ?>
                        </select>
                    </div>
                    <?php if (isset($_POST['submit'])) { ?>
                        <div class="alert alert-warning col-xs-12">
                            <?php foreach ($error as $value) echo $value . "<br>"; ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade " id="view">
                <label for="ouname">Please Select Organization Unit(s)</label>
                <?php while ($rowsou = $resultou->fetch_array(MYSQLI_NUM)): { ?>
                    <div class="checkbox" id="ouname">
                        <label><input type="checkbox" name="ou_name[]"
                                      value="<?php echo $rowsou[0]; ?>"><?php echo $rowsou[1]; ?></label>
                    </div>
                <?php } endwhile; ?>
                <input type="submit" name="submit" value="Submit" class="btn-primary pull-left">
            </div>
        </div>
    </div>
</form>

<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>

<!--Calender Bootstrap inclusion for date picker INPUT-->

<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>