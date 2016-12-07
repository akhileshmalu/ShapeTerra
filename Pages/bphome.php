<?php

$pagename = "bphome";

error_reporting(E_ALL);
ini_set('display_errors', '1');

/*
 * This Page controls Intiation of Academic BluePrint module.
 */

$cur  = date('Y-m-d H:i:s');
session_start();
$error = array();
$errorflag =0;

$sqlbroad ="";
$ou=array();


require_once ("../Resources/Includes/connect.php");




require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>
<link href="../Resources/Library/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<div class="overlay hidden"></div>
<?php if (isset($_POST['submit'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Blueprint Home</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">AY2016-2017</h1>
        <p class="status"><span>Status:</span> In Progress</p>
        <p class="status"><span>Last Modified:</span> 8/9/16</p>
    </div>

    <div id="main-box" class="col-xs-5 col-xs-offset-1">
        <h1 class="box-title">Tasks</h1>
        <ul class="task-list">
            <li><a><span class="icon">&#xe01c;</span> Task 1</a></li>
            <li><a><span class="icon">&#xe01c;</span> Task 2</a></li>
        </ul>
    </div>

    <div id="main-box" class="col-xs-4 col-xs-offset-1 ">
        <h1 class="box-title">Completed Tasks</h1>
        <ul class="task-completed-list">
            <li><a><span class="icon">S</span> Task 4</a></li>
            <li><a><span class="icon">S</span> Task 5</a></li>
        </ul>
    </div>

</div>


<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>

<!--Calender Bootstrap inclusion for date picker INPUT-->
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
<script src="../Resources/Library/js/tabchange.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
