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
            $sql = "SELECT * FROM $tablename ;";
            $result = $this->connection->prepare($sql);
            $result->execute();

            $fields = $result->fetch(4);
            $primary_key = $fields->name;
        } catch (PDOException $e) {
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
            error_log($e->getMessage());
        }
        return $primary_key;
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

}