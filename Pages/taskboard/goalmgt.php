<?php
session_start();

$ouid = $_SESSION['login_ouid'];
$ouabbrev = $_SESSION['login_ouabbrev'];
$bpayname= $_SESSION['bpayname'];

require_once("../../Resources/Includes/connect.php");

require_once("grid.php");

if ($ouid <> 4) {
// load our grid with a table for other than Provost
    $grid = new Grid("BP_UnitGoals", $mysqli, array(
        "save" => false,
        "delete" => false,
        "where" => "OU_ABBREV = '$ouabbrev' and UNIT_GOAL_AY='$bpayname'  ",
//        "fields"=>array(
//            "LAST_EDIT"=>"",
// "joins"=>array(
// 	"LEFT JOIN categories ON categories.CategoryID = tutorials.CategoryID"
// ),

//        date("F j, Y, g:i a", strtotime($rowbroad[2] ) );
// "select" => 'selectFunction'
    ));
} else {
// Do not load grid for Provost rights
    $grid = new Grid("BP_UnitGoals", $mysqli, array(
        "save" => false,
        "delete" => false,
        "where" => "UNIT_GOAL_AY='$bpayname'  ",
    ));

}