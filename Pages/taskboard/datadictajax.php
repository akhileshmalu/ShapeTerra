<?php
session_start();

$author = $_SESSION['login_userid'];

require_once("../../Resources/Includes/connect.php");

// require Grid Class
require_once("grid.php");

if ($_SESSION['login_right'] == 7) {

    $grid = new Grid("DataDictionary", $mysqli, array(
        "save" => false,
        "delete" => false,

        "where" =>"STATUS <> 'Archived'  ",

        "fields" => array(
            "DATA_ELMNT_FUNC_NAME" => "DataDictionary.DATA_ELMNT_FUNC_NAME",
            "RESPONSIBLE_PARTY" => "DataDictionary.RESPONSIBLE_PARTY",
            "TIMESTAMP" => "DATE_FORMAT(DataDictionary.MOD_TIMESTAMP,'%Y-%m-%d %H:%i')",
            "LAST_MOD_BY" => "CONCAT(PermittedUsers.LNAME,', ',PermittedUsers.FNAME)",
            "ID_DATA_ELEMENT" => "DataDictionary.ID_DATA_ELEMENT",
            "STATUS" => "DataDictionary.STATUS",
        ),
        "joins" => array(
            "LEFT JOIN PermittedUsers ON PermittedUsers.ID_STATUS = DataDictionary.MOD_BY "
        ),
        // "select" => 'selectFunction'
    ));

} else {

    $grid = new Grid("DataDictionary", $mysqli, array(
        "save" => false,
        "delete" => false,

        "where" => "STATUS = 'Approved' or ( STATUS = 'Proposed' and MOD_BY = '$author') ",

        "fields" => array(
            "DATA_ELMNT_FUNC_NAME" => "DataDictionary.DATA_ELMNT_FUNC_NAME",
            "RESPONSIBLE_PARTY" => "DataDictionary.RESPONSIBLE_PARTY",
            "TIMESTAMP" => "DATE_FORMAT(DataDictionary.MOD_TIMESTAMP,'%Y-%m-%d %H:%i')",
            "LAST_MOD_BY" => "CONCAT(PermittedUsers.LNAME,', ',PermittedUsers.FNAME)",
            "ID_DATA_ELEMENT" => "DataDictionary.ID_DATA_ELEMENT",
            "STATUS" => "DataDictionary.STATUS",
        ),
        "joins" => array(
            "LEFT JOIN PermittedUsers ON PermittedUsers.ID_STATUS = DataDictionary.MOD_BY "
        ),
        // "select" => 'selectFunction'
    ));

}




?>