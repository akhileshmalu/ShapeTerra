<?php

require_once ("../Resources/Includes/initalize.php");
$Initiatives = new Initialize();
$Initiatives->checkSessionStatus();
$connection = $Initiatives->connection;

$message =array();

$sql = "Select * from PermittedUsers";
$result = $mysqli->query($sql);

if(isset($_POST['submit'])){

    if(!empty($_POST['email'])) {
        $email = $_POST['email'];
        $today = date("Y-m-d");
        $sql = "UPDATE PermittedUsers SET USER_STATUS = '-1' where NETWORK_USERNAME='$email';";
        $sql .= "UPDATE PermittedUsers SET DATE_TERMINATE = '$today' where NETWORK_USERNAME='$email';";
        if ($mysqli->multi_query($sql)) {
            $message[0] = "User account has been successfully deactivated.";
        } else {
            $message[0] = "User account could not be deactivated. Please retry";
        }
    } else {
        $message[0] = "Please select a user";
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
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" redirect="account.php" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="col-lg-3 col-md-6 col-xs-9" id="ContentRight">
    <form action ="" method="POST">
        <div class="form-group">
            <label for="privilege">Please select User to Deactivate:</label>
            <select name="email" class="form-control">
                <option value ='0' selected></option>
                <?php while($row = $result ->fetch_array(MYSQLI_NUM)): if(strcmp($row[1],$_SESSION['login_email'])) {; ?>
                    <option value="<?php echo $row[1]; ?>"> <?php echo $row[1]; ?> </option>
                <?php } else { continue;} endwhile; ?>
            </select>
        </div>
        <input type="submit" name="submit" value="Delete"  class="btn-primary btn-sm">
    </form>
</div>
<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>
