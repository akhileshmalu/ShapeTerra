<?php
session_start();

$ouid = $_SESSION['login_ouid'];

require_once("../../Resources/Includes/connect.php");
// require our class
require_once("grid.php");

if ($ouid <> 4) {
    // load our grid with a table for other than Provost
    $grid = new Grid("broadcast", $mysqli ,array(
        "save" => false,
        "delete" => false,
        "where" => "BROADCAST_OU = '$ouid'",
        // "joins"=>array(
        // 	"LEFT JOIN categories ON categories.CategoryID = tutorials.CategoryID"
        // ),
//         "fields"=>array(
//         "OU_ABBREV" => "broadcast.OU_ABBREV"
//             ),
        // "select" => 'selectFunction'
    ));
} else {
    // load our grid with a table for Provost
    $grid = new Grid("broadcast",$mysqli, array(
        "save" => false,
        "delete" => false,
      //  "where" => "'",
        "fields"=>array(
            "BROADCAST_AY" => "broadcast.BROADCAST_AY",
            "BROADCAST_STATUS" => "broadcast.BROADCAST_STATUS",
            "BROADCAST_DESC" => "broadcast.BROADCAST_DESC",
            "OU_ABBREV" => "broadcast.OU_ABBREV"
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