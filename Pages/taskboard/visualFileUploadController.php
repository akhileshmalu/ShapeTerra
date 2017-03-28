<?php

/*TODO restrict by user's college*/

if (isset($_POST['functionNum']))
    $function = $_POST['functionNum'];

if (isset($_GET['functionNum']))
    $function = $_GET['functionNum'];

if (!empty($function)) {
//    require_once("../../Resources/Includes/ChartVisualizations.php");
//    $ChartVisualizations = new ChartVisualizations();

    require_once("visualClass.php");
    $ChartVisualizations = new visualClass();
    $ChartVisualizations->checkSessionStatus();
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
    case 6:
        require_once("../../Resources/Includes/FILEUPLOAD.php");
        $ouname = $_GET['ouchoice'];
        $fileupload = new FILEUPLOAD();
        $resultTableName = $fileupload->GetStatus();
        $rowsTableName = $resultTableName->fetch(2);
        $tablename = $rowsTableName['NAME_UPLOADFILE'];
        $primaryKey = $fileupload->GetPrimaryKey($tablename);
        $option = " OU_ABBREV = '$ouname' AND ";
        $dynamicTable = $fileupload->HTMLtable($tablename,$primaryKey,$option);
        echo $dynamicTable;
        break;
    default:
        break;
}