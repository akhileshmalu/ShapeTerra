<?php
session_start();											  //session Started
require_once ("connect.php");                                 //Instance of Object class-connection Created
$email = $_SESSION['login_email'];					  //Session Variable store

$sql = " SELECT fname, email FROM user where email ='$email' ";														//Query to Database
$result = $mysqli->query($sql);                             // Query Execution
$rows = $result -> fetch_assoc();							//Fetching to Show on Account page
$_SESSION['login_fname'] = $rows['fname'];

$mysqli->close();

?>


<!doctype html>
<html>
<head>
<link href="layout.css" rel="stylesheet" type="text/css" />
<meta charset="UTF-8">
<title>Account Page</title>
</head>

<body>
<div id="Holder">
	<!--<div id="Header">-->
	<div id="NavBar">
	<nav>
		<ul>
            <li> <a href="logout.php">Log out</a></li>
            <!--
			<li> <a href="Register.php">Register</a></li>
			<li> <a href="ForgotPassword.php">Fogot Password</a></li> -->
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

    </div>
    <div id="ContentRight"></div>
</div>
<div id="Footer"></div>
</div>
</body>
</html>
