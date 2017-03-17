<?php


/*
 * This is Generic File  Uploaf page which picks column name from csv file and match with DB scheme.
 * If match is correct , Insert records other wise
 */
require_once("../Resources/Includes/FILEUPLOAD.php");
$fileUpload = new FILEUPLOAD();
$fileUpload->checkSessionStatus();
$connection = $fileUpload->connection;

//Initialization of local variables

$message = array();
$errorflag = 0;

//Variable import for SQL & Workflow important variables : Should not be cleaned in Refactoring.

$content_id = $_GET['linkid'];
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_userid'];
$ouid = $_SESSION['login_ouid'];
$FUayname = $_SESSION['FUayname'];
$outype = $_SESSION['login_outype'];


//Menu control back Button
$BackToFileUploadHome = true;

// File Upload Status & Details.
$resultfucontent = $fileUpload->GetStatus();
$rowsfucontent = $resultfucontent->fetch(2);

/*
 * Get Table names & Primary key to identify correct upload behaviour
 */
$tablename = $rowsfucontent['NAME_UPLOADFILE'];
$primarykey = $fileUpload->GetPrimaryKey($tablename);


if (isset($_POST['upload'])) {

    switch ($tablename) {
        case "IR_AC_DiversityPersonnel" :
            $message = $fileUpload->uploadDiversityPersonnel();
            break;

        case "IR_AC_DiversityStudent":
            $message = $fileUpload->uploadDiversityStudent();
            break;

        case "IR_AC_Enrollments":
            $message = $fileUpload->uploadEnrollment();
            break;

        case "IR_AC_FacultyPop" :
            $message = $fileUpload->uploadFacultyPopulation();
            break;
        default :
            $message = $fileUpload->uploadGenericFile();
    }

}
if (isset($_POST['validate'])) {
    $message[0] = $fileUpload->Validate();
}
if (isset($_POST['error'])) {
    $message[0] = $fileUpload->Error($tablename, $primarykey);
}


require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<script src="../Resources/Library/js/chart.min.js"></script>

<div class="overlay hidden"></div>
<?php if (isset($_POST['upload']) || isset($_POST['save']) || isset($_POST['error'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" redirect="<?php echo '../Pages/fileuploadhome.php?ayname=' . $FUayname; ?>"
                class="end btn-primary">Close
        </button>
    </div>
<?php } ?>

<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title" class="">Service Unit Data Upload Management</h1>
    </div>

    <!-- Possible Greeting box -->
    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 id="fuayname" class="box-title"><?php echo $FUayname; ?></h1>
        <p id="data-table-name" class="status"><span>Data Table Name:</span> <?php
            echo $rowsfucontent['NAME_UPLOADFILE']; ?></p>
        <p class="status"><span>File Status:</span> <?php echo $rowsfucontent['STATUS_UPLOADFILE']; ?></p>
        <p class="status">
            <span>Last Modified:</span> <?php if ($rowsfucontent['LAST_MODIFIED_ON'] != "") {
                echo date("F j, Y, g:i a", strtotime($rowsfucontent['LAST_MODIFIED_ON']));
            } ?>
        </p>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">

        <?php if ($rowsfucontent['STATUS_UPLOADFILE'] == 'No File Provided') { ?>
            <form action="<?php echo "uploadfile.php?linkid=" . $content_id ?>" method="post" class=""
                  enctype="multipart/form-data">
                <div id="" style="margin-top: 10px;">
                    <div id="suppfacinfo" class="form-group">
                        <label for="supinfo">
                            <small><em>Please Browse to the correct file as above.</em></small>
                        </label>
                        <input id="supinfo" type="file" name="csv" onchange="selectorfile(this)" class="form-control"
                               required>
                    </div>
                    <!--                    <input type="button" id="cancelbtn" value="Cancel & Discard" class="btn-primary cancelFUbox pull-left">-->
                    <input type="submit" name="upload" id="upload" class="btn-primary pull-right" value="Upload">
                </div>
            </form>
        <?php } else { ?>

        <form action="<?php echo $_SERVER['PHP_SELF'] . "?linkid=" . $content_id ?>" method="post">

            <div id="" style="margin-top: 10px;">

                <label for="validitychk">
                    <small><em>Please review the data table below to confirm values appear as intended.
                            If you observe any errors you may choose to depricate the file from system.If everything
                            appears as intended, Please Confirm.</em></small>
                </label>
                <div id="validitychk" class="form-group">

                    <div id="display">
                        <?php $fileUpload->getDistinctCollegeName(); ?>
                    </div>
                    <div id="dataValidation">
                        <!--                            <p><b>Display Data - Table Name: -->
                        <?php //echo $tablename; ?><!--</b></p>-->
                        <!--                            --><?php //// Display Of Values in validation from IR_AC_DiversityStudent Table of Database
                        //                            $dynamictable = $fileUpload->HTMLtable($tablename, $primarykey);
                        //                            echo $dynamictable;
                        //                            ?>
                    </div>

                    <p style="margin-top: 10px;" class="col-xs-12">Please Select <strong>Validation Confirmed</strong>
                    to Confirm Uploading If
                        Above Data is
                        Correct.</p>
                </div>

                <input type="submit" name="save" id="save" class="btn-primary pull-right"
                       value="Validation Confirmed">
                <input type="submit" name="error" id="error" class="btn-primary pull-right"
                       value="Error Detected">
            </div>
    </div>

    </form>
    <?php } ?>
</div>

<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>

<script src="../Resources/Library/js/cancelbox.js"></script>
<script src="../Resources/Library/js/visualData.js"></script>

<script type="text/javascript">
    function selectorfile(selected) {
        var b = $(selected).val().substr(12);
        alert(b + " is selected.");
    }
</script>
