<?php

  error_reporting(1);
  @ini_set('display_errors', 1);
  session_start();
  ob_start();

  if(isset($_POST['functionNum']))
    $function = $_POST['functionNum'];

  if (isset($_GET['functionNum']))
    $function = $_GET['functionNum'];

  if (!empty($function)){

    $Data = new Data;

  }

  switch ($function) {
    case 1:
      $Data->getBluePrintsContent();
      break;
    default:
      break;
  }

  Class Data{

    private $conection;

    function __construct(){
      $this->connection = $this->connection();
    }

    private function connection(){

      try{

        $connection = new PDO(sprintf('mysql:host=%s;dbname=%s', "localhost", "TESTDB"), "root", "");
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $connection;

      }catch(PDOException $e){

        return $e->getMessage();
        exit();

      }

    }

    public function getBluePrintsContent(){

      $getBluePrintsContent = $this->connection->prepare("SELECT * FROM `BpContents`");
      $getBluePrintsContent->execute();
      $rowsGetBluePrintsContent = $getBluePrintsContent->rowCount();

      if ($rowsGetBluePrintsContent > 0){

        $counter = 0;

        while($data = $getBluePrintsContent->fetch()){

          if ($counter != 0){

            echo ",".json_encode($data);

          }else{

            echo json_encode($data);

          }

          $counter++;

        }

      }else{

        echo "<h6>No data was found!</h6>";

      }

    }

    public function saveExecutiveSummaryDet(){

      if (!empty($_POST["college-school-input"]) && !empty($_POST["deans-name-input"]) && !empty($_POST["deans-title-input"])){ //!empty($_FILES["deans-college-school-logo"])

        $collegeSchoolInput = $_POST["college-school-input"];
        $deansNameInput = $_POST["deans-name-input"];
        $deansTitleInput = $_POST["deans-title-input"];
        $deansSignatureLogo = $_FILES["deans-signature-logo"]["name"];
        $deansPortraitLogo = $_FILES["deans-portrait-logo"]["name"];
        $deansCollegeSchoolLogo = $_FILES["deans-college-school-logo"]["name"];

        if (!empty($deansSignatureLogo)){

          $deansSignatureLogoName = $deansSignatureLogo;
          $deansSignatureLogoTmpDir = $_FILES["deans-signature-logo"]["tmp_name"];
          $deansSignatureLogoFinalName = hash("SHA512", $deansSignatureName);
          $deansSignatureLogoFinalDir = "../uploads/logos/".$deansSignatureLogoFinalName;

          $size = getimagesize($deansSignatureLogoTmpDir);

          if ($size[0] != 250 && $size[1] != 75){

            //error handling
            exit();

          }else{

            if (exif_imagetype($deansSignatureLogoTmpDir) == IMAGETYPE_GIF || exif_imagetype($deansSignatureLogoTmpDir) == IMAGETYPE_JPG || exif_imagetype($deansSignatureLogoTmpDir) == IMAGETYPE_PNG){

              move_uploaded_file($deansSignatureLogoTmpDir,$deansSignatureLogoFinalDir);

            }else{

              //error handling
              exit();

            }

          }

        }

        if (!empty($deansPortraitLogo)){

          $deansPortraitLogoName = $deansPortraitLogo;
          $deansPortraitLogoTmpDir = $_FILES["deans-signature-logo"]["tmp_name"];
          $deansPortraitLogoFinalName = hash("SHA512", $deansPortraitLogoName);
          $deansPortraitLogoFinalDir = "../uploads/logos/".$deansPortraitLogoFinalName;

          $size = getimagesize($deansPortraitLogoTmpDir);

          if ($size[0] != 250 && $size[1] != 75){

            //error handling
            exit();

          }else{

            if (exif_imagetype($deansPortraitLogoTmpDir) == IMAGETYPE_GIF || exif_imagetype($deansPortraitLogoTmpDir) == IMAGETYPE_JPG || exif_imagetype($deansPortraitLogoTmpDir) == IMAGETYPE_PNG){

              move_uploaded_file($deansPortraitLogoTmpDir,$deansPortraitLogoFinalDir);

            }else{

              //error handling
              exit();

            }

          }

        }

        if (!empty($deansCollegeSchoolLogo)){

          $deansCollegeSchoolLogoName = $deansCollegeSchoolLogo;
          $deansCollegeSchoolLogoTmpDir = $_FILES["deans-college-school-logo"]["tmp_name"];
          $deansCollegeSchoolLogoFinalName = hash("SHA512", $deansCollegeSchoolLogoName);
          $deansCollegeSchoolLogoFinalDir = "../uploads/logos/".$deansCollegeSchoolLogoFinalName;

          $size = getimagesize($deansCollegeSchoolLogoTmpDir);

          if (exif_imagetype($deansCollegeSchoolLogoTmpDir) == IMAGETYPE_GIF || exif_imagetype($deansCollegeSchoolLogoTmpDir) == IMAGETYPE_JPG || exif_imagetype($deansCollegeSchoolLogoTmpDir) == IMAGETYPE_PNG){

            move_uploaded_file($deansCollegeSchoolLogoTmpDir,$deansCollegeSchoolLogoFinalDir);

          }else{

            //echo 1;//error handling
            exit();

          }

        }

        if (strlen($collegeSchoolInput) > 125){

          //error handling
          exit();

        }

        if (strlen($deansNameInput) > 75){

          //error handling
          exit();

        }

        if (strlen($deansTitleInput) > 225){

          //error handling
          exit();

        }

        $selectedYear = $_SESSION["bpayname"];
        $unit = $_SESSION["bpouabbrev"];
        $lastUpdated = date('Y-m-d H:i:s');
        $ouid = $_SESSION['login_ouid'];
        $author = $_SESSION['login_userid'];
        if ($ouid == 4) {
          $ouabbrev = $_SESSION['bpouabbrev'];
        }else{
          $ouabbrev = $_SESSION['login_ouabbrev'];
        }

        //until images are imped
        $deansPortraitLogoFinalDir = "";
        $deansSignatureLogoFinalDir = "";
        $deansCollegeSchoolLogoFinalDir = "";

        $findCurrentExecSum = $this->connection->prepare("SELECT * FROM `AC_ExecSum` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $findCurrentExecSum->bindParam(1,$selectedYear,PDO::PARAM_STR);
        $findCurrentExecSum->bindParam(2,$unit,PDO::PARAM_STR);
        $findCurrentExecSum->execute();
        $rowsFindCurrentExecSum = $findCurrentExecSum->rowCount();

        if ($rowsFindCurrentExecSum > 0){

          $insertNewData = $this->connection->prepare("UPDATE `AC_ExecSum` SET MOD_TIMESTAMP = ?, UNIT_NAME = ?, DEAN_NAME_PRINT = ?, DEAN_TITLE = ?, DEAN_PORTRAIT = ?, DEAN_SIGNATURE = ?, COMPANION_LOGO = ? WHERE OU_ABBREV = ? AND OUTCOMES_AY = ?");
          $insertNewData->bindParam(1,$lastUpdated,PDO::PARAM_STR);
          $insertNewData->bindParam(2,$collegeSchoolInput,PDO::PARAM_STR);
          $insertNewData->bindParam(3,$deansNameInput,PDO::PARAM_STR);
          $insertNewData->bindParam(4,$deansTitleInput,PDO::PARAM_STR);
          $insertNewData->bindParam(5,$deansPortraitLogoFinalDir,PDO::PARAM_STR);
          $insertNewData->bindParam(6,$deansSignatureLogoFinalDir,PDO::PARAM_STR);
          $insertNewData->bindParam(7,$deansCollegeSchoolLogoFinalDir,PDO::PARAM_STR);
          $insertNewData->bindParam(8,$unit,PDO::PARAM_STR);
          $insertNewData->bindParam(9,$selectedYear,PDO::PARAM_STR);
          $insertNewData->execute();

        }else{

          $insertNewData = $this->connection->prepare("INSERT INTO `AC_ExecSum`(OU_ABBREV,OUTCOMES_AY,OUTCOMES_AUTHOR,MOD_TIMESTAMP,UNIT_NAME,DEAN_NAME_PRINT,DEAN_TITLE,DEAN_PORTRAIT,DEAN_SIGNATURE,COMPANION_LOGO)
          VALUES(?,?,?,?,?,?,?,?,?,?)");
          $insertNewData->bindParam(1,$unit,PDO::PARAM_STR);
          $insertNewData->bindParam(2,$selectedYear,PDO::PARAM_STR);
          $insertNewData->bindParam(3,$author,PDO::PARAM_STR);
          $insertNewData->bindParam(4,$lastUpdated,PDO::PARAM_STR);
          $insertNewData->bindParam(5,$collegeSchoolInput,PDO::PARAM_STR);
          $insertNewData->bindParam(6,$deansNameInput,PDO::PARAM_STR);
          $insertNewData->bindParam(7,$deansTitleInput,PDO::PARAM_STR);
          $insertNewData->bindParam(8,$deansPortraitLogoFinalDir,PDO::PARAM_STR);
          $insertNewData->bindParam(9,$deansSignatureLogoFinalDir,PDO::PARAM_STR);
          $insertNewData->bindParam(10,$deansCollegeSchoolLogoFinalDir,PDO::PARAM_STR);
          $insertNewData->execute();

        }

      }else{

        //error handling
        //exit();

      }

    }

    public function saveExecutiveSummaryIntro(){

      if (!empty($_POST["introduction-input"]) && !empty($_POST["highlights-input"])){

        $introductionInput = $_POST["introduction-input"];
        $highlightsNarrativeInput = $_POST["highlights-input"];

        if (strlen($introductionInput) > 1000){

          //error handling
          exit();

        }

        if (strlen($highlightsNarrativeInput) > 2500){

          //error handling
          exit();

        }

        $selectedYear = $_SESSION["bpayname"];
        $unit = $_SESSION["bpouabbrev"];
        $lastUpdated = date('Y-m-d H:i:s');
        $ouid = $_SESSION['login_ouid'];
        $author = $_SESSION['login_userid'];
        if ($ouid == 4) {
          $ouabbrev = $_SESSION['bpouabbrev'];
        }else{
          $ouabbrev = $_SESSION['login_ouabbrev'];
        }

        $findCurrentExecSum = $this->connection->prepare("SELECT * FROM `AC_ExecSum` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $findCurrentExecSum->bindParam(1,$selectedYear,PDO::PARAM_STR);
        $findCurrentExecSum->bindParam(2,$unit,PDO::PARAM_STR);
        $findCurrentExecSum->execute();
        $rowsFindCurrentExecSum = $findCurrentExecSum->rowCount();

        if ($rowsFindCurrentExecSum > 0){

          $updateExecSumIntro = $this->connection->prepare("UPDATE `AC_ExecSum` SET INTRODUCTION = ? AND HIGHLIGHTS_NARRATIVE = ? AND MOD_TIMESTAMP = ? WHERE OU_ABBREV = ? AND OUTCOMES_AY = ?");
          $updateExecSumIntro->bindParam(1,$introductionInput,PDO::PARAM_STR);
          $updateExecSumIntro->bindParam(2,$highlightsNarrativeInput,PDO::PARAM_STR);
          $updateExecSumIntro->bindParam(3,$time,PDO::PARAM_STR);
          $updateExecSumIntro->bindParam(4,$unit,PDO::PARAM_STR);
          $updateExecSumIntro->bindParam(5,$selectedYear,PDO::PARAM_STR);
          $updateExecSumIntro->execute();

        }else{

          $insertExecSumIntro = $this->connection->prepare("INSERT INTO `AC_ExecSum` (OU_ABBREV,OUTCOMES_AY,OUTCOMES_AUTHOR,MOD_TIMESTAMP,INTRODUCTION,HIGHLIGHTS_NARRATIVE) VALUES (?,?,?,?,?,?)");
          $insertExecSumIntro->bindParam(1,$unit,PDO::PARAM_STR);
          $insertExecSumIntro->bindParam(2,$selectedYear,PDO::PARAM_STR);
          $insertExecSumIntro->bindParam(3,$author,PDO::PARAM_STR);
          $insertExecSumIntro->bindParam(4,$lastUpdated,PDO::PARAM_STR);
          $insertExecSumIntro->bindParam(5,$introductionInput,PDO::PARAM_STR);
          $insertExecSumIntro->bindParam(6,$highlightsNarrativeInput,PDO::PARAM_STR);
          $insertExecSumIntro->execute();

        }

      }

    }

    public function getExecutiveSummary(){

      $selectedYear = $_SESSION["bpayname"];
      $unit = $_SESSION["bpouabbrev"];

      $getExecutiveSummary = $this->connection->prepare("SELECT * FROM `AC_ExecSum` WHERE OU_ABBREV = ? AND OUTCOMES_AY = ?");
      $getExecutiveSummary->bindParam(1,$unit,PDO::PARAM_STR);
      $getExecutiveSummary->bindParam(2,$selectedYear,PDO::PARAM_STR);
      $getExecutiveSummary->execute();

      return $getExecutiveSummary->fetch();

    }

    public function checkImageExecSumInfo(){

    }

  }

?>
