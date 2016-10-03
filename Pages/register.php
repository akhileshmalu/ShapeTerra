<?php

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
    if (empty($_POST['email'])) {
        $error[2] = " Please enter email address.";
        $errorflag = 1;
    }else{
        if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
            $error[2] = "Invalid Email Address.";
            $errorflag = 1;

        }
    }
    if (empty($_POST['password'])) {
        $error[3] = " Please enter password.";
        $errorflag = 1;
    }
    if (empty($_POST['confirmpassword'])) {
        $error[4] = " Please confirm password.";
        $errorflag = 1;
    }
    if (strcmp($_POST['password'],$_POST['confirmpassword'])) {
        $error[5] = " Confirm Password does not match with Password.";
        $errorflag = 1;
    }


    if($errorflag == 0){

        require_once ("connect.php");                          // Established Connection

        $fname = test_input($_POST['fname']);                  //Secured Input
        $lname = test_input($_POST['lname']);
        $password = test_input($_POST['password']);
        $email = test_input($_POST['email']);

        $sql = "INSERT INTO user(fname,lname,password,email) VALUES ('$fname','$lname','$password','$email')";

        if($mysqli -> query($sql)) {
        /*Statement Prepare & Bind
        $stmt = $mysqli->prepare("INSERT INTO TESTDB.user(fname, lname,password,email) VALUES (?,?,?,?);");
        $stmt -> bind_param("ssss",$fname,$lname,$password,$email);

        //Statement Execute
        $stmt -> execute();*/

        $error[0] = "User Registered Successfully.";
        $error[0].="Confirmation Email sent to registered email";

        //Confirmation Mail Variables
        $sub = "Congratulations! You have successfully registered.";
        $msg = "Hello $fname"."<br/>"."<br/>";
        $msg .= "You Password is: $password"."<br/><br/>"."Thank you for registering with us";

        mail($email,$sub,$msg,$headers);
        } else $error [0] = "Registration Failed. Please retry";

        $mysqli ->close();

    }
}

?>

<!doctype html>
<html>
<head>
    <link href="layout.css" rel="stylesheet" type="text/css" />
    <title>User Registration</title>
</head>

<body>
<div id="Holder">
    <!--<div id="Header"></div> -->
    <div id="NavBar">
        <nav>
            <ul>
                <li> <a href="login.php">Login</a></li>
                <!--<li> <a href="Register.php">Register</a></li>
                <li> <a href="ForgotPassword.php">Fogot Password</a></li> -->
            </ul>
        </nav>
    </div>
    <div id="Content">
        <div id="PageHeading">
            <h1>Register Here!</h1>
        </div>
        <div id="ContentLeft">
           <h6> <label >Welcome to Registration Page.<br> </label></h6><br/><br/>

        </div>
        <div id="ContentRight">
            <form action="" id="RegisterForm" name="RegisterForm" method="POST">
                <table width="400" border="0">
                    <tbody>
                    <tr>
                        <td>
                            <table border="0">
                                <tbody>
                                <tr>
                                    <td><input type="text" name="fname" placeholder="Enter your First Name" class="StyleSheettext1" required></td>
                                    <td><input type="text" name="lname" placeholder="Enter your Last Name" class="StyleSheettext1" required></td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="email" name="email"  placeholder="Enter your Email Address" class="StyleSheettext1" required>
                        </td>
                    </tr>
                    <tr>
                        <td><table border="0">
                                <tbody>
                                <tr>
                                    <td>
                                        <input type="password" name="password" placeholder="Enter your Password" class="StyleSheettext1" required>
                                    </td>
                                    <td>
                                        <input type="password" name="confirmpassword" placeholder="Please confirm your Password" class="StyleSheettext1" required>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td><br></td>
                    </tr>
                    <tr>
                        <td><input type="submit" name="submit" class="StyleSheettext2" value="Register"></td>
                    </tr>
                    <tr>
                        <td><br><label id="error" > <?php if(isset($_POST['submit'])){ foreach ($error as $value) echo $value."<br>";} ?></label></td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <div id="Footer"></div>
</div>
</body>
</html>