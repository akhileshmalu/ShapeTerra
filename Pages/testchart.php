<?php
  session_start();
  require_once ("../Resources/Includes/connect.php");
  $ouabbrev= $_SESSION['login_ouabbrev'];
  $sqluser = "select NETWORK_USERNAME,OU_NAME,SYS_USER_ROLE,FNAME,LNAME from PermittedUsers Inner Join Hierarchy ON PermittedUsers.USER_OU_MEMBERSHIP = Hierarchy.OU_ABBREV where OU_ABBREV = '$ouabbrev'";
  $resultuser = $mysqli->query($sqluser);
  $rowsuser = $resultuser->fetch_assoc();

  require ("../Resources/Includes/visualdata.php");
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
</head>
<body>
  <script src="../Resources/Library/js/chart.min.js"></script>
  <h5>Select filter by year</h5>
  <?php $VisualData->getAcademicYears($mysqli); ?>
  <canvas id="chart1" width="500" height="500"></canvas>
  <?php $VisualData->chartAcademicEnrollements(); ?>
  <?php require_once("../Resources/Includes/footer.php"); ?>
</body>
</html>
