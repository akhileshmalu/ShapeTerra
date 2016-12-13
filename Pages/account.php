<?php
session_start();										  	//session Started
require_once ("../Resources/Includes/connect.php");          	//Instance of Object class-connection Created
$email = $_SESSION['login_email'];					  			//Session Variable store
$sql = " SELECT FNAME,LNAME,USER_OU_MEMBERSHIP FROM PermittedUsers where NETWORK_USERNAME ='$email' ";														//Query to Database
$result = $mysqli->query($sql);                             	// Query Execution
$rows = $result -> fetch_assoc();								//Fetching to Show on Account page
$_SESSION['login_fname'] = $rows['FNAME'];
$_SESSION['login_lname'] = $rows['LNAME'];
$ouid = $rows['USER_OU_MEMBERSHIP'];
$_SESSION['login_ouid'] = $ouid;

//Menu control for back to dashboard button
//true: Dont show button
//false: show button
$notBackToDashboard = true;

require_once("../Resources/Includes/header.php");
?>

<!--
below headers for task board design purpose
-->

<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrapTable.css"/>
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="taskboard/bootstrap/css/bootstrap-responsive.min.css"/>


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
		<h1 id="title" class="">Home</h1>
	</div>

	<!-- Possible Greeting box -->
	<div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Hello <?php echo $rows['FNAME']; ?>! </h1>
        <p class="status"><span>Orginzation Unit: </span> <?php echo $rowsmenu['OU_ABBREV']; ?></p>
        <p class="status"><span>User role: </span> <?php echo $rowsmenu['USER_RIGHT']; ?></p>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
    	<h1 class="box-title">Select an Academic Year</h1>
		<div id="taskboard" class="">
			<table class="taskboard" action="taskboard/accountajax.php" title="TaskBoard">
				<tr>
					<th col="BROADCAST_AY" type="text" href="../Pages/bphome.php?ayname={{value}}">Academic Year</th>
					<th col="BROADCAST_DESC" type="text">Description</th>
					<?php if ($ouid == 4) { ?>
						<th col="BROADCAST_STATUS" type="text">Status</th>
					<?php } else { ?>
						<th col="BROADCAST_STATUS_OTHERS" type="text">Status</th>
					<?php } ?>
	<!--				<th col="Menucontrol" type="text" href="http://google.com?q={{value}}">Actions</th>-->
				</tr>
			</table>
		</div>
</div>

<?php
require_once("../Resources/Includes/footer.php");
?>
<script src="../Resources/Library/js/taskboard.js"></script>