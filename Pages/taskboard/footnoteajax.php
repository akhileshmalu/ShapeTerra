<?php
session_start();

$author = $_SESSION['login_userid'];

//require_once("../../Resources/Includes/connect.php");
require_once("../../Resources/Includes/Initialize.php");
$initialize = new Initialize();
$mysqli = $initialize->mysqli;

// require our class
require_once("grid.php");

if ($_SESSION['login_right'] == 7) {

    $grid = new Grid("Footnotes", $mysqli, array(
        "save" => false,
        "delete" => false,

        "where" =>"FOOTNOTE_STATUS <> 'Archived'  ",

        "fields" => array(
            "FOOTNOTE_DESC" => "Footnotes.FOOTNOTE_DESC",
            "FOOTNOTE_ACAD_YEAR" => "Footnotes.FOOTNOTE_ACAD_YEAR",
            "TIMESTAMP" => "DATE_FORMAT(Footnotes.MOD_TIMESTAMP,'%Y-%m-%d %H:%i')",
            "LAST_MOD_BY" => "CONCAT(PermittedUsers.LNAME,', ',PermittedUsers.FNAME)",
            "ID_FOOTNOTE" => "Footnotes.ID_FOOTNOTE",
            "STATUS" => "Footnotes.FOOTNOTE_STATUS",
        ),
        "joins" => array(
            "LEFT JOIN PermittedUsers ON PermittedUsers.ID_STATUS = Footnotes.MOD_BY "
        ),
        // "select" => 'selectFunction'
    ));

} else {

    $grid = new Grid("Footnotes", $mysqli, array(
        "save" => false,
        "delete" => false,

        "where" => "FOOTNOTE_STATUS = 'Approved' or ( FOOTNOTE_STATUS = 'Proposed' and MOD_BY = '$author') ",

        "fields" => array(
            "FOOTNOTE_DESC" => "Footnotes.FOOTNOTE_DESC",
            "FOOTNOTE_ACAD_YEAR" => "Footnotes.FOOTNOTE_ACAD_YEAR",
            "TIMESTAMP" => "DATE_FORMAT(Footnotes.MOD_TIMESTAMP,'%Y-%m-%d %H:%i')",
            "LAST_MOD_BY" => "CONCAT(PermittedUsers.LNAME,', ',PermittedUsers.FNAME)",
            "ID_FOOTNOTE" => "Footnotes.ID_FOOTNOTE",
            "STATUS" => "Footnotes.FOOTNOTE_STATUS",
        ),
        "joins" => array(
            "LEFT JOIN PermittedUsers ON PermittedUsers.ID_STATUS = Footnotes.MOD_BY "
        ),
        // "select" => 'selectFunction'
    ));

}


?>