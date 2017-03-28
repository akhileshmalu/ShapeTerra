<?php
require_once("Initialize.php");

Class BPCONTENTS extends Initialize
{

//      protected $connection;
    public $errorflag, $message, $author, $time, $contentLinkId, $bpayname, $ouabbrev, $ouid, $bpid;

    function __construct()
    {
        //getting the connection object
        parent::__construct();
//          $this->connection = new PDO(sprintf('mysql:host=%s;dbname=%s', HOSTNAME, DB), USERNAME, PASSCODE);
//          $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->errorflag = 0;
        $this->message = null;
        $this->author = $_SESSION['login_userid'];
        $this->time = date('Y-m-d H:i:s');
        $this->contentLinkId = $_GET['linkid'];;
        $this->bpayname = $_SESSION['bpayname'];
        $this->bpid = $_SESSION ['bpid'];
        $this->ouid = $_SESSION['login_ouid'];

        if ($this->ouid == 4) {
            $this->ouabbrev = $_SESSION['bpouabbrev'];
        } else {
            $this->ouabbrev = $_SESSION['login_ouabbrev'];
        }

    }

    public function GetStatus()
    {
        $this->contentLinkId = $_GET['linkid'];
        try {
            $sqlbpstatus = "SELECT CONTENT_STATUS FROM `BpContents` WHERE ID_CONTENT = :contentlink_id ;";
            $resultbpstatus = $this->connection->prepare($sqlbpstatus);
            $resultbpstatus->bindParam(':contentlink_id', $this->contentLinkId, 2);
            $resultbpstatus->execute();
        } catch (PDOException $e) {
            //SYSTEM::pLog($e->__toString(),$_SERVER['PHP_SELF']);
            error_log($e->getMessage());
        }
        return $resultbpstatus;
    }

    public function SubmitApproval()
    {
        $this->contentLinkId = $_GET['linkid'];
        $this->time = date('Y-m-d H:i:s');

        try {
            $sqlSubmitApr = "UPDATE `BpContents` SET CONTENT_STATUS = 'Pending Dean Approval',
BP_AUTHOR= :author , MOD_TIMESTAMP =:timeStampmod  WHERE ID_CONTENT = :contentlink_id ;";

            $resultSubmitApr = $this->connection->prepare($sqlSubmitApr);
            $resultSubmitApr->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultSubmitApr->bindParam(":timeStampmod", $this->time, PDO::PARAM_STR);
            $resultSubmitApr->bindParam(":contentlink_id", $this->contentLinkId, PDO::PARAM_STR);

            if ($resultSubmitApr->execute()) {

                $this->message = " Info submitted Successfully";

            } else {
                $this->message = " Info Could not be submitted. Please Retry.";
            }

        } catch (PDOException $e) {

            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);

        }
        return $this->message;

    }

    public function Approve()
    {

        $this->contentLinkId = $_GET['linkid'];
        $this->time = date('Y-m-d H:i:s');

        try {

            $sqlAprove = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Approved', BP_AUTHOR = :author,
              MOD_TIMESTAMP = :timeStampmod WHERE ID_CONTENT =:contentlink_id; ";

            $resultAprove = $this->connection->prepare($sqlAprove);
            $resultAprove->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultAprove->bindParam(":timeStampmod", $this->time, PDO::PARAM_STR);
            $resultAprove->bindParam(":contentlink_id", $this->contentLinkId, PDO::PARAM_STR);

            if ($resultAprove->execute()) {

                $this->message = " Info Approved Successfully";

            } else {

                $this->message = " Info Could not be Approved. Please Retry.";

            }

        } catch (PDOException $e) {

            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);

        }
        return $this->message;
    }

    public function Reject()
    {

        $this->contentLinkId = $_GET['linkid'];
        $this->time = date('Y-m-d H:i:s');
        try {

            $sqlReject = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Rejected', BP_AUTHOR = :author,
              MOD_TIMESTAMP =:timeStampmod WHERE ID_CONTENT = :contentlink_id; ";

            $resultReject = $this->connection->prepare($sqlReject);
            $resultReject->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultReject->bindParam(":timeStampmod", $this->time, PDO::PARAM_STR);
            $resultReject->bindParam(":contentlink_id", $this->contentLinkId, PDO::PARAM_STR);

            if ($resultReject->execute()) {
                $this->message = " Info Rejected Successfully";
            } else {
                $this->message = " Info Could not be Rejected. Please Retry.";

            }

        } catch (PDOException $e) {

            error_log($e->getMessage());

        }
        return $this->message;
    }

    public function BlueprintStatusDisplay()
    {
        $ouid = $_SESSION['login_ouid'];
        try {
            if ($ouid == 4) {
                $sqlbroad = "SELECT BROADCAST_AY,OU_NAME,BROADCAST_STATUS,LastModified FROM broadcast INNER JOIN Hierarchy ON broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY WHERE BROADCAST_AY= :bpayname AND Hierarchy.OU_ABBREV = :ouabbrev;";

                $resultbroad = $this->connection->prepare($sqlbroad);
                $resultbroad->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
                $resultbroad->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);

            } else {
                $sqlbroad = "SELECT BROADCAST_AY,OU_NAME, BROADCAST_STATUS_OTHERS,LastModified FROM broadcast INNER JOIN
        Hierarchy ON broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY WHERE BROADCAST_AY = :bpayname AND Hierarchy.OU_ABBREV = :ouabbrev ;";

                $resultbroad = $this->connection->prepare($sqlbroad);
                $resultbroad->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
                $resultbroad->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            }

            $resultbroad->execute();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultbroad;
    }

    public function PlaceHolderValue($tablename, $primarykey)
    {
        try {
            $sqlexvalue = "SELECT * FROM $tablename WHERE OU_ABBREV = :ouabbrev AND $primarykey
IN (SELECT max($primarykey) FROM $tablename WHERE OUTCOMES_AY = :bpayname GROUP BY OU_ABBREV);";

            $resultexvalue = $this->connection->prepare($sqlexvalue);
            $resultexvalue->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultexvalue->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);

            $resultexvalue->execute();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultexvalue;
    }

}

Class EXECUTIVESUMCLASS extends BPCONTENTS
{

    public function SaveDraft()
    {

        $collname = $_POST['college-school-input'];
        $deanname = $_POST['deans-name-input'];
        $deantitle = $_POST['deans-title-input'];
        $introduction = Initialize::mynl2br($_POST['introduction-input']);
        $highlights = Initialize::mynl2br($_POST['highlights-input']);
        $bpid = $_SESSION['bpid'];
        $this->time = date('Y-m-d H:i:s');

        if ($_FILES['deans-portrait-logo']['tmp_name'] != "") {

            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/" . $_SESSION['site'] . "/uploads/exec_summary/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755);
            }
            $target_file_port = $target_dir . basename($_FILES["deans-portrait-logo"]["name"]);
            $deansPortraitLogoTmpDir = $_FILES["deans-portrait-logo"]["name"];
            $portsize = getimagesize($_FILES["deans-portrait-logo"]["name"]);

            if (exif_imagetype($deansPortraitLogoTmpDir) == IMAGETYPE_GIF ||
                exif_imagetype($deansPortraitLogoTmpDir) == IMAGETYPE_JPEG ||
                exif_imagetype($deansPortraitLogoTmpDir) == IMAGETYPE_PNG
            ) {

                if ($portsize[0] == 250 && $portsize[1] == 250) {

                    if (move_uploaded_file($_FILES["deans-portrait-logo"]["tmp_name"], $target_file_port)) {

                        $this->deansPortraitLogopath = $target_file_port;

                    } else {
                        $this->message = "Sorry, there was an error uploading your file.";
                    }

                } else {
                    $this->message = "Only 250 X 250 pixel files are allowed.";
                    $this->errorflag = 1;
                }

            } else {
                $this->message = "Sorry, only GIF, JPEG or PNG files are allowed.";
                $this->errorflag = 1;
            }

        }

        if ($_FILES['deans-signature-logo']['tmp_name'] != "") {

            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/" . $_SESSION['site'] . "/uploads/exec_summary/";
            $target_file_sign = $target_dir . basename($_FILES["deans-signature-logo"]["name"]);
            $deansPortraitSignTmpDir = $_FILES["deans-signature-logo"]["name"];
            $signsize = getimagesize($_FILES["deans-signature-logo"]["name"]);

            if (exif_imagetype($deansPortraitSignTmpDir) == IMAGETYPE_GIF ||
                exif_imagetype($deansPortraitSignTmpDir) == IMAGETYPE_JPEG ||
                exif_imagetype($deansPortraitSignTmpDir) == IMAGETYPE_PNG
            ) {

                if ($signsize[0] == 250 && $signsize[1] == 75) {

                    if (move_uploaded_file($_FILES["deans-signature-logo"]["tmp_name"], $target_file_sign)) {

                        $this->deansPortraitSignpath = $target_file_sign;

                    } else {

                        $this->message = "Sorry, there was an error uploading your file.";

                    }

                } else {

                    $this->message = "Only 250 X 75 pixel files are allowed.";
                    $this->errorflag = 1;

                }

            } else {

                $this->message = "Sorry, only GIF, JPEG or PNG files are allowed.";
                $this->errorflag = 1;

            }

        }

        if ($_FILES['deans-college-school-logo']['tmp_name'] != "") {

            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/" . $_SESSION['site'] . "/uploads/exec_summary/";
            $target_file_sch_logo = $target_dir . basename($_FILES["deans-college-school-logo"]["name"]);
            $deansSchoolLogoTmpDir = $_FILES["deans-college-school-logo"]["name"];
            //$imagedimension = getimagesize($_FILES["deans-college-school-logo"]["name"]);

            if (exif_imagetype($deansSchoolLogoTmpDir) == IMAGETYPE_GIF ||
                exif_imagetype($deansSchoolLogoTmpDir) == IMAGETYPE_JPEG ||
                exif_imagetype($deansSchoolLogoTmpDir) == IMAGETYPE_PNG
            ) {

                if (move_uploaded_file($_FILES["deans-college-school-logo"]["tmp_name"], $target_file_sch_logo)) {
                    $this->deansSchLogopath = $target_file_sch_logo;
                } else {
                    $this->message = "Sorry, there was an error uploading your file.";
                }

            } else {

                $this->message = "Sorry, only GIF, JPEG or PNG files are allowed.";
                $this->errorflag = 1;

            }

        }

        if ($this->errorflag != 1) {

            try {

                $sqlexecsum = "INSERT INTO `AC_ExecSum` (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP, UNIT_NAME,
              DEAN_NAME_PRINT, DEAN_TITLE, DEAN_PORTRAIT, DEAN_SIGNATURE, COMPANION_LOGO, INTRODUCTION, HIGHLIGHTS_NARRATIVE)
              VALUES (:ouabbrev,:bpayname,:author,:timestampmod,:collname,:deanname,:deantitle,:deansPortraitLogopath,
              :deansPortraitSignpath,:deansSchLogopath,:introduction,:highlights);";

                if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {
                    $sqlexecsum .= "UPDATE  `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR = :author,
MOD_TIMESTAMP =:timestampmod  WHERE ID_CONTENT =:contentlink_id;";

                    $sqlexecsum .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress',
BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= :author, LastModified =:timestampmod WHERE ID_BROADCAST = :bpid; ";
                }

                $execsum = $this->connection->prepare($sqlexecsum);
                $execsum->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
                $execsum->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
                $execsum->bindParam(":author", $this->author, PDO::PARAM_STR);
                $execsum->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
                $execsum->bindParam(":collname", $collname, PDO::PARAM_STR);
                $execsum->bindParam(":deanname", $deanname, PDO::PARAM_STR);
                $execsum->bindParam(":deantitle", $deantitle, PDO::PARAM_STR);
                $execsum->bindParam(":deansPortraitLogopath", $deansPortraitLogopath, PDO::PARAM_STR);
                $execsum->bindParam(":deansPortraitSignpath", $deansPortraitSignpath, PDO::PARAM_STR);
                $execsum->bindParam(":deansSchLogopath", $deansSchLogopath, PDO::PARAM_STR);
                $execsum->bindParam(":introduction", $introduction, PDO::PARAM_STR);
                $execsum->bindParam(":highlights", $highlights, PDO::PARAM_STR);
                if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {
                    $execsum->bindParam(":author", $this->author, PDO::PARAM_STR);
                    $execsum->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
                    $execsum->bindParam(":contentlink_id", $this->contentLinkId, PDO::PARAM_STR);
                    $execsum->bindParam(":author", $this->author, PDO::PARAM_STR);
                    $execsum->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
                    $execsum->bindParam(":bpid", $bpid, PDO::PARAM_STR);
                }

                if ($execsum->execute()) {
                    $this->message = "Executive Summary Info Added Successfully.";
                } else {
                    $this->message = "Executive Summary Info could not be added.";
                }

            } catch (PDOException $e) {
                error_log($e->getMessage());
                //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
            }

        }
        return $this->message;

    }

    public function PlaceHolderValue()
    {
        try {
            $sqlexvalue = "SELECT * FROM `AC_ExecSum` WHERE OU_ABBREV = :ouabbrev AND ID_EXECUTIVE_SUMMARY
IN (SELECT max(ID_EXECUTIVE_SUMMARY) FROM AC_ExecSum WHERE OUTCOMES_AY = :bpayname GROUP BY OU_ABBREV);";

            $resultexvalue = $this->connection->prepare($sqlexvalue);
            $resultexvalue->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultexvalue->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);

            $resultexvalue->execute();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultexvalue;
    }
}

Class ACADEMICPROGRAM extends BPCONTENTS
{
    public function SaveDraft()
    {
        $this->time = date('Y-m-d H:i:s');
        $programranking = Initialize::mynl2br($_POST['programranking']);
        $instructionalmodalities = Initialize::mynl2br($_POST['instructionalmodalities']);
        $launch = Initialize::mynl2br($_POST['launch']);
        $programterminations = Initialize::mynl2br($_POST['programterminators']);

        if ($_FILES['supinfo']['tmp_name'] != "") {
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/" . $_SESSION['site'] . "/uploads/ac_programs/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755);
            }
            $target_file = $target_dir . basename($_FILES["supinfo"]["name"]);
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            $imagedimension = getimagesize($_FILES["supinfo"]["name"]);

            if ($imageFileType != "pdf") {
                $this->message = "Sorry, only PDF files are allowed.";
                $this->errorflag = 1;

            } else {
                if (move_uploaded_file($_FILES["supinfo"]["tmp_name"], $target_file)) {
                    $supinfopath = $target_file;
                } else {
                    $this->message = "Sorry, there was an error uploading your file.";
                    $this->errorflag = 1;
                }
            }
        }
        if ($this->errorflag != 1) {

            $sqlacprogram = "INSERT INTO AC_Programs (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP,
        PROGRAM_RANKINGS, INSTRUCT_MODALITIES, PROGRAM_LAUNCHES, PROGRAM_TERMINATIONS, AC_SUPPL_PROGRAMS) VALUES
        (:ouabbrev, :bpayname, :author, :timestampmod, :programranking, :instructionalmodalities, :launch,
:programterminations, :supinfopath);";

            if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {
                $sqlacprogram .= "UPDATE `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR= :author,
            MOD_TIMESTAMP = :timestampmod WHERE ID_CONTENT =:contentlink_id ;";

                $sqlacprogram .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress',
BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= :author, LastModified = :timestampmod WHERE ID_BROADCAST = :bpid ; ";
            }

            $resultacprogram = $this->connection->prepare($sqlacprogram);
            $resultacprogram->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultacprogram->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
            $resultacprogram->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultacprogram->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
            $resultacprogram->bindParam(':programranking', $programranking, PDO::PARAM_STR);
            $resultacprogram->bindParam(':instructionalmodalities', $instructionalmodalities, PDO::PARAM_STR);
            $resultacprogram->bindParam(':launch', $launch, PDO::PARAM_STR);
            $resultacprogram->bindParam(':programterminations', $programterminations, PDO::PARAM_STR);
            $resultacprogram->bindParam(':supinfopath', $supinfopath, PDO::PARAM_STR);

            if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

                $resultacprogram->bindParam(":author", $this->author, PDO::PARAM_STR);
                $resultacprogram->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
                $resultacprogram->bindParam(':contentlink_id', $this->contentLinkId, PDO::PARAM_STR);
                $resultacprogram->bindParam(":author", $this->author, PDO::PARAM_STR);
                $resultacprogram->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
                $resultacprogram->bindParam(':bpid', $this->bpid, PDO::PARAM_STR);
            }

            if ($resultacprogram->execute()) {
                $this->message = "Academic Program Info Added Succesfully.";
            } else {
                $this->message = "Academic Program Info could not be added.";
            }
        }
        return $this->message;
    }

    public function PlaceHolderValue()
    {
        try {
            $sqlexvalue = "SELECT * FROM `AC_Programs` WHERE OU_ABBREV = :ouabbrev AND ID_AC_PROGRAMS IN
(SELECT MAX(ID_AC_PROGRAMS) FROM `AC_Programs` WHERE OUTCOMES_AY = :bpayname GROUP BY OU_ABBREV)";

            $resultexvalue = $this->connection->prepare($sqlexvalue);
            $resultexvalue->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultexvalue->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);

            $resultexvalue->execute();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultexvalue;
    }
}


Class FACULTYINFO extends BPCONTENTS
{
    public function SaveDraft()
    {
        $this->time = date('Y-m-d H:i:s');
        $resactivity = Initialize::mynl2br($_POST['researchtextarea']);
        $facdev = Initialize::mynl2br($_POST['factextarea']);
        $createact = Initialize::mynl2br($_POST['cractivity']);
        $this->contentLinkId = $_GET['linkid'];


        //    if ($_FILES["supinfo"]["error"] > 0) {
        //        $error[0] = "Return Code: No Input " . $_FILES["supinfo"]["error"] . "<br />";
        //        $errorflag = 1;
        //
        //    } else {
        //        $target_dir = "../../user"."/".$name."/";

        if ($_FILES['supinfo']['tmp_name'] != "") {
            //$target_dir = "../uploads/facultyInfo/";
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/" . $_SESSION['site'] . "/uploads/facultyInfo/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755);
            }
            $target_file = $target_dir . basename($_FILES["supinfo"]["name"]);
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);


            if ($imageFileType != "pdf") {

                $this->message = "Sorry, only PDF files are allowed.";
                $this->errorflag = 1;

            } else {
                if (move_uploaded_file($_FILES["supinfo"]["tmp_name"], $target_file)) {
                    // $error[0] = "The file " . basename($_FILES["supinfo"]["name"]) . " has been uploaded.";
                    $supinfopath = $target_file;
                } else {
                    $this->message = "Sorry, there was an error uploading your file.";
                }
            }
        }

        if ($this->errorflag != 1) {
            $sqlfacinfo = "INSERT INTO `AC_FacultyInfo` (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP,
            RSRCH_SCHOLRLY_ACTIVITY, FACULTY_DEVELOPMENT, CREATIVE_ACTIVITY, AC_SUPPL_FACULTY)
   VALUES (:ouabbrev,:bpayname,:author,:timestampmod, :resactivity, :facdev,:createact,:supinfopath);";

            if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {
                $sqlfacinfo .= "UPDATE  `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR= :author, 
                MOD_TIMESTAMP =:timestampmod  WHERE ID_CONTENT =:contentlink_id;";

                $sqlfacinfo .= "UPDATE  `broadcast` SET BROADCAST_STATUS = 'In Progress', 
BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= :author, LastModified =:timestampmod WHERE ID_BROADCAST = :bpid; ";
            }


            $resultfacinfo = $this->connection->prepare($sqlfacinfo);
            $resultfacinfo->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultfacinfo->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
            $resultfacinfo->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultfacinfo->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
            $resultfacinfo->bindParam(':resactivity', $resactivity, PDO::PARAM_STR);
            $resultfacinfo->bindParam(':facdev', $facdev, PDO::PARAM_STR);
            $resultfacinfo->bindParam(':createact', $createact, PDO::PARAM_STR);
            $resultfacinfo->bindParam(':supinfopath', $supinfopath, PDO::PARAM_STR);

            if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {
                $resultfacinfo->bindParam(":author", $this->author, PDO::PARAM_STR);
                $resultfacinfo->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
                $resultfacinfo->bindParam(':contentlink_id', $this->contentLinkId, PDO::PARAM_STR);
                $resultfacinfo->bindParam(":author", $this->author, PDO::PARAM_STR);
                $resultfacinfo->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
                $resultfacinfo->bindParam(':bpid', $this->bpid, PDO::PARAM_STR);
            }

            if ($resultfacinfo->execute()) {

                $this->message = "Faculty Info Added Succesfully.";
            } else {
                $this->message = "Faculty Info could not be added.";
            }

        }
        return $this->message;
    }

    public function PlaceHolderValue()
    {
        try {
            $sqlexvalue = "SELECT * FROM `AC_FacultyInfo` WHERE OU_ABBREV = :ouabbrev AND ID_FACULTY_INFO IN (SELECT max(ID_FACULTY_INFO) FROM AC_FacultyInfo WHERE OUTCOMES_AY = :bpayname GROUP BY OU_ABBREV); ";

            $resultsexvalue = $this->connection->prepare($sqlexvalue);
            $resultsexvalue->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultsexvalue->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
            $resultsexvalue->execute();


        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultsexvalue;
    }
}

Class GOALOUTCOME extends BPCONTENTS
{
    public function SaveDraft()
    {
        $goalstatus = $_POST['goal_status'];
        $goalach = Initialize::mynl2br($_POST['goal_ach']);
        $resutilzed = Initialize::mynl2br($_POST['goal_resutil']);
        $goalconti = Initialize::mynl2br($_POST['goal_conti']);
        $resneed = Initialize::mynl2br($_POST['resoneed']);
        $goalincomplan = Initialize::mynl2br($_POST['goal_plan_incomp']);
        $goalupcominplan = Initialize::mynl2br($_POST['goal_plan_upcoming']);
        $goalreportstatus = "In Progress";
        $goal_id = $_GET['goal_id'];
        $this->time = date('Y-m-d H:i:s');


        $sqlgoalout = "INSERT INTO `BP_UnitGoalOutcomes` (ID_UNIT_GOAL, OUTCOMES_AUTHOR, MOD_TIMESTAMP, GOAL_REPORT_STATUS, GOAL_STATUS, GOAL_ACHIEVEMENTS, GOAL_RSRCS_UTLZD, GOAL_CONTINUATION, GOAL_RSRCS_NEEDED, GOAL_PLAN_INCOMPLT, GOAL_UPCOMING_PLAN )
      VALUES (:goal_id,:author,:timestampmod,:goalreportstatus,:goalstatus,:goalach,:resutilzed,:goalconti,:resneed,
      :goalincomplan,:goalupcominplan)
      ON DUPLICATE KEY UPDATE `ID_UNIT_GOAL` = VALUES(`ID_UNIT_GOAL`), OUTCOMES_AUTHOR = VALUES(`OUTCOMES_AUTHOR`), MOD_TIMESTAMP = VALUES(`MOD_TIMESTAMP`),
      GOAL_REPORT_STATUS = VALUES(`GOAL_REPORT_STATUS`), GOAL_STATUS = VALUES(`GOAL_STATUS`), GOAL_ACHIEVEMENTS = VALUES(`GOAL_ACHIEVEMENTS`), GOAL_RSRCS_UTLZD = VALUES(`GOAL_RSRCS_UTLZD`),
      GOAL_CONTINUATION = VALUES(`GOAL_CONTINUATION`), GOAL_RSRCS_NEEDED = VALUES(`GOAL_RSRCS_NEEDED`); ";

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {
            $sqlgoalout .= "UPDATE `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR= :author, MOD_TIMESTAMP =:timestampmod WHERE ID_CONTENT =:contentlink_id;";

            $sqlgoalout .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress', BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= :author, LastModified =:timestampmod WHERE ID_BROADCAST = :bpid; ";
        }

        $resultgoalout = $this->connection->prepare($sqlgoalout);
        $resultgoalout->bindParam(":goal_id", $goal_id, PDO::PARAM_STR);
        $resultgoalout->bindParam(":author", $this->author, PDO::PARAM_STR);
        $resultgoalout->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
        $resultgoalout->bindParam(":goalreportstatus", $goalreportstatus, PDO::PARAM_STR);
        $resultgoalout->bindParam(':goalstatus', $goalstatus, PDO::PARAM_STR);
        $resultgoalout->bindParam(':goalach', $goalach, PDO::PARAM_STR);
        $resultgoalout->bindParam(':resutilzed', $resutilzed, PDO::PARAM_STR);
        $resultgoalout->bindParam(':goalconti', $goalconti, PDO::PARAM_STR);
        $resultgoalout->bindParam(':resneed', $resneed, PDO::PARAM_STR);
        $resultgoalout->bindParam(':goalincomplan', $goalincomplan, PDO::PARAM_STR);
        $resultgoalout->bindParam(':goalupcominplan', $goalupcominplan, PDO::PARAM_STR);

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {
            $resultgoalout->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultgoalout->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
            $resultgoalout->bindParam(":contentlink_id", $this->contentLinkId, PDO::PARAM_STR);
            $resultgoalout->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultgoalout->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
            $resultgoalout->bindParam(':bpid', $this->bpid, PDO::PARAM_STR);
        }


        if ($resultgoalout->execute()) {
            $this->message = "Goal Outcome Saved.";
        } else {
            $this->message = "Goal Outcome could not be Saved.";
        }

        return $this->message;
    }

    public function SubmitApproval()
    {
        $goal_id = $_GET['goal_id'];
        $goalreportstatus = "Pending Approval";

        try {
            $sqlgoaloutap = "UPDATE `BP_UnitGoalOutcomes` SET GOAL_REPORT_STATUS = :goalreportstatus WHERE ID_UNIT_GOAL = :goal_id; ";

            $resultgoaloutap = $this->connection->prepare($sqlgoaloutap);
            $resultgoaloutap->bindParam(":goalreportstatus", $goalreportstatus, PDO::PARAM_STR);
            $resultgoaloutap->bindParam(':goal_id', $goal_id, PDO::PARAM_STR);

            //$sqlgoaloutap .= "Update `BpContents` set CONTENT_STATUS = 'Pending Dean Approval', BP_AUTHOR= '$author', MOD_TIMESTAMP ='$time' where ID_CONTENT ='$contentlink_id';";

            if ($resultgoaloutap->execute()) {
                $this->message = "Goal Outcome submitted for Approval.";
            } else {
                $this->message = "Goal Outcome could not be Submitted.";
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $this->message;
    }

    public function Approve()
    {


        try {
            $sqlmission = "UPDATE `BP_UnitGoalOutcomes` SET GOAL_REPORT_STATUS = 'Dean Approved' WHERE ID_UNIT_GOAL = :goal_id; ";

            $sqlmissionresult = $this->connection->prepare($sqlmission);
            $sqlmissionresult->bindParam(":goal_id", $this->contentLinkId, PDO::PARAM_INT);

            if ($sqlmissionresult->execute()) {
                $message[0] = "Goal Outcome Approved Successfully";
            } else {
                $message[0] = "Goal Outcome Could not be Approved. Please Retry.";
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
    }

    public function Reject()
    {

        try {
            $sqlmission = "UPDATE `BP_UnitGoalOutcomes` SET GOAL_REPORT_STATUS = 'Dean Rejected' WHERE ID_UNIT_GOAL = :goal_id; ";

            $sqlmissionresult = $this->connection->prepare($sqlmission);
            $sqlmissionresult->bindParam(":goal_id", $this->contentLinkId, PDO::PARAM_INT);

            if ($sqlmissionresult->execute()) {
                $message[0] = "Goal Outcome Rejected Successfully";
            } else {
                $message[0] = "Goal Outcome Could not be Rejected. Please Retry.";
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $this->message;
    }

    public function OutcomePlaceholders()
    {
        $goal_id = $_GET['goal_id'];
        try {
            $sqlexgoalout = "SELECT * FROM BP_UnitGoalOutcomes WHERE ID_UNIT_GOAL = :goal_id ";
            $resultexgoalout = $this->connection->prepare($sqlexgoalout);
            $resultexgoalout->bindParam(":goal_id", $goal_id, PDO::PARAM_INT);
            $resultexgoalout->execute();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultexgoalout;
    }

    public function PlaceHolderValue()
    {
        $goal_id = $_GET['goal_id'];
        try {
            $sqlunitgoal = "SELECT * FROM BP_UnitGoals WHERE ID_UNIT_GOAL = :goal_id ";
            $resultunitgoal = $this->connection->prepare($sqlunitgoal);
            $resultunitgoal->bindParam(":goal_id", $goal_id, PDO::PARAM_INT);
            $resultunitgoal->execute();


        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultunitgoal;
    }
}


Class ALUMNIDEVELOPMENT extends BPCONTENTS
{
    public function SaveDraft()
    {
        $this->time = date('Y-m-d H:i:s');
        $alumni = Initialize::mynl2br($_POST['alumni']);
        $development = Initialize::mynl2br($_POST['development']);
        $supinfopath = null;

        if ($_FILES['supinfo']['tmp_name'] != "") {
//            $target_dir = "../uploads/alumni_dev/";
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/" . $_SESSION['site'] . "/uploads/alumni_dev/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755);
            }
            $target_file = $target_dir . basename($_FILES["supinfo"]["name"]);
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            $imagedimension = getimagesize($_FILES["supinfo"]["name"]);


            if ($imageFileType != "pdf") {
                $this->message = "Sorry, only PDF files are allowed.";
                $this->errorflag = 1;

            } else {
                if (move_uploaded_file($_FILES["supinfo"]["tmp_name"], $target_file)) {
                    // $error[0] = "The file " . basename($_FILES["supinfo"]["name"]) . " has been uploaded.";
                    $supinfopath = $target_file;
                } else {
                    $this->message = "Sorry, there was an error uploading your file.";
                    $this->errorflag = 1;
                }
            }
        }
        if ($this->errorflag != 1) {

            $sqlalumnidev = "INSERT INTO `AC_AlumDev` (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP,
AC_UNIT_ALUMNI, AC_UNIT_DEVT_FUND_GIFTS, AC_UNIT_SUPPL_ALUM_DEV) VALUES (:ouabbrev, :bpayname, :author, 
:timeStampmod, :alumni, :development, :supinfopath);";

            if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {
                $sqlalumnidev .= "UPDATE `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR= :author,
            MOD_TIMESTAMP = :timeStampmod WHERE ID_CONTENT =:contentlink_id ;";

                $sqlalumnidev .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress',
BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= :author, LastModified = :timeStampmod WHERE ID_BROADCAST = :bpid ; ";
            }

            $resultalumnidev = $this->connection->prepare($sqlalumnidev);

            $resultalumnidev->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultalumnidev->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
            $resultalumnidev->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultalumnidev->bindParam(":timeStampmod", $this->time, PDO::PARAM_STR);
            $resultalumnidev->bindParam(':alumni', $alumni, PDO::PARAM_STR);
            $resultalumnidev->bindParam(':development', $development, PDO::PARAM_STR);
            $resultalumnidev->bindParam(':supinfopath', $supinfopath, PDO::PARAM_STR);

            if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

                $resultalumnidev->bindParam(":author", $this->author, PDO::PARAM_STR);
                $resultalumnidev->bindParam(":timeStampmod", $this->time, PDO::PARAM_STR);
                $resultalumnidev->bindParam(':contentlink_id', $this->contentLinkId, PDO::PARAM_STR);
                $resultalumnidev->bindParam(":author", $this->author, PDO::PARAM_STR);
                $resultalumnidev->bindParam(":timeStampmod", $this->time, PDO::PARAM_STR);
                $resultalumnidev->bindParam(':bpid', $this->bpid, PDO::PARAM_STR);
            }
            if ($resultalumnidev->execute()) {

                $this->message = "Alumni Development Info Added Succesfully.";
            } else {
                $this->message = "Alumni Development Info could not be added.";
            }
        }
        return $this->message;
    }

    public function PlaceHolderValue()
    {
        try {
            $sqlexvalue = "SELECT * FROM `AC_AlumDev` WHERE OU_ABBREV = :ouabbrev AND ID_ALUMNI_DEV IN
(SELECT MAX(ID_ALUMNI_DEV) FROM `AC_AlumDev` WHERE OUTCOMES_AY = :bpayname GROUP BY OU_ABBREV)";

            $resultexvalue = $this->connection->prepare($sqlexvalue);
            $resultexvalue->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultexvalue->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);

            $resultexvalue->execute();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultexvalue;
    }
}

Class COLLABORATION extends BPCONTENTS
{
    public function SaveDraft()
    {
        $this->time = date('Y-m-d H:i:s');
        $supinfopath = null;
        $internalcollaborators = Initialize::mynl2br($_POST['internalcollaborators']);
        $externalcollaborators = Initialize::mynl2br($_POST['externalcollaborators']);
        $othercollaborators = Initialize::mynl2br($_POST['othercollaborators']);

        if ($_FILES['supinfo']['tmp_name'] != "") {
//            $target_dir = "../uploads/collaborations";
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/" . $_SESSION['site'] . "/uploads/collaborations/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755);
            }
            $target_file = $target_dir . basename($_FILES["supinfo"]["name"]);
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);


            if ($imageFileType != "pdf") {
                $this->message = "Sorry, only PDF files are allowed.";
                $this->errorflag = 1;

            } else {
                if (move_uploaded_file($_FILES["supinfo"]["tmp_name"], $target_file)) {
                    $supinfopath = $target_file;
                } else {
                    $this->message = "Sorry, there was an error uploading your file.";
                }
            }
        }

        if ($this->errorflag != 1) {

            $sqlcollob = "INSERT INTO `AC_Collaborations` (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR,
MOD_TIMESTAMP, COLLAB_INTERNAL, COLLAB_EXTERNAL, COLLAB_OTHER, SUPPL_COLLABORATIONS) VALUES (:ouabbrev, :bpayname,
:author, :timestampmod, :internalcollaborators, :externalcollaborators, :othercollaborators, :supinfopath);";

            if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {
                $sqlcollob .= "UPDATE `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR= :author,
            MOD_TIMESTAMP = :timestampmod WHERE ID_CONTENT =:contentlink_id ;";

                $sqlcollob .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress',
BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= :author, LastModified = :timestampmod WHERE ID_BROADCAST = :bpid ; ";
            }

            $resultclimate = $this->connection->prepare($sqlcollob);

            $resultclimate->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultclimate->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
            $resultclimate->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultclimate->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
            $resultclimate->bindParam(':internalcollaborators', $internalcollaborators, PDO::PARAM_STR);
            $resultclimate->bindParam(':externalcollaborators', $externalcollaborators, PDO::PARAM_STR);
            $resultclimate->bindParam(':othercollaborators', $othercollaborators, PDO::PARAM_STR);
            $resultclimate->bindParam(':supinfopath', $supinfopath, PDO::PARAM_STR);

            if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

                $resultclimate->bindParam(":author", $this->author, PDO::PARAM_STR);
                $resultclimate->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
                $resultclimate->bindParam(':contentlink_id', $this->contentLinkId, PDO::PARAM_STR);
                $resultclimate->bindParam(":author", $this->author, PDO::PARAM_STR);
                $resultclimate->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
                $resultclimate->bindParam(':bpid', $this->bpid, PDO::PARAM_STR);
            }
            if ($resultclimate->execute()) {

                $this->message = "Academic Collaborations Info Added Succesfully.";
            } else {
                $this->message = "Academic Collaborations Info could not be added.";
            }
        }
        return $this->message;
    }

    public function PlaceHolderValue()
    {
        try {
            $sqlexvalue = "SELECT * FROM `AC_Collaborations` WHERE OU_ABBREV = :ouabbrev AND ID_COLLABORATIONS IN
(SELECT MAX(ID_COLLABORATIONS) FROM `AC_Collaborations` WHERE OUTCOMES_AY = :bpayname GROUP BY OU_ABBREV)";

            $resultexvalue = $this->connection->prepare($sqlexvalue);
            $resultexvalue->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultexvalue->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);

            $resultexvalue->execute();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultexvalue;
    }
}

Class CAMPUSCLIMATE extends BPCONTENTS
{
    public function SaveDraft()
    {
        $this->time = date('Y-m-d H:i:s');
        $climate = Initialize::mynl2br($_POST['climate']);
        $supinfopath = null;

        if ($_FILES['supinfo']['tmp_name'] != "") {
//            $target_dir = "../uploads/campus_climate/";
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/" . $_SESSION['site'] . "/uploads/campus_climate/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755);
            }
            $target_file = $target_dir . basename($_FILES["supinfo"]["name"]);
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            $imagedimension = getimagesize($_FILES["supinfo"]["name"]);

            if ($imageFileType != "pdf") {
                $this->message = "Sorry, only PDF files are allowed.";
                $this->errorflag = 1;

            } else {
                if (move_uploaded_file($_FILES["supinfo"]["tmp_name"], $target_file)) {
                    $supinfopath = $target_file;
                } else {
                    $this->message = "Sorry, there was an error uploading your file.";
                    $this->errorflag = 1;
                }
            }
        }
        if ($this->errorflag != 1) {

            $sqlclimate = "INSERT INTO `AC_CampusClimateInclusion` (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR,
MOD_TIMESTAMP, CLIMATE_INCLUSION, SUPPL_CLIMATE_INCLUSION) VALUES (:ouabbrev, :bpayname, :author, :timestampmod,
:climate, :supinfopath);";

            if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {
                $sqlclimate .= "UPDATE `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR= :author,
            MOD_TIMESTAMP = :timestampmod WHERE ID_CONTENT =:contentlink_id ;";

                $sqlclimate .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress',
BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= :author, LastModified = :timestampmod WHERE ID_BROADCAST = :bpid ; ";
            }

            $resultclimate = $this->connection->prepare($sqlclimate);

            $resultclimate->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultclimate->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
            $resultclimate->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultclimate->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
            $resultclimate->bindParam(':climate', $climate, PDO::PARAM_STR);
            $resultclimate->bindParam(':supinfopath', $supinfopath, PDO::PARAM_STR);

            if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

                $resultclimate->bindParam(":author", $this->author, PDO::PARAM_STR);
                $resultclimate->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
                $resultclimate->bindParam(':contentlink_id', $this->contentLinkId, PDO::PARAM_STR);
                $resultclimate->bindParam(":author", $this->author, PDO::PARAM_STR);
                $resultclimate->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
                $resultclimate->bindParam(':bpid', $this->bpid, PDO::PARAM_STR);
            }
            if ($resultclimate->execute()) {

                $this->message = "Campus & Climate Info Added Succesfully.";
            } else {
                $this->message = "Campus & Climate Info could not be added.";
            }
        }
        return $this->message;
    }

    public function PlaceHolderValue()
    {

        try {
            $sqlexvalue = "SELECT * FROM `AC_CampusClimateInclusion` WHERE OU_ABBREV = :ouabbrev AND
            ID_CLIMATE_INCLUSION IN (SELECT MAX(ID_CLIMATE_INCLUSION) FROM `AC_CampusClimateInclusion` WHERE
            OUTCOMES_AY = :bpayname GROUP BY OU_ABBREV)";

            $resultexvalue = $this->connection->prepare($sqlexvalue);
            $resultexvalue->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultexvalue->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);

            $resultexvalue->execute();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultexvalue;
    }
}

Class MVV extends BPCONTENTS
{
    public function SaveDraft()
    {
        $this->time = date('Y-m-d H:i:s');
//      $prevbpid = stringtoid($this->bpayname);
//      $prevbpayname = idtostring($prevbpid - 101);
        $missionstatement = Initialize::mynl2br($_POST['missionstatement']);
        $missionupdatedate = date("Y-m-d", strtotime($_POST['misupdate']));
        $visionstatement = Initialize::mynl2br($_POST['visionstatement']);
        $visionupdatedate = date("Y-m-d", strtotime($_POST['visupdate']));
        $visNoResp = $_POST['visNoResponse'];
        $valuestatement = Initialize::mynl2br($_POST['valuestatement']);
        $valueupdatedate = date("Y-m-d", strtotime($_POST['valupdate']));
        $valNoResp = $_POST['valNoResponse'];


        $sqlmission = "INSERT INTO `BP_MissionVisionValues` (OU_ABBREV,MVV_AUTHOR, MOD_TIMESTAMP, UNIT_MVV_AY, 
MISSION_STATEMENT, MISSION_UPDATE_DATE, VISION_STATEMENT,VISION_UPDATE_DATE,VISION_NO_RESPONSE,VALUES_STATEMENT,
VALUE_UPADTE_DATE,VALUE_NO_RESPONSE) VALUES (:ouabbrev,:author,:timestampmod,:bpayname,:missionstatement,
:missionupdatedate,:visionstatement, :visionupdatedate,:visNoResp ,:valuestatement,:valueupdatedate, :valNoResp);";

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {
            $sqlmission .= "UPDATE  `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR= :author, MOD_TIMESTAMP =:timestampmod  WHERE ID_CONTENT = :contentlink_id; ";

            $sqlmission .= "UPDATE  `broadcast` SET BROADCAST_STATUS = 'In Progress',BROADCAST_STATUS_OTHERS = 'In Progress',  AUTHOR= :author, LastModified = :timestampmod WHERE ID_BROADCAST = :bpid; ";
        }

        $resultmission = $this->connection->prepare($sqlmission);

        $resultmission->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
        $resultmission->bindParam(":author", $this->author, PDO::PARAM_STR);
        $resultmission->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
        $resultmission->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
        $resultmission->bindParam(':missionstatement', $missionstatement, PDO::PARAM_STR);
        $resultmission->bindParam(':missionupdatedate', $missionupdatedate, PDO::PARAM_STR);
        $resultmission->bindParam(':visionstatement', $visionstatement, PDO::PARAM_STR);
        $resultmission->bindParam(':visionupdatedate', $visionupdatedate, PDO::PARAM_STR);
        $resultmission->bindParam(':visNoResp', $visNoResp, PDO::PARAM_STR);
        $resultmission->bindParam(':valuestatement', $valuestatement, PDO::PARAM_STR);
        $resultmission->bindParam(':valueupdatedate', $valueupdatedate, PDO::PARAM_STR);
        $resultmission->bindParam(':valNoResp', $valNoResp, PDO::PARAM_STR);

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {
            $resultmission->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultmission->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
            $resultmission->bindParam(":contentlink_id", $this->contentLinkId, PDO::PARAM_STR);
            $resultmission->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultmission->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
            $resultmission->bindParam(":bpid", $this->bpid, PDO::PARAM_STR);
        }

        if ($resultmission->execute()) {
            $this->message = "Mission, Vission, & Values Successfully";
        } else {
            $this->message = "Mission, Vission, & Values Could not be Updated. Please Retry.";
        }
        return $this->message;
    }

    public function PlaceHolderValue()
    {
        $prevbpid = Initialize::stringtoid($this->bpayname);
        $prevbpayname = Initialize::idtostring($prevbpid - 101);
        try {
            $sqlmission = "SELECT * FROM BP_MissionVisionValues WHERE OU_ABBREV = :ouabbrev AND ID_UNIT_MVV IN (SELECT max(ID_UNIT_MVV) FROM BP_MissionVisionValues WHERE UNIT_MVV_AY IN (:bpayname,:prevbpayname) GROUP BY OU_ABBREV)";


            $resultmission = $this->connection->prepare($sqlmission);
            $resultmission->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultmission->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
            $resultmission->bindParam(":prevbpayname", $prevbpayname, PDO::PARAM_STR);
            $resultmission->execute();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultmission;
    }
}

Class INITIATIVES extends BPCONTENTS
{
    public function SaveDraft()
    {
        $this->time = date('Y-m-d H:i:s');
        $this->contentLinkId = $_GET['linkid'];
        $ugexplearn = Initialize::mynl2br($_POST['ugexplearning']);
        $gradexplearn = Initialize::mynl2br($_POST['gradexplearning']);
        $afford = Initialize::mynl2br($_POST['afford']);
        $reputation = Initialize::mynl2br($_POST['reputation']);
        $challenges = Initialize::mynl2br($_POST['challenges']);

        if ($_FILES['supinfo']['tmp_name'] != "") {
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/" . $_SESSION['site'] . "/uploads/initiatives/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755);
            }
            $target_file = $target_dir . basename($_FILES["supinfo"]["name"]);
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

            if ($imageFileType != "pdf") {
                $this->message = "Sorry, only PDF files are allowed.";
                $this->errorflag = 1;

            } else {
                if (move_uploaded_file($_FILES["supinfo"]["tmp_name"], $target_file)) {
                    $supinfopath = $target_file;
                } else {
                    $this->message = "Sorry, there was an error uploading your file.";
                    $this->errorflag = 1;
                }
            }
        }
        if ($this->errorflag != 1) {

            $sqlinitiatives = "INSERT INTO `AC_InitObsrv` (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP,
 EXPERIENTIAL_LEARNING_UGRAD, EXPERIENTIAL_LEARNING_GRAD, AFFORDABILITY, REPUTATION_ENHANCE, CHALLENGES,
 AC_SUPPL_INITIATIVES_OBSRV) VALUES (:ouabbrev, :bpayname, :author, :timestampmod, :ugexplearn, :gradexplearn,
 :afford, :reputation, :challenges, :supinfopath);";

            if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {
                $sqlinitiatives .= "UPDATE `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR= :author,
            MOD_TIMESTAMP = :timestampmod WHERE ID_CONTENT =:contentlink_id ;";

                $sqlinitiatives .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress',
BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= :author, LastModified = :timestampmod WHERE ID_BROADCAST = :bpid ; ";
            }

            $resultInitiative = $this->connection->prepare($sqlinitiatives);

            $resultInitiative->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultInitiative->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
            $resultInitiative->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultInitiative->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
            $resultInitiative->bindParam(':ugexplearn', $ugexplearn, PDO::PARAM_STR);
            $resultInitiative->bindParam(':gradexplearn', $gradexplearn, PDO::PARAM_STR);
            $resultInitiative->bindParam(':afford', $afford, PDO::PARAM_STR);
            $resultInitiative->bindParam(':reputation', $reputation, PDO::PARAM_STR);
            $resultInitiative->bindParam(':challenges', $challenges, PDO::PARAM_STR);
            $resultInitiative->bindParam(':supinfopath', $supinfopath, PDO::PARAM_STR);

            if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

                $resultInitiative->bindParam(":author", $this->author, PDO::PARAM_STR);
                $resultInitiative->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
                $resultInitiative->bindParam(':contentlink_id', $this->contentLinkId, PDO::PARAM_STR);
                $resultInitiative->bindParam(":author", $this->author, PDO::PARAM_STR);
                $resultInitiative->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
                $resultInitiative->bindParam(':bpid', $this->bpid, PDO::PARAM_STR);
            }
            if ($resultInitiative->execute()) {

                $this->message = "Initiatives & Observations Updated Succesfully.";
            } else {
                $this->message = "Initiatives & Observations could not be Updated.";
            }
        }
        return $this->message;
    }

    public function PlaceHolderValue()
    {
        try {
            $sqlexvalue = "SELECT * FROM `AC_InitObsrv` WHERE OU_ABBREV = :ouabbrev AND ID_INITIATIVES_OBSERVATIONS IN
(SELECT MAX(ID_INITIATIVES_OBSERVATIONS) FROM `AC_InitObsrv` WHERE OUTCOMES_AY = :bpayname GROUP BY OU_ABBREV)";

            $resultexvalue = $this->connection->prepare($sqlexvalue);
            $resultexvalue->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultexvalue->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);

            $resultexvalue->execute();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultexvalue;
    }
}

Class COMMUNITYENGAGEMENT extends BPCONTENTS
{
    public function SaveDraft()
    {
        $this->time = date('Y-m-d H:i:s');
        $this->contentLinkId = $_GET['linkid'];
        $communityEngage = Initialize::mynl2br($_POST['cmmtyEngage']);
        $communityPerception = Initialize::mynl2br($_POST['cmmtyPerception']);
        $facultyEngagement = Initialize::mynl2br($_POST['facultyEngagement']);


        if ($_FILES['supinfo']['tmp_name'] != "") {
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/" . $_SESSION['site'] . "/uploads/communityEngagement/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755);
            }
            $target_file = $target_dir . basename($_FILES["supinfo"]["name"]);
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);


            if ($imageFileType != "pdf") {
                $this->message = "Sorry, only PDf files are allowed.";
                $this->errorflag = 1;

            } else {
                if (move_uploaded_file($_FILES["supinfo"]["tmp_name"], $target_file)) {
                    $supinfopath = $target_file;
                } else {
                    $this->message = "Sorry, there was an error uploading your file.";
                    $this->errorflag = 1;
                }
            }
        }
        if ($this->errorflag != 1) {

            $sqlcommunityEng = "INSERT INTO `AC_CommunityEngage`(OU_ABBREV, OUTCOMES_AY, CMMTY_ENGMNT_ACTVTY, 
            ENGAGE_CMMTY_PERCEPTIONS, ENGAGE_FACULTY_INCTV, SUPPL_CMTY_ENGMNTS) VALUES (:ouabbrev, :bpayname, 
            :communityEngage, :communityPerception, :facultyEngagement, :supinfopath);";


            if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

                $sqlcommunityEng .= "UPDATE `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR = :author,
            MOD_TIMESTAMP = :timestampmod WHERE ID_CONTENT =:contentlink_id ;";

                $sqlcommunityEng .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress',
BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR = :author, LastModified = :timestampmod WHERE ID_BROADCAST = :bpid ; ";
            }

            $resultInitiative = $this->connection->prepare($sqlcommunityEng);

            $resultInitiative->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultInitiative->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
            $resultInitiative->bindParam(':communityEngage', $communityEngage, PDO::PARAM_STR);
            $resultInitiative->bindParam(':communityPerception', $communityPerception, PDO::PARAM_STR);
            $resultInitiative->bindParam(':facultyEngagement', $facultyEngagement, PDO::PARAM_STR);
            $resultInitiative->bindParam(':supinfopath', $supinfopath, PDO::PARAM_STR);

            if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

                $resultInitiative->bindParam(":author", $this->author, PDO::PARAM_STR);
                $resultInitiative->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
                $resultInitiative->bindParam(':contentlink_id', $this->contentLinkId, PDO::PARAM_STR);
                $resultInitiative->bindParam(":author", $this->author, PDO::PARAM_STR);
                $resultInitiative->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
                $resultInitiative->bindParam(':bpid', $this->bpid, PDO::PARAM_STR);
            }
            if ($resultInitiative->execute()) {

                $this->message = "Community Engagement Updated Succesfully.";
            } else {
                $this->message = "Community Engagement could not be Updated.";
            }
        }
        return $this->message;
    }

    public function PlaceHolderValue()
    {
        try {
            $sqlexvalue = "SELECT * FROM `AC_CommunityEngage` WHERE OU_ABBREV = :ouabbrev AND ID_COMMUNITY_ENGAGEMENT IN
(SELECT MAX(ID_COMMUNITY_ENGAGEMENT) FROM `AC_CommunityEngage` WHERE OUTCOMES_AY = :bpayname GROUP BY OU_ABBREV)";

            $resultexvalue = $this->connection->prepare($sqlexvalue);
            $resultexvalue->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultexvalue->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);

            $resultexvalue->execute();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultexvalue;
    }
}

Class FACULTYAWARDS extends BPCONTENTS
{
    public function SaveDraft()
    {
        $this->time = date('Y-m-d H:i:s');
        $this->contentLinkId = $_GET['linkid'];

        $award_id = $_GET['award_id'];
        $awardType = $_POST['awardType'];
        $awardLoc = $_POST['awardLoc'];
        $recipLname = $_POST['recipLname'];
        $recipFname = $_POST['recipFname'];
        $awardTitle = $_POST['awardTitle'];
        $awardOrg = $_POST['awardOrg'];
        $dateAward = $_POST['dateAward'];

        $sqlAcFacAward = "UPDATE `AC_FacultyAwards` SET OUTCOMES_AUTHOR = :author, MOD_TIMESTAMP = :timeStampmod, 
AWARD_TYPE = :awardType, AWARD_LOCATION = :awardLoc, RECIPIENT_NAME_LAST = :recipLname, 
RECIPIENT_NAME_FIRST = :recipFname, AWARD_TITLE = :awardTitle, AWARDING_ORG = :awardOrg, DATE_AWARDED = :dateAward 
WHERE ID_FACULTY_AWARDS = :award_id ;";

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

            $sqlAcFacAward .= "UPDATE `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR = :author,
            MOD_TIMESTAMP = :timestampmod WHERE ID_CONTENT =:contentlink_id ;";

            $sqlAcFacAward .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress',
BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR = :author, LastModified = :timestampmod WHERE ID_BROADCAST = :bpid ; ";
        }

        $resultFacultyAwards = $this->connection->prepare($sqlAcFacAward);

        $resultFacultyAwards->bindParam(":author", $this->author, PDO::PARAM_STR);
        $resultFacultyAwards->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);

        $resultFacultyAwards->bindParam(":awardType", $awardType, PDO::PARAM_STR);
        $resultFacultyAwards->bindParam(":awardLoc", $awardLoc, PDO::PARAM_STR);
        $resultFacultyAwards->bindParam(':recipLname', $recipLname, PDO::PARAM_STR);
        $resultFacultyAwards->bindParam(':recipFname', $recipFname, PDO::PARAM_STR);
        $resultFacultyAwards->bindParam(':awardTitle', $awardTitle, PDO::PARAM_STR);
        $resultFacultyAwards->bindParam(':awardOrg', $awardOrg, PDO::PARAM_STR);
        $resultFacultyAwards->bindParam(':dateAward', $dateAward, PDO::PARAM_STR);
        $resultFacultyAwards->bindParam(':award_id', $award_id, PDO::PARAM_INT);

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

            $resultFacultyAwards->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultFacultyAwards->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
            $resultFacultyAwards->bindParam(':contentlink_id', $this->contentLinkId, PDO::PARAM_STR);
            $resultFacultyAwards->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultFacultyAwards->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
            $resultFacultyAwards->bindParam(':bpid', $this->bpid, PDO::PARAM_STR);
        }
        if ($resultFacultyAwards->execute()) {

            $this->message = "Award Updated Succesfully.";
        } else {
            $this->message = "Award Could not be Updated.";
        }
        return $this->message;
    }

    public function PlaceHolderValue()
    {
        try {
            $award_id = $_GET['award_id'];
            $sqlexvalue = "SELECT * FROM `AC_FacultyAwards` WHERE ID_FACULTY_AWARDS = :id ;";
            $resultexvalue = $this->connection->prepare($sqlexvalue);
            $resultexvalue->bindParam(":id", $award_id, PDO::PARAM_INT);
            $resultexvalue->execute();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultexvalue;
    }
}

Class FACULTYNOMINATIONS extends BPCONTENTS
{
    public function SaveDraft()
    {
        $this->time = date('Y-m-d H:i:s');
        $this->contentLinkId = $_GET['linkid'];

        $award_id = $_GET['award_id'];
        $awardType = $_POST['awardType'];
        $awardLoc = $_POST['awardLoc'];
        $recipLname = $_POST['recipLname'];
        $recipFname = $_POST['recipFname'];
        $awardTitle = $_POST['awardTitle'];
        $awardOrg = $_POST['awardOrg'];
        $dateNominated = $_POST['dateNominated'];

        $sqlAcFacAward = "UPDATE `AC_FacultyNominations` SET OUTCOMES_AUTHOR = :author, MOD_TIMESTAMP = :timeStampmod, 
AWARD_TYPE = :awardType, AWARD_LOCATION = :awardLoc, RECIPIENT_NAME_LAST = :recipLname, 
RECIPIENT_NAME_FIRST = :recipFname, AWARD_TITLE = :awardTitle, AWARDING_ORG = :awardOrg, DATE_NOMINATED = :dateNominated 
WHERE ID_FACULTY_NOMINATIONS = :award_id ;";

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

            $sqlAcFacAward .= "UPDATE `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR = :author,
            MOD_TIMESTAMP = :timestampmod WHERE ID_CONTENT =:contentlink_id ;";

            $sqlAcFacAward .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress',
BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR = :author, LastModified = :timestampmod WHERE ID_BROADCAST = :bpid ; ";
        }

        $resultFacultyAwards = $this->connection->prepare($sqlAcFacAward);

        $resultFacultyAwards->bindParam(":author", $this->author, PDO::PARAM_STR);
        $resultFacultyAwards->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);

        $resultFacultyAwards->bindParam(":awardType", $awardType, PDO::PARAM_STR);
        $resultFacultyAwards->bindParam(":awardLoc", $awardLoc, PDO::PARAM_STR);
        $resultFacultyAwards->bindParam(':recipLname', $recipLname, PDO::PARAM_STR);
        $resultFacultyAwards->bindParam(':recipFname', $recipFname, PDO::PARAM_STR);
        $resultFacultyAwards->bindParam(':awardTitle', $awardTitle, PDO::PARAM_STR);
        $resultFacultyAwards->bindParam(':awardOrg', $awardOrg, PDO::PARAM_STR);
        $resultFacultyAwards->bindParam(':dateNominated', $dateNominated, PDO::PARAM_STR);
        $resultFacultyAwards->bindParam(':award_id', $award_id, PDO::PARAM_INT);

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

            $resultFacultyAwards->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultFacultyAwards->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
            $resultFacultyAwards->bindParam(':contentlink_id', $this->contentLinkId, PDO::PARAM_STR);
            $resultFacultyAwards->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultFacultyAwards->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
            $resultFacultyAwards->bindParam(':bpid', $this->bpid, PDO::PARAM_STR);
        }
        if ($resultFacultyAwards->execute()) {

            $this->message = "Nominations Updated Succesfully.";
        } else {
            $this->message = "Nominations Could not be Updated.";
        }
        return $this->message;
    }

    public function PlaceHolderValue()
    {
        try {
            $award_id = $_GET['award_id'];
            $sqlexvalue = "SELECT * FROM `AC_FacultyNominations` WHERE ID_FACULTY_NOMINATIONS = :id ;";
            $resultexvalue = $this->connection->prepare($sqlexvalue);
            $resultexvalue->bindParam(":id", $award_id, PDO::PARAM_INT);
            $resultexvalue->execute();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultexvalue;
    }
}

Class CONCLUDINGREMARKS extends BPCONTENTS
{
    public function SaveDraft()
    {
        $this->time = date('Y-m-d H:i:s');
        $this->contentLinkId = $_GET['linkid'];

        $quantOutcomes = Initialize::mynl2br($_POST['quantOutcomes']);
        $remarkCoolstuff = Initialize::mynl2br($_POST['coolStuff']);

        $sql = "INSERT INTO `AC_ConcludingRemarks` (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP, 
REMARKS_QUANT_OUTCOMES, REMARKS_COOLSTUFF) VALUES (:ouabbrev, :bpayname, :author, :timeStampmod, :remarksquant, 
:cool);";

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

            $sql .= "UPDATE `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR = :author,
            MOD_TIMESTAMP = :timestampmod WHERE ID_CONTENT =:contentlink_id ;";

            $sql .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress',
BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR = :author, LastModified = :timestampmod WHERE ID_BROADCAST = :bpid ; ";
        }

        $result = $this->connection->prepare($sql);

        $result->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
        $result->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
        $result->bindParam(":author", $this->author, PDO::PARAM_STR);
        $result->bindParam(":timeStampmod", $this->time, PDO::PARAM_STR);
        $result->bindParam(":remarksquant", $quantOutcomes, PDO::PARAM_STR);
        $result->bindParam(":cool", $remarkCoolstuff, PDO::PARAM_STR);

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

            $result->bindParam(":author", $this->author, PDO::PARAM_STR);
            $result->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
            $result->bindParam(':contentlink_id', $this->contentLinkId, PDO::PARAM_STR);
            $result->bindParam(":author", $this->author, PDO::PARAM_STR);
            $result->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
            $result->bindParam(':bpid', $this->bpid, PDO::PARAM_STR);
        }
        if ($result->execute()) {

            $this->message = "Concluding Remark updated sucessfully..";
        } else {
            $this->message = "Concluding Remark Could not be Updated.";
        }
        return $this->message;
    }

    public function PlaceHolderValue()
    {
        try {
            $sqlexvalue = "SELECT * FROM `AC_ConcludingRemarks` WHERE OU_ABBREV = :ouabbrev AND ID_CONCLUDING_REMARKS IN
(SELECT MAX(ID_CONCLUDING_REMARKS) FROM `AC_ConcludingRemarks` WHERE OUTCOMES_AY = :bpayname GROUP BY OU_ABBREV)";

            $resultexvalue = $this->connection->prepare($sqlexvalue);
            $resultexvalue->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultexvalue->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);

            $resultexvalue->execute();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultexvalue;
    }
}

Class RECRUITRETENTION extends BPCONTENTS
{
    public function SaveDraft()
    {
        $this->time = date('Y-m-d H:i:s');
        $this->contentLinkId = $_GET['linkid'];

        $recruitment = Initialize::mynl2br($_POST['recruitment']);
        $retention = Initialize::mynl2br($_POST['retention']);

        $sql = "INSERT INTO `AC_RecruitReten` (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP, 
        STUDENT_RECRUITMENT_EFFORTS,STUDENT_RETENTION_EFFORTS) VALUES (:ouabbrev, :bpayname, :author, :timeStampmod, 
        :recruitment, :retention);";

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

            $sql .= "UPDATE `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR = :author,
            MOD_TIMESTAMP = :timeStampmod WHERE ID_CONTENT =:contentlink_id ;";

            $sql .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress',
BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR = :author, LastModified = :timeStampmod WHERE ID_BROADCAST = :bpid ; ";
        }

        $result = $this->connection->prepare($sql);

        $result->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
        $result->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
        $result->bindParam(":author", $this->author, PDO::PARAM_STR);
        $result->bindParam(":timeStampmod", $this->time, PDO::PARAM_STR);
        $result->bindParam(":recruitment", $recruitment, PDO::PARAM_STR);
        $result->bindParam(":retention", $retention, PDO::PARAM_STR);

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

            $result->bindParam(":author", $this->author, PDO::PARAM_STR);
            $result->bindParam(":timeStampmod", $this->time, PDO::PARAM_STR);
            $result->bindParam(':contentlink_id', $this->contentLinkId, PDO::PARAM_STR);
            $result->bindParam(":author", $this->author, PDO::PARAM_STR);
            $result->bindParam(":timeStampmod", $this->time, PDO::PARAM_STR);
            $result->bindParam(':bpid', $this->bpid, PDO::PARAM_STR);
        }
        if ($result->execute()) {

            $this->message = "Recruitment & retention updated successfully. ";
        } else {
            $this->message = "Recruitment & retention could not be updated.";
        }
        return $this->message;
    }

    public function PlaceHolderValue()
    {
        try {
            $sqlexvalue = "SELECT * FROM `AC_RecruitReten` WHERE OU_ABBREV = :ouabbrev AND ID_FACULTY_INFO IN
(SELECT MAX(ID_FACULTY_INFO) FROM `AC_RecruitReten` WHERE OUTCOMES_AY = :bpayname GROUP BY OU_ABBREV)";

            $resultexvalue = $this->connection->prepare($sqlexvalue);
            $resultexvalue->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultexvalue->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);

            $resultexvalue->execute();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultexvalue;
    }
}

Class TEACHING extends BPCONTENTS
{
    public function SaveDraft()
    {
        $this->time = date('Y-m-d H:i:s');
        $this->contentLinkId = $_GET['linkid'];

        $facStuRatio = Initialize::mynl2br($_POST['FACULTY_STUDENT_RATIO']);
        $facStuRatioNarrative = Initialize::mynl2br($_POST['FACULTY_STUDENT_RATIO_NARRTV']);

        $sql = "INSERT INTO `AC_Teaching` (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP, 
       FACULTY_STUDENT_RATIO,FACULTY_STUDENT_RATIO_NARRTV ) VALUES (:ouabbrev, :bpayname, :author, :timeStampmod, 
        :facStuRatio, :facStuRatioNarrative);";

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

            $sql .= "UPDATE `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR = :author,
            MOD_TIMESTAMP = :timeStampmod WHERE ID_CONTENT =:contentlink_id ;";

            $sql .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress',
BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR = :author, LastModified = :timeStampmod WHERE ID_BROADCAST = :bpid ; ";
        }

        $result = $this->connection->prepare($sql);

        $result->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
        $result->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
        $result->bindParam(":author", $this->author, PDO::PARAM_STR);
        $result->bindParam(":timeStampmod", $this->time, PDO::PARAM_STR);
        $result->bindParam(":facStuRatio", $facStuRatio, PDO::PARAM_STR);
        $result->bindParam(":facStuRatioNarrative", $facStuRatioNarrative, PDO::PARAM_STR);

        if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

            $result->bindParam(":author", $this->author, PDO::PARAM_STR);
            $result->bindParam(":timeStampmod", $this->time, PDO::PARAM_STR);
            $result->bindParam(':contentlink_id', $this->contentLinkId, PDO::PARAM_STR);
            $result->bindParam(":author", $this->author, PDO::PARAM_STR);
            $result->bindParam(":timeStampmod", $this->time, PDO::PARAM_STR);
            $result->bindParam(':bpid', $this->bpid, PDO::PARAM_STR);
        }
        if ($result->execute()) {

            $this->message = "Recruitment & retention updated successfully. ";
        } else {
            $this->message = "Recruitment & retention could not be updated.";
        }
        return $this->message;
    }

}