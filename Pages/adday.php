<?php
session_start();
$error = array();
$errorflag =0;

require_once ("../Resources/Includes/connect.php");

if(isset($_POST['submit'])) {
    if(!isset($_POST['startdate'])){
        $error[0]= "Please select a Start date for Academic Year";
        $errorflag = 1;
    }
    if($_POST['startdate'] >= $_POST['enddate']){
        $error[0]= "End date should be older than Start date.";
        $errorflag = 1;
    }

    if($errorflag!=1){
        $startdate = $_POST['startdate'];
        $enddate = $_POST['enddate'];
        $censusdate = $_POST['censusdate'];

        $id = stringdatestoid($startdate,$enddate);
        $desc = idtostring($id);

        $sqlaycheck = "select * from AcademicYears where ACAD_YEAR_DESC = '$desc'";
        $resultaycheck = $mysqli->query($sqlaycheck);
        $rowsaycheck = $resultaycheck->num_rows;
        if($rowsaycheck >=1) {
            $error[1] = "Academic Year already Exist";
            $errorflag = 1;
        }
        if ($errorflag != 1) {

            $sql = "INSERT INTO AcademicYears (ID_ACAD_YEAR,ACAD_YEAR_DESC,ACAD_YEAR_DATE_BEGIN,ACAD_YEAR_DATE_END,DATE_CENSUS) VALUES ('$id','$desc','$startdate','$enddate','$censusdate');";
            if ($mysqli->query($sql)) {
                $error[0] = "Academic Year Added Successfully.";
            } else {
                $error [0] = "Academic Year Could not be added.";
            }
        }
    }
}


require_once("../Resources/Includes/header.php");
?>

<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<body>
<?php
// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

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
<div id="main-content" class="col-xs-10">
    <h1 id="title">Add Academic Year</h1> 

    <div class="content-general">
        <form action="" method="POST">
        <div class="col-xs-4" id="table-container">
            <label for="datetimepicker1">Please Select Academic Year Start date :</label>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker1'>
                    <input type='text' name="startdate" class="form-control">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <label for="datetimepicker2">Please Select Academic Year End date :</label>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker2'>
                    <input type='text' name="enddate" class="form-control">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
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

            <input type="submit" name="submit" value="Submit" class="btn-primary pull-left">
        </div>
        </form>
    </div>
</div>

<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>