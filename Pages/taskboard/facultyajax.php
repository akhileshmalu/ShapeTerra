<?php
session_start();

$ouid = $_SESSION['login_ouid'];
$ouabbrev = $_SESSION['login_ouabbrev'];

require_once("../../Resources/Includes/connect.php");

require_once("grid.php");

if ($ouid <> 4) {
// load our grid with a table for other than Provost
    $grid = new Grid("AC_FacultyAwards", $mysqli, array(
        "save" => false,
        "delete" => false,
        "where" => "OU_ABBREV = '$ouabbrev'",
// "joins"=>array(
// 	"LEFT JOIN categories ON categories.CategoryID = tutorials.CategoryID"
// ),
 "fields"=>array(
     "RECIPIENT_NAME"=>"CONCAT(RECIPIENT_NAME_LAST,', ',SUBSTRING(RECIPIENT_NAME_FIRST,1,1))",
 ),
// "select" => 'selectFunction'
    ));
} else {
// Do not load grid for Provost rights
    $grid = new Grid("AC_FacultyAwards", $mysqli, array(
        "save" => false,
        "delete" => false,
    ));

}