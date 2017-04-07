<?php

//require_once ("../Resources/Includes/connect.php");
//
//if(isset($_POST['ok'])) {
//    $data = $_POST['text1'];
//    $data = trim($data);
//    echo $data . "<br>";
//    $data = htmlspecialchars($data, ENT_QUOTES, 'ISO-8859-1', true);
//    echo $data . "<br>";
//    $data = htmlentities($data, ENT_QUOTES, 'ISO-8859-1', true);
//    echo $data . "<br>";
//
//    echo htmlspecialchars_decode($data);
//
//
//
//}
//require_once ("../../Resources/Includes/Initialize.php");
//
//$initialized = new Initialize();

//echo phpinfo();
//$site  = "Shapeterra";
//echo $_SERVER['DOCUMENT_ROOT'];
//$target_dir = $_SERVER['DOCUMENT_ROOT']."/".$_SESSION['site']."/uploads/communityEngagement/";
//echo $target_dir;
//if (!file_exists($target_dir)) {
//    mkdir($target_dir, 0777);
//    echo "<p>Folder created</p>";
//} else echo "Folder Exist";

?>

<!--<!DOCTYPE html>-->
<!--<HTML>-->
<!--<head>-->
<!--<title>This is Test Work</title>-->
<!--    <meta content="text/html" charset="UTF-8">-->
<!--    <meta http-equiv="x-ua-compatible" content="ie=edge">-->
<!--    <meta name="viewport" content="width=device-width, initial-scale=1.0">-->
<!--</head>-->
<!--<body>-->
<!--<form action="testwork.php" method="POST">-->
<!--    <label> Enter character to check</label>-->
<!--    <input type="text" name="text1" >-->
<!--    <input type="submit" name="ok">-->
<!--</form>-->
<!--</body>-->
<!--<footer>-->
<!---->
<!--</footer>-->
<!--</HTML>-->
<!DOCTYPE html>
<html lang="en">
<head>
    <title>JavaScript PDF Viewer Demo</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>l
    <script type="text/javascript">
        function PreviewImage() {
            pdffile=document.getElementById("uploadPDF").files[0];
            pdffile_url=URL.createObjectURL(pdffile);
            $('#viewer').attr('src',pdffile_url);
        }
    </script>
</head>
<body>
<input id="uploadPDF" type="file" name="myPDF"/>&nbsp;
<input type="button" value="Preview" onclick="PreviewImage();" />

<div style="clear:both">
    <iframe id="viewer" frameborder="0" scrolling="no" width="400" height="600"></iframe>
</div>
</body>
</html>