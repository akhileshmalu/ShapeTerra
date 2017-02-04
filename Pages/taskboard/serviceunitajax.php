<?php
session_start();

$ouid = $_SESSION['login_ouid'];
$outype = $_SESSION['login_outype'];

require_once("../../Resources/Includes/connect.php");

// require our class
require_once("grid.php");


// load grid with Service Upload Years
$grid = new Grid("AcademicYears", $mysqli, array(

    "save" => false,

    "delete" => false,


//
//    "fields" => array(
//
//        "BROADCAST_AY" => "broadcast.BROADCAST_AY",
//        "BROADCAST_STATUS_OTHERS" => "broadcast.BROADCAST_STATUS_OTHERS",
//        "BROADCAST_DESC" => "broadcast.BROADCAST_DESC",
//        "OU_ABBREV" => "broadcast.OU_ABBREV",
//        "AUTHOR" => "CONCAT(PermittedUsers.LNAME,', ',PermittedUsers.FNAME)",
//        "LastModified" => "DATE_FORMAT(broadcast.LastModified,'%Y-%m-%d %H:%i')",
//
//    ),
//    "joins" => array(
//        "inner JOIN PermittedUsers ON PermittedUsers.ID_STATUS = broadcast.AUTHOR"
//    ),
// "select" => 'selectFunction'
));




?>


