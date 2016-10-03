<?php

date_default_timezone_set("America/New_York");                  //Setting time zone to EST time

session_start();                                      		    // Session Initiation
$error =array();                                 		  		    //Variable to store error msg

if(isset($_POST['login'])) {
    if ( empty($_POST['email']) || ( empty($_POST['password']) ) ) {
        $error[0] =  "Invalid Email Address or password";
    } else {
        require_once ("connect.php");                               //Instance of Object class-connection Created
        $email = test_input($_POST['email']);                       // Secured Input
        $password = test_input($_POST['password']);

        //Query into database for record check
        $sql =  "SELECT * FROM user where email ='$email' AND password ='$password'";

        $result = $mysqli -> query($sql);                             //Query Execution
        $row_cnt = $result -> num_rows;                               //count no of rows in result object.
        if ($row_cnt >= '1') {                                        //If there exist one or more records
            $_SESSION['login_email'] = $email;                   //session variable register
            //header("location:admin.php");                           //redirect to account page
            header("location:account.php");                           //redirect to account page

        } else {
            $error[0] = "Incorrect Email address or Password! ";
        }


        $mysqli->close();								               //Close Connection
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

<body>

<div id ="Main" class="row">
    <div id="login-form" class="col-xs-4 col-xs-offset-4 text-center">
        <form name = "loginform" action ="" method="POST">
                <img src="../Resources/Images/logousc.jpg" align="middle">
                <div class="row">
                    <input id ="email" type="email"  placeholder="Email Address" name="email" class = "StyleSheettext1" required />
                    
                    <input id="password" type="password" placeholder="Password" name="password" class ="StyleSheettext1" required/>
                </div>
                <label id="error"> <?php foreach ($error as $value)echo $value; ?> </label>
                <div class="row">
                    <input type="submit" name = "login" class = "StyleSheettext2 col-sm-offset-1 col-sm-3" value="Login" id ="login">
                    <button type ="button"  name = "signup"  onclick="registerfunction()" class = "StyleSheettext2" id="signup">
                        Sign Up
                    </button>
                </div>
                <script type="text/javascript">
                    function registerfunction() {
                        email.removeAttribute("required");
                        password.removeAttribute("required");
                        window.location.href = "register.php";
                    }
                </script>
                <a href="forgotpassword.php" id = "link">Lost your Password ? </a></h2>
        </form>
    </div>
</div>
<?php
require_once("../Resources/Includes/footer.php");
?>
<!-- </body>
</html> -->
