<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

/*
 * This Page controls Intiation of Academic BluePrint module.
 */

session_start();
if(!$_SESSION['isLogged']) {
    header("location:login.php");
    die();
}
$time  = date('Y-m-d H:i:s');
$error = array();
$errorflag =0;

$sqlbroad ="";
$ou=array();
$broad_id = 0;
$author = $_SESSION['login_userid'];
$first = TRUE;


require_once ("../Resources/Includes/connect.php");

/*
 * Query to show Non terminated Organization Unit as on date.
 */
$sqlou = "Select * from Hierarchy where OU_ABBREV != 'UNAFFIL' and OU_DATE_END IS NULL and OU_TYPE='Academic Unit';";
$resultou = $mysqli->query($sqlou);

/*
 * Query to show Academic years for Initiating Blue Print.
 */

$sqlay = "Select * from AcademicYears;";
$resultay = $mysqli->query($sqlay);

/*
 * Blue Print Content Items & Details.
 */

$bpcontent = array(
    array("Mission, Vision & Values","mvv.php"),
    array("Unit Goals","unitgoaloverview.php"),
    array("Goal Management & Outcomes","goaloutcomeshome.php"),
    array("Academic Programs","academicPrograms.php"),
    array("Alumni & Development","alumniDevelopment.php"),
    array("Collaborations","collaborations.php"),
    array("Campus Climate","campusClimate.php"),
    array("Executive Summary","executivesum.php"),
    array("Faculty Awards","facultyawards.php"),
    array("Faculty Info","facultyInfo.php"),
    array("Initiatives & Observations","initiatives.php"),
);


/*
 * File Upload Section
 */
$uploaddatafiles = array(
    array("IR_AC_DiversityPersonnel","ac_diversitypersonnel.php"),
    array("IR_AC_DiversityStudent","ac_diversitystudent.php"),
    array("IR_AC_Enrollments","uploadfile.php"),
//    array("IR_AC_Facilities","uploadfile.php"),
    array("IR_AC_FacultyPop","ac_facultypop.php"),
//    array("IR_AC_Transfers","uploadfile.php"),
//    array("IR_AC_AdmTestScores","uploadfile.php"),
);


if (isset($_POST['submit'])) {
    if (empty($_POST['AY'])) {
        $error[0] = "Please select Academic Year.";
        $errorflag = 1;
    }

    if (!isset($_POST['ou_name'])) {
        $error[1] = "Please select Organizational Unit.";
        $errorflag = 1;
    }

    if ($errorflag != 1) {

        $ou = $_POST['ou_name'];
        $ay = $_POST['AY'];

        /*
         * Broadcast status
         *  -  Initiated by Provost : Provost opened Academic Year
         *  -  In Progress : Academic Units Started Contribution
         *  -  Completed by User : Academic Contributor Finished confirmation
         */
        /*
         * Broadcast status_Others
         *  -  Initiated by Provost : Provost opened Academic Year
         *  -  Approved by Admin : Administrator of Unit
         *  -  Completed by User : Academic Contributor Finished confirmation
         */

        foreach ($ou as $value) {
            list($ouid, $ouabbrev) = explode(",", $value);

            $sqlbroadcheck = "select * from broadcast where BROADCAST_AY='$ay' and find_in_set('$ouid',BROADCAST_OU)>0; ";
            $resultbroadcheck = $mysqli->query($sqlbroadcheck);
            $rowbroadcheck = $resultbroadcheck->num_rows;
            if ($rowbroadcheck >= 1) {
                $error[1] = "You have already Initiated BluePrint for Org Unit: " . $ouabbrev . " for year : " . $ay;
                $errorflag = 1;
            } else {


                $broadcaststatus = "Initiated by Provost";
                $broadcastmsg = $ouabbrev . " Academic BluePrint";

                /*
                 * select last inserted value
                 */
                $sqllastval = "SELECT max(ID_BROADCAST) AS Lastid FROM broadcast;";
                $resultlastval = $mysqli->query($sqllastval);
                $rowslastval = $resultlastval->fetch_assoc();

                if ($first) {
                    $broad_id = intval($rowslastval['Lastid']) + 1;
                    $first = false;

                } else {
                    $broad_id++;
                }

                $sqlbroad .= "INSERT INTO broadcast(ID_BROADCAST,OU_ABBREV,BROADCAST_OU,BROADCAST_DESC,BROADCAST_STATUS,BROADCAST_AY,BROADCAST_STATUS_OTHERS,LastModified,AUTHOR) VALUES ('$broad_id','$ouabbrev','$ouid','$broadcastmsg','$broadcaststatus','$ay','$broadcaststatus','$time','$author');";

                /*
                 * Content Creation per BluePrint
                 */

                for ($j = 0; $j < count($bpcontent); $j++) {
                    $topicdesc = $bpcontent[$j][0];
                    $topiclink = $bpcontent[$j][1];


                    $srno = $j+1;
                    $sqlbroad .= "INSERT INTO BpContents(Linked_BP_ID,CONTENT_BRIEF_DESC,CONTENT_LINK,MOD_TIMESTAMP,Sr_No) VALUES ('$broad_id','$topicdesc','$topiclink','$time','$srno');";

                }
                foreach ($uploaddatafiles as $file) {
                    $filename = $file[0];
                    $filelink = $file[1];
                    $sqlbroad .= "INSERT INTO IR_SU_UploadStatus(NAME_UPLOADFILE,LINK_UPLOADFILE ,OUTCOME_AY,LAST_MODIFIED_BY) VALUES ('$filename','$filelink','$desc','$author');";
                }
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
        <button type="button" redirect="<?php echo "account.php";?>"  class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Initiate Academic BluePrint</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <form action="" method="POST" >
            <h2>1. Select Academic Year <span class="icon" data-toggle="tooltip" data-placement="top"
            title="Tooltip on top">&#xe009;</span></h2>
                <div class="col-xs-3">
                    <select  name="AY" class="col-xs-4 form-control" id="AYname" style="padding: 0px !important; background-color: #fff !important;">
                        <option value=""></option>
                        <?php while ($rowsay = $resultay->fetch_array(MYSQLI_NUM)): { ?>
                            <option value="<?php echo $rowsay[1]; ?>"><?php echo $rowsay[1]; ?></option>
                        <?php } endwhile; ?>
                    </select>
                </div>
                <br />
            <h2>2. Select Organization Unit <span class="icon" data-toggle="tooltip" data-placement="top"
            title="Tooltip on top">&#xe009;</span></h2>
            <div class="checkbox" id="ouname">
                <label><input type="checkbox" id="ckbCheckAll" >All Active Academic Units </label>
            </div>
            <?php while ($rowsou = $resultou->fetch_array(MYSQLI_NUM)): { ?>
                <div class="checkbox" id="ouname">
                    <label><input type="checkbox" name="ou_name[]"
                              class="checkBoxClass" value="<?php echo $rowsou[0].",".$rowsou[2]; ?>"><?php echo $rowsou[1]; ?></label>
                </div>
            <?php } endwhile; ?>
            <input type="submit" name="submit" value="Submit" class="btn-primary pull-right col-xs-3">
    </div>
</div>


<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>

<!--Calender Bootstrap inclusion for date picker INPUT-->
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
<script src="../Resources/Library/js/tabAlert.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
