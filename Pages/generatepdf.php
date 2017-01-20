<?php
session_start();
if(!$_SESSION['isLogged']) {
    header("location:login.php");
    die();
}
require_once ("../Resources/Includes/connect.php");

$email = $_SESSION['login_email'];
$sqlmenu = "select USER_ROLE,OU_NAME,USER_RIGHT,SYS_USER_ROLE,SYS_USER_RIGHT, OU_ABBREV,FNAME,LNAME from PermittedUsers inner join UserRights on PermittedUsers.SYS_USER_RIGHT = UserRights.ID_USER_RIGHT
inner join UserRoles on PermittedUsers.SYS_USER_ROLE = UserRoles.ID_USER_ROLE
inner join Hierarchy on PermittedUsers.USER_OU_MEMBERSHIP = Hierarchy.ID_HIERARCHY WHERE  NETWORK_USERNAME ='$email';";
$resultmenu = $menucon->query($sqlmenu);
$rowsmenu = $resultmenu ->fetch_assoc();
$_SESSION['login_ouabbrev'] = $rowsmenu['OU_ABBREV'];
$ouabbrev =$rowsmenu['OU_ABBREV'];

$sqlbroad = "SELECT * FROM broadcast inner join Hierarchy on BROADCAST_OU = Hierarchy.ID_HIERARCHY INNER join AcademicYears on BROADCAST_AY=AcademicYears.ACAD_YEAR_DESC where Hierarchy.OU_ABBREV = '$ouabbrev';";
$resultbroad=$mysqli1->query($sqlbroad);
$rowsbroad = $resultbroad->fetch_assoc();
$aydesc = $rowsbroad['BROADCAST_AY'];


$sqlmvv = "Select * from BP_MissionVisionValues;";
$resultmvv = $mysqli->query($sqlmvv);
$rowsmvv = $resultmvv->fetch_assoc();
$goalcount =1;

$sqlunit = "select * from BP_UnitGoals a inner join BP_UnitGoalOutcomes b on a.ID_UNIT_GOAL=b.ID_UNIT_GOAL where find_in_set ('$aydesc',UNIT_GOAL_AY)>0 and OU_ABBREV ='$ouabbrev';";
$resultunit = $mysqli->query($sqlunit);



require_once("../tcpdf/tcpdf.php");


define("PDF_HEADER_TITLE","Print" );
define("PDF_CREATOR","Academic BluePrint" );
define ('PDF_AUTHOR', 'USC');
define ('PDF_HEADER_STRING', "USC Development");


$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf->SetCreator(PDF_CREATOR);
$obj_pdf->SetTitle("Academic BluePrint");
$obj_pdf->setHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$obj_pdf->SetDefaultMonospacedFont('helvetica');
$obj_pdf->setFooterMargin(PDF_MARGIN_FOOTER);
$obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
$obj_pdf->setPrintHeader(false);
$obj_pdf->setPrintFooter(false);
$obj_pdf->SetAutoPageBreak(TRUE, 10);
$obj_pdf->SetFont('helvetica', '', 12);
$obj_pdf->AddPage();

//$content .='<p>&nbsp;</p>
//<p>&nbsp;</p>
//<p>&nbsp;</p>
//<p>&nbsp;</p>
//<p>&nbsp;</p>
//<p>&nbsp;</p>
//<h1 style="text-align: center;"><span style="color: #800000;"><strong>Blueprint for Academic Excellence</strong></span></h1>
//<p>&nbsp;</p>
//<h1 style="text-align: center;">&nbsp;</h1>
//<h1 style="text-align: center;"><span style="color: #800000;"><strong>';
//$content .= $rowsmenu['OU_NAME'];
//$content .= '</strong></span></h1>
//<p>&nbsp;</p>
//<h1 style="text-align: center;">&nbsp;</h1>
//<h1 style="text-align: center;"><span style="color: #800000;">'.date('M-Y',strtotime($rowsbroad['ACAD_YEAR_DATE_END'])).'</span></h1>
//<p style="text-align: center;"><span style="color: #800000;">&nbsp;</span></p>
//<p style="text-align: center;">&nbsp;</p>
//<p style="text-align: center;"><span style="color: #800000;"><img src="../Resources/Library/logo/USC.png" alt="" width="334" height="118" /></span></p>
//<p style="text-align: center;">&nbsp;</p>
//<p style="page-break-after:always;"></p>';
//
//$content .='<p>&nbsp;</p>
//<p>Executive Summary</p>
//<hr />
//<p>&nbsp;</p>
//<p>&nbsp;</p>
//<p id="exesumtitle1"></p>';

$content .='
<head>
<link rel="stylesheet" href="../Pages/blueprint/Blueprinthtml/base.min.css"/>
<link rel="stylesheet" href="../Pages/blueprint/Blueprinthtml/fancy.min.css"/>
<link rel="stylesheet" href="../Pages/blueprint/Blueprinthtml/main.css"/>


</head>

<div class="pc pc1 w0 h0">
            <div class="t m0 x0 h1 y0 ff1 fs0 fc0 sc0 ls0 ws0"><strong><p  style="color:rgb(140,38,51);font-size:40px; text-align: center">Blueprint for Academic Excellence
            </div>
            <div
                class="t m0 x3 h2 y3 ff1 fs0 fc1 sc0 ls0 ws0"><p style="color:rgb(0,0,0);font-size:30px; text-align: center">'. $rowsmenu['OU_NAME'] . '</p></div>
            <div class="t m0 x4 h2 y4 ff2 fs0 fc0 sc0 ls0 ws0"><p style="color:rgb(140,38,51);font-size:30px; text-align: center">' . date('M-Y',strtotime($rowsbroad['ACAD_YEAR_DATE_END'])). '</p></div>
            <p style="text-align: center;"><span style="color: #800000;"><img src="../Resources/Library/logo/USC.png" alt="" width="334" height="118" /></span></p>
        <p style="page-break-after:always;"></p>
</div>
<div class="pc pc2 w0 h0">
            <h1 style="font-size:16px;padding:auto;">Executive Summary</h1>
            <hr />
            <div class="t m0 x3 h5 y7 ff2 fs2 fc1 sc0 ls0 ws0"><span class="_ _0"></span>'. $rowsbroad['BROADCAST_EXECSUM'].'
            </div>
            <p style="page-break-after:always;"></p>
            <span class="_ _2"> </span><p><br/></p>
            <h1 style="font-size:16px;padding:auto;">Blueprint for Academic Excellence</h1>
            <hr />
            <p>'.$rowsmenu['OU_NAME'].'</p>
<p>'. $rowsmenu['FNAME'].",".$rowsmenu['LNAME']. "," . $rowsmenu['USER_RIGHT'] . '</p>
<p>&nbsp;</p>
<p>Executive Summary &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 2</p>
<p>Blueprint for Academic Excellence&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;  3</p>
<p>&nbsp; &nbsp;Mission &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 4</p>
<p>&nbsp; &nbsp;Vision &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 4</p>
<p>&nbsp; &nbsp;Values &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;4</p>
<p>&nbsp; &nbsp;Goals &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;4</p>
<p>Outcomes &ndash; 2015-2016 Academic Year &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;6</p>
<p>&nbsp; &nbsp;Faculty Development &amp; Activities &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 6</p>
<p>&nbsp; &nbsp;Faculty Awards &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 6</p>
<p>&nbsp; &nbsp;Personnel &ndash; Faculty &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 7</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p style="page-break-after:always;"></p><span class="_ _2"> </span><p><br/></p>

<hr />
<h1 style="font-size:16px;">Mission</h1>
<hr />
<p>'.$rowsmvv['MISSION_STATEMENT'].'</p><br/>
<hr />
<h1 style="font-size:16px;">Vision</h1>
<hr />
<p>'.$rowsmvv['VISION_STATEMENT'].'</p><br/>
<hr />
<h1 style="font-size:16px;">Vision</h1>
<hr />
<p>'.$rowsmvv['VALUES_STATEMENT'].'</p>
<p style="height=40pt;"><br/></p>

<hr />
<h1 style="font-size:16px;">Goals</h1>
<hr />';
while ($rowsunit = $resultunit->fetch_array(MYSQLI_ASSOC)) {
    $content .= '<p>Goal'.$goalcount.'  &ndash; &lt;' . $rowsunit['UNIT_GOAL_TITLE'].'&gt;</p>';
//    $content .= '<p style="color: silver"> <hr/></p>';
$content .= '<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;<strong>Goal Achievements :</strong>&nbsp;&nbsp;&nbsp; '.$rowsunit['GOAL_ACHIEVEMENTS'].'</p>';
}



$obj_pdf->writeHTML($content);
$obj_pdf->Output('GoalSheet.pdf', 'I');

?>

