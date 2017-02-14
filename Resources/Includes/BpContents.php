<?php

  Class BPCONTENTS
  {

      protected $connection;
      public $errorflag, $message, $author, $time, $contentLinkId, $bpayname, $ouabbrev, $ouid, $bpid;

      function __construct()
      {
          //getting the connection object
          $this->connection = new PDO(sprintf('mysql:host=%s;dbname=%s', HOSTNAME, DB), USERNAME, PASSCODE);
          $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
          try
          {
              $sqlbpstatus = "SELECT CONTENT_STATUS FROM `BpContents` WHERE ID_CONTENT = :contentlink_id ;";
              $resultbpstatus = $this->connection->prepare($sqlbpstatus);
              $resultbpstatus->bindParam(':contentlink_id', $this->contentLinkId, 2);
              $resultbpstatus->execute();
          }
          catch (PDOException $e)
          {
              //SYSTEM::pLog($e->__toString(),$_SERVER['PHP_SELF']);
              error_log($e->getMessage());
          }
          return $resultbpstatus;
      }

      public function SubmitApproval()
      {
          $this->contentLinkId = $_GET['linkid'];
          $this->time = date('Y-m-d H:i:s');

          try
          {
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

          } catch (PDOException $e){

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
              MOD_TIMESTAMP = :timeStampmod where ID_CONTENT =:contentlink_id; ";

              $resultAprove = $this->connection->prepare($sqlAprove);
              $resultAprove->bindParam(":author", $this->author, PDO::PARAM_STR);
              $resultAprove->bindParam(":timeStampmod", $this->time, PDO::PARAM_STR);
              $resultAprove->bindParam(":contentlink_id", $this->contentLinkId, PDO::PARAM_STR);

              if ($resultAprove->execute()) {

                  $this->message = " Info Approved Successfully";

              } else {

                  $this->message = " Info Could not be Approved. Please Retry.";

              }

          } catch (PDOException $e){

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
              MOD_TIMESTAMP =:timestampmod WHERE ID_CONTENT = :contentlink_id; ";

              $resultReject = $this->connection->prepare($sqlReject);
              $resultReject->bindParam(":author", $this->author, PDO::PARAM_STR);
              $resultReject->bindParam(":timeStampmod", $this->time, PDO::PARAM_STR);
              $resultReject->bindParam(":contentlink_id", $this->contentLinkId, PDO::PARAM_STR);

              if ($resultReject->execute()) {
                  $this->message = " Info Rejected Successfully";
              } else {
                  $this->message = " Info Could not be Rejected. Please Retry.";

              }

          } catch (PDOException $e){

              error_log($e->getMessage());

          }
          return $this->message;
      }

      public function BlueprintStatusDisplay()
      {
          $ouid = $_SESSION['login_ouid'];
          try
          {
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

          }
          catch (PDOException $e)
          {
              error_log($e->getMessage());
              //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
          }
          return $resultbroad;
      }

  }
Class EXECUTIVESUMCLASS extends BPCONTENTS
{

    public function SaveDraft()
    {

        $collname = $_POST['college-school-input'];
        $deanname = $_POST['deans-name-input'];
        $deantitle = $_POST['deans-title-input'];
        $introduction = mynl2br($_POST['introduction-input']);
        $highlights = mynl2br($_POST['highlights-input']);
        $bpid = $_SESSION['bpid'];
        $this->time = date('Y-m-d H:i:s');

        if ($_FILES['deans-portrait-logo']['tmp_name'] != "") {

            $target_dir = "../uploads/exec_summary/";
            $target_file_port = $target_dir . basename($_FILES["deans-portrait-logo"]["name"]);
            $deansPortraitLogoTmpDir = $_FILES["deans-portrait-logo"]["name"];
            $portsize = getimagesize($_FILES["deans-portrait-logo"]["name"]);

            if (exif_imagetype($deansPortraitLogoTmpDir) == IMAGETYPE_GIF ||
                exif_imagetype($deansPortraitLogoTmpDir) == IMAGETYPE_JPEG ||
                exif_imagetype($deansPortraitLogoTmpDir) == IMAGETYPE_PNG) {

                if ($portsize[0] == 250 && $portsize[1] == 250) {

                    if (move_uploaded_file($_FILES["deans-portrait-logo"]["tmp_name"], $target_file_port)) {

                        $this->deansPortraitLogopath = $target_file_port;

                    } else {
                        $this->message[1] = "Sorry, there was an error uploading your file.";
                    }

                } else {
                    $this->message[1] = "Only 250 X 250 pixel files are allowed.";
                    $this->errorflag = 1;
                }

            } else {
                $this->message[1] = "Sorry, only GIF, JPEG or PNG files are allowed.";
                $this->errorflag = 1;
            }

        }

        if ($_FILES['deans-signature-logo']['tmp_name'] != "") {

            $target_dir = "../uploads/exec_summary/";
            $target_file_sign = $target_dir . basename($_FILES["deans-signature-logo"]["name"]);
            $deansPortraitSignTmpDir = $_FILES["deans-signature-logo"]["name"];
            $signsize = getimagesize($_FILES["deans-signature-logo"]["name"]);

            if (exif_imagetype($deansPortraitSignTmpDir) == IMAGETYPE_GIF ||
                exif_imagetype($deansPortraitSignTmpDir) == IMAGETYPE_JPEG ||
                exif_imagetype($deansPortraitSignTmpDir) == IMAGETYPE_PNG) {

                if ($signsize[0] == 250 && $signsize[1] == 75) {

                    if (move_uploaded_file($_FILES["deans-signature-logo"]["tmp_name"], $target_file_sign)) {

                        $this->deansPortraitSignpath = $target_file_sign;

                    } else {

                        $this->message[2] = "Sorry, there was an error uploading your file.";

                    }

                } else {

                    $this->message[1] = "Only 250 X 75 pixel files are allowed.";
                    $this->errorflag = 1;

                }

            } else {

                $this->message[1] = "Sorry, only GIF, JPEG or PNG files are allowed.";
                $this->errorflag = 1;

            }

        }

        if ($_FILES['deans-college-school-logo']['tmp_name'] != "") {

            $target_dir = "../uploads/exec_summary/";
            $target_file_sch_logo = $target_dir . basename($_FILES["deans-college-school-logo"]["name"]);
            $deansSchoolLogoTmpDir = $_FILES["deans-college-school-logo"]["name"];
            //$imagedimension = getimagesize($_FILES["deans-college-school-logo"]["name"]);

            if (exif_imagetype($deansSchoolLogoTmpDir) == IMAGETYPE_GIF ||
                exif_imagetype($deansSchoolLogoTmpDir) == IMAGETYPE_JPEG ||
                exif_imagetype($deansSchoolLogoTmpDir) == IMAGETYPE_PNG){

                if (move_uploaded_file($_FILES["deans-college-school-logo"]["tmp_name"], $target_file_sch_logo)) {
                    $this->deansSchLogopath = $target_file_sch_logo;
                } else {
                    $this->message[3] = "Sorry, there was an error uploading your file.";
                }

            } else {

                $this->message[1] = "Sorry, only GIF, JPEG or PNG files are allowed.";
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
                    $sqlexecsum .= "UPDATE  `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR= :author,
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
                    $this->message[0] = "Executive Summary Info Added Successfully.";
                } else {
                    $this->message[0] = "Executive Summary Info could not be added.";
                }

            } catch (PDOException $e){
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
        $programranking = mynl2br($_POST['programranking']);
        $instructionalmodalities = mynl2br($_POST['instructionalmodalities']);
        $launch = mynl2br($_POST['launch']);
        $programterminations = mynl2br($_POST['programterminators']);

        if ($_FILES['supinfo']['tmp_name'] != "") {
            $target_dir = "../uploads/ac_programs/";
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