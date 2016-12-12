
<?php

/*
*
* To add menu items
* add an array inside the menu array
* array(Display Name, href link, icon , menu div position,role control, selected)
* Icon is represented by a character, list can be found here http://demo.amitjakhu.com/dripicons/
* menu div position: main, goal;				role Control : Provost, basic
* selected is either true or false, set initial to false and selected will be determined by page
*
*/
require_once ("../Resources/Includes/connect.php");
$email = $_SESSION['login_email'];
$sqlmenu = "select USER_ROLE,OU_NAME,USER_RIGHT,SYS_USER_ROLE,SYS_USER_RIGHT, OU_ABBREV,FNAME,LNAME ,USER_OU_MEMBERSHIP from PermittedUsers inner join UserRights on PermittedUsers.SYS_USER_RIGHT = UserRights.ID_USER_RIGHT
inner join UserRoles on PermittedUsers.SYS_USER_ROLE = UserRoles.ID_USER_ROLE
inner join Hierarchy on PermittedUsers.USER_OU_MEMBERSHIP = Hierarchy.ID_HIERARCHY WHERE  NETWORK_USERNAME ='$email';";
$resultmenu = $menucon->query($sqlmenu);
$rowsmenu = $resultmenu ->fetch_assoc();
$_SESSION['login_ouabbrev'] = $rowsmenu['OU_ABBREV'];
$ouid = $rowsmenu['USER_OU_MEMBERSHIP'];


$pagename=null;

$sqlmenuctrl = "select * from broadcast where BROADCAST_OU = $ouid; ";
$resultmenuctrl = $menucon->query($sqlmenuctrl);
$rowsmenuctrl = $resultmenuctrl->fetch_assoc();

$testMenu = false;
if($pagename == "bphome"){
	$testMenu = true;
}


$menu = array(
	//array("Home", "../$navdir"."Pages/account.php", "" ,"main","basic", true),
	//array("Create BluePrint", "../$navdir"."Pages/createbp.php", "&#xe02f;" ,"main","user", true),
	//array("Approve BluePrint", "../$navdir"."Pages/approvebp.php", "&#xe04e;" ,"main","approver", true),
	array("Add Academic Year", "../$navdir"."Pages/adday.php", "" ,"main","provost", true),
	array("Edit Academic Year", "../$navdir"."Pages/editay.php", "" ,"main","provost", true),
	array("Initiate Academic BluePrint", "../$navdir"."Pages/initiatebp.php", "" ,"main","provost", false),
	array("Approve Request", "../$navdir"."Pages/updateaccess.php", "&#xe04e" ,"admin","basic", false),
	array("Deactivate Users", "../$navdir"."Pages/delete.php", "" ,"admin","basic", false),
	array("Request privilege", "../$navdir"."Pages/requestupgrade.php", "&#xe02f;" ,"user","basic", false),
	);

if($rowsmenuctrl['Menucontrol'] == 'Approver') {
	$string = array("Approve BluePrint", "../$navdir"."Pages/approvebp.php", "&#xe04e;" ,"main","approver", true);
	array_push($menu,$string);
}

if ($rowsmenuctrl['Menucontrol'] == 'User') {
	$string = array("Create BluePrint", "../$navdir" . "Pages/createbp.php", "&#xe02f;", "main", "user", true);
	array_push($menu, $string);
}

?>

<link href="../Resources/Library/css/menu.css" rel="stylesheet" type="text/css" />

<div id="logo-box" class="col-xs-2">
	<a href="account.php" class="btn-link"><h1>Academic<span>Blueprint</span></h1></a>
</div>

<div class="row" id="top-bar">
	<!-- 
	Username
	-->
	<div id="user-name" class="dropdown">
	  <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    <span class='icon'>&#xe056;</span><?php echo substr($_SESSION['login_lname'],0,1).", ".$_SESSION['login_fname']; ?>
	    <span class="caret"></span>
	  </button>
	
	<!-- 
	Logout
	-->

	  <a id="log-out" href="../Pages/logout.php">
	  	<span class="icon">=</span>
	  </a>

	<!-- 
	Username Dropdown
	-->
	  <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">

		<!-- User info -->
		  <li><p class="text-muted h6" style="margin-left:20px">Org Unit : <?php echo $rowsmenu['OU_ABBREV']; ?></p></li>
		  <li><p class="text-muted h6" style="margin-left:20px">Role : <?php echo $rowsmenu['SYS_USER_ROLE']; ?></p> </li>
		  <li><p class="text-muted h6" style="margin-left:20px">Right desc : <?php echo $rowsmenu['USER_RIGHT']; ?></p> </li>

		   <li role="separator" class="divider"></li>


		  <li><a href="../Pages/profile.php"><span class="icon">&#xe058;</span>Profile</a></li>
		  <li><a href="../Pages/resetpassword.php"><span class="icon">&#xe014;</span>Reset Password</a></li>
		  <li role="separator" class="divider"></li>

			<!-- User Specific Menu under Drop Down-->
		  <?php
		  switch ($rowsmenu['SYS_USER_ROLE'])
		  {
			  case "admin_user" :
				  for($i = 0; $i < count($menu); $i++){
					  if($menu[$i][3] == "admin"){
						  echo "<li><a class = '". ($menu[$i][4] ? "selected" : "") ."'href='../../Pages/". $menu[$i][1] ."'><span class='icon'>". $menu[$i][2] . "</span>" . $menu[$i][0] ."</a></li>";
					  }
				  }
				  echo "<li role='separator' class='divider'></li>";
				  break;
			  case "contrib_academic" :
				  for($i = 0; $i < count($menu); $i++){
					  if($menu[$i][3] == "user"){
						  echo "<li><a class = '". ($menu[$i][4] ? "selected" : "") ."'href='../../Pages/". $menu[$i][1] ."'><span class='icon'>". $menu[$i][2] . "</span>" . $menu[$i][0] ."</a></li>";
					  }
				  }
				  echo "<li role='separator' class='divider'></li>";
				  break;
			  default :
				  for($i = 0; $i < count($menu); $i++){
					  if($menu[$i][3] == "user"){
					  	echo "<li><a class = '". ($menu[$i][4] ? "selected" : "") ."'href='../../Pages/". $menu[$i][1] ."'><span class='icon'>". $menu[$i][2] . "</span>" . $menu[$i][0] ."</a></li>";
					  }
				  }
				  echo "<li role='separator' class='divider'></li>";
				  break;
		  }
		  ?>
		  <li><a href="../Pages/logout.php"><span class="icon">=</span>Log Out</a></li>
	  </ul>
	</div>

<!--
Generate PDF button currently disabled.

	<button id="generate-pdf" type="button" class="btn-link" onclick="gotopdf()">
	    <span class='icon'>:</span>Generate PDF
	</button>
-->
</div>

	<!-- 
	Menu
	-->

<nav class="col-xs-2" id="menu">
	<!-- Main menu -->
	<ul class="col-xs-12">
		<li class="" id="header"><a class="main" href="#" onclick="return false">
		<span id="main" class="icon minus hidden">&#xe024;</span>
		<span id="main" class="icon plus">&#xe035;</span>
		Main</a></li>

		<?php
		for ($i = 0; $i < count($menu); $i++) {
			if (strcmp($rowsmenu['SYS_USER_ROLE'], "provost") == 0) {
				if ($menu[$i][3] == "main" && ($menu[$i][4] == "provost" OR $menu[$i][4] == "basic")) {
					echo "<li><a id ='" . $menu[$i][3] . "' class = '" . ($menu[$i][4] ? "selected" : "") . " hidden' href='../../Pages/" . $menu[$i][1] . "'><span class='icon'>" . $menu[$i][2] . "</span>" . $menu[$i][0] . "</a></li>";

				}
				continue;
			}
			if ($rowsmenu['SYS_USER_RIGHT'] == 3) {
				if ($menu[$i][3] == "main" && ($menu[$i][4] == "approver" OR $menu[$i][4] == "basic")) {
					echo "<li><a id ='" . $menu[$i][3] . "' class = '" . ($menu[$i][4] ? "selected" : "") . " hidden' href='../../Pages/" . $menu[$i][1] . "'><span class='icon'>" . $menu[$i][2] . "</span>" . $menu[$i][0] . "</a></li>";
				}
				continue;
			}

			if ($menu[$i][3] == "main" && ($menu[$i][4] <> "provost" and $menu[$i][4] <> "approver")) {
				echo "<li><a id ='" . $menu[$i][3] . "' class = '" . ($menu[$i][4] ? "selected" : "") . " hidden' href='../../Pages/" . $menu[$i][1] . "'><span class='icon'>" . $menu[$i][2] . "</span>" . $menu[$i][0] . "</a></li>";
			}
		}
		?>
	</ul>
	<!-- Blueprint Home Menu -->

</nav>

