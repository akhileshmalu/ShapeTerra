<?php
session_start();											  	//session Started
require_once ("../Resources/Includes/connect.php");          	//Instance of Object class-connection Created
$email = $_SESSION['login_email'];					  			//Session Variable store
$sql = " SELECT FNAME,LNAME,USER_OU_MEMBERSHIP FROM PermittedUsers where NETWORK_USERNAME ='$email' ";														//Query to Database
$result = $mysqli->query($sql);                             	// Query Execution
$rows = $result -> fetch_assoc();								//Fetching to Show on Account page
$_SESSION['login_fname'] = $rows['FNAME'];
$_SESSION['login_lname'] = $rows['LNAME'];
$ouid = $rows['USER_OU_MEMBERSHIP'];
$_SESSION['login_ouid'] = $ouid;


require_once("../Resources/Includes/header.php");
?>

<link href="Css/account.css" rel="stylesheet" type="text/css" />

<!--
below headers for task board design purpose
-->

<link rel="stylesheet" href="dashboard/bootstrap/css/bootstrap.css"/>
<link rel="stylesheet" href="dashboard/bootstrap/css/bootstrap.min.css"/>
<link rel="stylesheet" href="dashboard/bootstrap/css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="dashboard/bootstrap/css/bootstrap-responsive.min.css"/>

<link rel="stylesheet" href="Css/grid.css" title="openJsGrid"/>
<script src="../Resources/Library/js/root.js"></script>
<script src="../Resources/Library/js/grid.js"></script>

<!--
  Above Headers are for Task Board design
-->

<?php
require_once("../Resources/Includes/menu.php");
?>


<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
	<div id="title-header">
		<h1 id="title">Dashboard</h1>
	</div>
	<div id="dashboard" class="col-lg-12">
		<table class="grid dashboard" action="dashboard/ajax.php" title="TaskBoard">
			<tr>
				<th col="BROADCAST_AY" type="text" width="200">Academic Year</th>
				<th col="BROADCAST_DESC" type="text" width="200">Description</th>
				<?php if ($ouid == 4) { ?>
					<th col="BROADCAST_STATUS" type="text" width="360">Status</th>
				<?php } else { ?>
					<th col="BROADCAST_STATUS_OTHERS" type="text" width="360">Status</th>
				<?php } ?>
			</tr>
		</table>
	</div>
</div>

<?php
require_once("../Resources/Includes/footer.php");
?>
<script src="../Resources/Library/js/dashboard.js"></script>