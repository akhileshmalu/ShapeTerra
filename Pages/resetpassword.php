<?php
session_start();
$error = array();
$errorflag = 0;



if(isset($_POST['reset'])){
    if (empty($_POST['newpassword'])) {
        $error[1] = " Please enter new password.";
        $errorflag = 1;
    }
    if (empty($_POST['confirmpassword'])) {
        $error[2] = " Please confirm new password.";
        $errorflag = 1;
    }
    if (strcmp($_POST['newpassword'],$_POST['confirmpassword'])) {
        $error[3] = " Confirmed Password does not match with new Password.";
        $errorflag = 1;
    }
    if($errorflag == 0) {

        require_once("../Resources/includes/connect.php");
        $newpassword = md5(test_input($_POST['newpassword']));
        $email = $_SESSION['login_email'];

        $sql = "UPDATE user SET password = '$newpassword' WHERE email = '$email'";
        if ($mysqli->query($sql)) {

            //Confirmation Mail Variables
            $sub = "You have successfully updated your password.";
            $msg = "Hello" . "<br/>" ."<br/>";
            $msg .= "You have succesfully updated your password"."<br>". "You may login with your new password."."<br/><br/>" . "Thank you for giving us a chance to serve you";
            mail($email, $sub, $msg, $headers);

            $error[0] = "Password Updated Successfully";
        } else {
            $error[0] = "Password could not be updated. Please retry";
        }


        $mysqli->close();


    }
}
require_once ("../Resources/Includes/header.php");
?>

<!--
<!doctype html>
<html>
<head>
    <link href="layout.css" rel="stylesheet" type="text/css" />
    <meta charset="UTF-8">
    <title>Account Page</title>
</head>
-->
    <link href="Css/layout.css" rel="stylesheet" type="text/css" />
    <body>
<div id="Holder">
   <!-- <div id="Header"></div> -->
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
            <h1>Welcome ,</h1>				<!-- User first name Display -->
        </div>
        <div id="ContentLeft">
            <label for="accountlink">Account Links:<br> </label><br/><br/><br/>
            <a href="account.php" id="linkbutton">Home Page</a></><br>
        </div>
        <div id="ContentRight">
            <form ACTION="" id="resetpassword" name="resetpasswordform" method="POST">
                <table width="400" border="0" align="center">
                    <tbody>
                    <tr>
                        <td><input name="newpassword" type="password" placeholder="Enter your New password" class="StyleSheettext1" id="newpassword" required></td>
                    </tr>
                    <tr>
                        <td><input name="confirmpassword" type="password" placeholder="Confirm your new password" class="StyleSheettext1" id="conpassword" required></td>
                    </tr>
                    <tr>
                        <td> <br> <input type="submit" name="reset" id="reset" class = "StyleSheettext2" value="Reset Password"></td>
                    </tr>
                    <tr>
                        <td>
                            <br> <label id="error"> <?php foreach ($error as $value)echo $value; ?> <br> </label>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
    <div id="Footer"></div>
</div>
<?php
require_once("../Resources/Includes/footer.php");
?>