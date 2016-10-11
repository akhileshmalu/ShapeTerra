<?php
session_start();
require_once ("../Resources/includes/connect.php");
$error =array();

$sql = "Select * from user";
$result = $mysqli->query($sql);

if(isset($_POST['submit'])){

    if(!empty($_POST['email']))
    {
        if($_POST['rights']!=0) {

            $email = $_POST['email'];
            $rights = $_POST['rights'];
            $rightdefine="";

            switch ($rights){
                case '1':
                    $rightdefine ="User";
                    break;
                case '2':
                    $rightdefine = "Super User";
                    break;
                case '3':
                    $rightdefine = "Admin";
                    break;
                case '4':
                    $rightdefine = "Super Admin";
                    break;
                default:
                    $rightdefine="Undefined";
                    break;
            }

            $sql = "UPDATE user SET rights='$rights' where email='$email'";

            if ($mysqli->query($sql)) {

                //Confirmation Mail Variables
                $sub = "Your privileges has been changed successfully.";
                $msg = "Hello" . "<br/>" ."<br/>";
                $msg .= "Administrator has modified your user privileges to '$rightdefine''"."<br>"."<br/><br/>"."Thank you for giving us a chance to serve you";
                mail($email, $sub, $msg, $headers);

                $error[0] = "Privileges Updated Successfully";
            } else {
                $error[0] = "Privileges could not be updated. Please retry";
            }
        } else {
            $error [0] = "Please select valid user privilege";
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
    <select name="email" class="StyleSheettext1" >
        <option value =""></option>
        <?php while($row = $result ->fetch_array(MYSQLI_NUM)): if(strcmp($row[0],$_SESSION['login_email'])) {; ?>
            <option value="<?php echo $row[0]; ?>"> <?php echo $row[0]; ?> </option>
        <?php } else { continue;} endwhile; ?>
    </select>
    <br>
    <label>Please select Privilege level: </label> <br>
    <select name="rights" class="StyleSheettext1">
        <option value=''>  </option>
        <option value='1'> User </option>
        <option value='2'> Super User </option>
        <option value='3'> Admin </option>
        <option value='4'> Super Admin </option>
    </select>

    <br>
    <label id="error"> <?php foreach ($error as $value)echo $value; ?> <br> </label> <br>
    <input type="submit" name="submit" value="update" id="reset" class="StyleSheettext2">
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