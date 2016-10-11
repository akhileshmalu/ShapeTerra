<?php
session_start();
require_once ("../Resources/Includes/connect.php");
$error = array();

//link shall be used only once
$newhash = md5( rand(0,1000));


/* Below segments checks variable from activation link and activation type (refer to forgotpassword.php) */
if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])) {
    //Activation from First time user
    if ($_GET['type'] == 1) {

        $email = test_input($_GET['email']);
        $hash = $_GET['hash'];
        $sql = "Select * from user where email = '$email' and hash ='$hash' ";
        $result = $mysqli->query($sql);

        if ($result->num_rows) {
            $sql = "UPDATE user SET status = '1' where email = '$email' and hash ='$hash' ";
            $mysqli->query($sql);
            $error[0] = "User Activated Successfully";

            // Link shall be used only once.
            $sql = "UPDATE user SET hash = '$newhash' where email = '$email'";
            $mysqli->query($sql);

        } else {
            $error[0] = "Failed to Activate account.";
        }
    }

    // Activation from Lost your Password
    if ($_GET['type'] == 0) {
        $email = test_input($_GET['email']);
        $hash = $_GET['hash'];

        $sql = "select * FROM user where email = '$email' and hash ='$hash'";
        $result = $mysqli->query($sql);

        if ($result->num_rows) {

            // Link shall be used only once.
            $sql = "UPDATE user SET hash = '$newhash' where email = '$email'";
            $mysqli->query($sql);

            $_SESSION['login_email'] = $email;
            header("location:resetpassword.php");
        } else {
            $error[0] = "This password key has expired.Please request a fresh key";
        }

    }

}
require_once("../Resources/Includes/header.php");
?>

<!--
<!doctype html>
<html>
 <head>
    <link href="layout.css" rel="stylesheet" type="text/css" />
    <meta charset="UTF-8">
    <title>User Management Portal</title>
</head> -->

<link href="Css/layout.css" rel="stylesheet" type="text/css" />
<body>

<div id ="Container">
    <form name = "loginform" action ="login.php" method="POST">
        <h2><img src="../Resources/Images/logousc.jpg" align="middle">
            <input id ="email" type="email"  placeholder="Email Address" name="email" class = "StyleSheettext1" required />
            <br>
            <input id="password" type="password" placeholder="Password" name="password" class ="StyleSheettext1" required />
            <br>
            <label id="error"> <?php foreach ($error as $value)echo $value; ?> <br> </label><br>
            <input type="submit" name = "login" class = "StyleSheettext2" value="Login" id ="login">
            <button type ="button"  name = "signup"  onclick="registerfunction()" class = "StyleSheettext2" id="signup">Sign Up</button>
            <script type="text/javascript">
                function registerfunction() {
                    email.removeAttribute("required");
                    password.removeAttribute("required");
                    window.location.href = "register.php";
                }
            </script>
            <br></h2><br>
        <h2><a href="forgotpassword.php" id = "link">Lost your Password ? </a></h2>
    </form>
</div>
<?php
require_once("../Resources/Includes/footer.php");
?>
<!-- </body>
</html> -->
