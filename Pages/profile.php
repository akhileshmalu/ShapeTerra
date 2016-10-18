<?php

session_start();
$error = array();                                               //Error Array Created
$errorflag = 0;                                                 //Flag Create

require_once ("../Resources/Includes/connect.php");          	//Instance of Object class-connection Created
$email = $_SESSION['login_email'];					  			//Session Variable store
$sql = " SELECT fname, lname,email,rights FROM user where email ='$email' ";														//Query to Database
$result = $mysqli->query($sql);                             	// Query Execution
$rows = $result -> fetch_assoc();


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


        $sql = "Update user SET fname = '$fname' where email = '$email';";
        $sql .= "Update user SET lname = '$lname' where email = '$email';";
        if($mysqli -> multi_query($sql)) {
            $error[0] = "Profile Updated Successfully.";
            $_SESSION['login_fname'] = $fname;
            $_SESSION['login_lname'] = $lname;

        } else $error [0] = "Registration Failed. Please retry";

        $mysqli ->close();

    }
}

require_once ("../Resources/Includes/header.php");
?>


</head>
<body>
<?php
// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>
    <div class="col-lg-offset-3 col-lg-3 col-md-6 col-xs-9" id="ContentRight">
        <form action ="" method="POST">
            <div class="form-group">
                <label for="fn">Enter your First Name:</label>
                <input name="fname" type="text" placeholder="<?php echo $rows['fname']?>" class="form-control" id="fn" required>
            </div>
            <div class="form-group">
                <label for="ln">Enter your Last Name:</label>
                <input name="lname" type="text" placeholder="<?php echo $rows['lname']?>" class="form-control" id="ln" required>
            </div>
            <?php if(isset($_POST['submit'])) { ?>
                <div class="alert alert-danger">
                    <span class="icon">&#xe063;</span><?php foreach ($error as $value)echo $value."<br/>"; ?>
                </div>
            <?php } ?>
            <input type="submit" name="submit" value="Update"  class="btn-primary btn-sm">
        </form>
    </div>
<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>
