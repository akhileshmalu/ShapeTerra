<?php
session_start();
require_once ("../Resources/Includes/connect.php");
$error =array();
$fname = $_SESSION['login_fname'];
$approver = $_SESSION['login_email'];

$sql = "Select email,rights from requestpanel where reqstatus = '1' and approver='$approver'";
$result = $mysqli->query($sql);
//$rows = $result->fetch_assoc();

if(isset($_POST['submit'])){

    if(!empty($_POST['email']))
    {
        if($_POST['rights']!=0) {

            $email = $_POST['email'];
            $rights = $_POST['rights'];
            $rightdefine="";

            switch ($rights){
                case '1':
                    $rightdefine ="User";
                    break;
                case '2':
                    $rightdefine = "Super User";
                    break;
                case '3':
                    $rightdefine = "Admin";
                    break;
                case '4':
                    $rightdefine = "Super Admin";
                    break;
                default:
                    $rightdefine="Undefined";
                    break;
            }

            $sql = "UPDATE user SET rights='$rights' where email='$email';";
            $sql .= "UPDATE requestpanel SET reqstatus='2' where email='$email';";

            if ($mysqli->multi_query($sql)) {

                //Confirmation Mail Variables
                $sub = "Your privileges has been changed successfully.";
                $msg = "Hello" . "<br/>" ."<br/>";
                $msg .= "Administrator has modified your user privileges to '$rightdefine''"."<br>"."<br/><br/>"."Thank you for giving us a chance to serve you";
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
        $rights = $_POST['requestedright'];
        $rightdefine="";

        switch ($rights){
            case '1':
                $rightdefine ="User";
                break;
            case '2':
                $rightdefine = "Super User";
                break;
            case '3':
                $rightdefine = "Admin";
                break;
            case '4':
                $rightdefine = "Super Admin";
                break;
            default:
                $rightdefine="Undefined";
                break;
            }

            $sql = "UPDATE requestpanel SET reqstatus='3' where email='$email'";

            if ($mysqli->query($sql)) {

                //Confirmation Mail Variables
                $sub = "Your privilege request has been rejected.";
                $msg = "Hello" . "<br/>" ."<br/>";
                $msg .= "Approver has rejected your privileges request for '$rightdefine' level"."<br>"."<br/><br/>"."Thank you for giving us a chance to serve you";
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
<div class="col-lg-offset-3 col-lg-3 col-md-6 col-xs-9" id="ContentRight">
    <form action ="" method="POST">
        <div class="form-group">
            <label for="privilege">Please select User:</label>
            <select name="email" class="form-control" >
                <option value =""></option>
                <?php while($row = $result ->fetch_array(MYSQLI_NUM)): if(strcmp($row[0],$_SESSION['login_email'])) {; ?>
                    <option value="<?php echo $row[0]; ?>"> <?php echo $row[0]; ?> </option>
                <?php } else { continue;} endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="approver">Please select Privilege level for User:</label>
            <select name="rights" class="form-control">
                <option value=''>  </option>
                <option value='1'> User </option>
                <option value='2'> Super User </option>
                <option value='3'> Admin </option>
                <option value='4'> Super Admin </option>
            </select>
        </div>
        <?php if(isset($_POST['submit'])) { ?>
            <div class="alert alert-danger">
                <span class="icon">&#xe063;</span> <?php foreach ($error as $value)echo $value; ?>
            </div>
        <?php } ?>
        <input type="submit" name="submit" value="Accept"  class="btn-primary btn-sm">
        <input type="submit" name="reject" value="Reject"  class="btn-primary btn-sm">
    </form>
</div>
<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>
