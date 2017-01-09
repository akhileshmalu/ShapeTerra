<?php

require_once("../../Resources/Includes/connect.php");

//Uniqueness check of Functional name

if($_POST['funcname']) {
    $funcname = $_POST['funcname'];
    $sqluniqchk = "select * from DataDictionary where DATA_ELMNT_FUNC_NAME = '$funcname';";
    $resultuniqchk = $mysqli->query($sqluniqchk);

    if ($resultuniqchk->num_rows > 0) {
        echo 0;
    } else {
        echo 1;
    }
}

if($_POST['tecname']) {
    $techname = $_POST['tecname'];
    $sqluniqchk = "select * from DataDictionary where DATA_ELEMENT_TECH_NAME = '$techname';";
    $resultuniqchk = $mysqli->query($sqluniqchk);

    if ($resultuniqchk->num_rows > 0) {
        echo 0;
    } else {
        echo 1;
    }
}

if($_POST['ftitle']) {
    $ftitle = $_POST['ftitle'];
    $sqluniqchk = "select * from Footnotes where FOOTNOTE_DESC = '$ftitle';";
    $resultuniqchk = $mysqli->query($sqluniqchk);

    if ($resultuniqchk->num_rows > 0) {
        echo 0;
    } else {
        echo 1;
    }
}
