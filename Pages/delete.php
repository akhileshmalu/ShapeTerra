<?php

session_start();
require_once ("../Resources/Includes/connect.php");
$error =array();

$sql = "Select * from user";
$result = $mysqli->query($sql);

if(isset($_POST['submit'])){

    if(!empty($_POST['email'])) {
        if(strcmp($_POST['email'],$_SESSION['login_email'])) {

            $email = $_POST['email'];
            $sql = "UPDATE user SET status = '-1' where email='$email'";

            if ($mysqli->query($sql)) {
                $error[0] = "User account has been successfully deleted.";
            } else {
                $error[0] = "User account could not be deleted. Please retry";
            }
        } else {
            $error[0] = "User cannot delete itself.";
        }

    } else {
        $error[0] = "Please select a user";
    }
}
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
            <a href="account.php" id = "linkbutton">Home Page</a>

        </div>
        <div id="ContentRight"></div>
        <form action ="" method="POST">

            <label>Please select User: </label> <br>
            <select name="email" class="StyleSheettext1">
                <option value ='0' selected></option>
                <?php while($row = $result ->fetch_array(MYSQLI_NUM)): if(strcmp($row[0],$_SESSION['login_email'])) {; ?>
                    <option value="<?php echo $row[0]; ?>"> <?php echo $row[0]; ?> </option>
                <?php } else { continue;} endwhile; ?>
            </select>
            <br>
            <label id="error"> <?php foreach ($error as $value)echo $value; ?> <br> </label> <br>
            <input type="submit" name="submit" value="Delete" id="reset" class="StyleSheettext2">
            <br>
        </form>
    </div>
</div>
<?php
require_once("../Resources/Includes/footer.php");
?>
<!--
</body>
</html>
-->