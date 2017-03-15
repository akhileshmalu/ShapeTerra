<?php


/*
 * This is Generic File  Uploaf page which picks column name from csv file and match with DB scheme.
 * If match is correct , Insert records other wise
 */
require_once("../Resources/Includes/FILEUPLOAD.php");
$fileUpload = new FILEUPLOAD();
$fileUpload->checkSessionStatus();
$connection = $fileUpload->connection;

$message = array();
$errorflag = 0;
$i = 0;
$count = 1;


$content_id = $_GET['linkid'];
$outype = $_SESSION['login_outype'];
$FUayname = $_SESSION['FUayname'];
$ouid = $_SESSION['login_ouid'];
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_userid'];
$BackToFileUploadHome = true;
$discardid = array();
//$fields = array ();


$csv = array();
$tablefields = array();
$tablevalue = array();
$sqlupload = null;
$notBackToDashboard = true;
$datavalues = array();


// File Upload Status & Details.
$resultfucontent = $fileUpload->GetStatus();
$rowsfucontent = $resultfucontent->fetch(2);
$tablename = $rowsfucontent['NAME_UPLOADFILE'];

$primary_key = $fileUpload->GetPrimaryKey($tablename);


// Display Of Values in validation from IR_AC_DiversityStudent Table of Database
$dynamictable = $fileUpload->HTMLtable($tablename, $primary_key);

if (isset($_POST['upload'])) {

    $tablename = $rowsfucontent['NAME_UPLOADFILE'];
    //$ayname = $_POST['ay'];


// check there are no errors
    if ($_FILES['csv']['error'] == 0) {

        $name = $_FILES['csv']['name'];
        $ext = strtolower(end(explode('.', $_FILES['csv']['name'])));
        $type = $_FILES['csv']['type'];
        $tmpName = $_FILES['csv']['tmp_name'];

        // check the file is a csv
        if ($ext === 'csv') {

            if (($handle = fopen($tmpName, 'r')) !== FALSE) {
                // necessary if a large csv file
                set_time_limit(0);

                $row = 0;

                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {

                    // get the values from the csv
                    for ($i = 1; $i <= count($data); $i++) {

                        $colindex = 'col' . $i;
                        $csv[$row][$colindex] = $data[$i - 1];

                        //Prepare SQL query for Table variables from File with ('',) to support SQL syntax
                        // Insert into <table_name> (Value1 , Value2 ) Values ('a','b'); all fields & values should have ('a',) where required.
                        if ($i != count($data)) {

                            if ($row == 0) {

                                $tablefields[$i] = $csv[$row][$colindex] . ',';
                                $tablefld = $csv[$row][$colindex];

                            } else {


                                // Manual Author & Modified Time entry into SQL with row values of file
                                if ($i == 3) {
                                    $tablevalue[$row][$i - 1] = $author;
                                } elseif ($i == 4) {
                                    $tablevalue[$row][$i - 1] = $time;
                                } else {
                                    $tablevalue[$row][$i - 1] = $csv[$row][$colindex];
                                }

                            }

                        } else {
                            // terminal values should not have (,) in SQL Query.
                            if ($row == 0) {
                                $tablefields[$i] = $csv[$row][$colindex];
                            } else {
                                $tablevalue[$row][$i - 1] = $csv[$row][$colindex];

                            }
                        }

                    }

                    // inc the row
                    $row++;
                }


                if ($errorflag == 0) {

//                    for ($j = 1; $j < $row; $j++) {
                        $sqlupload .= "INSERT INTO $tablename ( ";
                        foreach ($tablefields as $fields) {
                            $sqlupload .= $fields;
                        }
                        $sqlupload .= " ) Values (";


//                    foreach ($tablevalue[$j] as $fieldvalue) {
//                        $sqlupload .= $fieldvalue;
//                    }
//                    $sqlupload .= ");";

                        for ($i = 0; $i < count($tablefields); $i++) {
                            $sqlupload .= ($i == count($tablefields) - 1) ? '?' : '?,';
                        }
                        $sqlupload .= ");";

                        // Prepare Sql Query
                        $result = $connection->prepare($sqlupload);

                        // binding Variables
                        for ($j = 1; $j < $row; $j++) {
                            for ($i = 0; $i < count($tablevalue[$j]); $i++) {
                                $result->bindParam($i + 1, $tablevalue[$j][$i], 2);
                            }
                            $result->execute();
                        }


                    $sqlupload = "UPDATE IR_SU_UploadStatus SET STATUS_UPLOADFILE ='Pending Validation',
 LAST_MODIFIED_BY = :author, LAST_MODIFIED_ON = :timeStampmod WHERE IR_SU_UploadStatus.ID_UPLOADFILE = :content_id; ";

                    $resultupload = $connection->prepare($sqlupload);
                    $resultupload->bindParam(':author', $author, 2);
                    $resultupload->bindParam(':timeStampmod', $time, 2);
                    $resultupload->bindParam(':content_id', $content_id, 1);

                    if ($resultupload->execute()) {
                        $message[0] = "Data Uploaded Successfully.";
                    } else {
                        $message[0] = "Error in Data. Upload Failed.";
                    }

                } else {

                    $message[0] = "Data Composition does not match. Please check data & reload.";

                }

                fclose($handle);
            }
        } else {
            $message[0] = "Please select only csv format File";
        }
    } else {
        $message[0] = "Error in Uploading File. ";
    }

}
if (isset($_POST['validate'])) {
    $message[0] = $fileUpload->Validate();
}
if (isset($_POST['error'])) {
    $message[0] = $fileUpload->Error($tablename, $primary_key);
}


require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>
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
        <p class="status"><span>Data Table Name:</span> <?php echo $rowsfucontent['NAME_UPLOADFILE']; ?></p>
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

            <form action="<?php echo $_SERVER['PHP_SELF']."?linkid=" . $content_id ?>" method="post"
                  enctype="multipart/form-data">

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

                            <p>Please Select <strong>Validation Confirmed</strong> to Confirm Uploading If Below Data is
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

    <script type="text/javascript">
        function selectorfile(selected) {
            var b = $(selected).val().substr(12);
            alert(b + " is selected.");
        }


    </script>
