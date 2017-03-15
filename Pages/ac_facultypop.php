<?php

require_once("../Resources/Includes/FILEUPLOAD.php");
$facultyPop = new FILEUPLOAD();
$facultyPop->checkSessionStatus();
$connection = $facultyPop->connection;

$message = array();
$errorflag = 0;
$i = 0;
$index = 0;
$count = 1;
$csv = array();
$tablefields = array();
$tablevalue = array();
$sqlupload = null;
$notBackToDashboard = true;
$sumtenurefac = array();
$sumresearchfac = array();
$sumclinicfac = array();
$sumotherfac = array();
$datavalues = array();

$content_id = $_GET['linkid'];
$outype = $_SESSION['login_outype'];
$FUayname = $_SESSION['FUayname'];
$ouid = $_SESSION['login_ouid'];
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_userid'];
$BackToFileUploadHome = true;
$discardid = array();

// File Upload Status & Details.
$resultfucontent = $facultyPop->GetStatus();
$rowsfucontent = $resultfucontent->fetch(2);

$tablename = $rowsfucontent['NAME_UPLOADFILE'];


// Display Of Values in validation from IR_AC_DiversityStudent Table of Database
$resultdatadisplay = $facultyPop->DataDisplay("IR_AC_FacultyPop", "ID_AC_FACULTY_POPULATION");

// File Upload Status & Details.
//try
//{
//    $sqlfucontent = "SELECT * FROM IR_SU_UploadStatus LEFT JOIN PermittedUsers ON
//PermittedUsers.ID_STATUS =IR_SU_UploadStatus.LAST_MODIFIED_BY WHERE IR_SU_UploadStatus.ID_UPLOADFILE= :content_id ;";
//    $resultfucontent = $connection->prepare($sqlfucontent);
//    $resultfucontent->execute(['content_id'=> $content_id]);
//}
//catch (PDOException $e)
//{
//    //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
//    error_log($e->getMessage());
//}
//$rowsfucontent = $resultfucontent->fetch(4);
//$tablename = $rowsfucontent['NAME_UPLOADFILE'];
//
//// Display Of Values in validation from IR_AC_DiversityStudent Table of Database
//try
//{
//    $sqldatadisplay = "SELECT * FROM IR_AC_FacultyPop WHERE ID_AC_FACULTY_POPULATION IN
// (SELECT MAX(ID_AC_FACULTY_POPULATION) FROM IR_AC_FacultyPop WHERE OUTCOMES_AY = :FUayname GROUP BY OU_ABBREV );";
//    $resultdatadisplay = $connection->prepare($sqldatadisplay);
//    $resultdatadisplay->execute(['FUayname' => $FUayname]);
//}
//catch (PDOException $e)
//{
//    //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
//    error_log($e->getMessage());
//}
$rowsdatadisplay = $resultdatadisplay->fetch(4);


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

                            } else {

                                // Manual Author & Modified Time entry into SQL with row values of file
                                if ($i == 3) {
                                    $tablevalue[$row][$i - 1] = $author;
                                } elseif ($i == 4) {
                                    $tablevalue[$row][$i - 1] = $time;
                                } elseif ($i == 13) {
                                    $tablevalue[$row][$i - 1] = $sumtenurefac[$row - 1];
                                } elseif ($i == 17) {
                                    $tablevalue[$row][$i - 1] = $sumresearchfac[$row - 1];
                                } elseif ($i == 22) {
                                    $tablevalue[$row][$i - 1] = $sumclinicfac[$row - 1];
                                }
//                                elseif ($i == 25) {
//                                    $tablevalue[$row][$i - 1] = $sumotherfac[$row - 1];
//                                }
                                else {
                                    $tablevalue[$row][$i - 1] = $csv[$row][$colindex];
                                }

                                //Total of Faculty
                                if ($i >= 5 and $i <= 12) {
                                    $sumtenurefac[$row - 1] += intval($csv[$row][$colindex]);
                                }
                                if ($i >= 14 and $i <= 16) {
                                    $sumresearchfac[$row - 1] += intval($csv[$row][$colindex]);
                                }

                                //Validation check of Graduate Male + Female with comparision to composition of Graduate Sudents
                                if ($i >= 18 and $i <= 21) {
                                    $sumclinicfac[$row - 1] += intval($csv[$row][$colindex]);

                                }
                                if ($i == 23 or $i == 24) {
                                    $sumotherfac[$row - 1] += intval($csv[$row][$colindex]);
                                }

                            }

                        } else {
                            // terminal values should not have (,) in SQL Query.
                            if ($row == 0) {
                                $tablefields[$i] = $csv[$row][$colindex];
                            } else {
                                // Last Column needs sum of previous 2 fields (i = 23,24).
                                $tablevalue[$row][$i - 1] = $sumotherfac[$row - 1];;

                            }
                        }
                    }

                    // inc the row
                    $row++;
                }


                if ($errorflag == 0) {

                    // Create SQL for All ORG Units
                    $sqlupload = "INSERT INTO IR_AC_FacultyPop (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP,
 TTF_FTE_PROF_TNR, TTF_FTE_ASSOC_PROF_TNR, TTF_FTE_PROF, TTF_FTE_ASSOC_PROF, TTF_FTE_ASSIST_PROF, TTF_FTE_LIBR_TNR,
   TTF_FTE_LIBR, TTF_FTE_ASSIST_LIBR, TTF_FTE_ALL, RSRCH_FTE_PROF, RSRCH_FTE_ASSOC_PROF, RSRCH_FTE_ASSIST_PROF, 
   RSRCH_FTE_ALL, CIF_FTE_CLNCL_PROF, CIF_FTE_CLNCL_ASSOC_PROF, CIF_FTE_CLNCL_ASSIST_PROF, CIF_FTE_INSTR_LCTR, 
   CIF_FTE_ALL, OTHRFAC_PT_ADJUNCT, OTHRFAC_PT_OTHER, OTHRFAC_ALL) VALUES (";

                    for ($i = 0; $i < count($tablefields); $i++) {

                        $sqlupload .= ($i == count($tablefields) - 1) ? '?' : '?,';
                    }
                    $sqlupload .= ");";;
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
                LAST_MODIFIED_BY = :author, LAST_MODIFIED_ON = :timeStampmod WHERE 
                IR_SU_UploadStatus.ID_UPLOADFILE =:content_id; ";

                    $result = $connection->prepare($sqlupload);
                    $result->bindParam(':author', $author, 2);
                    $result->bindParam(':timeStampmod', $time, 2);
                    $result->bindParam(':content_id', $content_id, 2);


                    if ($result->execute()) {

                        //USCALLAU USC ALL Academic Units Aggregator record creation . Also includes the idea to let
                        // user update more units in future. Below query group all discrete units and resolve
                        // collusion basis latest (max) ID value and then sum the records and constitute USCAAU

                        try {
                            $sqlupload = "INSERT INTO IR_AC_FacultyPop (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, 
MOD_TIMESTAMP, TTF_FTE_PROF_TNR, TTF_FTE_ASSOC_PROF_TNR, TTF_FTE_PROF, TTF_FTE_ASSOC_PROF, TTF_FTE_ASSIST_PROF,
 TTF_FTE_LIBR_TNR, TTF_FTE_LIBR, TTF_FTE_ASSIST_LIBR, TTF_FTE_ALL, RSRCH_FTE_PROF, RSRCH_FTE_ASSOC_PROF, 
 RSRCH_FTE_ASSIST_PROF, RSRCH_FTE_ALL, CIF_FTE_CLNCL_PROF, CIF_FTE_CLNCL_ASSOC_PROF, CIF_FTE_CLNCL_ASSIST_PROF,
  CIF_FTE_INSTR_LCTR, CIF_FTE_ALL, OTHRFAC_PT_ADJUNCT, OTHRFAC_PT_OTHER, OTHRFAC_ALL) 
  SELECT 'USCAAU' AS OU,:FUayname AS AY, :authorName AS AUTHOR,:timeCurrent AS MOD_Time,  sum(TTF_FTE_PROF_TNR), 
  sum(TTF_FTE_ASSOC_PROF_TNR), sum(TTF_FTE_PROF), sum(TTF_FTE_ASSOC_PROF), sum(TTF_FTE_ASSIST_PROF), 
  sum(TTF_FTE_LIBR_TNR), sum(TTF_FTE_LIBR),sum(TTF_FTE_ASSIST_LIBR), sum(TTF_FTE_ALL),sum(RSRCH_FTE_PROF), 
  sum(RSRCH_FTE_ASSOC_PROF), sum(RSRCH_FTE_ASSIST_PROF), sum(RSRCH_FTE_ALL), sum(CIF_FTE_CLNCL_PROF), 
  sum(CIF_FTE_CLNCL_ASSOC_PROF), sum(CIF_FTE_CLNCL_ASSIST_PROF), sum(CIF_FTE_INSTR_LCTR),sum(CIF_FTE_ALL),
  sum(OTHRFAC_PT_ADJUNCT), sum(OTHRFAC_PT_OTHER), sum(OTHRFAC_ALL) FROM IR_AC_FacultyPop 
WHERE ID_AC_FACULTY_POPULATION IN (SELECT max(ID_AC_FACULTY_POPULATION) FROM IR_AC_FacultyPop 
WHERE OUTCOMES_AY = :FUayname GROUP BY OU_ABBREV );";

                            $resultupload = $connection->prepare($sqlupload);
                            $resultupload->bindParam(':FUayname', $FUayname, 2);
                            $resultupload->bindParam(':authorName', $author, 2);
                            $resultupload->bindParam(':timeCurrent', $time, 2);

                            if ($resultupload->execute()) {
                                $message[0] = "Data Uploaded Successfully.";
                            } else {
                                $message[0] = "Error in Data. Upload Failed.";
                            }


                        } catch (PDOException $e) {
                            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
                            error_log($e->getMessage());
                        }

                    }

                } else {
                    $message[0] = "Faculty Population Data  does not match. Please check data & reload.";
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

//if (isset($_POST['upload'])) {
//
//    $tablename = $rowsfucontent['NAME_UPLOADFILE'];
//    //$ayname = $_POST['ay'];
//
//
//// check there are no errors
//    if ($_FILES['csv']['error'] == 0) {
//
//        $name = $_FILES['csv']['name'];
//        $ext = strtolower(end(explode('.', $_FILES['csv']['name'])));
//        $type = $_FILES['csv']['type'];
//        $tmpName = $_FILES['csv']['tmp_name'];
//
//        // check the file is a csv
//        if ($ext === 'csv') {
//
//            if (($handle = fopen($tmpName, 'r')) !== FALSE) {
//                // necessary if a large csv file
//                set_time_limit(0);
//
//                $row = 0;
//
//                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
//
//
//                    // number of fields in the csv
////                $col_count = count($data);
//
//                    // get the values from the csv
//                    for ($i = 1; $i <= count($data); $i++) {
//
//                        $colindex = 'col' . $i;
//                        $csv[$row][$colindex] = $data[$i - 1];
//
//                        //Prepare SQL query for Table variables from File with ('',) to support SQL syntax
//                        // Insert into <table_name> (Value1 , Value2 ) Values ('a','b'); all fields & values should have ('a',) where required.
//                        if ($i != count($data)) {
//
//                            if ($row == 0) {
//
//                                $tablefields[$i] = $csv[$row][$colindex] . ',';
//
//                            } else {
//
//                                // Manual Author & Modified Time entry into SQL with row values of file
//                                if ($i == 3) {
//                                    $tablevalue[$row][$i - 1] = "'" . $author . "'" . ',';
//                                } elseif ($i == 4) {
//                                    $tablevalue[$row][$i - 1] = "'" . $time . "'" . ',';
//                                } elseif ($i == 15) {
//                                    $tablevalue[$row][$i - 1] = "'" . $sumtenurefac[$row - 1] . "'" . ',';
//                                } elseif ($i == 19) {
//                                    $tablevalue[$row][$i - 1] = "'" . $sumresearchfac[$row - 1] . "'" . ',';
//                                } elseif ($i == 24) {
//                                    $tablevalue[$row][$i - 1] = "'" . $sumclinicfac[$row - 1] . "'" . ',';
//                                } elseif ($i == 27) {
//                                    $tablevalue[$row][$i - 1] = "'" . $sumotherfac[$row - 1] . "'" . ',';
//                                } else {
//                                    $tablevalue[$row][$i - 1] = "'" . $csv[$row][$colindex] . "'" . ',';
//                                }
//
//                                //Total of Faculty
//                                if ($i >= 5 and $i<=14) {
//                                    $sumtenurefac[$row - 1] += intval($csv[$row][$colindex]);
//                                }
//                                if ($i >= 16 and $i <= 18) {
//                                    $sumresearchfac[$row - 1] += intval($csv[$row][$colindex]);
//                                }
//
//                                //Validation check of Graduate Male + Female with comparision to composition of Graduate Sudents
//                                if ($i >= 20 and $i <= 23) {
//                                    $sumclinicfac[$row - 1] += intval($csv[$row][$colindex]);
//
//                                }
//                                if ($i == 25 or $i == 26) {
//                                    $sumotherfac[$row - 1] += intval($csv[$row][$colindex]);
//                                }
//
//                            }
//
//                        } else {
//                            // terminal values should not have (,) in SQL Query.
//                            if ($row == 0) {
//                                $tablefields[$i] = $csv[$row][$colindex];
//                            } else {
//                                $tablevalue[$row][$i - 1] = "'" . $csv[$row][$colindex] . "'";
//
//                            }
//                        }
//
//
//                    }
//
//                    // inc the row
//                    $row++;
//                }
//
//
//                for ($j = 1; $j < $row; $j++) {
//                    $sqlupload .= "INSERT INTO $tablename ( ";
//                    foreach ($tablefields as $fields) {
//                        $sqlupload .= $fields;
//                    }
//                    $sqlupload .= " ) Values (";
//                    foreach ($tablevalue[$j] as $fieldvalue) {
//                        $sqlupload .= $fieldvalue;
//                    }
//                    $sqlupload .= ");";
//                }
//
//                $sqlupload .= "Update IR_SU_UploadStatus SET STATUS_UPLOADFILE ='Pending Validation',
// LAST_MODIFIED_BY = '$author', LAST_MODIFIED_ON = '$time' where IR_SU_UploadStatus.ID_UPLOADFILE = '$content_id'; ";
//
//                if ($errorflag == 0) {
//
//                    if ($mysqli->multi_query($sqlupload)) {
//
//                        //USCALLAU USC ALL Academic Units Aggregator record creation . Also includes the idea to let user update more units in future
//                        // Below query group all discrete units and resolve collusion basis latest (max) ID value and then sum the records and constitute USCAAU
//
//                        $sqlupload = "INSERT INTO IR_AC_FacultyPop (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP, TTF_FTE_ALL, TTF_FTE_PROF_TNR, TTF_FTE_ASSOC_PROF_TNR, TTF_FTE_PROF, TTF_FTE_ASSOC_PROF, TTF_FTE_ASSIST_PROF, RSRCH_FTE_ALL, RSRCH_FTE_PROF, RSRCH_FTE_ASSOC_PROF, RSRCH_FTE_ASSIST_PROF, CIF_FTE_ALL, CIF_FTE_CLNCL_PROF, CIF_FTE_CLNCL_ASSOC_PROF, CIF_FTE_CLNCL_ASSIST_PROF, CIF_FTE_INSTR_LCTR, OTHRFAC_ALL, OTHRFAC_PT_ADJUNCT, OTHRFAC_PT_OTHER, STUDENT_FACULTY_RATIO)
//SELECT 'USCAAU' AS OU,'$FUayname' AS AY,'$author' AS AUTHOR,'$time' AS MOD_Time,sum(TTF_FTE_ALL), sum(TTF_FTE_PROF_TNR), sum(TTF_FTE_ASSOC_PROF_TNR), sum(TTF_FTE_PROF), sum(TTF_FTE_ASSOC_PROF), sum(TTF_FTE_ASSIST_PROF), sum(RSRCH_FTE_ALL), sum(RSRCH_FTE_PROF), sum(RSRCH_FTE_ASSOC_PROF), sum(RSRCH_FTE_ASSIST_PROF), sum(CIF_FTE_ALL), sum(CIF_FTE_CLNCL_PROF), sum(CIF_FTE_CLNCL_ASSOC_PROF), sum(CIF_FTE_CLNCL_ASSIST_PROF), sum(CIF_FTE_INSTR_LCTR), sum(OTHRFAC_ALL), sum(OTHRFAC_PT_ADJUNCT), sum(OTHRFAC_PT_OTHER),
//, 'NA' FROM IR_AC_FacultyPop where ID_AC_FACULTY_POPULATION in (select max(ID_AC_FACULTY_POPULATION) from IR_AC_FacultyPop where OUTCOMES_AY = '$FUayname' group by OU_ABBREV );";
//                        $mysqli->query($sqlupload);
//
//
//                        $message[0] = "Data Uploaded Successfully.";
//
//                    } else {
//
//                        $message[0] = "Error in Data. Upload Failed.";
//
//                    }
//
//                } else {
//
//                    $message[0] = "Faculty Data Composition does not match. Please check data & reload.";
//
//                }
//
//                fclose($handle);
//            }
//        } else {
//            $message[0] = "Please select only csv format File";
//        }
//    } else {
//        $message[0] = "Error in Uploading File. ";
//    }
//
//}

if (isset($_POST['validate'])) {
    $message[0] = $facultyPop->Validate();
}
if (isset($_POST['error'])) {
    $message[0] = $facultyPop->Error("IR_AC_FacultyPop", "ID_AC_FACULTY_POPULATION");
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
            <form action="<?php echo "ac_facultypop.php?linkid=" . $content_id ?>" method="post" class=""
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
            <form action="<?php echo "ac_facultypop.php?linkid=" . $content_id ?>" method="post"
                  enctype="multipart/form-data">
                <div id="" style="margin-top: 10px;">
                    <label for="validitychk">
                        <small><em>Please review the data table below to confirm values appear as intended.
                                If you observe any errors you may choose to depricate the file from system.If everything
                                appears as intended, Please Confirm.</em></small>
                    </label>
                    <div id="validitychk" class="form-group">
                        <form action="" method="POST" >
                        <h2 class="data-display">Select Academic Unit to View</h2>
                            <div class="col-xs-3">
                                <select  name="AY" class="col-xs-4 form-control" id="AYgoal">
                                    <option value="USC Columbia All Academic Units">USC Columbia All Academic Units
                                    </option>
                                    <option value="Other Units">Other Units</option>
                                    <option value="Other Units">Other Units</option>
                                    <option value="Other Units">Other Units</option>
                                    <option value="Other Units">Other Units</option>
                                </select>
                            </div>
                        </form>
                        <div id="display" class="col-xs-12">
                            <h2 class="data-display">Display Data - Table Name: <?php echo $tablename; ?></h2>
                            <p><b class="garnet">Academic Year: </b><?php echo $rowsdatadisplay['OUTCOMES_AY'];?></p>
                            <p><b class="garnet">College/School: </b><?php echo $rowsdatadisplay['OU_ABBREV'];?></p>
                            <p><b class="garnet">Outcomes Author: </b><?php
                                echo $facultyPop->getUserName($rowsdatadisplay['OUTCOMES_AUTHOR']);
                                ?></p>
                            <p><b class="garnet">Last Modified:</b> <?php echo $rowsdatadisplay['TTF_FTE_PROF_TNR']; ?> </p>


                            <h2 class="data-display">Tenure-track Faculty (FTE Positions)</h2>
                            <table >
                                <tr class="indent">
                                    <td><b class="garnet">Professor, with tenure:</b></td>
                                    <td><?php echo $rowsdatadisplay['TTF_FTE_PROF_TNR']; ?></td>
                                </tr>
                                <tr class="indent">
                                    <td><b class="garnet">Associate Professor, with tenure:</b></td>
                                    <td><?php echo $rowsdatadisplay['TTF_FTE_ASSOC_PROF_TNR']; ?></td>
                                </tr>
                                <tr class="indent">
                                    <td><b class="garnet">Professor:</b></td>
                                    <td><?php echo $rowsdatadisplay['TTF_FTE_PROF']; ?></td>
                                </tr>
                                <tr class="indent">
                                    <td><b class="garnet">Associate Professor:</b></td>
                                    <td><?php echo $rowsdatadisplay['TTF_FTE_ASSOC_PROF']; ?></td>
                                </tr>
                                <tr class="indent">
                                    <td><b class="garnet">Assistant Professor:</b></td>
                                    <td><?php echo $rowsdatadisplay['TTF_FTE_ASSIST_PROF']; ?></td>
                                </tr>
                                <tr class="indent"><td><br /></td></tr>
                                <tr class="indent">
                                    <td><b class="garnet">Total - Tenure-track Faculty (FTE positions):</b></td>
                                    <td><?php echo $rowsdatadisplay['TTF_FTE_ALL']; ?></td>
                                </tr>

                            <tr><td><h2 class="data-display">Research Faculty (FTE Positions)</h2></td></tr>

                            <tr class="indent">
                                <td><b class="garnet">Research Professor:</b></td>
                                <td> <?php echo $rowsdatadisplay['RSRCH_FTE_PROF']; ?> </td>
                            </tr>
                            <tr class="indent">
                                <td><b class="garnet">Research Associate Professor:</b></td>
                                <td> <?php echo $rowsdatadisplay['RSRCH_FTE_ASSOC_PROF']; ?> </td>
                            </tr>

                            <tr class="indent">
                                <td><b class="garnet">Research Assistant Professor:</b></td>
                                <td> <?php echo $rowsdatadisplay['RSRCH_FTE_ASSIST_PROF']; ?>  </td>
                            </tr>

                            <tr class="indent"><td><br /></td></tr>

                            <tr class="indent">
                                <td><b class="garnet">Total - Research Faculty (FTE Positions):</b></td>
                                <td> <?php echo $rowsdatadisplay['RSRCH_FTE_ALL']; ?> </td>
                            </tr>

                           <tr><td ><h2 class="data-display">Clinical/Instructional Faculty (FTE Positions)</h2></td></tr>

                            <tr class="indent">
                                <td><b class="garnet">Clinical  Professor:</b></td>
                                <td> <?php echo $rowsdatadisplay['CIF_FTE_CLNCL_PROF']; ?> </td>
                            </tr>
                            <tr class="indent">
                                <td><b class="garnet">Clinical  Associate Professor:</b></td>
                                <td> <?php echo $rowsdatadisplay['CIF_FTE_CLNCL_ASSOC_PROF']; ?> </td>
                            </tr>
                            <tr class="indent">
                                <td><b class="garnet">Clinical  Assistant Professor:</b></td>
                                <td> <?php echo $rowsdatadisplay['CIF_FTE_CLNCL_ASSIST_PRO']; ?>  </td>
                            </tr>
                            <tr class="indent">
                                <td><b class="garnet">Instructor/Lecturer:</b></td>
                                <td> <?php echo $rowsdatadisplay['CIF_FTE_INSTR_LCTR']; ?>  </td>
                            </tr>
                            <tr class="indent"><td><br /></td></tr>
                            <tr class="indent">
                                <td><b class="garnet">Total - Clinical/Instructional Faculty (FTE positions):</b></td>
                                <td> <?php echo $rowsdatadisplay['CIF_FTE_ALL']; ?> </td>
                            </tr>

                            <tr><td><h2 class="data-display">Other Faculty</h2></td></tr>

                            <tr class="indent">
                                <td><b class="garnet">Adjunct Faculty</b></td>
                                <td><?php echo $rowsdatadisplay['OTHRFAC_PT_ADJUNCT']; ?> </td>
                            </tr>
                            <tr class="indent">
                                <td><b class="garnet">Other Faculty:</b></td>
                                <td><?php echo $rowsdatadisplay['OTHRFAC_PT_OTHER']; ?> </td>
                            </tr>
                            <tr class="indent"><td><br /></td></tr>
                            <tr class="indent">
                                <td><b class="garnet">Total - Other Faculty:</b></td>
                                <td><?php echo $rowsdatadisplay['OTHRFAC_ALL']; ?>  </td>
                            </tr>

                            <p>Please Select <strong>Validation Confirmed</strong> to Confirm Uploading If Below Data is Correct.</p>
                            </table>
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
