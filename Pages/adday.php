<?php

/*
 * This Page controls addition of Academic Year module.
 */

session_start();
$error = array();
$errorflag ='';
$ouname[]="";

require_once ("../Resources/Includes/connect.php");
$sql = "Select * from Hierarchy";
$result = $mysqli->query($sql);


if(isset($_POST['submit'])) {
    if(!isset($_POST['startdate'])){
        $error[0]= "Please select a Start date for Academic Year";
        $errorflag = 1;
    }

    if($errorflag!=1){
        $startdate = $_POST['startdate'];
        $enddate = $_POST['enddate'];
        $censusdate = $_POST['censusdate'];

        $ouname = $_POST['ou_name[]'];
        foreach ($ouname as $value) {
            echo $value;
        }

        $id = stringtoid($startdate,$enddate);
        $desc = idtostring($id);

        //$sql = "INSERT INTO AcademicYears (ID_ACAD_YEAR,ACAD_YEAR_DESC,ACAD_YEAR_DATE_BEGIN,ACAD_YEAR_DATE_END,DATE_CENSUS) VALUES ('$id','$desc','$startdate','$enddate','$censusdate');";
        if($mysqli->query($sql)){
            $error[0] = "Academic Year Added Successfully.";
        } else {
            $error [0] = "Academic Year Could not be added.";
        }
    }
}

/*
 * Function to obtain String from ID and ID from String.
 */

function stringtoid ($string1, $string2){

    $id1 = intval(substr($string1,2,2));
    $id2= intval(substr($string2,2,2));
    $id = ($id1*100)+$id2;
    return $id;
}

function idtostring ($id){
    $id2= $id %100;
    $id1= intval($id/100);
    $string = "AY20".$id1."-20".$id2;
    return $string;
}

require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="Css/templateTabs.css" rel="stylesheet" type="text/css" />
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<div class="hr"></div>
    <div id="main-content" class="col-xs-10">
        <h1 id="title">Academic BluePrint</h1>
 
        <ul id="tabs" class="nav nav-pills" id="menu-secondary" role="tablist">
            <li class="active"><a href="#add">Tab 1</a></li>
            <li><a href="#view">Tab 2</a></li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active fade in" id="add">
                <div class="col-xs-4" id="table-container">
                    <form action ="" method="POST">
                    <label for="datetimepicker1">Please Select Academic Year Start date :</label>
                    <div class="form-group">
                        <div class='input-group date'  id='datetimepicker1'>
                            <input type='text' name="startdate"  class="form-control" required/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <label for="datetimepicker2">Please Select Academic Year End date :</label>
                    <div class="form-group">
                        <div class='input-group date' id='datetimepicker2'>
                            <input type='text' name="enddate" class="form-control" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <label for="datetimepicker3">Please Select Date Census :</label>
                    <div class="form-group">
                        <div class='input-group date' id='datetimepicker3'>
                            <input type='text' name="censusdate" class="form-control" required/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>


        <div role="tabpanel" class="tab-pane fade " id="view">
            <label for="ouname">Please Select Organization Unit(s)</label>
                <?php while($row = $result->fetch_array(MYSQLI_NUM)): { ?>
                    <div class="checkbox" id="ouname">
                    <label><input type="checkbox" name="ou_name[]" value="<?php echo $row[0]; ?>"><?php echo $row[1]; ?></label>
                </div>
                <?php } endwhile;?>
                <?php if(isset($_POST['submit'])) { ?>
                    <div class="alert alert-warning">
                        <?php foreach ($error as $value)echo $value; ?>
                    </div>
                <?php } ?>
                <input type="submit" name="submit" value="Submit" class="btn-primary pull-left">
                </form>
        </div>
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