<?php
session_start();											  	//session Started
require_once ("../Resources/Includes/connect.php");          	//Instance of Object class-connection Created
$email = $_SESSION['login_email'];					  			//Session Variable store
$sql = " SELECT FNAME,LNAME,USER_OU_MEMBERSHIP FROM PermittedUsers where NETWORK_USERNAME ='$email' ";														//Query to Database
$result = $mysqli->query($sql);                             	// Query Execution
$rows = $result -> fetch_assoc();								//Fetching to Show on Account page
$_SESSION['login_fname'] = $rows['FNAME'];
$_SESSION['login_lname'] = $rows['LNAME'];
$ou = $rows['USER_OU_MEMBERSHIP'];
$_SESSION['login_ouid'] = $ou;

if($ou <> 4) {
	$sqltask = "select * from broadcast where BROADCAST_OU = '$ou';";
} else {
	$sqltask = "select * from broadcast;";
}
$result1 = $mysqli1->query($sqltask);

require_once("../Resources/Includes/header.php");
?>

<?php
require_once("../Resources/Includes/menu.php");
?>
<div class="row">
<div id="actionlist" class="col-lg-offset-8 col-lg-8">
		<h2>Task List</h2>
		<table class="table table-striped table-hover">
			<thead>
			<tr>
				<th>Task Description</th>
				<th>Task Status</th>
			</tr>
			</thead>
			<tbody>
			<?php while($rows1 = $result1->fetch_assoc()): ?>
			<tr class="info">
				<td><?php echo $rows1["BROADCAST_DESC"]; ?></td>
				<td><?php echo $rows1["BROADCAST_STATUS"]; ?></td>
			</tr>
			<?php endwhile; ?>
			</tbody>
		</table>
</div>
</div>
<?php
require_once("../Resources/Includes/footer.php");
?>
