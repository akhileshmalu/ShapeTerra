<?php

session_start();
$error = array();                                               //Error Array Created
$errorflag = 0;                                                 //Flag Create

require_once ("../Resources/Includes/connect.php");          	//Instance of Object class-connection Created
$email = $_SESSION['login_email'];					  			//Session Variable store
$sql = " SELECT FNAME, LNAME FROM PermittedUsers where NETWORK_USERNAME ='$email' ";														//Query to Database
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


        $sql = "Update PermittedUsers SET FNAME = '$fname' where NETWORK_USERNAME = '$email';";
        $sql .= "Update PermittedUsers SET LNAME = '$lname' where NETWORK_USERNAME = '$email';";
        if($mysqli -> multi_query($sql)) {
            $error[0] = "Profile Updated Successfully.";
            $_SESSION['login_fname'] = $fname;
            $_SESSION['login_lname'] = $lname;

        } else $error [0] = "Registration Failed. Please retry";

        $mysqli ->close();

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
<?php if (isset($_POST['submit'])) { ?>
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
        <h1 class="box-title">Update Your Profile</h1>
        <form action ="" method="POST">
            <div class="form-group">
                <label for="fn">Enter your First Name:</label>
                <input name="fname" type="text" placeholder="<?php echo $rows['FNAME']?>" class="form-control" id="fn" required>
            </div>
            <div class="form-group">
                <label for="ln">Enter your Last Name:</label>
                <input name="lname" type="text" placeholder="<?php echo $rows['LNAME']?>" class="form-control" id="ln" required>
            </div>

            <input type="submit" name="submit" value="Update"  class="btn-primary btn-sm">
        </form>
    </div>
</div>
<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>
