<?php
session_start();
require_once ("../Resources/Includes/connect.php");
$error =array();
$fname = $_SESSION['login_fname'];
$approver = $_SESSION['login_email'];

$sql = "Select email,rights,role from requestpanel where reqstatus = '1' and approver='$approver'";
$result = $mysqli->query($sql);
//$rows = $result->fetch_assoc();


/*
 * Selection of all available User Rights
 */
$rightsql ="select ID_USER_RIGHT,USER_RIGHT from UserRights";
$rightresult = $mysqli1->query($rightsql);

/*
 * Selection of all available User roles
 */

$rolesql ="select ID_USER_ROLE, USER_ROLE from UserRoles";
$roleresult = $mysqli2->query($rolesql);



if(isset($_POST['submit'])){

    if(!empty($_POST['email']))
    {
        if($_POST['user-rights']!=0) {

            $email = $_POST['email'];
            $rights = $_POST['user-rights'];
            $role= $_POST['role'];


            $sql = "UPDATE PermittedUsers SET SYS_USER_RIGHT='$rights' where NETWORK_USERNAME='$email';";
            $sql .= "UPDATE PermittedUsers SET SYS_USER_ROLE='$role' where NETWORK_USERNAME='$email';";
            $sql .= "UPDATE requestpanel SET reqstatus='2' where email='$email';";

            if ($mysqli->multi_query($sql)) {

                //Confirmation Mail Variables
                $sub = "Your privileges has been changed successfully.";
                $msg = "Hello" . "<br/>" ."<br/>";
                $msg .= "Administrator has modified your user privileges. "."<br>"."<br/><br/>"."Thank you for giving us a chance to serve you";
                mail($email, $sub, $msg, $headers);

                $error[0] = "Privileges Updated Successfully";
            } else {
                $error[0] = "Privileges could not be updated. Please retry";
            }
        } else {
            $error [0] = "Please select valid user privilege";
        }
    } else {
        $error[0] = "Please select a user";
    }

}
if(isset($_POST['reject'])){

    if(!empty($_POST['email']))
    {
        $email = $_POST['email'];

        $sql = "UPDATE requestpanel SET reqstatus='3' where email='$email'";

        if ($mysqli->query($sql)) {
            //Confirmation Mail Variables
            $sub = "Your privilege request has been rejected.";
            $msg = "Hello" . "<br/>" ."<br/>";
            $msg .= "Approver has rejected your privileges request."."<br>"."<br/><br/>"."Thank you for giving us a chance to serve you";
            mail($email, $sub, $msg, $headers);
            $error[0] = "Request rejected Successfully";

        } else {
            $error[0] = "Request could not be rejected. Please retry";
        }
    } else {
        $error[0] = "Please select a user";
    }

}


require_once("../Resources/Includes/header.php");
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
        <button type="button" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="col-lg-offset-3 col-lg-3 col-md-6 col-xs-9" id="ContentRight">
    <form action ="" method="POST">
        <div class="form-group">
            <label for="email-user">Please select User:</label>
            <select name="email" class="form-control" onchange="selectlist()" id="email-user">
                <option value =""></option>
                <?php while($row = $result ->fetch_array(MYSQLI_NUM)): if(strcmp($row[0],$_SESSION['login_email'])) {; ?>
                    <option value="<?php echo $row[0]; ?>" roles="<?php echo $row[2]?>" rights="<?php echo $row[1]?>"> <?php echo $row[0]; ?> </option>
                <?php } else { continue;} endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="privilege">Please select desired Privilege <b>Role</b> for User:</label>
            <select name="role" class="form-control" id="privilege">
                <option value =""></option>
                <?php while($row1 = $roleresult ->fetch_array(MYSQLI_NUM)):   ?>
                    <option value="<?php echo $row1[0]; ?>"> <?php echo $row1[1]; ?></option>
                <?php  endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="approver">Please select Privilege <b>Right</b> for User:</label>
            <select name="user-rights" class="form-control" id="approver">
                <option value =""></option>
                <?php while($row2 = $rightresult ->fetch_array(MYSQLI_NUM)):   ?>
                    <option value="<?php echo $row2[0]; ?>"> <?php echo $row2[1]; ?></option>
                <?php  endwhile; ?>
            </select>
        </div>

        <input type="submit" name="submit" value="Accept"  class="btn-primary btn-sm">
        <input type="submit" name="reject" value="Reject"  class="btn-primary btn-sm">
    </form>
</div>
<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>
<script src="../Resources/Library/js/userapprove.js"></script>