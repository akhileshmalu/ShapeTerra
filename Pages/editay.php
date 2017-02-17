<?php

require_once ("../Resources/Includes/initalize.php");
$initalize = new Initialize();
$initalize->checkSessionStatus();
$connection = $initalize->connection;

$message = array();
$errorflag =0;

require_once("../Resources/Includes/connect.php");
/*
 * Query for Selecting academic Year
 */
try
{
    $sqlay = "Select * from AcademicYears";
    $resultay = $connection->prepare($sqlay);
    $resultay->execute();
}
catch (PDOException $e)
{
    error_log($e->getMessage());
    //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
}



if (isset($_POST['submit'])) {

    try {


        if (!isset($_POST['startdate'])) {
            $message[0] = "Please select a Start date for Academic Year";
            $errorflag = 1;
        }
        if ($_POST['startdate'] >= $_POST['enddate']) {
            $message[0] = "End date should be older than Start date.";
            $errorflag = 1;
        }

        if ($errorflag != 1) {
            $ayid = $_POST['AY'];
            $startdate = $_POST['startdate'];
            $enddate = $_POST['enddate'];
            $censusdate = $_POST['censusdate'];

            $sqlEditAy = "UPDATE `AcademicYears` SET ACAD_YEAR_DATE_BEGIN = :startdate, ACAD_YEAR_DATE_END = :enddate,
            DATE_CENSUS = :censusdate WHERE ID_ACAD_YEAR = :ayid ";

            $resultEditAy = $connection->prepare($sqlEditAy);
            $resultEditAy->bindParam(':startdate', $startdate, 2);
            $resultEditAy->bindParam(':enddate', $enddate, 2);
            $resultEditAy->bindParam(':censusdate', $censusdate, 2);
            $resultEditAy->bindParam(':ayid', $ayid, 1);

            if ($resultEditAy->execute()) {
                $message[0] = "Academic Year Updated Successfully.";
            } else {
                $message [0] = "Academic Year Could not be updated.";
            }
        }

    }
    catch (PDOException $e)
    {
        error_log($e->getMessage());
        //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
    }

}


require_once("../Resources/Includes/header.php");
?>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
</head>
<body>

<?php
// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<!-- Main Content goes here -->

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
<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Edit Academic Year</h1>
    </div>
    <div class="content-general">
        <form action="" method="POST">
            <div class="col-xs-4" id="table-container">
                <div class="form-group">
                    <label for="AYgoal">Please select Academic Year:</label>
                    <select name="AY" class="form-control" id="AYgoal" required>
                        <option value=""></option>
                        <?php while ($rowsay = $resultay->fetch(4)): { ?>
                            <option value="<?php echo $rowsay[0]; ?>" dummy1="<?php echo $rowsay[2]; ?>" dummy2="<?php echo $rowsay[3];?>" dummy3="<?php echo $rowsay[4]; ?>" > <?php echo $rowsay[1]; ?> </option>
                        <?php } endwhile; ?>
                    </select>
                </div>
                <div class="hidden" id="editdates">
                    <label for="datetimepicker1">Please Select Academic Year Start date :</label>
                    <div class="form-group">
                        <div class='input-group date' id='datetimepicker1'>
                            <input type='text' name="startdate" class="form-control" id="startdate">
                            <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                        </div>
                    </div>
                    <label for="datetimepicker2">Please Select Academic Year End date :</label>
                    <div class="form-group">
                        <div class='input-group date' id='datetimepicker2'>
                            <input type='text' name="enddate" class="form-control" id="enddate">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                        </div>
                    </div>
                    <label for="datetimepicker3">Please Select Date Census :</label>
                    <div class="form-group">
                        <div class='input-group date' id='datetimepicker3'>
                            <input type='text' name="censusdate" class="form-control" id="cencusdate" required/>
                            <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                        </div>
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
