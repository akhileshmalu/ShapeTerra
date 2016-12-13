<?php
session_start();
$error = array();
$errorflag = 0;
$fname = $_SESSION['login_fname'];


if(isset($_POST['reset'])){
    if (empty($_POST['newpassword'])) {
        $error[0] = " Please enter new password.";
        $errorflag = 1;
    }
    if (empty($_POST['confirmpassword'])) {
        $error[1] = " Please confirm new password.";
        $errorflag = 1;
    }
    if (strcmp($_POST['newpassword'],$_POST['confirmpassword'])) {
        $error[2] = " Confirmed Password does not match with new Password.";
        $errorflag = 1;
    }
    if($errorflag == 0) {

        require_once("../Resources/Includes/connect.php");

        $newpassword = md5(test_input($_POST['newpassword']));
        $email = $_SESSION['login_email'];

        $sql = "UPDATE PermittedUsers SET PW_DEV = '$newpassword' WHERE NETWORK_USERNAME = '$email'";
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

//Menu control for back to dashboard button
//true: Dont show button
//false: show button
$notBackToDashboard = true;

require_once ("../Resources/Includes/header.php");
?>
</head>
<body>
<?php
// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<div class="overlay hidden"></div>
<?php if (isset($_POST['reset'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" redirect="account.php" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Profile</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Update Your Password</h1>
        <form action ="" method="POST">
            <div class="form-group">
                <label for="newpass">Enter New Password:</label>
                <input name="newpassword" type="password" class="form-control" id="newpass" >
            </div>
            <div class="form-group">
                <label for="confirmpass">Confirm your Password:</label>
                <input name="confirmpassword" type="password" class="form-control" id="confirmpass" >
            </div>

            <input type="submit" name="reset" value="Reset Password" class="btn-primary btn-sm">
        </form>
    </div>
</div>

<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>