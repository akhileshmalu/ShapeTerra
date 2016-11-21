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

if($ouid <> 4) {
	$sqltask = "select * from broadcast where BROADCAST_OU = '$ouid';";
} else {
	$sqltask = "select * from broadcast;";
}
$resulttask = $mysqli1->query($sqltask);

require_once("../Resources/Includes/header.php");
?>

<link href="Css/account.css" rel="stylesheet" type="text/css" />

<?php
require_once("../Resources/Includes/menu.php");
?>



<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
	<div id="title-header">
    	<h1 id="title">Dashboard</h1> 
    </div>
	<table class="table table-striped table-hover col-xs-12">
		<thead>
		<tr>
			<th>Task Description</th>
			<th>Task Status</th>
		</tr>
		</thead>
		<tbody>
		<?php while($rowstask = $resulttask->fetch_assoc()): ?>
		<tr class="">
			<td><?php echo $rowstask["BROADCAST_DESC"]; ?></td>
			<td><?php echo $rowstask["BROADCAST_STATUS"]; ?></td>
		</tr>
		<?php endwhile; ?>
		</tbody>
	</table>
   
</div>

<?php
require_once("../Resources/Includes/footer.php");
?>
