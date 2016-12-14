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
        "fields"=>array(
            "UNIT_GOAL_TITLE"=>"BP_UnitGoals.UNIT_GOAL_TITLE",
            "GOAL_REPORT_STATUS"=>"BP_UnitGoalOutcomes.GOAL_REPORT_STATUS",
            "MOD_TIMESTAMP"=>"BP_UnitGoalOutcomes.MOD_TIMESTAMP",
            "OUTCOMES_AUTHOR"=>"BP_UnitGoalOutcomes.OUTCOMES_AUTHOR",
            "ID_UNIT_GOAL"=>"BP_UnitGoals.ID_UNIT_GOAL",
            ),
 "joins"=>array(
 	"left JOIN BP_UnitGoalOutcomes ON BP_UnitGoals.ID_UNIT_GOAL =BP_UnitGoalOutcomes.ID_UNIT_GOAL "
 ),

//        date("F j, Y, g:i a", strtotime($rowbroad[2] ) );
// "select" => 'selectFunction'
    ));
} else {
// Do not load grid for Provost rights
    $grid = new Grid("BP_UnitGoals", $mysqli, array(
        "save" => false,
        "delete" => false,
        "where" => "UNIT_GOAL_AY='$bpayname'  ",
        "fields"=>array(
            "UNIT_GOAL_TITLE"=>"BP_UnitGoals.UNIT_GOAL_TITLE",
            "GOAL_REPORT_STATUS"=>"BP_UnitGoalOutcomes.GOAL_REPORT_STATUS",
            "MOD_TIMESTAMP"=>"BP_UnitGoalOutcomes.MOD_TIMESTAMP",
            "OUTCOMES_AUTHOR"=>"BP_UnitGoalOutcomes.OUTCOMES_AUTHOR",
            "ID_UNIT_GOAL"=>"BP_UnitGoals.ID_UNIT_GOAL",
        ),
        "joins"=>array(
            "left JOIN BP_UnitGoalOutcomes ON BP_UnitGoals.ID_UNIT_GOAL =BP_UnitGoalOutcomes.ID_UNIT_GOAL "
        ),
    ));

}