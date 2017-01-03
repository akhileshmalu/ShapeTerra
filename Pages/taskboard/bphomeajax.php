<?php
session_start();

$ouid = $_SESSION['login_ouid'];
$outype = $_SESSION['login_outype'];
$ouabbrev = $_SESSION['login_ouabbrev'];
$bpayname= $_SESSION['bpayname'];
$bpouabbrev = $_SESSION['bpouabbrev'];

require_once("../../Resources/Includes/connect.php");

require_once("grid.php");

if ($outype == "Academic Unit") {

    // Load Grid For Academic Unit Members

    $grid = new Grid("BpContents", $mysqli, array(
        "save" => false,
        "delete" => false,
        "where" => "OU_ABBREV = '$ouabbrev' and BROADCAST_AY='$bpayname' and BP_OU_TYPE = '$outype' ",
        "fields" => array(
            "CONTENT_BRIEF_DESC" => "BpContents.CONTENT_BRIEF_DESC",
            "CONTENT_LINK" => "BpContents.CONTENT_LINK",
            "MOD_TIMESTAMP" => "DATE_FORMAT(BpContents.MOD_TIMESTAMP,'%Y-%m-%d %H:%i')",
            "CONTENT_STATUS" => "BpContents.CONTENT_STATUS",
            "ID_CONTENT" => "BpContents.ID_CONTENT",
            "ID_BROADCAST" => "broadcast.ID_BROADCAST",
            "BROADCAST_AY" => "broadcast.BROADCAST_AY",
            "OU_ABBREV" => "broadcast.OU_ABBREV",
            "BP_AUTHOR" => "CONCAT(PermittedUsers.LNAME,', ',PermittedUsers.FNAME)",
            "Sr_No" => "BpContents.Sr_No",

        ),
        "joins" => array(
            "inner JOIN broadcast ON BpContents.Linked_BP_ID = broadcast.ID_BROADCAST ",
            "left JOIN PermittedUsers ON PermittedUsers.ID_STATUS = BpContents.BP_AUTHOR"
        ),

//        date("F j, Y, g:i a", strtotime($rowbroad[2] ) );
// "select" => 'selectFunction'
    ));
} else {
    if ($outype == "Service Unit") {

        // Load Grid For Service Unit Members

        $grid = new Grid("BpContents", $mysqli, array(
            "save" => false,
            "delete" => false,
            "where" => "OU_ABBREV = '$bpouabbrev' and BROADCAST_AY='$bpayname' and BP_OU_TYPE = '$outype' ",
            "fields" => array(
                "CONTENT_BRIEF_DESC" => "BpContents.CONTENT_BRIEF_DESC",
                "CONTENT_LINK" => "BpContents.CONTENT_LINK",
                "MOD_TIMESTAMP" => "DATE_FORMAT(BpContents.MOD_TIMESTAMP,'%Y-%m-%d %H:%i')",
                "CONTENT_STATUS" => "BpContents.CONTENT_STATUS",
                "ID_CONTENT" => "BpContents.ID_CONTENT",
                "ID_BROADCAST" => "broadcast.ID_BROADCAST",
                "BROADCAST_AY" => "broadcast.BROADCAST_AY",
                "OU_ABBREV" => "broadcast.OU_ABBREV",
                "BP_AUTHOR" => "CONCAT(PermittedUsers.LNAME,', ',PermittedUsers.FNAME)",
                "Sr_No" => "BpContents.Sr_No",

            ),
            "joins" => array(
                "inner JOIN broadcast ON BpContents.Linked_BP_ID = broadcast.ID_BROADCAST ",
                "left JOIN PermittedUsers ON PermittedUsers.ID_STATUS = BpContents.BP_AUTHOR"
            ),

//        date("F j, Y, g:i a", strtotime($rowbroad[2] ) );
// "select" => 'selectFunction'
        ));
    } else {

        // Load grid for Provost rights
        $grid = new Grid("BpContents", $mysqli, array(
            "save" => false,
            "delete" => false,
            "where" => "OU_ABBREV = '$bpouabbrev' and BROADCAST_AY='$bpayname'  ",
            "fields" => array(
                "CONTENT_BRIEF_DESC" => "BpContents.CONTENT_BRIEF_DESC",
                "CONTENT_LINK" => "BpContents.CONTENT_LINK",
                "MOD_TIMESTAMP" => "BpContents.MOD_TIMESTAMP",
                "BP_AUTHOR" => "CONCAT(PermittedUsers.FNAME,', ',PermittedUsers.LNAME)",
                "CONTENT_STATUS" => "BpContents.CONTENT_STATUS",
                "ID_CONTENT" => "BpContents.ID_CONTENT",
                "ID_BROADCAST" => "broadcast.ID_BROADCAST",
                "BROADCAST_AY" => "broadcast.BROADCAST_AY",
                "OU_ABBREV" => "broadcast.OU_ABBREV",
                "Sr_No" => "BpContents.Sr_No",

            ),

            "joins" => array(
                "left JOIN broadcast ON BpContents.Linked_BP_ID =broadcast.ID_BROADCAST ",
                "left JOIN PermittedUsers ON PermittedUsers.ID_STATUS =BpContents.BP_AUTHOR"
            ),
        ));

    }
}