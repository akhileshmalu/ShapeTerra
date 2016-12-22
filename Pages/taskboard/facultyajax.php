<?php
session_start();

require_once("../../Resources/Includes/connect.php");

$bpayname = $_SESSION['bpayname'];
$ouid = $_SESSION['login_ouid'];

if ($ouid <> 4) {
    $ouabbrev = $_SESSION['login_ouabbrev'];
} else {
    $ouabbrev = $_SESSION['bpouabbrev'];
}


require_once("grid.php");

// load our grid with a table for other than Provost role
$grid = new Grid("AC_FacultyAwards", $mysqli, array(
    "save" => false,
    "delete" => false,
    "where" => "OU_ABBREV = '$ouabbrev' and OUTCOMES_AY='$bpayname' ",
    "fields" => array(
        "RECIPIENT_NAME" => "CONCAT(RECIPIENT_NAME_LAST,', ',SUBSTRING(RECIPIENT_NAME_FIRST,1,1))",
    ),
));

