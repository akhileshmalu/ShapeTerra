<?php

require_once "Initialize.php";
class FILEUPLOAD extends Initialize
{
    public $errorflag, $message, $author, $time, $contentLinkId, $FUayname, $ouabbrev, $outype, $ouid;

    function __construct()
    {
        //getting the connection object
        parent::__construct();
        $this->errorflag = 0;
        $this->message = null;
        $this->author = $_SESSION['login_userid'];
        $this->time = date('Y-m-d H:i:s');


        $this->contentLinkId = $_GET['linkid'];;
        $this->FUayname = $_SESSION['FUayname'];
        $this->bpid = $_SESSION ['bpid'];
        $this->ouid = $_SESSION['login_ouid'];
        $this->outype = $_SESSION['login_outype'];

        if ($this->ouid == 4) {
            $this->ouabbrev = $_SESSION['bpouabbrev'];
        } else {
            $this->ouabbrev = $_SESSION['login_ouabbrev'];
        }
    }

    public function GetStatus()
    {
        try {
            $sqlfucontent = "SELECT * FROM IR_SU_UploadStatus LEFT JOIN PermittedUsers ON PermittedUsers.ID_STATUS =
  IR_SU_UploadStatus.LAST_MODIFIED_BY WHERE IR_SU_UploadStatus.ID_UPLOADFILE= :content_id;";
            $resultfucontent = $this->connection->prepare($sqlfucontent);
            $resultfucontent->bindParam(':content_id', $this->contentLinkId, 1);
            $resultfucontent->execute();
        } catch (PDOException $e) {
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
            error_log($e->getMessage());
        }
        return $resultfucontent;
    }
    public function GetPrimaryKey($tablename)
    {
        try {
            $sql = "SHOW KEYS FROM $tablename WHERE Key_name = 'PRIMARY';";
            $result = $this->connection->prepare($sql);
            $result->execute();

            $fields = $result->fetch(4);
//            $primary_key = $fields['Column_name'];
        } catch (PDOException $e) {
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
            error_log($e->getMessage());
        }
        return $fields['Column_name'];
    }

    public function Validate()
    {
        try {
            $sqlupload = "UPDATE IR_SU_UploadStatus SET STATUS_UPLOADFILE='Complete',LAST_MODIFIED_BY =:author,
        LAST_MODIFIED_ON =:timeCurrent WHERE IR_SU_UploadStatus.ID_UPLOADFILE = :content_id;";

            if ($this->connection->prepare($sqlupload)->execute(['author' => $this->author, 'timeCurrent' => $this->time,
                'content_id' => $this->contentLinkId])
            ) {
                $this->message = "Data Validated Successfully.";
            } else {
                $this->message = "Error in Data validation.Process Failed.";
            }
        } catch (PDOException $e) {
//        SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
            error_log($e->getMessage());
        }
        return $this->message;
    }

    public function Error($tablename, $primarykey)
    {
        $sqlupload = "Update IR_SU_UploadStatus SET STATUS_UPLOADFILE ='No File Provided',LAST_MODIFIED_BY =:author,
    LAST_MODIFIED_ON =:timeStampmod  WHERE  IR_SU_UploadStatus.ID_UPLOADFILE = :content_id; ";

        $sqlupload .="DELETE FROM $tablename WHERE OUTCOMES_AY =:FUayname AND $primarykey <>0;";

        $result = $this->connection->prepare($sqlupload);
        $result->bindParam(':author',$this->author,2);
        $result->bindParam(':timeStampmod',$this->time,2);
        $result->bindParam(':content_id',$this->contentLinkId,2);
        $result->bindParam(':FUayname',$this->FUayname,2);

        if ($result->execute()) {
            $this->message = "Data Deprecated.Please Reload the File";
        } else {
            $this->message = "Error in Data Deprecation.Process Failed.";
        }
        return $this->message;
    }

    public function DataDisplay($tablename, $primarykey, $option = "")
    {
        // Display Of Values in validation from IR_AC_DiversityStudent Table of Database
        try {
            $sqldatadisplay = "SELECT * FROM $tablename WHERE $option $primarykey IN
  (SELECT MAX($primarykey) FROM $tablename WHERE OUTCOMES_AY = :FUayname GROUP BY OU_ABBREV);";
            $resultdatadisplay = $this->connection->prepare($sqldatadisplay);
            $resultdatadisplay->bindParam(':FUayname', $this->FUayname, 2);
            $resultdatadisplay->execute();
        } catch (PDOException $e) {
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
            error_log($e->getMessage());
        }
        return $resultdatadisplay;
    }
    public function HTMLtable($tablename, $primarykey, $option = "")
    {

        $dynamictable = null;
        $datavalues = array();
        $colCount = 0;
        $i = 0;
        $count = 1;
        $meta = array();

        // Display Of Values in validation from IR_AC_DiversityStudent Table of Database
        try {
            $sqldatadisplay = "SELECT * FROM $tablename WHERE $option $primarykey IN
  (SELECT MAX($primarykey) FROM $tablename WHERE OUTCOMES_AY = :FUayname GROUP BY OU_ABBREV);";
            $resultdatadisplay = $this->connection->prepare($sqldatadisplay);
            $resultdatadisplay->bindParam(':FUayname', $this->FUayname, 2);
            $resultdatadisplay->execute();
//            echo $sqldatadisplay;
        } catch (PDOException $e) {
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
            error_log($e->getMessage());
        }

        $dynamictable = "<table border='1' cellpadding='5' class='table'><tr>";
        $fieldcnt = $resultdatadisplay->columnCount();
        $num_records = $resultdatadisplay->rowCount();

        while ($meta = $resultdatadisplay->getColumnMeta($colCount)) {
            $datavalues[0][$i] = $meta[name];
            $i++;
            $colCount++;
        }

        while ($rowsdatadisplay = $resultdatadisplay->fetch(4)) {
            for ($col = 0; $col < $fieldcnt; $col++) {
                $datavalues[$count][$col] = $rowsdatadisplay[$col];
            }
            $count++;
        }
        for ($j = 1; $j < $fieldcnt; $j++) {
            for ($i = 0; $i <= $num_records; $i++) {
                if ($i == 0) {
                    $dynamictable .= "<td>" . $datavalues[$i][$j] . "</td>";
                } else {
                    $dynamictable .= "<td>" . $datavalues[$i][$j] . "</td>";
                }

            }
            $dynamictable .= "</tr>";
        }

        $dynamictable .= '</table>';

        return $dynamictable;

    }

    public function getDistinctCollegeName()
    {
        $resultfucontent = $this->GetStatus();
        $rowsfucontent = $resultfucontent->fetch(2);
        $tablename = $rowsfucontent['NAME_UPLOADFILE'];

        try {
            $sql = "SELECT distinct(A.OU_ABBREV),B.OU_NAME FROM $tablename A INNER JOIN Hierarchy B On A.OU_ABBREV = B
            .OU_ABBREV
WHERE OUTCOMES_AY = :FUayname;";
            $result = $this->connection->prepare($sql);
            $result->bindParam(':FUayname', $this->FUayname, 2);
            $result->execute();
        } catch (PDOException $e) {

            error_log($e->getMessage());
        }

        echo "
<h2 class='data-display'>Select Academic Unit to View</h2>
                            <div class='col-xs-6'>
                                <select  name='ou' class='form-control' onchange='showEnrollmentData()' id='ou'>";
        echo "<option value=''>--Select Academic Unit --</option>";
        while($rows = $result->fetch(2)) {
            echo "<option value='".$rows['OU_ABBREV']."'>".$rows['OU_NAME']."</option>";
        }

        echo '                  </select>
                            </div>';

    }

    public function uploadDiversityPersonnel()
    {

        //local variables

        $csv = array();
        $sumfac = array();
        $tablefields = array();
        $tablevalue = array();
        $message = array();

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

                                    $tablefields[$i] = $csv[$row][$colindex] . ',';

                                } else {

                                    // Manual Author & Modified Time entry into SQL with row values of file
                                    if ($i == 3) {
                                        $tablevalue[$row][$i - 1] = $this->author ;
                                    } elseif ($i == 4) {
                                        $tablevalue[$row][$i - 1] = $this->time ;
                                    } else {
                                        $tablevalue[$row][$i - 1] =  $csv[$row][$colindex];
                                    }

                                    //Validation check of Faculty Male + Female
                                    if ($i == 5 or $i == 6) {
                                        $sumfac[$row - 1] += intval($csv[$row][$colindex]);
                                    }
                                    // Vs Sum of all category
                                    if ($i == 7 or $i == 8 or $i == 9 or $i == 10 or $i == 11 or $i == 12 or $i == 13 or
                                        $i == 14
                                        //or $i == 15
                                    ) {
                                        $sumfac[$row - 1] -= intval($csv[$row][$colindex]);
                                    }

//                                //Validation check of STAFF Male + Female {Removed From DB}
//
//                                if ($i == 16 or $i == 17) {
//                                    $sumstaff[$row - 1] += intval($csv[$row][$colindex]);
//
//                                }
//                                if ($i == 18 or $i == 19 or $i == 20 or $i == 21 or $i == 22 or $i == 23 or $i == 24
//                                    or $i == 25
//                                ) {
//                                    $sumstaff[$row - 1] -= intval($csv[$row][$colindex]);
//                                }

                                }

                            } else {
                                // terminal values should not have (,) in SQL Query.
                                if ($row == 0) {
                                    $tablefields[$i] = $csv[$row][$colindex];
                                } else {
                                    $tablevalue[$row][$i - 1] = $csv[$row][$colindex];

                                    //Terminal Values of record reside here
                                    if ($i == 15) {
                                        $sumfac[$row - 1] -= intval($csv[$row][$colindex]);
                                    }
                                }
                            }
                        }
                        // inc the row
                        $row++;
                    }

                    for ($checkfac = 0; $checkfac < count($sumfac); $checkfac++) {
                        if ($sumfac[$checkfac] != 0) {
                            $message[$checkfac + 1] = "Mismatch in Faculty Composition.Record No: " . ($checkfac + 1) .
                                "<br>".$sumfac[$checkfac];
                            $this->errorflag = 1;
                        }
                    }

//                for ($checkstaff = 0; $checkstaff < count($sumstaff); $checkstaff++) {
//                    if ($sumstaff[$checkstaff] != 0) {
//                        $message[$checkfac + 1] = "Mismatch in Staff Composition.Record No: " . ($checkstaff + 1) . "<br>";
//                        $errorflag = 1;
//                        $checkfac++;
//                    }
//                }


                    if ($this->errorflag == 0) {

                        // Create SQL for All ORG Units
                        $sqlupload = "INSERT INTO IR_AC_DiversityPersonnel ( OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR,
 MOD_TIMESTAMP, FAC_FEMALE, FAC_MALE, FAC_AMERIND_ALASKNAT, FAC_ASIAN, FAC_BLACK, FAC_HISPANIC, FAC_HI_PAC_ISL, 
 FAC_NONRESIDENT_ALIEN, FAC_TWO_OR_MORE, FAC_UNKNOWN_RACE_ETHNCTY, FAC_WHITE) VALUES (";

                        for ($i = 0; $i < count($tablefields); $i++) {

                            $sqlupload .= ($i == count($tablefields) - 1) ? '?' : '?,';
                        }
                        $sqlupload .= ");";

                        ;
                        // Prepare Sql Query
                        $result = $this->connection->prepare($sqlupload);

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

                        $result = $this->connection->prepare($sqlupload);
                        $result->bindParam(':author', $this->author, 2);
                        $result->bindParam(':timeStampmod', $this->time, 2);
                        $result->bindParam(':content_id', $this->contentLinkId, 2);


//                    for ($j = 1; $j < $row; $j++) {
//                        $sqlupload .= "INSERT INTO $tablename ( ";
//                        foreach ($tablefileds as $fields) {
//                            $sqlupload .= $fields;
//                        }
//                        $sqlupload .= " ) Values (";
//                        foreach ($tablevalue[$j] as $fieldvalue) {
//                            $sqlupload .= $fieldvalue;
//                        }
//                        $sqlupload .= ");";
//                    }


                        if ($result->execute()) {

                            //USCALLAU USC ALL Academic Units Aggregator record creation . Also includes the idea to let
                            // user update more units in future. Below query group all discrete units and resolve
                            // collusion basis latest (max) ID value and then sum the records and constitute USCAAU

                            try {
                                $sqlupload = "INSERT INTO IR_AC_DiversityPersonnel (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR,
                        MOD_TIMESTAMP, FAC_FEMALE, FAC_MALE, FAC_AMERIND_ALASKNAT, FAC_ASIAN, FAC_BLACK,
                        FAC_HISPANIC, FAC_HI_PAC_ISL, FAC_NONRESIDENT_ALIEN, FAC_TWO_OR_MORE,
                        FAC_UNKNOWN_RACE_ETHNCTY, FAC_WHITE) SELECT 'USCAAU' AS OU,
                        :FUayname AS AY,:authorName AS AUTHOR,:timeCurrent AS MOD_Time, SUM(FAC_FEMALE), SUM(FAC_MALE),
                        SUM(FAC_AMERIND_ALASKNAT), SUM(FAC_ASIAN), SUM(FAC_BLACK), SUM(FAC_HISPANIC),
                        SUM(FAC_HI_PAC_ISL), SUM(FAC_NONRESIDENT_ALIEN), SUM(FAC_TWO_OR_MORE),
                        SUM(FAC_UNKNOWN_RACE_ETHNCTY), SUM(FAC_WHITE) FROM IR_AC_DiversityPersonnel WHERE
                        ID_IR_AC_DIVERSITY_PERSONNEL IN (SELECT MAX(ID_IR_AC_DIVERSITY_PERSONNEL) FROM
                        IR_AC_DiversityPersonnel WHERE OUTCOMES_AY = :FUayname GROUP BY OU_ABBREV);";

                                $resultupload = $this->connection->prepare($sqlupload);
                                $resultupload->bindParam(':FUayname', $this->FUayname, 2);
                                $resultupload->bindParam(':authorName', $this->author, 2);
                                $resultupload->bindParam(':timeCurrent', $this->time, 2);

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

        return $message;
    }

    public function uploadGenericFile()
    {
        $resultfucontent = $this->GetStatus();
        $rowsfucontent = $resultfucontent->fetch(2);
        $tablename = $rowsfucontent['NAME_UPLOADFILE'];


        // local variables
        $csv = array();
        $tablefields = array();
        $tablevalue = array();
        $message = array();

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
                                        $tablevalue[$row][$i - 1] = $this->author;
                                    } elseif ($i == 4) {
                                        $tablevalue[$row][$i - 1] = $this->time;
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


                    // Validation Space ; If there is any error in validation flag errorflag to 1 to not proceed.

                    if ($this->errorflag == 0) {

                        $sqlupload = "INSERT INTO $tablename ( ";
                        foreach ($tablefields as $fields) {
                            $sqlupload .= $fields;
                        }
                        $sqlupload .= " ) Values (";


                        for ($i = 0; $i < count($tablefields); $i++) {
                            $sqlupload .= ($i == count($tablefields) - 1) ? '?' : '?,';
                        }
                        $sqlupload .= ");";

                        // Prepare Sql Query
                        $result = $this->connection->prepare($sqlupload);

                        // binding Variables
                        for ($j = 1; $j < $row; $j++) {
                            for ($i = 0; $i < count($tablevalue[$j]); $i++) {
                                $result->bindParam($i + 1, $tablevalue[$j][$i], 2);
                            }
                            $result->execute();
                        }


                        $sqlupload = "UPDATE IR_SU_UploadStatus SET STATUS_UPLOADFILE ='Pending Validation',
 LAST_MODIFIED_BY = :author, LAST_MODIFIED_ON = :timeStampmod WHERE IR_SU_UploadStatus.ID_UPLOADFILE = :content_id; ";

                        $resultupload = $this->connection->prepare($sqlupload);
                        $resultupload->bindParam(':author', $this->author, 2);
                        $resultupload->bindParam(':timeStampmod', $this->time, 2);
                        $resultupload->bindParam(':content_id', $this->contentLinkId, 1);

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

    return $message;
    }

    public function uploadDiversityStudent()
    {
        //local variables

        $csv = array();
        $sumUgrad = array();
        $sumGrad = array();
        $tablefields = array();
        $tablevalue = array();
        $message = array();

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
                        // $col_count = count($data);

                        // get the values from the csv
                        for ($i = 1; $i <= count($data); $i++) {

                            $colindex = 'col' . $i;
                            $csv[$row][$colindex] = $data[$i - 1];

                            //Prepare SQL query for Table variables from File with ('',) to support SQL syntax
                            // Insert into <table_name> (Value1 , Value2 ) Values ('a','b'); all fields & values should
                            // have ('a',) where required.
                            if ($i != count($data)) {

                                if ($row == 0) {
                                    // Fields of Table
                                    $tablefields[$i] = $csv[$row][$colindex] . ',';
                                } else {

                                    // Manual Author & Modified Time entry into SQL with row values of file
                                    if ($i == 3) {
                                        $tablevalue[$row][$i - 1] =  $this->author;
                                    } else {
                                        if ($i == 4) {
                                            $tablevalue[$row][$i - 1] = $this->time;
                                        } else {
                                            $tablevalue[$row][$i - 1] = $csv[$row][$colindex];
                                        }
                                    }

                                    //Validation check of Undergraduate Male + Female with comparision to composition of
                                    // UGrad Sudents
                                    if($i ==5 or $i == 6) {
                                        $sumUgrad[$row-1] += intval($csv[$row][$colindex]);
                                    }
                                    if($i == 7 or $i ==8 or $i == 9 or $i == 10 or $i == 11 or $i == 12 or $i == 13 or $i
                                        == 14 or $i == 15) {
                                        $sumUgrad[$row-1] -= intval($csv[$row][$colindex]);
                                    }

                                    //Validation check of Graduate Male + Female with comparision to composition of
                                    // Graduate Sudents
                                    if($i ==16 or $i == 17) {
                                        $sumGrad[$row-1] += intval($csv[$row][$colindex]);

                                    }
                                    if($i == 18 or $i ==19 or $i == 20 or $i == 21 or $i == 22 or $i == 23 or $i == 24 or
                                        $i == 25) {
                                        $sumGrad[$row-1] -= intval($csv[$row][$colindex]);
                                    }
                                }

                            } else {
                                // terminal values should not have (,) in SQL Query.
                                if ($row == 0) {
                                    $tablefields[$i] = $csv[$row][$colindex];
                                } else {
                                    $tablevalue[$row][$i - 1] = $csv[$row][$colindex];

                                    //Terminal Values of record reside here
                                    if($i == 26) {
                                        $sumGrad[$row-1] -= intval($csv[$row][$colindex]);
                                    }
                                }
                            }
                        }
                        // inc the row
                        $row++;
                    }

                    for ($checkug = 0;$checkug < count($sumUgrad);$checkug++) {
                        if($sumUgrad[$checkug] != 0) {
                            $message[$checkug+1] = "Mismatch in UnderGraduates Composition.Record No: ".($checkug+1)."<br>";
                            $this->errorflag = 1;
                        }
                    }
                    for ($checkgrad = 0; $checkgrad < count($sumGrad);$checkgrad++) {
                        if($sumGrad[$checkgrad] != 0) {
                            $message[$checkug+1] = "Mismatch in Graduates Composition.Record No: ".($checkgrad +1)."<br>";
                            $this->errorflag = 1;
                            $checkug++;
                        }
                    }

                    if ($this->errorflag == 0) {

                        // Create SQL for All ORG Units
                        $sqlupload = "INSERT INTO IR_AC_DiversityStudent (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR,
 MOD_TIMESTAMP, UGRAD_FEMALE, UGRAD_MALE, UGRAD_AMERIND_ALASKNAT, UGRAD_ASIAN, UGRAD_BLACK, UGRAD_HISPANIC, 
 UGRAD_HI_PAC_ISL, UGRAD_NONRESIDENT_ALIEN, UGRAD_TWO_OR_MORE, UGRAD_UNKNOWN_RACE_ETHNCTY, UGRAD_WHITE, GRAD_FEMALE,
  GRAD_MALE, GRAD_AMERIND_ALASKNAT, GRAD_ASIAN, GRAD_BLACK, GRAD_HISPANIC, GRAD_HI_PAC_ISL, GRAD_NONRESIDENT_ALIEN, 
  GRAD_TWO_OR_MORE, GRAD_UNKNOWN_RACE_ETHNCTY, GRAD_WHITE) VALUES (";

                        for ($i = 0; $i < count($tablefields); $i++) {

                            $sqlupload .= ($i == count($tablefields) - 1) ? '?' : '?,';
                        }
                        $sqlupload .= ");";

                        ;
                        // Prepare Sql Query
                        $result = $this->connection->prepare($sqlupload);

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

                        $result = $this->connection->prepare($sqlupload);
                        $result->bindParam(':author', $this->author, 2);
                        $result->bindParam(':timeStampmod', $this->time, 2);
                        $result->bindParam(':content_id', $this->contentLinkId, 2);

                        if ($result->execute()) {

                            //USCALLAU USC ALL Academic Units Aggregator record creation . Also includes the idea to let
                            // user update more units in future. Below query group all discrete units and resolve
                            // collusion basis latest (max) ID value and then sum the records and constitute USCAAU

                            try {

                                $sqlupload = "INSERT INTO IR_AC_DiversityStudent (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, 
MOD_TIMESTAMP, UGRAD_FEMALE, UGRAD_MALE, UGRAD_AMERIND_ALASKNAT, UGRAD_ASIAN, UGRAD_BLACK, UGRAD_HISPANIC, 
UGRAD_HI_PAC_ISL, UGRAD_NONRESIDENT_ALIEN, UGRAD_TWO_OR_MORE, UGRAD_UNKNOWN_RACE_ETHNCTY, UGRAD_WHITE, GRAD_FEMALE, 
GRAD_MALE, GRAD_AMERIND_ALASKNAT, GRAD_ASIAN, GRAD_BLACK, GRAD_HISPANIC, GRAD_HI_PAC_ISL, GRAD_NONRESIDENT_ALIEN, 
GRAD_TWO_OR_MORE, GRAD_UNKNOWN_RACE_ETHNCTY, GRAD_WHITE) SELECT 'USCAAU' AS OU,:FUayname AS AY,:authorName AS AUTHOR,
:timeCurrent AS MOD_Time,sum(UGRAD_FEMALE), sum(UGRAD_MALE), sum(UGRAD_AMERIND_ALASKNAT), sum(UGRAD_ASIAN), 
sum(UGRAD_BLACK), sum(UGRAD_HISPANIC), sum(UGRAD_HI_PAC_ISL), sum(UGRAD_NONRESIDENT_ALIEN), sum(UGRAD_TWO_OR_MORE), 
 sum(UGRAD_UNKNOWN_RACE_ETHNCTY), sum(UGRAD_WHITE), sum(GRAD_FEMALE), sum(GRAD_MALE), sum(GRAD_AMERIND_ALASKNAT), 
 sum(GRAD_ASIAN), sum(GRAD_BLACK), sum(GRAD_HISPANIC), sum(GRAD_HI_PAC_ISL), sum(GRAD_NONRESIDENT_ALIEN), 
 sum(GRAD_TWO_OR_MORE), sum(GRAD_UNKNOWN_RACE_ETHNCTY), sum(GRAD_WHITE) FROM IR_AC_DiversityStudent
  where ID_IR_AC_DIVERSITY_STUDENTS in (select max(ID_IR_AC_DIVERSITY_STUDENTS) from IR_AC_DiversityStudent 
  where OUTCOMES_AY = :FUayname group by OU_ABBREV );";

                                $resultupload = $this->connection->prepare($sqlupload);
                                $resultupload->bindParam(':FUayname', $this->FUayname, 2);
                                $resultupload->bindParam(':authorName', $this->author, 2);
                                $resultupload->bindParam(':timeCurrent', $this->time, 2);
                                $resultupload->bindParam(':FUayname', $this->FUayname, 2);

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

                        $message[0] = "Student Data Composition does not match. Please check data & reload.";

                    }

                    fclose($handle);
                }
            } else{
                $message[0] = "Please select only csv format File";
            }
        } else {
            $message[0] = "Error in Uploading File. ";
        }
        return $message;

    }

    public function uploadFacultyPopulation()
    {
        //local variables

        $csv = array();
        $tablefields = array();
        $tablevalue = array();
        $message = array();
        $sumtenurefac = array();
        $sumresearchfac = array();
        $sumclinicfac = array();
        $sumotherfac = array();

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
                                        $tablevalue[$row][$i - 1] = $this->author;
                                    } elseif ($i == 4) {
                                        $tablevalue[$row][$i - 1] = $this->time;
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


                    if ($this->errorflag == 0) {

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
                        $result = $this->connection->prepare($sqlupload);

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

                        $result = $this->connection->prepare($sqlupload);
                        $result->bindParam(':author', $this->author, 2);
                        $result->bindParam(':timeStampmod', $this->time, 2);
                        $result->bindParam(':content_id', $this->contentLinkId, 2);


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

                                $resultupload = $this->connection->prepare($sqlupload);
                                $resultupload->bindParam(':FUayname', $this->FUayname, 2);
                                $resultupload->bindParam(':authorName', $this->author, 2);
                                $resultupload->bindParam(':timeCurrent', $this->time, 2);

                                if ($resultupload->execute()) {
                                    $message[0] = "Data Uploaded Successfully.";
                                } else {
                                    $message[0] = "Error in capturing USC All Academic UNIT data.";
                                }


                            } catch (PDOException $e) {
                                //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
                                error_log($e->getMessage());
                            }

                        } else {
                            $message[0] = "Error in Data Upload. Please retry.";
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

        return $message;
    }

    public function uploadEnrollment()
    {

        // local variables
        $csv = array();
        $tablefields = array();
        $tablevalue = array();
        $message = array();

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
                                        $tablevalue[$row][$i - 1] = $this->author;
                                    } elseif ($i == 4) {
                                        $tablevalue[$row][$i - 1] = $this->time;
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


                    // Validation Space ; If there is any error in validation flag errorflag to 1 to not proceed.

                    if ($this->errorflag == 0) {

                        $sqlupload = "INSERT INTO `IR_AC_Enrollments` (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR,
 MOD_TIMESTAMP, ENROLL_HC_FRESH, ENROLL_HC_SOPH, ENROLL_HC_JUNR, ENROLL_HC_SENR, ENROLL_HC_MASTERS, ENROLL_HC_DOCTORAL,
  ENROLL_HC_MEDICINE, ENROLL_HC_LAW, ENROLL_HC_PHARMD, ENROLL_HC_GRAD_CERT) Values ( ";

                        for ($i = 0; $i < count($tablefields); $i++) {
                            $sqlupload .= ($i == count($tablefields) - 1) ? '?' : '?,';
                        }
                        $sqlupload .= ");";

                        // Prepare Sql Query
                        $result = $this->connection->prepare($sqlupload);

                        // binding Variables
                        for ($j = 1; $j < $row; $j++) {
                            for ($i = 0; $i < count($tablevalue[$j]); $i++) {
                                $result->bindParam($i + 1, $tablevalue[$j][$i], 2);
                            }
                            $result->execute();
                        }


                        $sqlupload = "UPDATE IR_SU_UploadStatus SET STATUS_UPLOADFILE ='Pending Validation',
 LAST_MODIFIED_BY = :author, LAST_MODIFIED_ON = :timeStampmod WHERE IR_SU_UploadStatus.ID_UPLOADFILE = :content_id; ";

                        $resultupload = $this->connection->prepare($sqlupload);
                        $resultupload->bindParam(':author', $this->author, 2);
                        $resultupload->bindParam(':timeStampmod', $this->time, 2);
                        $resultupload->bindParam(':content_id', $this->contentLinkId, 1);

                        if ($resultupload->execute()) {

                            try {
                                $sqlupload = "INSERT INTO IR_AC_Enrollments (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR,
 MOD_TIMESTAMP, ENROLL_HC_FRESH, ENROLL_HC_SOPH, ENROLL_HC_JUNR, ENROLL_HC_SENR, ENROLL_HC_MASTERS, ENROLL_HC_DOCTORAL,
  ENROLL_HC_MEDICINE, ENROLL_HC_LAW, ENROLL_HC_PHARMD, ENROLL_HC_GRAD_CERT) 
  SELECT 'USCAAU' AS OU,:FUayname AS AY, :authorName AS AUTHOR,:timeCurrent AS MOD_Time,  sum(ENROLL_HC_FRESH), 
  sum(ENROLL_HC_SOPH), sum(ENROLL_HC_JUNR), sum(ENROLL_HC_SENR), sum(ENROLL_HC_MASTERS), 
  sum(ENROLL_HC_DOCTORAL), sum(ENROLL_HC_MEDICINE),sum(ENROLL_HC_LAW), sum(ENROLL_HC_PHARMD),sum(ENROLL_HC_GRAD_CERT) 
  FROM IR_AC_Enrollments WHERE ID_AC_ENROLLMENTS IN (SELECT max(ID_AC_ENROLLMENTS) FROM IR_AC_Enrollments 
WHERE OUTCOMES_AY = :FUayname GROUP BY OU_ABBREV );";

                                $resultupload = $this->connection->prepare($sqlupload);
                                $resultupload->bindParam(':FUayname', $this->FUayname, 2);
                                $resultupload->bindParam(':authorName', $this->author, 2);
                                $resultupload->bindParam(':timeCurrent', $this->time, 2);

                                if ($resultupload->execute()) {
                                    $message[0] = "Data Uploaded Successfully.";
                                } else {
                                    $message[0] = "Data Upload Successfully. Error in capturing USC All Academic UNIT 
                                    data.";
                                }


                            } catch (PDOException $e) {
                                //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
                                error_log($e->getMessage());
                            }


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

        return $message;
    }

}