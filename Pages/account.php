<?php
session_start();											  	//session Started
require_once ("../Resources/Includes/connect.php");          	//Instance of Object class-connection Created
$email = $_SESSION['login_email'];					  			//Session Variable store
$sql = " SELECT fname, email FROM user where email ='$email' ";														//Query to Database
$result = $mysqli->query($sql);                             	// Query Execution
$rows = $result -> fetch_assoc();								//Fetching to Show on Account page
$_SESSION['login_fname'] = $rows['fname'];

$mysqli->close();
require_once("../Resources/Includes/header.php");
?>

<!--
<!doctype html>
<html>
<head>
<link href="Css/layout.css" rel="stylesheet" type="text/css" />
<meta charset="UTF-8">
<title>Account Page</title>
</head>
-->
<link href="Css/layout.css" rel="stylesheet" type="text/css" />
<body>
<div id="Holder">
	<!--<div id="Header">-->
	<div id="NavBar">
		<nav>
			<ul>
				<li> <a href="profile.php">Profile</a></li>
				<li> <a href="logout.php">Log out</a></li>

			</ul>
		</nav>
	</div>
	<div id="Content">
		<div id="PageHeading">
			<h1>Welcome <?php echo $_SESSION['login_fname']; ?> ,</h1>				<!-- User first name Display -->
		</div>
		<div id="ContentLeft">
			<label for="accountlink">Account Links:<br> </label><br/><br/>
			<!--<h6> <b id="Welcome"> Tools  </b>  </h6> -->
			<br/> <br/>
			<a href="resetpassword.php" id="linkbutton">Reset Password </a> <br/> <br/>
			<a href="updateaccess.php" id="linkbutton">User Access</a> <br/> <br/>
			<a href="delete.php" id="linkbutton">Delete User</a> <br/> <br/>
		</div>
		<div id="ContentRight"></div>
	</div>
	<div id="Footer"></div>
</div>
<?php
require_once("../Resources/Includes/footer.php");
?>
<!--
</body>
</html>
-->