<?php
session_start();

$ouid = $_SESSION['login_ouid'];
$outype = $_SESSION['login_outype'];
$FUayname = $_SESSION['FUayname'];

require_once("../../Resources/Includes/connect.php");

require_once("grid.php");


    // Load Grid For Service Unit Members
    $grid = new Grid("IR_SU_UploadStatus", $mysqli, array(
        "save" => false,
        "delete" => false,
        "where" => "OUTCOME_AY = '$FUayname' ",
        "fields" => array(
            "NAME_UPLOADFILE" => "IR_SU_UploadStatus.NAME_UPLOADFILE",
            "OUTCOME_AY" =>  "IR_SU_UploadStatus.OUTCOME_AY",
            "MOD_TIMESTAMP" => "DATE_FORMAT(IR_SU_UploadStatus.LAST_MODIFIED_ON,'%Y-%m-%d %H:%i')",
            "STATUS_UPLOADFILE" => "IR_SU_UploadStatus.STATUS_UPLOADFILE",
            "ID_UPLOADFILE" => "IR_SU_UploadStatus.ID_UPLOADFILE",
            "OU_ABBREV" => "IR_SU_UploadStatus.OU_ABBREV",
            "LINK_UPLOADFILE" => "IR_SU_UploadStatus.LINK_UPLOADFILE",
            "FILE_AUTHOR" => "CONCAT(PermittedUsers.LNAME,', ',PermittedUsers.FNAME)",

        ),
        "joins" => array(
            "left JOIN PermittedUsers ON PermittedUsers.ID_STATUS = IR_SU_UploadStatus.LAST_MODIFIED_BY"
        ),

//        date("F j, Y, g:i a", strtotime($rowbroad[2] ) );
// "select" => 'selectFunction'
    ));