<?php

// Connect to MySql Database
$mysqli = new mysqli("localhost","provostuser","91Na$3qyfR(7","provostdata");
$site = “https://shapeterra.com”;

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