<?php

/*TODO restrict by user's college*/

error_reporting(1);
@ini_set('display_errors', 1);


if (isset($_POST['functionNum']))
    $function = $_POST['functionNum'];

if (isset($_GET['functionNum']))
    $function = $_GET['functionNum'];

if (!empty($function)) {
//    require_once("../../Resources/Includes/ChartVisualizations.php");
//    $ChartVisualizations = new ChartVisualizations();

    require_once("visualClass.php");
    $ChartVisualizations = new visualClass();
}

switch ($function) {
    case 1:
        $ChartVisualizations->chartEnrollements();
        break;
    case 2:
        $ChartVisualizations->chartFaculty();
        break;
    case 3:
        $ChartVisualizations->chartDiversityStudent();
        break;
    case 4:
        $ChartVisualizations->chartDiversityPersonnel();
        break;
    case 5:
        $ChartVisualizations->exportToPng($_POST["imagebase"], $_POST["name"]);
        break;
    default:
        break;
}