<?php
session_start();
if(session_destroy()) // Destroying All Sessions
{
    unset($mysql);
    unset($result);
    unset($rows);
    $connection = null;


    header("Location: login.php"); // Redirecting To Home Page
}
