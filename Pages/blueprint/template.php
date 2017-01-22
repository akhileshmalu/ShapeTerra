<?php
session_start();
require_once ("../../Resources/Includes/connect.php");
$ouabbrev= $_SESSION['login_ouabbrev'];
$sqluser = "select NETWORK_USERNAME,OU_NAME,SYS_USER_ROLE,FNAME,LNAME from PermittedUsers Inner Join Hierarchy ON PermittedUsers.USER_OU_MEMBERSHIP = Hierarchy.OU_ABBREV where OU_ABBREV = '$ouabbrev'";
$resultuser = $mysqli->query($sqluser);
$rowsuser = $resultuser->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Academic BluePrint</title>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>


<!--Main content goes-->

</body>
</html>