<?php
  session_start();
  require_once ("../Resources/Includes/connect.php");
  $ouabbrev= $_SESSION['login_ouabbrev'];
  $sqluser = "select NETWORK_USERNAME,OU_NAME,SYS_USER_ROLE,FNAME,LNAME from PermittedUsers Inner Join Hierarchy ON PermittedUsers.USER_OU_MEMBERSHIP = Hierarchy.OU_ABBREV where OU_ABBREV = '$ouabbrev'";
  $resultuser = $mysqli->query($sqluser);
  $rowsuser = $resultuser->fetch_assoc();

  require ("../Resources/Includes/visualData.php");
  $VisualData = new VisualData;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Academic BluePrint</title>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require_once("../Resources/Includes/header.php"); ?>
  <link rel="stylesheet" href="../Resources/Library/css/VisualData.css" media="screen">
  <script src="../Resources/Library/js/chart.min.js"></script>
</head>
<body>
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
            <?php $VisualData->getAcademicYears($mysqli); ?>
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
        <?php $VisualData->getDiversityStudentYears(); ?>
        <div class="p-a-2"></div>
        <h5>Select filter by year</h5>
        <?php $VisualData->getDiversityFacultyYears(); ?>
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
        <?php $VisualData->getFacultyYears($mysqli); ?>
      </div>
      <div class="col-md-9">
        <div id="faculty-chart">
        </div>
      </div>
    </div>
  </div>
  <script src="../Resources/Library/js/visualData.js"></script>
  <?php require_once("../Resources/Includes/footer.php"); ?>
</body>
</html>
