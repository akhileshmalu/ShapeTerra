<?php
session_start();
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

//$ouname = ;

require_once("../Resources/tcpdf/tcpdf.php");


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

$content .='<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<h1 style="text-align: center;"><span style="color: #800000;"><strong>Blueprint for Academic Excellence</strong></span></h1>
<p>&nbsp;</p>
<h1 style="text-align: center;">&nbsp;</h1>
<h1 style="text-align: center;"><span style="color: #800000;"><strong>';
$content .= $rowsmenu['OU_NAME'];
$content .= '</strong></span></h1>
<p>&nbsp;</p>
<h1 style="text-align: center;">&nbsp;</h1>
<h1 style="text-align: center;"><span style="color: #800000;">'.date('M-Y',strtotime($rowsbroad['ACAD_YEAR_DATE_END'])).'</span></h1>
<p style="text-align: center;"><span style="color: #800000;">&nbsp;</span></p>
<p style="text-align: center;">&nbsp;</p>
<p style="text-align: center;"><span style="color: #800000;"><img src="../Resources/Library/logo/USC.png" alt="" width="334" height="118" /></span></p>
<p style="text-align: center;">&nbsp;</p>
<p style="page-break-after:always;"></p>';

$content .='<p>&nbsp;</p>
<p>Executive Summary</p>
<hr />
<p>&nbsp;</p>
<p>&nbsp;</p>
<p id="exesumtitle1"></p>';


$obj_pdf->writeHTML($content);
$obj_pdf->Output('GoalSheet.pdf', 'I');

?>

