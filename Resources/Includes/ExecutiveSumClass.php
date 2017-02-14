<?php

require "../Includes/BpContents.php";

  Class EXECUTIVESUMCLASS extends BPCONTENTS {

//    private $connection;

//    function __construct(){
//
//      //getting the connection object
//        $this->connection = new PDO(sprintf('mysql:host=%s;dbname=%s', HOSTNAME, DB), USERNAME, PASSCODE);
//        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//        $this->errorflag = 0;
//        $this->message = array();
//
//    }

    public function saveDraft($ouabbrev,$time,$author,$contentlink_id,$bpayname)
    {

      $collname = $_POST['college-school-input'];
      $deanname = $_POST['deans-name-input'];
      $deantitle = $_POST['deans-title-input'];
      $introduction = mynl2br($_POST['introduction-input']);
      $highlights = mynl2br($_POST['highlights-input']);


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

          $sqlexecsum .= "UPDATE  `BpContents` set CONTENT_STATUS = 'In Progress', BP_AUTHOR= :author,
MOD_TIMESTAMP =:timestampmod  where ID_CONTENT =:contentlink_id;";

          $sqlexecsum .= "UPDATE  `broadcast` set BROADCAST_STATUS = 'In Progress', 
BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= :author, LastModified =:timestampmod where ID_BROADCAST = :bpid; ";

          $execsum = $this->connection->prepare($sqlexecsum);
          $execsum->bindParam(":ouabbrev", $ouabbrev, PDO::PARAM_STR);
          $execsum->bindParam(":bpayname", $bpayname, PDO::PARAM_STR);
          $execsum->bindParam(":author", $author, PDO::PARAM_STR);
          $execsum->bindParam(":timestampmod", $time, PDO::PARAM_STR);
          $execsum->bindParam(":collname", $collname, PDO::PARAM_STR);
          $execsum->bindParam(":deanname", $deanname, PDO::PARAM_STR);
          $execsum->bindParam(":deantitle", $deantitle, PDO::PARAM_STR);
          $execsum->bindParam(":deansPortraitLogopath", $deansPortraitLogopath, PDO::PARAM_STR);
          $execsum->bindParam(":deansPortraitSignpath", $deansPortraitSignpath, PDO::PARAM_STR);
          $execsum->bindParam(":deansSchLogopath", $deansSchLogopath, PDO::PARAM_STR);
          $execsum->bindParam(":introduction", $introduction, PDO::PARAM_STR);
          $execsum->bindParam(":highlights", $highlights, PDO::PARAM_STR);
          $execsum->bindParam(":author", $author, PDO::PARAM_STR);
          $execsum->bindParam(":timestampmod", $time, PDO::PARAM_STR);
          $execsum->bindParam(":contentlink_id", $contentlink_id, PDO::PARAM_STR);
          $execsum->bindParam(":author", $author, PDO::PARAM_STR);
          $execsum->bindParam(":timestampmod", $time, PDO::PARAM_STR);
          $execsum->bindParam(":bpid", $bpid, PDO::PARAM_STR);

          if ($execsum->execute()) {
            $this->message[0] = "Executive Summary Info Added Succesfully.";
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

//    public function submitApproval()
//    {
//
//      $contentlink_id = $_GET['linkid'];
//
//      try {
//
//        $sqlfacinfo .= "UPDATE `BpContents` SET CONTENT_STATUS = 'Pending Dean Approval',
//BP_AUTHOR= :author ,MOD_TIMESTAMP =:time  where ID_CONTENT = :contentlink_id;";
//
//        $sqlfacinforesult = $this->connection->prepare($sqlfacinfo);
//        $sqlfacinforesult->bindParam(":author", $author, PDO::PARAM_STR);
//        $sqlfacinforesult->bindParam(":time", $time, PDO::PARAM_STR);
//        $sqlfacinforesult->bindParam(":contentlink_id", $contentlink_id, PDO::PARAM_STR);
//
//        if ($sqlfacinforesult->execute()) {
//
//          $error[0] = "Executive Summary Info submitted Successfully";
//
//        } else {
//
//          $error[0] = "Executive Summary Info Could not be submitted. Please Retry.";
//
//        }
//
//      } catch (PDOException $e){
//
//        error_log($e->getMessage());
//        //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
//
//      }
//
//    }

//    public function approve()
//    {
//
//      $contentlink_id = $_GET['linkid'];
//
//      try {
//
//        $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Approved', BP_AUTHOR = :author, MOD_TIMESTAMP = :time  where ID_CONTENT =:contentlink_id; ";
//
//        $sqlmissionresult = $this->connection->prepare($sqlmission);
//        $sqlmissionresult->bindParam(":author", $author, PDO::PARAM_STR);
//        $sqlmissionresult->bindParam(":time", $time, PDO::PARAM_STR);
//        $sqlmissionresult->bindParam(":contentlink_id", $contentlink_id, PDO::PARAM_STR);
//
//        if ($sqlmissionresult->execute()) {
//
//          $error[0] = "Executive Summary Info Approved Successfully";
//
//        } else {
//
//          $error[0] = "Executive Summary Info Could not be Approved. Please Retry.";
//
//        }
//
//      } catch (PDOException $e){
//
//        error_log($e->getMessage());
//        //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
//
//      }
//
//    }
//
//    public function reject()
//    {
//
//      $contentlink_id = $_GET['linkid'];
//
//      try {
//
//        $sqlmission = "UPDATE `BpContents` SET CONTENT_STATUS = 'Dean Rejected', BP_AUTHOR = :author, MOD_TIMESTAMP =:timestampmod
//        where ID_CONTENT = :contentlink_id; ";
//
//        $sqlmissionresult = $this->connection->prepare($sqlmission);
//        $sqlmissionresult->bindParam(":author", $author, PDO::PARAM_STR);
//        $sqlmissionresult->bindParam(":time", $time, PDO::PARAM_STR);
//        $sqlmissionresult->bindParam(":contentlink_id", $contentlink_id, PDO::PARAM_STR);
//
//        if ($sqlmissionresult->execute()) {
//
//          $error[0] = "Executive Summary Info Rejected Successfully";
//
//        } else {
//
//          $error[0] = "Executive Summary Info Could not be Rejected. Please Retry.";
//
//        }
//
//      } catch (PDOException $e){
//
//        error_log($e->getMessage());
//
//      }
//
//    }

  }
