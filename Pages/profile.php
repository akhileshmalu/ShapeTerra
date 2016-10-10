<?php
session_start();
$error = array();                                               //Error Array Created
$errorflag = 0;                                                 //Flag Create

if(isset($_POST['submit'])) {
    if (empty($_POST['fname'])) {                            //Error handling via error array
        $error[0] = " Please enter first name.";
        $errorflag = 1;
    }
    if (empty($_POST['lname'])) {
        $error[1] = " Please enter last name.";
        $errorflag = 1;
    }
    if($errorflag == 0){

        require_once ("../Resources/Includes/connect.php");                          // Established Connection

        $fname = test_input($_POST['fname']);                  //Secured Input
        $lname = test_input($_POST['lname']);
        $email = $_SESSION['login_email'];


        $sql = "Update user SET fname = '$fname' where email = '$email'";

        if($mysqli -> query($sql)) {
            $error[0] = "Profile Updated Successfully.";
        } else $error [0] = "Registration Failed. Please retry";

        $mysqli ->close();

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
        <form ACTION="" id="updateprofile" name="updateprofileform" method="POST">
            <table width="400" border="0" align="center">
                <tbody>
                <tr>
                    <td><input name="fname" type="text" placeholder="Enter your First Name" class="StyleSheettext1" id="newpassword" required></td>
                </tr>
                <tr>
                    <td><input name="lname" type="text" placeholder="Enter your Last name" class="StyleSheettext1" id="conpassword" required></td>
                </tr>
                <tr>
                    <td> <br> <input type="submit" name="submit" id="reset" class = "StyleSheettext2" value="Update"></td>
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