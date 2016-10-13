<?php
// session_start();											  	//session Started
// require_once ("../Resources/Includes/connect.php");          	//Instance of Object class-connection Created
// $email = $_SESSION['login_email'];					  			//Session Variable store
// $sql = " SELECT fname, email FROM user where email ='$email' ";														//Query to Database
// $result = $mysqli->query($sql);                             	// Query Execution
// $rows = $result -> fetch_assoc();								//Fetching to Show on Account page
// $_SESSION['login_fname'] = $rows['fname'];

// $mysqli->close();
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
<?php
require_once("../Resources/Includes/menu.php");
?>
</div>
<?php
require_once("../Resources/Includes/footer.php");
?>
<!--
</body>
</html>
-->