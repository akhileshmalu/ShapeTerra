<?php

  require_once("../Resources/Includes/Initialize.php");
  $initalize = new Initialize();
  $initalize->checkSessionStatus();
  $connection = $initalize->connection;

  require_once("../Resources/Includes/ChartVisualizations.php");
  $ChartVisualizations = new ChartVisualizations();

  $message = array();
  $errorflag = 0;
  @$bpid = $_GET['id'];
  $bpayname = $_GET['ayname'];
  $ouid = $_SESSION['login_ouid'];
  $outype = $_SESSION['login_outype'];
  $_SESSION['bpayname'] = $bpayname;
  $_SESSION['bpid'] = $bpid;

  if ($outype == "Administration") {

    $ouabbrev = $_GET['ou_abbrev'];
    $_SESSION['bpouabbrev'] = $_GET['ou_abbrev'];

  }else{

    $ouabbrev = $_SESSION['login_ouabbrev'];

  }

  // SQL to display Blueprint Content
  try{

      $sqlbpcontent = "SELECT * FROM `BpContents` INNER JOIN broadcast ON
      BpContents.Linked_BP_ID = broadcast.ID_BROADCAST LEFT JOIN PermittedUsers ON PermittedUsers.ID_STATUS = broadcast
      .AUTHOR WHERE OU_ABBREV = :ouabbrev AND BROADCAST_AY=:bpayname ";
      $resultbpcontent = $connection->prepare($sqlbpcontent);
      $resultbpcontent->bindParam(':ouabbrev', $ouabbrev, 2);
      $resultbpcontent->bindParam(':bpayname', $bpayname, 2);
      $resultbpcontent->execute();
      $numbpcontent = $resultbpcontent->rowCount();

  }catch (PDOException $e){

      //SYSTEM::pLog($e->__toString(),$_SERVER['PHP_SELF']);
      error_log($e->getMessage());

  }

  try{

      if ($outype == "Administration" || $outype == "Service Unit") {

          $sqlbroad = "SELECT BROADCAST_AY,OU_NAME,BROADCAST_STATUS,LastModified FROM broadcast INNER JOIN Hierarchy ON
          broadcast.BROADCAST_OU=Hierarchy.ID_HIERARCHY WHERE BROADCAST_AY=:bpayname AND Hierarchy.OU_ABBREV =:ouabbrev ;";
          $resultbroad = $connection->prepare($sqlbroad);
          $resultbroad->bindParam(':bpayname', $bpayname, 2);
          $resultbroad->bindParam(':ouabbrev', $ouabbrev, 2);

      }else{

          $sqlbroad = "SELECT BROADCAST_AY,OU_NAME, BROADCAST_STATUS_OTHERS,LastModified FROM broadcast INNER JOIN
          Hierarchy ON broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY WHERE BROADCAST_AY=:bpayname AND BROADCAST_OU =:ouid;";
          $resultbroad = $connection->prepare($sqlbroad);
          $resultbroad->bindParam(':bpayname', $bpayname, 2);
          $resultbroad->bindParam(':ouid', $ouid, 1);

      }

      $resultbroad->execute();

  }catch (PDOException $e){

    //SYSTEM::pLog($e->__toString(),$_SERVER['PHP_SELF']);
    error_log($e->getMessage());

  }

  $rowbroad = $resultbroad->fetch(4);

  require_once("../Resources/Includes/header.php");
  require_once("../Resources/Includes/menu.php");

?>
<link rel="stylesheet" href="../Resources/Library/css/VisualData.css" media="screen">
<script src="../Resources/Library/js/chart.min.js"></script>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.min.css"/>
<link rel="stylesheet" href="Css/grid.css" title="openJsGrid"/>
<script src="../Resources/Library/js/root.js"></script>
<script src="../Resources/Library/js/grid.js"></script>
<div class="overlay hidden"></div>
<?php if (isset($_POST['submit_bp'])) { ?>
  <div class="alert">
      <a href="#" class="close end"><span class="icon">9</span></a>
      <h1 class="title"></h1>
      <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
      <button type="button" redirect="account.php" class="end btn-primary">Close</button>
  </div>
<?php } ?>
<div class="hr"></div>
<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
  <div id="title-header">
      <h1 id="title">Data Visualizations</h1>
  </div>
  <div id="main-box" class="col-xs-10 col-xs-offset-1">
    <div class="col-xs-8">
        <h1 class="box-title"><?php echo $rowbroad[0]; ?></h1>
        <p class="status"><span>Org Unit Name:</span> <?php echo $rowbroad[1]; ?></p>
        <p class="status"><span>Status:</span> <?php echo $rowbroad[2]; ?></p>
    </div>
    <div class="col-xs-4">
        <a href="../Resources/Includes/fpdf_extended.php" target="_Blank" class="btn-primary">View PDF</a>
    </div>
  </div>
  <div id="main-box" class="col-xs-10 col-xs-offset-1">
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#grade" aria-controls="grade" role="tab" data-toggle="tab">By Grade</a></li>
      <li role="presentation"><a href="#faculty" aria-controls="faculty" role="tab" data-toggle="tab">By Faculty</a></li>
      <li role="presentation"><a href="#diversity" aria-controls="diversity" role="tab" data-toggle="tab">By Diversity</a></li>
    </ul>
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="grade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-3">
              <h5>Select filter by year</h5>
              <?php $ChartVisualizations->getAcademicYears($mysqli); ?>
            </div>
            <div class="col-md-9">
              <div id="academic-chart">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div role="tabpanel" class="tab-pane" id="diversity">
        <div class="col-md-3">
          <h5>Select filter by year</h5>
          <?php $ChartVisualizations->getDiversityStudentYears(); ?>
          <div class="p-a-2"></div>
          <h5>Select filter by year</h5>
          <?php $ChartVisualizations->getDiversityFacultyYears(); ?>
        </div>
        <div class="col-md-9">
          <h1 class='text-center'>Student Diversity</h1>
          <div id="student-diversity-chart">
          </div>
          <h1 class='text-center'>Faculty Diversity</h1>
          <div id="faculty-diversity-chart">
          </div>
        </div>
      </div>
      <div role="tabpanel" class="tab-pane" id="faculty">
        <div class="col-md-3">
          <h5>Select filter by year</h5>
          <?php $ChartVisualizations->getFacultyYears(); ?>
        </div>
        <div class="col-md-9">
          <div id="faculty-chart">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script src="../Resources/Library/js/taskboard.js"></script>
<?php require_once("../Resources/Includes/footer.php"); ?>























  <script src="../Resources/Library/js/visualData.js"></script>
  <?php require_once("../Resources/Includes/footer.php"); ?>
