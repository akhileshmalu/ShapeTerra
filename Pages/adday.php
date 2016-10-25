<?php

require_once("../Resources/Includes/header.php");

session_start();
$error = array();
$errorflag ='';

if(isset($_POST['submit'])) {
    if(!isset($_POST['startdate'])){
        $error[0]= "Please select a Start date for Academic Year";
        $errorflag = 1;
    }

    if($errorflag!=1){
        $startdate = $_POST['startdate'];
        $enddate = $_POST['enddate'];
        $censusdate = $_POST['censusdate'];
        $id = stringtoid($startdate,$enddate);
        $desc = idtostring($id);
        require_once ("../Resources/Includes/connect.php");
        $sql = "INSERT INTO AcademicYears (ID_ACAD_YEAR,ACAD_YEAR_DESC,ACAD_YEAR_DATE_BEGIN,ACAD_YEAR_DATE_END,DATE_CENSUS) VALUES ('$id','$desc','$startdate','$enddate','$censusdate');";
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
?>


<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<body>
<?php
// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>


<div class="col-lg-offset-3 col-lg-6 col-md-6 col-xs-9" id="ContentRight">
    <form action ="" method="POST">
        <div class='col-lg-6'>
            <label for="datetimepicker1">Please Select Academic Year Start date :</label>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker1'>
                    <input type='text' name="startdate" class="form-control" required/>
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
            <?php if(isset($_POST['submit'])) { ?>
                <div class="alert alert-warning">
                    <?php foreach ($error as $value)echo $value; ?>
                </div>
            <?php } ?>
            <input type="submit" name="submit" value="Submit" class="btn-primary">
        </div>
    </form>
</div>
<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>
