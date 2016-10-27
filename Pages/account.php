<?php
session_start();											  	//session Started
require_once ("../Resources/Includes/connect.php");          	//Instance of Object class-connection Created
$email = $_SESSION['login_email'];					  			//Session Variable store
$sql = " SELECT FNAME,LNAME FROM PermittedUsers where NETWORK_USERNAME ='$email' ";														//Query to Database
$result = $mysqli->query($sql);                             	// Query Execution
$rows = $result -> fetch_assoc();								//Fetching to Show on Account page
$_SESSION['login_fname'] = $rows['FNAME'];
$_SESSION['login_lname'] = $rows['LNAME'];




require_once("../Resources/Includes/header.php");
?>

<?php
	require_once("../Resources/Includes/menu.php");
?>
<?php
require_once("../Resources/Includes/footer.php");
?>
