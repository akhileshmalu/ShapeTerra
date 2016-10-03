<?php

$error=array();
$errorflag = 0;

if(isset($_POST['forgot'])){

    // Email Validity Check
    if (empty($_POST['email'])) {
        $error[0] = " Please enter email address.";
        $errorflag = 1;
    } else{
        if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
            $error[1] = "Invalid Email Address.";
            $errorflag = 1;
        }
    }

    // Process if Input is valid
    if($errorflag == 0){

        //Establish Connection
        require_once ("connect.php");
        $email = test_input($_POST['email']);

        $sql ="select fname, password FROM user where email = '$email'";
        $result = $mysqli -> query($sql);
        $rows = $result -> num_rows;

        if($rows != 0){
            while($record =$result ->fetch_assoc()) {
                $fname = $record['fname'];
                $password = $record['password'];

                //Confirmation Mail Variables
                $sub = "You have successfully retrieved password.";
                $msg = "Hello $fname" . "<br/>" . "<br/>";
                $msg .= "You Password is: $password" . "<br/><br/>" . "Thank you for giving us chance to serve you";
                mail($email, $sub, $msg, $headers);
                $error[0] = "Your Password has been sent to registered email id";
            }
            unset($record);
            unset($rows);
            unset($result);

        }else{
            $error[0] = "No Record found.Please use your registered email address.";
        }
    }
    $mysqli->close();

}
?>

<!doctype html>
<html>
<head>
    <link href="layout.css" rel="stylesheet" type="text/css" />
    <meta charset="UTF-8">
    <title>Forgot Password</title>
</head>

<body>
<div id="Holder">
    <!--<div id="Header"></div> -->
    <div id="NavBar">
        <nav>
            <ul>
                <li> <a href="login.php">Login</a></li>
                <!--
                <li> <a href="Register.php">Register</a></li>
                <li> <a href="ForgotPassword.php">Fogot Password</a></li> -->
            </ul>
        </nav>
    </div>
    <div id="Content">
        <div id="PageHeading">
            <h1>Welcome to Password Retrieval</h1>
        </div>
        <div id="ContentLeft">
            <label for="accountlink">Account Links:<br> </label><br/><br/>
        </div>
        <div id="ContentRight"></div>
        <form ACTION="" id="forgotpassword" name="forgotpasswordform" method="POST">
            <table width="400" border="0" align="center">
                <tbody>
                <tr>
                    <td><input name="email" type="email" placeholder="Enter your registered Email" class="StyleSheettext1" required></td>
                </tr>
                <tr>
                    <td> <br> <input type="submit" name="forgot" class ="StyleSheettext2" value="Retrieve Password" > </td>
                </tr>
                <tr>
                    <td> <label id="error"> <?php foreach ($error as $value)echo $value; ?> <br> </label> </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
    <div id="Footer"></div>
</div>
</body>
</html>

