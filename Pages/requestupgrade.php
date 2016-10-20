<?php
session_start();
$error = array();

require_once ("../Resources/Includes/connect.php");
$sql ="select email from user where rights ='4'";
$result = $mysqli->query($sql);

if(isset($_POST['request'])){

    if($_POST['rights']!=0)
    {
        if(!empty($_POST['approver'])){

            $email = $_SESSION['login_email'];
            $approver = $_POST['approver'];
            $rights = $_POST['rights'];
            $rightdefine="";
            $reqstatus = '1';

            /*
             * Request Status has been desfines as below
             * 1 = Requested
             * 2 = Accepted
             * 3 = Rejected
             *
             */

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

            $sql = "INSERT INTO requestpanel (email,rights,reqstatus,approver) VALUES ('$email','$rights','$reqstatus','$approver')";
            if ($mysqli->query($sql)) {

                //Confirmation Mail Variables to User
                $sub = "Your privileges change request has been submitted successfully.";
                $msg = "Hello" . "<br/>" ."<br/>";
                $msg .= "You have requeted for '$rightdefine' level privilege to Approver: '$approver' "."<br>"."<br/><br/>"."Thank you for giving us a chance to serve you";
                mail($email, $sub, $msg, $headers);


                //Confirmation Mail Variable to Approver
                $sub = "You have a request pending for approval";
                $msg = "Hello" . "<br/>"."<br/>";
                $msg .= "User: '$email' has requeted for '$rightdefine' level privilege"."<br>"."<br/><br/>"."Please action the request through your dashboard.";
                mail($approver, $sub, $msg, $headers);

                $error[0] = "Request submitted Successfully";
            } else {
                $error[0] = "Request could not be submitted. Please retry";
            }
        } else {
            $error[0] = "Please select an Approver";

        }
    } else {
        $error [0] = "Please select valid user privilege";
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
                <label for="privilege">Please select desired Privilege level:</label>
                <select name="rights" class="form-control" id="privilege">
                    <option value=''>  </option>
                    <option value='1'> User </option>
                    <option value='2'> Super User </option>
                    <option value='3'> Admin </option>
                    <option value='4'> Super Admin </option>
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
            <?php if(isset($_POST['request'])) { ?>
            <div class="alert alert-warning">
                <?php foreach ($error as $value)echo $value; ?>
            </div>
            <?php } ?>
            <input type="submit" name="request" value="submit" id="reset" class="btn-primary btn-sm">
    </form>
    </div>
<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>