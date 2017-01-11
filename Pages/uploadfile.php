<?php

session_start();
$error = array();
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

require_once ("../Resources/Includes/connect.php");


$csv = array();
$tablefileds = array();
$tablevalue = array();
$sqlupload =null;
$notBackToDashboard =true;
$sumtenurefac = array();
$sumresearchfac = array();
$sumclinicfac = array();
$sumotherfac = array();
$datavalues = array();


/*
 * File Upload Status & Details.
 */
$sqlfucontent = "select * from IR_SU_UploadStatus 
LEFT JOIN PermittedUsers ON  PermittedUsers.ID_STATUS= IR_SU_UploadStatus.LAST_MODIFIED_BY where  IR_SU_UploadStatus.ID_UPLOADFILE= '$content_id' ; ";
$resultfucontent = $mysqli->query($sqlfucontent);
$rowsfucontent = $resultfucontent ->fetch_assoc();
$tablename = $rowsfucontent['NAME_UPLOADFILE'];


/*
 * Primary Key of Table <ID>
 */
$sqlupload = "SELECT * from $tablename where OUTCOMES_AY = '$FUayname'; ";
$resultsqlupload = $mysqli->query($sqlupload);
$fields = $resultsqlupload->fetch_field();
$primary_key = $fields->name;


/*
 * Display Of Values in validation from IR_AC_DiversityStudent Table of Database
 */
$sqldatadisplay = "SELECT * FROM $tablename where $primary_key in (select max($primary_key) from $tablename where OUTCOMES_AY = '$FUayname' group by OU_ABBREV );";
//echo $sqldatadisplay;
//$sqldatadisplay = "SELECT * FROM $tablename where OUTCOMES_AY = '$FUayname' group by OU_ABBREV ;";
$resultdatadisplay = $mysqli -> query($sqldatadisplay);

$dynamictable = "<table border='1' cellpadding='5' class='table'><tr>";
$fieldcnt = $resultdatadisplay->field_count;

$num_records = $resultdatadisplay->num_rows;


while($meta = $resultdatadisplay->fetch_field()) {
    $datavalues[0][$i]=$meta->name;
    $i++;
}


while($rowsdatadisplay = $resultdatadisplay ->fetch_array(MYSQLI_NUM)) {
    for($col = 0;$col<$fieldcnt;$col++) {
        $datavalues[$count][$col] = $rowsdatadisplay[$col];
    }
    $count++;
}

for ($j = 1; $j < $fieldcnt; $j++) {
    for ($i = 0; $i <= $num_records; $i++) {
        if($i == 0)    {
            $dynamictable .= "<td>" . $datavalues[$i][$j] . "</td>";
        } else {
            $dynamictable .= "<td>" . $datavalues[$i][$j] . "</td>";
        }

    }
    $dynamictable .= "</tr>";
}
$dynamictable .= '</table>';



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


                    // number of fields in the csv
//                $col_count = count($data);

                    // get the values from the csv
                    for ($i = 1; $i <= count($data); $i++) {

                        $colindex = 'col' . $i;
                        $csv[$row][$colindex] = $data[$i - 1];

                        //Prepare SQL query for Table variables from File with ('',) to support SQL syntax
                        // Insert into <table_name> (Value1 , Value2 ) Values ('a','b'); all fields & values should have ('a',) where required.
                        if ($i != count($data)) {

                            if ($row == 0) {

                                $tablefileds[$i] = $csv[$row][$colindex] . ',';
                                $tablefld = $csv[$row][$colindex];

                            } else {


                                // Manual Author & Modified Time entry into SQL with row values of file
                                if ($i == 3) {
                                    $tablevalue[$row][$i - 1] = "'" . $author . "'" . ',';
                                } elseif ($i == 4) {
                                    $tablevalue[$row][$i - 1] = "'" . $time . "'" . ',';
                                } else {
                                    $tablevalue[$row][$i - 1] = "'" . $csv[$row][$colindex] . "'" . ',';
                                }

                                //Validation check of Undergraduate Male + Female with comparision to composition of UGrad Sudents
//                                if ($i == 5 or $i == 6 or $i == 7 or $i == 8 or $i == 9) {
//                                    $sumtenurefac[$row - 1] += intval($csv[$row][$colindex]);
//                                }
//                                if ($i == 11 or $i == 12 or $i == 13) {
//                                    $sumresearchfac[$row - 1] += intval($csv[$row][$colindex]);
//                                }
//
//                                //Validation check of Graduate Male + Female with comparision to composition of Graduate Sudents
//                                if ($i == 15 or $i == 16 or $i == 17 or $i == 18) {
//                                    $sumclinicfac[$row - 1] += intval($csv[$row][$colindex]);
//
//                                }
//                                if ($i == 20 or $i == 21) {
//                                    $sumotherfac[$row - 1] += intval($csv[$row][$colindex]);
//                                }

                            }

                        } else {
                            // terminal values should not have (,) in SQL Query.
                            if ($row == 0) {
                                $tablefileds[$i] = $csv[$row][$colindex];
                            } else {
                                $tablevalue[$row][$i - 1] = "'" . $csv[$row][$colindex] . "'";

                            }
                        }


                    }

                    // inc the row
                    $row++;
                }


                for ($j = 1; $j < $row; $j++) {
                    $sqlupload .= "INSERT INTO $tablename ( ";
                    foreach ($tablefileds as $fields) {
                        $sqlupload .= $fields;
                    }
                    $sqlupload .= " ) Values (";
                    foreach ($tablevalue[$j] as $fieldvalue) {
                        $sqlupload .= $fieldvalue;
                    }
                    $sqlupload .= ");";
                }

                $sqlupload .= "Update IR_SU_UploadStatus SET STATUS_UPLOADFILE ='Pending Validation',
 LAST_MODIFIED_BY = '$author', LAST_MODIFIED_ON = '$time' where IR_SU_UploadStatus.ID_UPLOADFILE = '$content_id'; ";

                if ($errorflag == 0) {

                    if ($mysqli->multi_query($sqlupload)) {

//USCALLAU USC ALL Academic Units Aggregator record creation . Also includes the idea to let user update more units in future
                        // Below query group all discrete units and resolve collusion basis latest (max) ID value and then sum the records and constitute USCAAU

                        $sqlupload = "INSERT INTO $tablename (";
                        for($i = 1;$i<count($tablefileds)-1;$i++) {
                            $sqlupload .=$tablefileds[$i].',';
                        }
                        $sqlupload .=$tablefileds[$i].") SELECT 'USCAAU' AS OU,'$FUayname' AS AY,'$author' AS AUTHOR,'$time' AS MOD_Time,";

                        for($i = 5;$i<count($tablefileds)-1;$i++) {
                            $sqlupload .="SUM(".$tablefileds[$i]."),";
                        }

                        $sqlupload .= "SUM(".$tablefileds[$i].") FROM $tablename where $primary_key in (SELECT MAX($primary_key) from $tablename where OUTCOMES_AY = '$FUayname' group by OU_ABBREV );";

                        $mysqli->query($sqlupload);

                        $error[0] = "Data Uploaded Successfully.";

                    } else {

                        $error[0] = "Error in Data. Upload Failed.";

                    }

                } else {

                    $error[0] = "Data Composition does not match. Please check data & reload.";

                }

                fclose($handle);
            }
        } else {
            $error[0] = "Please select only csv format File";
        }
    } else {
        $error[0] = "Error in Uploading File. ";
    }

}

if(isset($_POST['save'])) {

    $sqlupload = "Update IR_SU_UploadStatus SET STATUS_UPLOADFILE='Complete',LAST_MODIFIED_BY ='$author',LAST_MODIFIED_ON ='$time'  where  IR_SU_UploadStatus.ID_UPLOADFILE = '$content_id'; ";


    if ($mysqli->query($sqlupload)) {
        $error[0] = "Data Validated Successfully.";
    } else {
        $error[0] = "Error in Data validation.Process Failed.";
    }

}
if(isset($_POST['error'])) {

    $sqlupload = "Update IR_SU_UploadStatus SET STATUS_UPLOADFILE='No File Provided',LAST_MODIFIED_BY ='$author',LAST_MODIFIED_ON ='$time'  where  IR_SU_UploadStatus.ID_UPLOADFILE = '$content_id'; ";

//    if ($mysqli->query($sqlupload)) {
//        $error[0] = "Data Deprecated.Please Reload the File";
//    } else {
//        $error[0] = "Error in Data Deprecation.Process Failed.";
//    }
    if ($mysqli->query($sqlupload)) {


        while ($rowssqlupload = $resultsqlupload->fetch_array(MYSQLI_NUM)) {
            $discardid[$index] = $rowssqlupload[0];
            $index++;
        }
        foreach ($discardid as $delete) {
            $sqldel .= "delete from $tablename where $primary_key = '$delete'; ";
        }
        if ($mysqli->multi_query($sqldel)) {
            $error[0] = "Data Deprecated.Please Reload the File";


        } else {
            $error[0] = "Error in Data Deprecation.Process Failed.";
        }
    } else {
        $error[0] = "Action Failed. Please Retry.";
    }

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
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" redirect="<?php echo '../Pages/fileuploadhome.php?ayname='.$FUayname; ?>" class="end btn-primary">Close</button>
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
            <span>Last Modified:</span> <?php if($rowsfucontent['LAST_MODIFIED_ON'] != "") { echo date("F j, Y, g:i a", strtotime($rowsfucontent['LAST_MODIFIED_ON'])); } ?>
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
        <?php } else{ ?>

            <form action="<?php echo "uploadfile.php?linkid=" . $content_id ?>" method="post"
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

                            <p>Please Select <strong>Validation Confirmed</strong> to Confirm Uploading If Below Data is Correct.</p>
                        </div>


                        <input type="submit" name="save" id="save" class="btn-primary pull-right"
                               value="Validation Confirmed">
                        <input type="submit" name="error" id="error" class="btn-primary pull-right"
                               value="Error Detected">


                        <!--            <input type="submit" name="save" id="save" class="btn-primary pull-right" value="Save">-->

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

