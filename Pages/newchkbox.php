<?php

require_once ("../Resources/Includes/initalize.php");
$initalize = new Initialize();
$initalize->checkSessionStatus();
$connection = $initalize->connection;

global $sqlcreatebp;
$sqlcreatebp = "";
$goalmodalcount=1000;

$ouabbrev = $_SESSION['login_ouabbrev'];

/*
 * Query handler for Input from Broadcast record <blueprint Intiation>
 */

$sqlbroad = "SELECT * FROM broadcast inner join Hierarchy on BROADCAST_OU = Hierarchy.ID_HIERARCHY where Hierarchy.OU_ABBREV = '$ouabbrev';";
$resultbroad = $mysqli1->query($sqlbroad);
$rowsbroad = $resultbroad->fetch_assoc();
$ay = $rowsbroad['BROADCAST_AY'];
$ayid = stringtoid($ay);

/*
 * Calculate Previous Year String
 */
$prevay = $ayid - 101;
$aydesc = idtostring($prevay);

$author = $_SESSION['login_email'];
$time = date('Y-m-d H:i:s');


if(isset($_POST['goal_submit'])) {
    $goaltitle = $_POST['goaltitle'];

    $unigoallink = $_POST['goallink'];
    foreach ($unigoallink as $value) {
        $unigoallinkname .= $value . ",";
    }
    $goalstatement = mynl2br($_POST['goalstatement']);
    $goalalignment = mynl2br($_POST['goalalignment']);

    $sqlcreatebp .= "INSERT INTO tempunitgoals ( OU_ABBREV, GOAL_AUTHOR, MOD_TIMESTAMP, UNIT_GOAL_AY, UNIT_GOAL_TITLE, LINK_UNIV_GOAL, GOAL_STATEMENT, GOAL_ALIGNMENT) VALUES ('$ouabbrev','$author','$time','$ay','$goaltitle','$unigoallinkname','$goalstatement','$goalalignment');";


    $goalmodalcount++;


    $mysqli->query($sqlcreatebp);



}

$_SESSION['postdata'] = $_POST;
unset($_POST);
unset($_REQUEST);


header('location:newchkbox.php');
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );

echo '
<script type="text/javascript">
location.reload();
</script>';

?>
