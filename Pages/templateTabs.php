<?php

	session_start();
$error = array();
$errorflag ='';
$ouname[]="";

require_once ("../Resources/Includes/connect.php");

	//Include Header
	require_once("../Resources/Includes/header.php");
?>
</head>
<body>
<?php
	// Include Menu and Top Bar
	require_once("../Resources/Includes/menu.php");
?>

<link href="Css/templateTabs.css" rel="stylesheet" type="text/css" />

<div class="hr"></div>
 	<div id="main-content" class="col-xs-10">
 		<h1 id="title">Goal Management</h1>
 
 		<ul id="tabs" class="nav nav-pills" id="menu-secondary" role="tablist">
 			<li class="active"><a href="#add">Add Goals</a></li>
 			<li><a href="#view">View Goals</a></li>
 		</ul>
 	</div>
 
 	<div class="tab-content">
     	<div role="tabpanel" class="tab-pane active fade in" id="add">
     	Tab 1
     	</div>
     	<div role="tabpanel" class="tab-pane fade " id="view">
     	Tab 2
     	<div>
 
   	</div>

<?php
	//Include Footer
	require_once("../Resources/Includes/footer.php");
?>
