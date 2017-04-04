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
      <h1 id="title">Official Data</h1>
  </div>
  
    <ul class="nav nav-tabs col-xs-10 col-xs-offset-1" role="tablist" style="margin-bottom:-25px;">
      <li role="presentation" class="active"><a href="#student-positions" role="tab" data-toggle="tab">Student Enrollments</a></li>
      <li role="presentation"><a href="#student-diversity" role="tab" data-toggle="tab">Student Diversity</a></li>
      <li role="presentation"><a href="#student-outcomes" role="tab" data-toggle="tab">Student Outcomes</a></li>
      <li role="presentation"><a href="#faculty-diversity" role="tab" data-toggle="tab">Faculty Diversity</a></li>
      <li role="presentation"><a href="#other-tables" role="tab" data-toggle="tab">Other Tables</a></li>
      <li role="presentation"><a href="#vpr-report" role="tab" data-toggle="tab">VPR Report</a></li>
    </ul>
    <div id="main-box" class="col-xs-10 col-xs-offset-1">
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="student-positions">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <?php $ChartVisualizations->chartEnrollementStudent(); ?>
            </div>
          </div>
        </div>
      </div>

      <div role="tabpanel" class="tab-pane" id="student-diversity">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <?php $ChartVisualizations->chartDiversityStudent(); ?>
            </div>
          </div>
        </div>
      </div>

      <div role="tabpanel" class="tab-pane" id="student-outcomes">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <?php $ChartVisualizations->chartStudentOutcomes(); ?>
            </div>
          </div>
        </div>
      </div>

      <div role="tabpanel" class="tab-pane" id="faculty-diversity">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="col-md-12">
                <?php $ChartVisualizations->chartDiversityFaculty(); ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div role="tabpanel" class="tab-pane" id="other-tables">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="col-md-12">
                <?php 
                       $ChartVisualizations->tableFacultyStudentRatio();
                       $ChartVisualizations->tableFacultyPop();
                       $ChartVisualizations->tableFacultyActions();
                       $ChartVisualizations->tableEnrollementbyTime();
                 ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div role="tabpanel" class="tab-pane" id="vpr-report" style="min-height: 200px;">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="col-md-4 col-md-offset-4">
                <h2 class="text-center">VPR Report</h2>
                <a class="text-center" style="color:#fff;" <?php echo "href='../uploads/vpr_report/".$ouabbrev."_research_funding_blueprint.pdf'"; ?>><button class="btn-primary pull-left">Click to download the VPR Report</button></a>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script src="../Resources/Library/js/taskboard.js"></script>
<?php require_once("../Resources/Includes/footer.php"); ?>
