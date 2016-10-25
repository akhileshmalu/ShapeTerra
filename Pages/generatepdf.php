<?php

function pdfgoal() {
    require_once ("../Resources/Includes/connect.php");
    $sql = "select * from UniversityGoals";
    $result = $mysqli->query($sql);
    while($row = $result ->fetch_array(MYSQLI_NUM)) {
    $output .= '<tr>
    <td>' . $row[0] . '</td>
    <td>' . $row[1] . '</td>
    <td>' . $row[2] . '</td>
    <td>' . $row[3] . '</td></tr>';
    }
    return $output;
}

//if(isset($_POST["create_pdf"]))
//{
require_once('../Resources/tcpdf/tcpdf.php');
$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf->SetCreator(PDF_CREATOR);
$obj_pdf->SetTitle("Export HTML Table data to PDF using TCPDF in PHP");
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
$content = '';
$content .= '
<h3 align="center">Academic BluePrint Goal sheet</h3><br /><br />
<table border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th width="5%">ID</th>
        <th width="30%">Academic Years</th>
        <th width="20%">Goal Title</th>
        <th width="45%">Goal Statement</th>
    </tr>
    ';
    $content .= pdfgoal();
    $content .= '</table>';
$obj_pdf->writeHTML($content);
$obj_pdf->Output('sample.pdf', 'I');
//}
?>