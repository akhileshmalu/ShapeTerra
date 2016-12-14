<?php
session_start();

$ouid = $_SESSION['login_ouid'];
$ouabbrev = $_SESSION['login_ouabbrev'];
$bpayname= $_SESSION['bpayname'];

require_once("../../Resources/Includes/connect.php");

require_once("grid.php");

if ($ouid <> 4) {
// load our grid with a table for other than Provost
    $grid = new Grid("BpContents", $mysqli, array(
        "save" => false,
        "delete" => false,
        "where" => "OU_ABBREV = '$ouabbrev' and BROADCAST_AY='$bpayname' ",
        "fields"=>array(
            "CONTENT_BRIEF_DESC"=>"BpContents.CONTENT_BRIEF_DESC",
            "CONTENT_LINK"=>"BpContents.CONTENT_LINK",
            "MOD_TIMESTAMP"=>"BpContents.MOD_TIMESTAMP",
            "BP_AUTHOR"=>"BpContents.BP_AUTHOR",
            "CONTENT_STATUS"=>"BpContents.CONTENT_STATUS",
            "ID_CONTENT"=>"BpContents.ID_CONTENT",
            "ID_BROADCAST"=>"broadcast.ID_BROADCAST",
            "BROADCAST_AY"=>"broadcast.BROADCAST_AY",
            "OU_ABBREV"=>"broadcast.OU_ABBREV",

        ),
        "joins"=>array(
            "inner JOIN broadcast ON BpContents.Linked_BP_ID =broadcast.ID_BROADCAST "
        ),


//        date("F j, Y, g:i a", strtotime($rowbroad[2] ) );
// "select" => 'selectFunction'
    ));
} else {
// Do not load grid for Provost rights
    $grid = new Grid("BpContents", $mysqli, array(
        "save" => false,
        "delete" => false,
        "where" => "BROADCAST_AY='$bpayname'  ",
        "joins"=>array(
            "left JOIN broadcast ON BpContents.Linked_BP_ID =broadcast.ID_BROADCAST "
        ),
    ));

}