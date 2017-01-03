<?php
session_start();



require_once("../../Resources/Includes/connect.php");
// require our class
require_once("grid.php");

if ($_SESSION['login_right'] == 7) {

    $grid = new Grid("DataDictionary", $mysqli, array(
        "save" => false,
        "delete" => false,

//        "where" =>

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

        "where" => "Status <> 'Proposed' ",

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