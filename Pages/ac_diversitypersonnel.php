<?php

  require_once ("../Resources/Includes/initalize.php");
  $initalize = new Initialize();
  $initalize->checkSessionStatus();
  $connection = $initalize->connection;

  /**
   * Initialization of local variables
   */

  $message = array();
  $errorflag = 0;
  $colCount = 0;
  $count = 1;
  $index = 0;
  $discardid = array();
  $sqldel = null;
  $csv = array();
  $tablefileds = array();
  $tablevalue = array();
  $sqlupload = null;
  $notBackToDashboard = true;
  $sumfac = array();
  $sumstaff = array();
  $datavalues = array();
  $meta = array();

  //Variable import for SQL
  $content_id = $_GET['linkid'];
  $time = date('Y-m-d H:i:s');
  $author = $_SESSION['login_userid'];
  $ouid = $_SESSION['login_ouid'];
  $FUayname = $_SESSION['FUayname'];
  $outype = $_SESSION['login_outype'];

  //Menu control back Button
  $BackToFileUploadHome = true;

  // File Upload Status & Details.
  try{
      $sqlfucontent = "SELECT * FROM IR_SU_UploadStatus LEFT JOIN PermittedUsers ON PermittedUsers.ID_STATUS =
  IR_SU_UploadStatus.LAST_MODIFIED_BY WHERE IR_SU_UploadStatus.ID_UPLOADFILE= :content_id;";
      $resultfucontent = $connection->prepare($sqlfucontent);
      $resultfucontent->bindParam(':content_id', $content_id, 1);
      $resultfucontent->execute();
  }catch (PDOException $e){
      //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
      error_log($e->getMessage());
  }

  $rowsfucontent = $resultfucontent->fetch(2);
  $tablename = $rowsfucontent['NAME_UPLOADFILE'];

  // Display Of Values in validation from IR_AC_DiversityStudent Table of Database
  try{
      $sqldatadisplay = "SELECT * FROM IR_AC_DiversityPersonnel WHERE ID_IR_AC_DIVERSITY_PERSONNEL IN
  (SELECT MAX(ID_IR_AC_DIVERSITY_PERSONNEL) FROM IR_AC_DiversityPersonnel WHERE OUTCOMES_AY = :FUayname GROUP BY
  OU_ABBREV);";
      $resultdatadisplay = $connection->prepare($sqldatadisplay);
      $resultdatadisplay->bindParam(':FUayname', $FUayname, 2);
      $resultdatadisplay->execute();
  }catch (PDOException $e){
      //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
      error_log($e->getMessage());
  }

  $dynamictable = "<table border='1' cellpadding='5' class='table'><tr>";
  $fieldcnt = $resultdatadisplay->columnCount();
  $num_records = $resultdatadisplay->rowCount();

  while($meta = $resultdatadisplay->getColumnMeta($colCount)) {
      $datavalues[0][$i]=$meta[name];
      $i++;
      $colCount++;
  }

  while ($rowsdatadisplay = $resultdatadisplay->fetch(4)) {
      for($col = 0; $col<$fieldcnt; $col++) {
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
                        // Insert into <table_name> (Value1 , Value2 ) Values ('a','b'); all fields & values should
                        // have ('a',) where required.
                        if ($i != count($data)) {

                            if ($row == 0) {

                                $tablefileds[$i] = $csv[$row][$colindex] . ',';

                            } else {

                                // Manual Author & Modified Time entry into SQL with row values of file
                                if ($i == 3) {
                                    $tablevalue[$row][$i - 1] = "'" . $author . "'" . ',';
                                } elseif ($i == 4) {
                                    $tablevalue[$row][$i - 1] = "'" . $time . "'" . ',';
                                } else {
                                    $tablevalue[$row][$i - 1] = "'" . $csv[$row][$colindex] . "'" . ',';
                                }

                                //Validation check of Undergraduate Male + Female with comparision to composition of
                                // UGrad Sudents
                                if ($i == 5 or $i == 6) {
                                    $sumfac[$row - 1] += intval($csv[$row][$colindex]);
                                }
                                if ($i == 7 or $i == 8 or $i == 9 or $i == 10 or $i == 11 or $i == 12 or $i == 13 or
                                    $i == 14 or $i == 15) {
                                    $sumfac[$row - 1] -= intval($csv[$row][$colindex]);
                                }

                                //Validation check of Graduate Male + Female with comparision to composition of
                                // Graduate Sudents
                                if ($i == 16 or $i == 17) {
                                    $sumstaff[$row - 1] += intval($csv[$row][$colindex]);

                                }
                                if ($i == 18 or $i == 19 or $i == 20 or $i == 21 or $i == 22 or $i == 23 or $i == 24
                                    or $i == 25) {
                                    $sumstaff[$row - 1] -= intval($csv[$row][$colindex]);
                                }

                            }

                        } else {
                            // terminal values should not have (,) in SQL Query.
                            if ($row == 0) {
                                $tablefileds[$i] = $csv[$row][$colindex];
                            } else {
                                $tablevalue[$row][$i - 1] = "'" . $csv[$row][$colindex] . "'";

                                //Terminal Values of record reside here
                                if ($i == 26) {
                                    $sumstaff[$row - 1] -= intval($csv[$row][$colindex]);
                                }
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
                LAST_MODIFIED_BY = '$author', LAST_MODIFIED_ON = '$time' where IR_SU_UploadStatus.ID_UPLOADFILE =
                '$content_id'; ";

                for ($checkfac = 0; $checkfac < count($sumfac); $checkfac++) {
                    if ($sumfac[$checkfac] != 0) {
                        $message[$checkfac + 1] = "Mismatch in Faculty Composition.Record No: " . ($checkfac + 1) . "<br>";
                        $errorflag = 1;
                    }
                }
                for ($checkstaff = 0; $checkstaff < count($sumstaff); $checkstaff++) {
                    if ($sumstaff[$checkstaff] != 0) {
                        $message[$checkfac + 1] = "Mismatch in Staff Composition.Record No: " . ($checkstaff + 1) . "<br>";
                        $errorflag = 1;
                        $checkfac++;
                    }
                }

                if ($errorflag == 0) {

                    if ($mysqli->multi_query($sqlupload)) {

                        //USCALLAU USC ALL Academic Units Aggregator record creation . Also includes the idea to let
                        // user update more units in future. Below query group all discrete units and resolve
                        // collusion basis latest (max) ID value and then sum the records and constitute USCAAU

                        try
                        {
                        $sqlupload = "INSERT INTO IR_AC_DiversityPersonnel (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR,
                        MOD_TIMESTAMP, FAC_FEMALE, FAC_MALE, FAC_AMERIND_ALASKNAT, FAC_ASIAN, FAC_BLACK,
                        FAC_HISPANIC, FAC_HI_PAC_ISL, FAC_NONRESIDENT_ALIEN, FAC_TWO_OR_MORE,
                        FAC_UNKNOWN_RACE_ETHNCTY, FAC_WHITE, STAFF_FEMALE, STAFF_MALE, STAFF_AMERIND_ALASKNAT,
                        STAFF_ASIAN, STAFF_BLACK, STAFF_HISPANIC, STAFF_HI_PAC_ISL, STAFF_NONRESIDENT_ALIEN,
                        STAFF_TWO_OR_MORE, STAFF_UNKNOWN_RACE_ETHNCTY, STAFF_WHITE) SELECT 'USCAAU' AS OU,
                        :FUayname AS AY,:authorName AS AUTHOR,:timeCurrent AS MOD_Time, SUM(FAC_FEMALE), SUM(FAC_MALE),
                        SUM(FAC_AMERIND_ALASKNAT), SUM(FAC_ASIAN), SUM(FAC_BLACK), SUM(FAC_HISPANIC),
                        SUM(FAC_HI_PAC_ISL), SUM(FAC_NONRESIDENT_ALIEN), SUM(FAC_TWO_OR_MORE),
                        SUM(FAC_UNKNOWN_RACE_ETHNCTY), SUM(FAC_WHITE), SUM(STAFF_FEMALE), SUM(STAFF_MALE),
                        SUM(STAFF_AMERIND_ALASKNAT), SUM(STAFF_ASIAN), SUM(STAFF_BLACK), SUM(STAFF_HISPANIC),
                        SUM(STAFF_HI_PAC_ISL), SUM(STAFF_NONRESIDENT_ALIEN), SUM(STAFF_TWO_OR_MORE),
                        SUM(STAFF_UNKNOWN_RACE_ETHNCTY), SUM(STAFF_WHITE) FROM IR_AC_DiversityPersonnel WHERE
                        ID_IR_AC_DIVERSITY_PERSONNEL IN (SELECT MAX(ID_IR_AC_DIVERSITY_PERSONNEL) FROM
                        IR_AC_DiversityPersonnel WHERE OUTCOMES_AY = :FUayname GROUP BY OU_ABBREV);";

                        $resultupload = $connection->prepare($sqlupload);
                            $resultupload->bindParam(':FUayname', $FUayname, 2);
                            $resultupload->bindParam(':authorName', $author, 2);
                            $resultupload->bindParam(':timeCurrent', $time, 2);
                            $resultupload->execute();
                        }
                        catch (PDOException $e)
                        {
                            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
                            error_log($e->getMessage());
                        }
                        $message[0] = "Data Uploaded Successfully.";

                    } else {
                        $message[0] = "Error in Data. Upload Failed.";
                    }

                } else {
                    $message[0] = "Personnel Data Composition does not match. Please check data & reload.";
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

if(isset($_POST['save'])) {

    try
    {
        $sqlupload = "Update IR_SU_UploadStatus SET STATUS_UPLOADFILE='Complete',LAST_MODIFIED_BY =:author,
        LAST_MODIFIED_ON =:timeCurrent WHERE IR_SU_UploadStatus.ID_UPLOADFILE = :content_id;";

        if ($connection->prepare($sqlupload)->execute(['author'=> $author, 'timeCurrent' => $time, 'content_id' =>
       $content_id])) {
            $message[0] = "Data Validated Successfully.";
        } else {
            $message[0] = "Error in Data validation.Process Failed.";
        }
    }
    catch (PDOException $e)
    {
//        SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        error_log($e->getMessage());
    }

}
if(isset($_POST['error'])) {

    $sqlupload = "Update IR_SU_UploadStatus SET STATUS_UPLOADFILE='No File Provided',LAST_MODIFIED_BY ='$author',
    LAST_MODIFIED_ON ='$time'  where  IR_SU_UploadStatus.ID_UPLOADFILE = '$content_id'; ";

    if ($mysqli->query($sqlupload)) {
        $sqlupload = "SELECT ID_IR_AC_DIVERSITY_PERSONNEL from IR_AC_DiversityPersonnel where OUTCOMES_AY = '$FUayname'; ";
        $resultsqlupload = $mysqli->query($sqlupload);

        while ($rowssqlupload = $resultsqlupload->fetch_assoc()) {
            $discardid[$index] = $rowssqlupload['ID_IR_AC_DIVERSITY_PERSONNEL'];
            $index++;
        }
        foreach ($discardid as $delete) {
            $sqldel .= "delete from IR_AC_DiversityPersonnel where ID_IR_AC_DIVERSITY_PERSONNEL = '$delete'; ";
        }
        if ($mysqli->multi_query($sqldel)) {
            $message[0] = "Data Deprecated.Please Reload the File";
        } else {
            $message[0] = "Error in Data Deprecation.Process Failed.";
        }
    } else {
        $message[0] = "Action Failed. Please Retry.";
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
            <form action="<?php echo "ac_diversitypersonnel.php?linkid=" . $content_id ?>" method="post" class=""
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
            <form action="<?php echo "ac_diversitypersonnel.php?linkid=" . $content_id ?>" method="post"
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
                            <p>Please Select <strong>Validation Confirmed</strong> to Confirm Uploading If Below Data
                                is Correct.</p>
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
