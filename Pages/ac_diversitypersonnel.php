<?php

/*
 * This Upload functionality does not match csv file first row headers to DB table fields.
 *
 */
require_once("../Resources/Includes/FILEUPLOAD.php");
$diversityPersonnel = new FILEUPLOAD();
$diversityPersonnel->checkSessionStatus();
$connection = $diversityPersonnel->connection;

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
$resultfucontent = $diversityPersonnel->GetStatus();
$rowsfucontent = $resultfucontent->fetch(2);

/*
 * Get Table names & Primary key to identify correct upload behaviour
 */
$tablename = $rowsfucontent['NAME_UPLOADFILE'];
$primarykey = $diversityPersonnel->GetPrimaryKey($tablename);

// Display Of Values in validation from IR_AC_DiversityStudent Table of Database
$dynamictable = $diversityPersonnel->HTMLtable($tablename, $primarykey);

if (isset($_POST['upload'])) {
    $message = $diversityPersonnel->uploadDiversityPersonnel();
}

if (isset($_POST['validate'])) {
    $message[0] = $diversityPersonnel->Validate();
}
if (isset($_POST['error'])) {
    $message[0] = $diversityPersonnel->Error($tablename, $primarykey);
}

require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");

?>
<div class="overlay hidden"></div>
<?php if (isset($_POST['upload']) || isset($_POST['validate']) || isset($_POST['error'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" redirect="<?php echo '../Pages/fileuploadhome.php?ayname='.$FUayname;?>" class="end
        btn-primary">Close</button>
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
        <p class="status"><span>Data Table Name:</span> <?php echo $rowsfucontent['NAME_UPLOADFILE']; ?></p>
        <p class="status"><span>File Status:</span> <?php echo $rowsfucontent['STATUS_UPLOADFILE']; ?></p>
        <p class="status">
            <span>Last Modified:</span> <?php if($rowsfucontent['LAST_MODIFIED_ON'] != "") {
                echo date("F j, Y, g:i a", strtotime($rowsfucontent['LAST_MODIFIED_ON'])); } ?>
        </p>
    </div>
    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <?php if ($rowsfucontent['STATUS_UPLOADFILE'] == 'No File Provided') { ?>
            <form action="<?php echo $_SERVER['PHP_SELF']."?linkid=" . $content_id ?>" method="post" class=""
                  enctype="multipart/form-data">
                <div id="" style="margin-top: 10px;">
                    <div id="suppfacinfo" class="form-group">
                        <label for="supinfo">
                            <small><em>Please Browse to the correct file as above.</em></small>
                        </label>
                        <input id="supinfo" type="file" name="csv" onchange="selectorfile(this)" class="form-control"
                               required>
                    </div>
                    <input type="submit" name="upload" id="upload" class="btn-primary pull-right" value="Upload">
                </div>
            </form>
        <?php } else{ ?>
            <form action="<?php echo $_SERVER['PHP_SELF']."?linkid=" . $content_id ?>" method="post">
                <div id="" style="margin-top: 10px;">
                    <label for="validitychk">
                        <small><em>Please review the data table below to confirm values appear as intended.
                                If you observe any errors you may choose to depricate the file from system.If everything
                                appears as intended, Please Confirm.</em></small>
                    </label>
                    <div id="validitychk" class="form-group">

                        <div id="display">
                            <p><b>Display Data - Table Name: <?php echo $tablename; ?></b></p>
                            <?php echo $dynamictable; ?>
                            <p>Please Select <strong>Validation Confirmed</strong> to Confirm Uploading If Below Data
                                is Correct.</p>
                        </div>

                        <input type="submit" name="validate" id="save" class="btn-primary pull-right"
                               value="Validation Confirmed">
                        <input type="submit" name="error" id="error" class="btn-primary pull-right"
                               value="Error Detected">
                    </div>
                </div>
            </form>
        <?php } ?>
    </div>
</div>
<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>

<script src="../Resources/Library/js/cancelbox.js"></script>

<script type="text/javascript">
    function selectorfile(selected) {
        var b = $(selected).val().substr(12);
        alert(b + " is selected.");
    }
</script>
