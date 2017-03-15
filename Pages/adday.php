<?php

require_once("../Resources/Includes/Initialize.php");
$initalize = new Initialize();
$initalize->checkSessionStatus();
$connection = $initalize->connection;

$message = array();
$errorflag = 0;
$author = $_SESSION['login_userid'];

if(isset($_POST['submit'])) {
    if(!isset($_POST['startdate'])){
        $message[0]= "Please select a Start date for Academic Year";
        $errorflag = 1;
    }

    if($_POST['startdate'] >= $_POST['enddate']){
        $message[0]= "End date should be older than Start date.";
        $errorflag = 1;
    }

    if($errorflag!=1) {
        $startdate = date("Y-m-d", strtotime($_POST['startdate']));
        $enddate = date("Y-m-d", strtotime($_POST['enddate']));
        $censusdate = date("Y-m-d", strtotime($_POST['censusdate']));


        $id = $initalize->stringdatestoid($startdate,$enddate);
        $desc = $initalize->idtostring($id);

        try
        {
            $sqlaycheck = "select * from AcademicYears where ACAD_YEAR_DESC = :description ;";
            $resultaycheck = $connection->prepare($sqlaycheck);
            $resultaycheck->execute(['description'=> $desc]);
        }
        catch (PDOException $e)
        {
            //        SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
            error_log($e->getMessage());
        }
        $rowsaycheck = $resultaycheck->rowCount();

        if($rowsaycheck >=1) {
            $message[1] = "Academic Year already Exist";
            $errorflag = 1;
        }
        if ($errorflag != 1) {
            try
            {
                $sqladday = "INSERT INTO AcademicYears (ID_ACAD_YEAR,ACAD_YEAR_DESC,ACAD_YEAR_DATE_BEGIN,
                ACAD_YEAR_DATE_END,DATE_CENSUS) VALUES (:id, :descrip,:startdate, :enddate, :censusdate );";
                $resultadday = $connection->prepare($sqladday);
                $resultadday->bindParam(':id', $id, 1);
                $resultadday->bindParam(':descrip', $desc, 2);
                $resultadday->bindParam(':startdate', $startdate, 2);
                $resultadday->bindParam(':enddate', $enddate, 2);
                $resultadday->bindParam(':censusdate', $censusdate, 2);

                if ($resultadday->execute()) {
                    $message[0] = "Academic Year Added Successfully.";
                } else {
                    $message [0] = "Academic Year Could not be added.";
                }
            }
            catch (PDOException $e)
            {
                //        SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
                error_log($e->getMessage());
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
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" redirect="account.php" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>
<div id="main-content" class="col-xs-10">
    <div id="title-header">
        <h1 id="title">Add Academic Year</h1>
    </div>
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
