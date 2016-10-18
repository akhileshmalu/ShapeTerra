<?php

// Connect to MySql Database
$mysqli = new mysqli("localhost","root","root","TESTDB");
$site = "localhost:8888/shapeterra";

/*
 * Menu directive for local server
 * Menu.php will utilize navdir variable to redirect to local host pages.
 * Server does not need navdir path.
 */
$navdir = "Shapeterra/";

// Check Connection Status
if($mysqli -> connect_error){
    echo "Connection Failed". $mysqli->connect_error;
}

// Input Security Check
function test_input($data){
    $data = trim($data);
    $data = htmlspecialchars($data,ENT_COMPAT,'ISO-8859-1', true);
    $data = htmlentities($data,ENT_COMPAT,'ISO-8859-1', true);
    return $data;
}

//Global Email Variable
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: ShapeTerra <admin@ShapeTerra.com>' . "\r\n";

?>
