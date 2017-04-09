<?php
include_once "Initialize.php";
Class DATADICTIONARY extends Initialize
{
//    protected $connection;
    public $elemId, $ouid, $date, $time, $author, $message;

    function __construct()
    {
        //getting the connection object
        parent::__construct ();
//        $this->connection = new PDO(sprintf('mysql:host=%s;dbname=%s', HOSTNAME, DB), USERNAME, PASSCODE);
//        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->elemId = $_GET['elem_id'];
        $this->ouid = $_SESSION['login_ouid'];
        $this->date = date("Y-m-d");
        $this->time = date('Y-m-d H:i:s');
        $this->author = $_SESSION['login_userid'];

    }

    public function ElementExValue()
    {
        try
        {
            $sqldataelem = "SELECT * FROM `DataDictionary` WHERE ID_DATA_ELEMENT= :elemid ;";
            $resultdataelem = $this->connection->prepare($sqldataelem);
            $resultdataelem->bindParam(':elemid', $this->elemId,1);
            $resultdataelem->execute();
        }
        catch (PDOException $e)
        {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultdataelem;
    }

    public function GetDataClassification()
    {
        try
        {
            $sqldataclass = "SELECT * FROM `DataClassification` ;";
            $resultdataclass = $this->connection->prepare($sqldataclass);
            $resultdataclass->execute();
        }
        catch (PDOException $e)
        {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultdataclass;
    }

    public function GetTopicAreas()
    {
        try
        {
            $sqltopicarea = "SELECT * FROM `TopicAreas` where TOPIC_FOR_DICTIONARY = 'Y';";
            $resulttopicarea = $this->connection->prepare($sqltopicarea);
            $resulttopicarea->execute();
        }
        catch (PDOException $e)
        {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resulttopicarea;
    }

    public function Approve()
    {
        try
        {
            $sqladdelem = "UPDATE `DataDictionary` SET STATUS = 'Approved' WHERE ID_DATA_ELEMENT = :elemid ;";
            $resultaddelem = $this->connection->prepare($sqladdelem);
            $resultaddelem->bindParam(':elemid', $this->elemId, 1);

            if ($resultaddelem->execute()) {
                $this->message = "Data Element has been approved & included in Data Dictionary.";
            } else {
                $this->message = "Data Element could not be approved.";
            }
        }
        catch (PDOException $e)
        {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $this->message;
    }

    public function Reject()
    {
        try
        {
            $this->elemId = $_GET['elem_id'];
            $funcname = Initialize::mynl2br($_POST['functionalname']."_trashed");
            $techname = Initialize::mynl2br($_POST['technicalname']."_trashed");
            $sqladdelem = "UPDATE `DataDictionary` SET STATUS = 'Archived', DATA_ELMNT_FUNC_NAME = :funcname, DATA_ELEMENT_TECH_NAME = :techname WHERE ID_DATA_ELEMENT = :elemid ;";
            $resultaddelem = $this->connection->prepare($sqladdelem);
            $resultaddelem->bindParam(':funcname', $funcname, 2);
            $resultaddelem->bindParam(':techname', $techname, 2);
            $resultaddelem->bindParam(':elemid', $this->elemId, 2);

// echo $resultaddelem.$this->elemid.$funcname.$techname;

            if ($resultaddelem->execute()) {
                $this->message = "Data Element has been Archived & excluded from Data Dictionary.";
            } else {
                $this->message = "Data Element could not be Archived.";
            }
        }
        catch (PDOException $e)
        {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $this->message;
    }

    public function SaveWithReview($status = "Proposed")
    {
        $bptopicstring = null;
        $this->time = date('Y-m-d H:i:s');
        try
        {
            $funcname = Initialize::mynl2br($_POST['functionalname']);
            $techname = Initialize::mynl2br($_POST['technicalname']);
            $syslabel = Initialize::mynl2br($_POST['syslabel']);
            $printlabel = $_POST['printlabel'];
            $dataclass = $_POST['dataclass'];
            $basicmean = Initialize::mynl2br($_POST['basicmean']);
            $userinst = Initialize::mynl2br($_POST['userinstr']);
            $timebasis = $_POST['timebasis'];
            $bptopic = $_POST['bptopic'];
            foreach ($bptopic as $item){
                $bptopicstring .=$item.',';
            }
            $usage = Initialize::mynl2br($_POST['usage']);
            $datasource = Initialize::mynl2br($_POST['datasource']);
            $resparty = $_POST['resparty'];
            $contact = $_POST['contactperson'];
            $datatype = $_POST['datatype'];
            $datatrans = Initialize::mynl2br($_POST['datatrans']);
            $valuemand = Initialize::mynl2br($_POST['valuemand']);
            $permitvalue = Initialize::mynl2br($_POST['permitvalue']);
            $constraint = Initialize::mynl2br($_POST['constraint']);
            $notes = Initialize::mynl2br($_POST['notes']);
            $defauthorfname = $_POST['defauthorfname'];
            $defauthorlname = $_POST['defauthorlname'];


            $sqladdelem = "INSERT INTO `DataDictionary` (DATA_ELMNT_FUNC_NAME, DATA_ELEMENT_TECH_NAME, LABEL_SYSTEM,
 LABEL_PRINT, STATUS, BASIC_MEANING,USER_INSTRCTN, TIME_BASIS_OUTCOME, INTERP_USAGE, DATA_CLASSIFICATION, DATA_SOURCE, 
 DATA_TYPE, DATA_TRANSFORM, BP_TOPIC, RESPONSIBLE_PARTY, CONTACT_PERSON, VALUES_MANDATORY, VALUES_PERMITTED, 
 VALUES_CONSTRAINTS, NOTES_DATA_ELEMENT, AUTHOR_FNAME,AUTHOR_LNAME, MOD_BY, MOD_TIMESTAMP) VALUES (:funcname,
 :techname, :syslabel,:printlabel, :statusElem, :basicmean,:userinst,:timebasis, :usageElem, :dataclass, :datasource,
 :datatype, :datatrans, :bptopicstring, :resparty,:contact, :valuemand, :permitvalue, :constraintVal,
 :notes, :defauthorfname, :defauthorlname, :author, :timeStampmod);";

            $resultaddelem = $this->connection->prepare($sqladdelem);

            $resultaddelem->bindParam(':funcname', $funcname, 2);
            $resultaddelem->bindParam(':techname', $techname, 2);
            $resultaddelem->bindParam(':syslabel', $syslabel, 2);
            $resultaddelem->bindParam(':printlabel', $printlabel, 2);
            $resultaddelem->bindParam(':statusElem', $status, 2);
            $resultaddelem->bindParam(':basicmean', $basicmean, 2);
            $resultaddelem->bindParam(':userinst', $userinst, 2);
            $resultaddelem->bindParam(':timebasis', $timebasis, 2);
            $resultaddelem->bindParam(':usageElem', $usage, 2);
            $resultaddelem->bindParam(':dataclass', $dataclass, 2);
            $resultaddelem->bindParam(':datasource', $datasource, 2);
            $resultaddelem->bindParam(':datatype', $datatype, 2);
            $resultaddelem->bindParam(':datatrans', $datatrans, 2);
            $resultaddelem->bindParam(':bptopicstring', $bptopicstring, 2);
            $resultaddelem->bindParam(':resparty', $resparty, 2);
            $resultaddelem->bindParam(':contact', $contact, 2);
            $resultaddelem->bindParam(':valuemand', $valuemand, 2);
            $resultaddelem->bindParam(':permitvalue', $permitvalue, 2);
            $resultaddelem->bindParam(':constraintVal', $constraint, 2);
            $resultaddelem->bindParam(':notes', $notes, 2);
            $resultaddelem->bindParam(':defauthorfname', $defauthorfname, 2);
            $resultaddelem->bindParam(':defauthorlname', $defauthorlname, 2);
            $resultaddelem->bindParam(':author', $this->author, 2);
            $resultaddelem->bindParam(':timeStampmod', $this->time, 2);


            if ($resultaddelem->execute()) {
                if($status == "Proposed") {
                    $this->message = "Your Data Element has been submitted for review.This will be accepted in data 
 dictionary post approval.";
                } else {
                    $this->message = "Your Data Element has been added in Data Dictionary.";
                }

            } else {
                $this->message = "Data Element could not be submitted.";
            }
        }
        catch (PDOException $e)
        {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $this->message;
    }

    public function Update()
    {
        $bptopicstring = null;
        $this->time = date('Y-m-d H:i:s');
        try
        {
            $funcname = Initialize::mynl2br($_POST['functionalname']);
            $techname = Initialize::mynl2br($_POST['technicalname']);
            $syslabel = Initialize::mynl2br($_POST['syslabel']);
            $printlabel = $_POST['printlabel'];
            $dataclass = $_POST['dataclass'];
            $basicmean = Initialize::mynl2br($_POST['basicmean']);
            $userinst = Initialize::mynl2br($_POST['userinstr']);
            $timebasis = $_POST['timebasis'];
            $bptopic = $_POST['bptopic'];
            foreach ($bptopic as $item){
                $bptopicstring .=$item.',';
            }
            $usage = Initialize::mynl2br($_POST['usage']);
            $datasource = Initialize::mynl2br($_POST['datasource']);
            $resparty = $_POST['resparty'];
            $contact = $_POST['contactperson'];
            $datatype = $_POST['datatype'];
            $datatrans = Initialize::mynl2br($_POST['datatrans']);
            $valuemand = Initialize::mynl2br($_POST['valuemand']);
            $permitvalue = Initialize::mynl2br($_POST['permitvalue']);
            $constraint = Initialize::mynl2br($_POST['constraint']);
            $notes = Initialize::mynl2br($_POST['notes']);
            $defauthorfname = $_POST['defauthorfname'];
            $defauthorlname = $_POST['defauthorlname'];

            $sqladdelem = "UPDATE `DataDictionary`  SET DATA_ELMNT_FUNC_NAME = :funcname, DATA_ELEMENT_TECH_NAME= 
            :techname, LABEL_SYSTEM = :syslabel, LABEL_PRINT = :printlabel, BASIC_MEANING = :basicmean,
 USER_INSTRCTN = :userinst, TIME_BASIS_OUTCOME = :timebasis, INTERP_USAGE = :usageElem, DATA_CLASSIFICATION = 
 :dataclass, DATA_SOURCE = :datasource, DATA_TYPE = :datatype, DATA_TRANSFORM = :datatrans, BP_TOPIC 
 = :bptopicstring, RESPONSIBLE_PARTY = :resparty, CONTACT_PERSON = :contact, VALUES_MANDATORY = :valuemand, 
 VALUES_PERMITTED = :permitvalue, VALUES_CONSTRAINTS = :constraintVal, NOTES_DATA_ELEMENT = :notes, AUTHOR_FNAME = 
 :defauthorfname, AUTHOR_LNAME = :defauthorlname, MOD_BY = :author, MOD_TIMESTAMP = :timeStampmod 
 WHERE ID_DATA_ELEMENT = :elemid";

            $resultaddelem = $this->connection->prepare($sqladdelem);

            $resultaddelem->bindParam(':funcname', $funcname, 2);
            $resultaddelem->bindParam(':techname', $techname, 2);
            $resultaddelem->bindParam(':syslabel', $syslabel, 2);
            $resultaddelem->bindParam(':printlabel', $printlabel, 2);
            $resultaddelem->bindParam(':basicmean', $basicmean, 2);
            $resultaddelem->bindParam(':userinst', $userinst, 2);
            $resultaddelem->bindParam(':timebasis', $timebasis, 2);
            $resultaddelem->bindParam(':usageElem', $usage, 2);
            $resultaddelem->bindParam(':dataclass', $dataclass, 2);
            $resultaddelem->bindParam(':datasource', $datasource, 2);
            $resultaddelem->bindParam(':datatype', $datatype, 2);
            $resultaddelem->bindParam(':datatrans', $datatrans, 2);
            $resultaddelem->bindParam(':bptopicstring', $bptopicstring, 2);
            $resultaddelem->bindParam(':resparty', $resparty, 2);
            $resultaddelem->bindParam(':contact', $contact, 2);
            $resultaddelem->bindParam(':valuemand', $valuemand, 2);
            $resultaddelem->bindParam(':permitvalue', $permitvalue, 2);
            $resultaddelem->bindParam(':constraintVal', $constraint, 2);
            $resultaddelem->bindParam(':notes', $notes, 2);
            $resultaddelem->bindParam(':defauthorfname', $defauthorfname, 2);
            $resultaddelem->bindParam(':defauthorlname', $defauthorlname, 2);
            $resultaddelem->bindParam(':author', $this->author, 2);
            $resultaddelem->bindParam(':timeStampmod', $this->time, 2);
            $resultaddelem->bindParam(':elemid', $this->elemId, 1);


            if ($resultaddelem->execute()) {
                $this->message = "Your Data Element has been updated in Data Dictionary.";
            } else {
                $this->message = "Data Element could not be updated.";
            }
        }
        catch (PDOException $e)
        {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $this->message;
    }

}