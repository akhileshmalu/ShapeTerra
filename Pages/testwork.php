<?php

require_once ("../Resources/Includes/connect.php");

if(isset($_POST['ok'])) {
    $data = $_POST['text1'];
    $data = trim($data);
    echo $data . "<br>";
    $data = htmlspecialchars($data, ENT_QUOTES, 'ISO-8859-1', true);
    echo $data . "<br>";
    $data = htmlentities($data, ENT_QUOTES, 'ISO-8859-1', true);
    echo $data . "<br>";

    echo htmlspecialchars_decode($data);
}

//echo phpinfo();
?>

<!DOCTYPE html>
<HTML>
<head>
<title>This is Test Work</title>
    <meta content="text/html" charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<form action="testwork.php" method="POST">
    <label> Enter character to check</label>
    <input type="text" name="text1" >
    <input type="submit" name="ok">
</form>
</body>
<footer>

</footer>
</HTML>
