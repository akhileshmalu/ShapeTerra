<?php
$error ="";
?>
<!DOCTYPE HTML>
<html>
<head>
    <link href="../Pages/Css/layout.css" rel="stylesheet" type="text/css" />
    <meta charset="UTF-8">
    <title>Console for Database</title>
</head>
<body>
<div id="NavBar">
    <nav>
        <ul>
            <li> <a href="../Pages/login.php">Login</a></li>
            <!--
            <li> <a href="Register.php">Register</a></li>
            <li> <a href="ForgotPassword.php">Fogot Password</a></li> -->
        </ul>
    </nav>
</div>
<div id="Container">
    <br> <h1>Console for Shapeterra DB</h1><br>
    <form id="consoleform" method ="POST">
        <br><br><br>
        <input type ="text" name ="query" class= "StyleSheettext1" placeholder="Enter your query">
        <br><br>
        <input type =submit name ="submit" class="StyleSheettext2" value ="Single Query" >
        <input type =submit name ="multi" class="StyleSheettext2" value ="Multi Query" >

        <br><br><label id="error"><?php echo $error ?> </label><br><hr>
        Display Result <br>
        <label id="error">
            <?php
            require_once("../Resources/Includes/connect.php");
            $error ="";

            if(isset($_POST['submit'])){

                $sql = $_POST['query'];
                IF($result = $mysqli ->query($sql)) {
                    /* fetch associative array */
                    while ($row = $result->fetch_assoc()) {
                        foreach ($row as $value) {echo $value. " || ";} echo "<br>";
                    }
                    echo "<br> <br> Query Executed.";

                    $result->free();
                    $mysqli->close();
                } else {
                    echo "Query Failed";
                }
            }
            if(isset($_POST['multi'])){

                $sql = $_POST['query'];

                if($mysqli->multi_query($sql))
                {
                    echo "Record Created";
                }else {
                    echo "Query Failed";
                }
            }
            ?>
        </label> <br><br><hr/>

    </form>
</div>

</body>
</html>
