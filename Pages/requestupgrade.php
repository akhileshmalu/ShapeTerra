<?php
<<<<<<< HEAD
session_start();
if(!$_SESSION['isLogged']) {
    header("location:login.php");
    die();
}
$message = array();
=======
>>>>>>> dbec9d37112f9ebc9bf4cdb7eb0a5a1c731422ff

require_once ("../Resources/Includes/initalize.php");
$initalize = new Initialize();
$initalize->checkSessionStatus();
$connection = $initalize->connection;

$error = array();

/*
 * Selection of All Admin_User for User Management
 */
$sql ="select NETWORK_USERNAME from PermittedUsers where SYS_USER_ROLE ='admin_user'";
$result = $connection->prepare($sql)
$result->excute();

/*
 * Selection of all available User Rights
 */
$rightsql ="select ID_USER_RIGHT,USER_RIGHT from UserRights";
$rightresult = $connetion->prepare($rightsql);
$rightresult->excute();

/*
 * Selection of all available User roles
 */

$rolesql ="select ID_USER_ROLE, USER_ROLE from UserRoles";
$roleresult = $connetion->prepare($rightsql);
$roleresult->excute();

if(isset($_POST['request'])){

    if($_POST['rights']!=0)
    {
        if(!empty($_POST['approver'])){

            $email = $_SESSION['login_email'];
            $approver = $_POST['approver'];
            $rights = $_POST['rights'];
            $role = $_POST['role'];
            $reqstatus = '1';

            /*
             * Request Status has been desfines as below
             * 1 = Requested
             * 2 = Accepted
             * 3 = Rejected
             *
             */


            $sql = "INSERT INTO requestpanel (email,rights,role,reqstatus,approver) VALUES ('$email','$rights','$role','$reqstatus','$approver')";
            $sqlresult = $connection->prepare($sql);
            if ($sqlresult->execute()) {

                //Confirmation Mail Variables to User
                $sub = "Your privileges change request has been submitted successfully.";
                $msg = "Hello" . "<br/>" ."<br/>";
                $msg .= "You have requeted for upgrade in privilege to Approver: '$approver' "."<br>"."<br/><br/>"."Thank you for giving us a chance to serve you";
                mail($email, $sub, $msg, $headers);


                //Confirmation Mail Variable to Approver
                $sub = "You have a request pending for approval";
                $msg = "Hello" . "<br/>"."<br/>";
                $msg .= "User: '$email' has requeted for privilege upgrade"."<br>"."<br/><br/>"."Please action the request through your taskboard.";
                mail($approver, $sub, $msg, $headers);

                $message[0] = "Request submitted Successfully";
            } else {
                $message[0] = "Request could not be submitted. Please retry";
            }
        } else {
            $message[0] = "Please select an Approver";

        }
    } else {
        $message [0] = "Please select valid user privilege";
    }

}

//Menu control for back to dashboard button
//true: Dont show button
//false: show button
$notBackToDashboard = true;

require_once("../Resources/Includes/header.php");
?>


</head>
<body>
<?php
// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<div class="overlay hidden"></div>
<?php if (isset($_POST['request'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" redirect="account.php" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Profile</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">Request Privilege</h1>
        <form action ="" method="POST">
            <div class="form-group">
                <label for="privilege">Please select desired Privilege <b>Role</b>:</label>
                <select name="role" class="form-control" id="privilege">
                    <option value =""></option>
                    <?php while($row1 = $roleresult ->fetch_array(MYSQLI_NUM)):   ?>
                        <option value="<?php echo $row1[0]; ?>"> <?php echo $row1[1]; ?></option>
                    <?php  endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="privilege">Please select desired Privilege <b>Right</b>:</label>
                <select name="rights" class="form-control" id="privilege">
                    <option value =""></option>
                    <?php while($row2 = $rightresult ->fetch_array(MYSQLI_NUM)):   ?>
                        <option value="<?php echo $row2[0]; ?>"> <?php echo $row2[1]; ?></option>
                    <?php  endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="approver">Please select Approver:</label>
                <select name="approver" class="form-control" id="approver" >
                    <option value =""></option>
                    <?php while($row = $result ->fetch_array(MYSQLI_NUM)): if(strcmp($row[0],$_SESSION['login_email'])) { ?>
                        <option value="<?php echo $row[0]; ?>"> <?php echo $row[0]; ?></option>
                    <?php } else { continue;} endwhile; ?>
                </select>
            </div>

            <input type="submit" name="request" value="submit" id="reset" class="btn-primary btn-sm">
    </form>
    </div>
</div>

<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>
