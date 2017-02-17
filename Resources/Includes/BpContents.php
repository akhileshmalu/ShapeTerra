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
        $this->time = date('Y-m-d H:i:s');
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

// Class FACULTYAWARDS extends BPCONTENTS
// {
//     public function SubmitDraft()
//     {

//       $awardType = $_POST['awardType'];
//       $awardLoc = $_POST['awardLoc'];
//       $recipLname = $_POST['recipLname'];
//       $recipFname = $_POST['recipFname'];
//       $awardTitle = $_POST['awardTitle'];
//       $awardOrg = $_POST['awardOrg'];
//       $dateAward = $_POST['dateAward'];
//       $contentlink_id = $_GET['linkid'];

//       $sqlAcFacAward = "INSERT INTO `AC_FacultyAwards`
//                           (OU_ABBREV,OUTCOMES_AY,OUTCOMES_AUTHOR,MOD_TIMESTAMP,AWARD_TYPE,AWARD_LOCATION,RECIPIENT_NAME_LAST,
//                           RECIPIENT_NAME_FIRST,AWARD_TITLE,timestampmod,DATE_AWARDED,ID_SORT)
//                           VALUES(:ouabbrev,:bpayname,:author,:timestampmod,:awardType,:awardLoc,:recipLname,:recipFname,
//                           :awardTitle,:awardOrg,:dateAward,'0');";

//       $sqlAcFacAward .= "UPDATE `BpContents` set CONTENT_STATUS = 'In progress', BP_AUTHOR = :author,MOD_TIMESTAMP =:timestampmod  
//                           where ID_CONTENT =:contentlink_id;";

//       $sqlAcFacAward .= "UPDATE  `broadcast` set BROADCAST_STATUS = 'In Progress', BROADCAST_STATUS_OTHERS = 'In Progress', 
//                           AUTHOR= :author, LastModified =:timestampmod where ID_BROADCAST = :bpid; ";

//       $sqlACFacAwardResult = $this->connection->prepare($sqlAcFacAward);
//       $sqlACFacAwardResult->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
//       $sqlACFacAwardResult->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
//       $sqlACFacAwardResult->bindParam(":author", $this->author, PDO::PARAM_STR);
//       $sqlACFacAwardResult->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
//       $sqlACFacAwardResult->bindParam(':awardType', $awardType, PDO::PARAM_STR);
//       $sqlACFacAwardResult->bindParam(':awardLoc', $awardLoc, PDO::PARAM_STR);
//       $sqlACFacAwardResult->bindParam(':recipLname', $recipLname, PDO::PARAM_STR);
//       $sqlACFacAwardResult->bindParam(':recipFname', $recipFname, PDO::PARAM_STR);
//       $sqlACFacAwardResult->bindParam(':awardTitle', $awardTitle, PDO::PARAM_STR);
//       $sqlACFacAwardResult->bindParam(':awardOrg', $awardOrg, PDO::PARAM_STR);
//       $sqlACFacAwardResult->bindParam(':dateAward', $dateAward, PDO::PARAM_STR);

//       if($mysqli->multi_query($sqlAcFacAward)){

//           $Data = new Data();
//           $Data->initOrderFacultyAwards();
//           $message[0] = "Award Added Succesfully.";

//       } else {
//           $message[0] = "Award Could not be Added.";
//       }


//         $programranking = mynl2br($_POST['programranking']);
//         $instructionalmodalities = mynl2br($_POST['instructionalmodalities']);
//         $launch = mynl2br($_POST['launch']);
//         $programterminations = mynl2br($_POST['programterminators']);

//         if ($_FILES['supinfo']['tmp_name'] != "") {
//             $target_dir = "../uploads/ac_programs/";
//             $target_file = $target_dir . basename($_FILES["supinfo"]["name"]);
//             $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
//             $imagedimension = getimagesize($_FILES["supinfo"]["name"]);

//             if ($imageFileType != "pdf") {
//                 $this->message = "Sorry, only PDF files are allowed.";
//                 $this->errorflag = 1;

//             } else {
//                 if (move_uploaded_file($_FILES["supinfo"]["tmp_name"], $target_file)) {
//                     $supinfopath = $target_file;
//                 } else {
//                     $this->message = "Sorry, there was an error uploading your file.";
//                     $this->errorflag = 1;
//                 }
//             }
//         }
//         if ($this->errorflag != 1) {

//             $sqlacprogram = "INSERT INTO AC_Programs (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP, 
//         PROGRAM_RANKINGS, INSTRUCT_MODALITIES, PROGRAM_LAUNCHES, PROGRAM_TERMINATIONS, AC_SUPPL_PROGRAMS) VALUES 
//         (:ouabbrev, :bpayname, :author, :timestampmod, :programranking, :instructionalmodalities, :launch, 
// :programterminations, :supinfopath);";

//             if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {
//                 $sqlacprogram .= "UPDATE `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR= :author, 
//             MOD_TIMESTAMP = :timestampmod WHERE ID_CONTENT =:contentlink_id ;";

//                 $sqlacprogram .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress', 
// BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= :author, LastModified = :timestampmod WHERE ID_BROADCAST = :bpid ; ";
//             }

//             $resultacprogram = $this->connection->prepare($sqlacprogram);
//             $resultacprogram->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
//             $resultacprogram->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
//             $resultacprogram->bindParam(":author", $this->author, PDO::PARAM_STR);
//             $resultacprogram->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
//             $resultacprogram->bindParam(':programranking', $programranking, PDO::PARAM_STR);
//             $resultacprogram->bindParam(':instructionalmodalities', $instructionalmodalities, PDO::PARAM_STR);
//             $resultacprogram->bindParam(':launch', $launch, PDO::PARAM_STR);
//             $resultacprogram->bindParam(':programterminations', $programterminations, PDO::PARAM_STR);
//             $resultacprogram->bindParam(':supinfopath', $supinfopath, PDO::PARAM_STR);

//             if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

//                 $resultacprogram->bindParam(":author", $this->author, PDO::PARAM_STR);
//                 $resultacprogram->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
//                 $resultacprogram->bindParam(':contentlink_id', $this->contentLinkId, PDO::PARAM_STR);
//                 $resultacprogram->bindParam(":author", $this->author, PDO::PARAM_STR);
//                 $resultacprogram->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
//                 $resultacprogram->bindParam(':bpid', $this->bpid, PDO::PARAM_STR);
//             }

//             if ($resultacprogram->execute()) {
//                 $this->message = "Academic Program Info Added Succesfully.";
//             } else {
//                 $this->message = "Academic Program Info could not be added.";
//             }
//         }
//         return $this->message;
//     }

//     public function PlaceHolderValue()
//     {
//         try {
//             $sqlexvalue = "SELECT * FROM `AC_Programs` WHERE OU_ABBREV = :ouabbrev AND ID_AC_PROGRAMS IN 
// (SELECT MAX(ID_AC_PROGRAMS) FROM `AC_Programs` WHERE OUTCOMES_AY = :bpayname GROUP BY OU_ABBREV)";

//             $resultexvalue = $this->connection->prepare($sqlexvalue);
//             $resultexvalue->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
//             $resultexvalue->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);

//             $resultexvalue->execute();

//         } catch (PDOException $e) {
//             error_log($e->getMessage());
//             //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
//         }
//         return $resultexvalue;
//     }
// }

Class FACULTYINFO extends BPCONTENTS
{
    public function SaveDraft()
    {
      $facdev = mynl2br($_POST['factextarea']);

      $createact = mynl2br($_POST['cractivity']);

      $contentlink_id = $_GET['linkid'];


  //    if ($_FILES["supinfo"]["error"] > 0) {
  //        $error[0] = "Return Code: No Input " . $_FILES["supinfo"]["error"] . "<br />";
  //        $errorflag = 1;
  //
  //    } else {
  //        $target_dir = "../../user"."/".$name."/";

      if ($_FILES['supinfo']['tmp_name'] !="") {
          $target_dir = "../uploads/facultyInfo/";
          $target_file = $target_dir . basename($_FILES["supinfo"]["name"]);
          $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);


          if ($imageFileType != "pdf") {
              $this->message[1] = "Sorry, only PDf files are allowed.";
              $this->errorflag = 1;

          } else {
              if (move_uploaded_file($_FILES["supinfo"]["tmp_name"], $target_file)) {
                  // $error[0] = "The file " . basename($_FILES["supinfo"]["name"]) . " has been uploaded.";
                  $supinfopath = $target_file;
              } else {
                  $this->message[2] = "Sorry, there was an error uploading your file.";
              }
          }
      }

      if ($this->errorflag != 1) {
          $sqlfacinfo = "INSERT INTO `AC_FacultyInfo` (OU_ABBREV, OUTCOMES_AY, OUTCOMES_AUTHOR, MOD_TIMESTAMP, FACULTY_DEVELOPMENT, CREATIVE_ACTIVITY, AC_SUPPL_FACULTY)
   VALUES (:ouabbrev,:bpayname,:author,:timestampmod,:facdev,:createact,:supinfopath);";

          $sqlfacinfo .= "Update  `BpContents` set CONTENT_STATUS = 'In Progress', BP_AUTHOR= :author,MOD_TIMESTAMP =:time  where ID_CONTENT =:contentlink_id;";

          $sqlfacinfo .= "Update  `broadcast` set BROADCAST_STATUS = 'In Progress', BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= :author, LastModified =:time where ID_BROADCAST = :bpid; ";

          $resultfacinfo = $this->connection->prepare($sqlfacinfo);
          $resultfacinfo->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
          $resultfacinfo->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
          $resultfacinfo->bindParam(":author", $this->author, PDO::PARAM_STR);
          $resultfacinfo->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
          $resultfacinfo->bindParam(':facdev', $facdev, PDO::PARAM_STR);
          $resultfacinfo->bindParam(':createact', $createact, PDO::PARAM_STR);
          $resultfacinfo->bindParam(':supinfopath', $supinfopath, PDO::PARAM_STR);

          if ($resultfacinfo->execute()) {

              $this->message[0] = "Faculty Info Added Succesfully.";
          } else {
              $this->message[3] = "Faculty Info could not be added.";
          }

      }
        return $this->message;
    }

    public function PlaceHolderValue()
    {
        try {
          $sqlexvalue = "SELECT * FROM `AC_FacultyInfo` where OU_ABBREV = :ouabbrev AND ID_FACULTY_INFO in (select max(ID_FACULTY_INFO) from AC_FacultyInfo where OUTCOMES_AY = :bpayname group by OU_ABBREV); ";

          $resultsexvalue = $this->connection->prepare($sqlexvalue);
          $resultsexvalue->bindParam(":ouabbrev", $ouabbrev, PDO::PARAM_STR);
          $resultsexvalue->bindParam(":bpayname", $bpayname, PDO::PARAM_STR);
          $resultsexvalue->execute();
 

        } catch(PDOException $e) {
            error_log($e->getMessage());
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
        }
        return $resultsexvalue;
    }
}Class ALUMNIDEVELOPMENT extends BPCONTENTS
{
    public function SaveDraft()
    {
        $this->time = date('Y-m-d H:i:s');
        $alumni = mynl2br($_POST['alumni']);
        $development = mynl2br($_POST['development']);
        $fundraising = mynl2br($_POST['fundraising']);
        $gifts = mynl2br($_POST['gifts']);
        $supinfopath = null;

        if ($_FILES['supinfo']['tmp_name'] != "") {
            $target_dir = "../uploads/alumni_dev/";
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
AC_UNIT_ALUMNI, AC_UNIT_DEVELOPMENT, AC_UNIT_FUNDRAISING, AC_UNIT_GIFTS, AC_UNIT_SUPPL_ALUM_DEV) VALUES (:ouabbrev,
:bpayname, :author, :timestampmod, :alumni, :development, :fundraising, :gifts, :supinfopath);";

            if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {
                $sqlalumnidev .= "UPDATE `BpContents` SET CONTENT_STATUS = 'In Progress', BP_AUTHOR= :author, 
            MOD_TIMESTAMP = :timestampmod WHERE ID_CONTENT =:contentlink_id ;";

                $sqlalumnidev .= "UPDATE `broadcast` SET BROADCAST_STATUS = 'In Progress', 
BROADCAST_STATUS_OTHERS = 'In Progress', AUTHOR= :author, LastModified = :timestampmod WHERE ID_BROADCAST = :bpid ; ";
            }

            $resultalumnidev = $this->connection->prepare($sqlalumnidev);

            $resultalumnidev->bindParam(":ouabbrev", $this->ouabbrev, PDO::PARAM_STR);
            $resultalumnidev->bindParam(":bpayname", $this->bpayname, PDO::PARAM_STR);
            $resultalumnidev->bindParam(":author", $this->author, PDO::PARAM_STR);
            $resultalumnidev->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
            $resultalumnidev->bindParam(':alumni', $alumni, PDO::PARAM_STR );
            $resultalumnidev->bindParam(':development', $development, PDO::PARAM_STR );
            $resultalumnidev->bindParam(':fundraising', $fundraising, PDO::PARAM_STR );
            $resultalumnidev->bindParam(':gifts', $gifts, PDO::PARAM_STR );
            $resultalumnidev->bindParam(':supinfopath', $supinfopath, PDO::PARAM_STR );

            if ($_SESSION['login_role'] == 'contributor' OR $_SESSION['login_role'] == 'teamlead') {

                $resultalumnidev->bindParam(":author", $this->author, PDO::PARAM_STR);
                $resultalumnidev->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
                $resultalumnidev->bindParam(':contentlink_id', $this->contentLinkId, PDO::PARAM_STR);
                $resultalumnidev->bindParam(":author", $this->author, PDO::PARAM_STR);
                $resultalumnidev->bindParam(":timestampmod", $this->time, PDO::PARAM_STR);
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
Class CAMPUSCLIMATE extends BPCONTENTS
{
    public function SaveDraft()
    {
        $this->time = date('Y-m-d H:i:s');
        $climate = mynl2br($_POST['climate']);
        $supinfopath = null;

        if ($_FILES['supinfo']['tmp_name'] != "") {
            $target_dir = "../uploads/campus_climate/";
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
            $resultclimate->bindParam(':climate', $climate, PDO::PARAM_STR );
            $resultclimate->bindParam(':supinfopath', $supinfopath, PDO::PARAM_STR );

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
Class COLLABORATION extends BPCONTENTS
{
    public function SaveDraft()
    {
        $this->time = date('Y-m-d H:i:s');
        $supinfopath = null;
        $internalcollaborators = mynl2br($_POST['internalcollaborators']);
        $externalcollaborators = mynl2br($_POST['externalcollaborators']);
        $othercollaborators = mynl2br($_POST['othercollaborators']);

        if ($_FILES['supinfo']['tmp_name'] != "") {
            $target_dir = "../uploads/collaborations";
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
            $resultclimate->bindParam(':internalcollaborators', $internalcollaborators, PDO::PARAM_STR );
            $resultclimate->bindParam(':externalcollaborators', $externalcollaborators, PDO::PARAM_STR );
            $resultclimate->bindParam(':othercollaborators', $othercollaborators, PDO::PARAM_STR );
            $resultclimate->bindParam(':supinfopath', $supinfopath, PDO::PARAM_STR );

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