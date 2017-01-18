<?php

session_start();
$error = array();
$errorflag = 0;
$i = 0;
$index = 0;
$count = 1;


$content_id = $_GET['linkid'];
$outype = $_SESSION['login_outype'];
$FUayname = $_SESSION['FUayname'];
$ouid = $_SESSION['login_ouid'];
$time = date('Y-m-d H:i:s');
$author = $_SESSION['login_userid'];
$BackToFileUploadHome = true;
$discardid = array();

require_once ("../Resources/Includes/connect.php");


$csv = array();
$tablefileds = array();
$tablevalue = array();
$sqlupload = null;
$notBackToDashboard = true;
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
 * Display Of Values in validation from IR_AC_DiversityStudent Table of Database
 */
$sqldatadisplay = "SELECT * FROM IR_AC_FacultyPop where ID_AC_FACULTY_POPULATION in (select max(ID_AC_FACULTY_POPULATION) from IR_AC_FacultyPop where OUTCOMES_AY = '$FUayname' group by OU_ABBREV );";
$resultdatadisplay = $mysqli->query($sqldatadisplay);
$rowsdatadisplay = $resultdatadisplay ->fetch_array(MYSQLI_NUM);


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

// for ($j = 1; $j < $fieldcnt; $j++) {
//     for ($i = 0; $i <= $num_records; $i++) {
//         if($i == 0)    {
//             $dynamictable .= "<td>" . $datavalues[$i][$j] . "</td>";
//         } else {
//             $dynamictable .= "<td>" . $datavalues[$i][$j] . "</td>";
//         }

//     }
//     $dynamictable .= "</tr>";
// }
// $dynamictable .= '</table>';


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

                            } else {

                                // Manual Author & Modified Time entry into SQL with row values of file
                                if ($i == 3) {
                                    $tablevalue[$row][$i - 1] = "'" . $author . "'" . ',';
                                } elseif ($i == 4) {
                                    $tablevalue[$row][$i - 1] = "'" . $time . "'" . ',';
                                } elseif ($i == 10) {
                                    $tablevalue[$row][$i - 1] = "'" . $sumtenurefac[$row - 1] . "'" . ',';
                                } elseif ($i == 14) {
                                    $tablevalue[$row][$i - 1] = "'" . $sumresearchfac[$row - 1] . "'" . ',';
                                } elseif ($i == 19) {
                                    $tablevalue[$row][$i - 1] = "'" . $sumclinicfac[$row - 1] . "'" . ',';
                                } elseif ($i == 22) {
                                    $tablevalue[$row][$i - 1] = "'" . $sumotherfac[$row - 1] . "'" . ',';
                                } else {
                                    $tablevalue[$row][$i - 1] = "'" . $csv[$row][$colindex] . "'" . ',';
                                }

                                //Validation check of Undergraduate Male + Female with comparision to composition of UGrad Sudents
                                if ($i == 5 or $i == 6 or $i == 7 or $i == 8 or $i == 9) {
                                    $sumtenurefac[$row - 1] += intval($csv[$row][$colindex]);
                                }
                                if ($i == 11 or $i == 12 or $i == 13) {
                                    $sumresearchfac[$row - 1] += intval($csv[$row][$colindex]);
                                }

                                //Validation check of Graduate Male + Female with comparision to composition of Graduate Sudents
                                if ($i == 15 or $i == 16 or $i == 17 or $i == 18) {
                                    $sumclinicfac[$row - 1] += intval($csv[$row][$colindex]);

                                }
                                if ($i == 20 or $i == 21) {
                                    $sumotherfac[$row - 1] += intval($csv[$row][$colindex]);
                                }

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

                        $sqlupload = "INSERT INTO IR_AC_FacultyPop (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP, TTF_FTE_ALL, TTF_FTE_PROF_TNR, TTF_FTE_ASSOC_PROF_TNR, TTF_FTE_PROF, TTF_FTE_ASSOC_PROF, TTF_FTE_ASSIST_PROF, RSRCH_FTE_ALL, RSRCH_FTE_PROF, RSRCH_FTE_ASSOC_PROF, RSRCH_FTE_ASSIST_PROF, CIF_FTE_ALL, CIF_FTE_CLNCL_PROF, CIF_FTE_CLNCL_ASSOC_PROF, CIF_FTE_CLNCL_ASSIST_PROF, CIF_FTE_INSTR_LCTR, OTHRFAC_ALL, OTHRFAC_PT_ADJUNCT, OTHRFAC_PT_OTHER, STUDENT_FACULTY_RATIO) 
SELECT 'USCAAU' AS OU,'$FUayname' AS AY,'$author' AS AUTHOR,'$time' AS MOD_Time,sum(TTF_FTE_ALL), sum(TTF_FTE_PROF_TNR), sum(TTF_FTE_ASSOC_PROF_TNR), sum(TTF_FTE_PROF), sum(TTF_FTE_ASSOC_PROF), sum(TTF_FTE_ASSIST_PROF), sum(RSRCH_FTE_ALL), sum(RSRCH_FTE_PROF), sum(RSRCH_FTE_ASSOC_PROF), sum(RSRCH_FTE_ASSIST_PROF), sum(CIF_FTE_ALL), sum(CIF_FTE_CLNCL_PROF), sum(CIF_FTE_CLNCL_ASSOC_PROF), sum(CIF_FTE_CLNCL_ASSIST_PROF), sum(CIF_FTE_INSTR_LCTR), sum(OTHRFAC_ALL), sum(OTHRFAC_PT_ADJUNCT), sum(OTHRFAC_PT_OTHER),
, 'NA' FROM IR_AC_FacultyPop where ID_AC_FACULTY_POPULATION in (select max(ID_AC_FACULTY_POPULATION) from IR_AC_FacultyPop where OUTCOMES_AY = '$FUayname' group by OU_ABBREV );";
                        $mysqli->query($sqlupload);


                        $error[0] = "Data Uploaded Successfully.";

                    } else {

                        $error[0] = "Error in Data. Upload Failed.";

                    }

                } else {

                    $error[0] = "Faculty Data Composition does not match. Please check data & reload.";

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

    if ($mysqli->query($sqlupload)) {
        $sqlupload = "SELECT ID_AC_FACULTY_POPULATION from IR_AC_FacultyPop where OUTCOMES_AY = '$FUayname'; ";
        $resultsqlupload = $mysqli->query($sqlupload);

        while ($rowssqlupload = $resultsqlupload->fetch_assoc()) {
            $discardid[$index] = $rowssqlupload['ID_AC_FACULTY_POPULATION'];
            $index++;
        }

        foreach ($discardid as $delete) {
            $sqldel .= "delete from IR_AC_FacultyPop where ID_AC_FACULTY_POPULATION = '$delete'; ";
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
                    <!--                    <input type="button" id="cancelbtn" value="Cancel & Discard" class="btn-primary cancelFUbox pull-left">-->
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
                                    <option value="USC Columbia All Academic Units">USC Columbia All Academic Units</option>
                                    <option value="Other Units">Other Units</option>
                                    <option value="Other Units">Other Units</option>
                                    <option value="Other Units">Other Units</option>
                                    <option value="Other Units">Other Units</option>
                                </select>
                            </div>
                        </form>



                        <div id="display" class="col-xs-12">

                            
                            <h2 class="data-display">Display Data - Table Name: <?php echo $tablename; ?></h2>

                            <p><b class="garnet">Academic Year:</b>2001</p>

                            <p><b class="garnet">College/School:</b>CEC</p>

                            <p><b class="garnet">Outcomes Author:</b>Blake Finn</p>

                            <p><b class="garnet">Last Modified:</b> <?php echo $rowsdatadisplay['TTF_FTE_PROF_TNR']; ?> </p> 


                            <h2 class="data-display">Tenure-track Faculty (FTE Positions)</h2>

                            
                            <p class="indent"><b class="garnet">Professor, with tenure:</b>
                            <?php echo $rowsdatadisplay['TTF_FTE_PROF_TNR']; ?> </p>                             
                            <p class="indent"><b class="garnet">Associate Professor, with tenure:</b> <?php echo $rowsdatadisplay['TTF_FTE_ASSOC_PROF_TNR']; ?> </p> 

                            <p class="indent"><b class="garnet">Professor:</b> <?php echo $rowsdatadisplay['TTF_FTE_PROF']; ?>  </p> 

                            <p class="indent"><b class="garnet">Associate Professor:</b> <?php echo $rowsdatadisplay['TTF_FTE_ASSOC_PROF']; ?> </p> 

                            <p class="indent"><b class="garnet">Assistant Professor:</b> <?php echo $rowsdatadisplay['TTF_FTE_ASSIST_PROF']; ?> </p> 

                            <br />

                            <p class="indent"><b class="garnet">Total - Tenure-track Faculty (FTE positions):</b> <?php echo $rowsdatadisplay['TTF_FTE_ALL']; ?> </p> 


                            <h2 class="data-display">Research Faculty (FTE Positions)</h2>

                            
                            <p class="indent"><b class="garnet">Research Professor:</b> <?php echo $rowsdatadisplay['RSRCH_FTE_PROF']; ?> </p>                             
                            <p class="indent"><b class="garnet">Research Associate Professor:</b> <?php echo $rowsdatadisplay['RSRCH_FTE_ASSOC_PROF']; ?> </p> 

                            <p class="indent"><b class="garnet">Research Assistant Professor:</b> <?php echo $rowsdatadisplay['RSRCH_FTE_ASSIST_PROF']; ?>  </p> 

                            <br />

                            <p class="indent"><b class="garnet">Total - Research Faculty (FTE Positions):</b> <?php echo $rowsdatadisplay['RSRCH_FTE_ALL']; ?> </p> 

                            <h2 class="data-display">Clinical/Instructional Faculty (FTE Positions)</h2>

                            
                            <p class="indent"><b class="garnet">Clinical  Professor:</b> <?php echo $rowsdatadisplay['CIF_FTE_CLNCL_PROF']; ?> </p>                             
                            <p class="indent"><b class="garnet">Clinical  Associate Professor:</b> <?php echo $rowsdatadisplay['CIF_FTE_CLNCL_ASSOC_PROF']; ?> </p> 

                            <p class="indent"><b class="garnet">Clinical  Assistant Professor:</b> <?php echo $rowsdatadisplay['CIF_FTE_CLNCL_ASSIST_PRO']; ?>  </p> 

                            <p class="indent"><b class="garnet">Instructor/Lecturer:</b> <?php echo $rowsdatadisplay['CIF_FTE_INSTR_LCTR']; ?>  </p> 

                            <br />

                            <p class="indent"><b class="garnet">Total - Clinical/Instructional Faculty (FTE positions):</b> <?php echo $rowsdatadisplay['CIF_FTE_ALL']; ?> </p> 

                            <h2 class="data-display">Other Faculty</h2>

                            
                            <p class="indent"><b class="garnet">Adjunct Faculty</b> <?php echo $rowsdatadisplay['OTHRFAC_PT_ADJUNCT']; ?> </p>                             
                            <p class="indent"><b class="garnet">Other Faculty:</b> <?php echo $rowsdatadisplay['OTHRFAC_PT_OTHER']; ?> </p>

                            <br /> 

                            <p class="indent"><b class="garnet">Total - Other Faculty:</b> <?php echo $rowsdatadisplay['OTHRFAC_ALL']; ?>  </p> 

                            <h2 class="data-display">Student Faculty Ratio</h2>

                            
                            <p class="indent"><b class="garnet">Student Faculty Ratio</b> <?php echo $rowsdatadisplay['STUDENT_FACULTY_RATIO']; ?> </p>

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

