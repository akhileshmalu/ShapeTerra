<?php
session_start();

$ouid = $_SESSION['login_ouid'];
$outype = $_SESSION['login_outype'];

require_once("../../Resources/Includes/Initialize.php");
$initialize = new Initialize();
$mysqli = $initialize->mysqli;
// require our class
require_once("grid.php");

if ($outype == "Academic Unit") {

    // load our grid with a table for other than Provost
    $grid = new Grid("broadcast", $mysqli ,array(

        "save" => false,

        "delete" => false,

        "where" => "BROADCAST_OU = '$ouid' ",

        "fields"=>array(
            "ID" => "broadcast.ID_BROADCAST",
            "BROADCAST_AY" => "broadcast.BROADCAST_AY",
            "BROADCAST_STATUS_OTHERS" => "broadcast.BROADCAST_STATUS_OTHERS",
            "BROADCAST_DESC" => "broadcast.BROADCAST_DESC",
            "OU_ABBREV" => "broadcast.OU_ABBREV",
            "AUTHOR"=>"CONCAT(PermittedUsers.LNAME,', ',PermittedUsers.FNAME)",
            "LastModified"=> "DATE_FORMAT(broadcast.LastModified,'%Y-%m-%d %H:%i')",

        ),
        "joins"=>array(
            "inner JOIN PermittedUsers ON PermittedUsers.ID_STATUS = broadcast.AUTHOR"
        ),
// "select" => 'selectFunction'
    ));

} else {

    // load our grid with a table for Provost
    $grid = new Grid("broadcast",$mysqli, array(
        "save" => false,
        "delete" => false,
        //  "where" => "'",
        "fields"=>array(
            "ID" => "broadcast.ID_BROADCAST",
            "BROADCAST_AY" => "broadcast.BROADCAST_AY",
            "BROADCAST_STATUS" => "broadcast.BROADCAST_STATUS",
            "BROADCAST_DESC" => "broadcast.BROADCAST_DESC",
            "OU_ABBREV" => "broadcast.OU_ABBREV",
            "AUTHOR"=>"CONCAT(PermittedUsers.LNAME,', ',PermittedUsers.FNAME)",
            "LastModified"=> "DATE_FORMAT(broadcast.LastModified,'%Y-%m-%d %H:%i')"

        ),
        "joins"=>array(
            "INNER JOIN PermittedUsers ON PermittedUsers.ID_STATUS = broadcast.AUTHOR"
        ),
    ));
}

// drop down function
// if you have anonymous function support, then you can just put this function in place of
// 'selectFunction'
// function selectFunction($grid) {
// 	$selects = array();

// 	// category select
// 	$grid->table = "categories";
// 	$selects["CategoryID"] = $grid->makeSelect("CategoryID","Name");

// 	// active select
// 	$selects["active"] = array("1"=>"true","0"=>"false");

// 	// render data
// 	$grid->render($selects);
// }

?>


