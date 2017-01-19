<?php

//Setting Default Time
date_default_timezone_set('America/New_York');
ini_set("date.timezone", "America/New_York");

// Connect to MySql Database
$db="TESTDB";

$mysqli = new mysqli("localhost","root","root",$db);
$mysqli1 = new mysqli("localhost","root","root",$db);
$mysqli2 = new mysqli("localhost","root","root",$db);


$site = "localhost:8888/shapeterra";

$menucon= new mysqli("localhost","root","root",$db);

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
$headers .= "From: ShapeTerra <admin@ShapeTerra.com>" . "\r\n";

/*Common String function to interchange AY-ID and Desc
*/
// When there is start date and end date on form.
function stringdatestoid ($string1, $string2){

    $id1 = intval(substr($string1,2,2));
    $id2= intval(substr($string2,2,2));
    $id = ($id1*100)+$id2;
    return $id;
}
// e.g. 1617 - AY2016-2017
function idtostring ($id){
    $id2= $id %100;
    $id1= intval($id/100);

    if($id2<$id1) {
        $id1 =str_pad($id1, 2, '0', STR_PAD_LEFT);
        $id2 =str_pad($id2, 2, '0', STR_PAD_LEFT);
        $string = "AY19".$id1."-20".$id2;
        return $string;
    }
    else {
        $id1 = str_pad($id1, 2, '0', STR_PAD_LEFT);
        $id2 =str_pad($id2, 2, '0', STR_PAD_LEFT);
        $string = "AY20" . $id1 . "-20" . $id2;
        return $string;
    }
}
// e.g. AY2016-2017 to 1617.
function stringtoid ($string){

    $id = intval(substr($string,4,2));
    $id = ($id*100)+$id+1;
    return $id;
}
/*
 * Function for preserving HTML line breaks in Text area
 */
function mynl2br($text) {
    return strtr($text, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />'));
}

function mybr2nl($text) {
    return strtr($text, array("<br />" => "\r\n"));
}
