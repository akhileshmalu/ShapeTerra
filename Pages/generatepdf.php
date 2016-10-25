<?php

require_once ("../Resources/Includes/connect.php");
$sql = "select * from UniversityGoals";
$result = $mysqli->query($sql);

require_once ("../Resources/Includes/pdfconnect.php");
$content = '';
$content .= '<h3 align="center">Academic BluePrint Goal sheet</h3><br /><br />
<table border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th width="5%">ID</th>
        <th width="30%">Academic Years</th>
        <th width="20%">Goal Title</th>
        <th width="45%">Goal Statement</th>
    </tr>';
while($row = $result ->fetch_array(MYSQLI_NUM)) {
    $content .= '<tr>
    <td>' . $row[0] . '</td>
    <td>' . $row[1] . '</td>
    <td>' . $row[2] . '</td>
    <td>' . $row[3] . '</td></tr>';
}

    $content .= '</table>';
    $obj_pdf->writeHTML($content);
    $obj_pdf->Output('GoalSheet.pdf', 'I');

?>