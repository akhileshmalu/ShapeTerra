<?php
session_start();

$ouid = $_SESSION['login_ouid'];
$ouabbrev = $_SESSION['login_ouabbrev'];
$bpayname= $_SESSION['bpayname'];
$bpouabbrev = $_SESSION['bpouabbrev'];

require_once("../../Resources/Includes/connect.php");

require_once("grid.php");

if ($ouid <> 4) {
// load our grid with a table for other than Provost
    $grid = new Grid("BP_UnitGoals", $mysqli, array(
        "save" => false,
        "delete" => false,
        "where" => "OU_ABBREV = '$ouabbrev' and UNIT_GOAL_AY='$bpayname'  ",
        "fields"=>array(
            "ID_UNIT_GOAL"=>"BP_UnitGoals.ID_UNIT_GOAL",
            "UNIT_GOAL_TITLE"=>"BP_UnitGoals.UNIT_GOAL_TITLE",
            "MOD_TIMESTAMP"=>"DATE_FORMAT(BP_UnitGoals.MOD_TIMESTAMP,'%Y-%m-%d %H:%i')",
            "AUTHOR"=>"CONCAT(PermittedUsers.LNAME,', ',PermittedUsers.FNAME)",
        ),

        "joins"=>array(
            "LEFT JOIN PermittedUsers ON PermittedUsers.ID_STATUS = BP_UnitGoals.GOAL_AUTHOR "
        ),

//        date("F j, Y, g:i a", strtotime($rowbroad[2] ) );
// "select" => 'selectFunction'
    ));
} else {
// Do not load grid for Provost rights
    $grid = new Grid("BP_UnitGoals", $mysqli, array(
        "save" => false,
        "delete" => false,
        "where" => " OU_ABBREV = '$bpouabbrev' and  UNIT_GOAL_AY='$bpayname' ",

        "fields"=>array(
            "ID_UNIT_GOAL"=>"BP_UnitGoals.ID_UNIT_GOAL",
            "UNIT_GOAL_TITLE"=>"BP_UnitGoals.UNIT_GOAL_TITLE",
            "MOD_TIMESTAMP"=>"DATE_FORMAT(BP_UnitGoals.MOD_TIMESTAMP,'%Y-%m-%d %H:%i')",
            "AUTHOR"=>"CONCAT(PermittedUsers.LNAME,', ',PermittedUsers.FNAME)",
        ),

        "joins"=>array(
            "LEFT JOIN PermittedUsers ON PermittedUsers.ID_STATUS = BP_UnitGoals.GOAL_AUTHOR "
        ),
    ));

}