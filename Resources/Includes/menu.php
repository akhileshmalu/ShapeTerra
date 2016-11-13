
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
$sqlmenu = "select USER_ROLE,OU_NAME,USER_RIGHT,SYS_USER_ROLE,SYS_USER_RIGHT, OU_ABBREV,FNAME,LNAME from PermittedUsers inner join UserRights on PermittedUsers.SYS_USER_RIGHT = UserRights.ID_USER_RIGHT
inner join UserRoles on PermittedUsers.SYS_USER_ROLE = UserRoles.ID_USER_ROLE
inner join Hierarchy on PermittedUsers.USER_OU_MEMBERSHIP = Hierarchy.ID_HIERARCHY WHERE  NETWORK_USERNAME ='$email';";
$resultmenu = $menucon->query($sqlmenu);
$rowsmenu = $resultmenu ->fetch_assoc();
$_SESSION['login_ouabbrev'] = $rowsmenu['OU_ABBREV'];

$menu = array(
	array("Dashboard", "../$navdir"."Pages/account.php", "&#xe002;" ,"main","basic", true),
	array("Goals", "../$navdir"."Pages/goalManagement.php", "&#xe002;","goal","basic", false),
	array("Create BluePrint", "../$navdir"."Pages/createbp.php", "&#xe002;" ,"main","user", true),
	array("Approve BluePrint", "../$navdir"."Pages/approvebp.php", "&#xe002;" ,"main","approver", true),
	array("Add Academic Year", "../$navdir"."Pages/adday.php", "&#xe002;" ,"main","provost", true),
//	array("Show BluePrint", "../$navdir"."Pages/blueprint/Blueprinthtml/content.php", "&#xe002;" ,"main","basic", false),
	array("Initiate Academic BluePrint", "../$navdir"."Pages/initiatebp.php", "&#xe002;" ,"main","provost", false),
	array("Approve Request", "../$navdir"."Pages/updateaccess.php", "&#xe057;" ,"admin","basic", false),
	array("Deactivate Users", "../$navdir"."Pages/delete.php", "&#xe053;" ,"admin","basic", false),
	array("Request privilege", "../$navdir"."Pages/requestupgrade.php", "&#xe055;" ,"user","basic", false),
	);


?>

<link href="../Resources/Library/css/menu.css" rel="stylesheet" type="text/css" />

<div class="row" id="top-bar">

	<svg width="34px" height="44px" viewBox="0 0 34 44" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
	    <desc>Created with Sketch.</desc>
	    <defs></defs>
	    <image id="logo22" stroke="none" fill="none" x="0" y="0" width="33.2727273" height="43.9825" xlink:href="data:image/png;base64,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"></image>
	</svg>
	

	<h1 class="hidde">Academic<span>Blueprint</span></h1>



	<!-- 
	Username
	-->
	<div id="user-name" class="dropdown">
	  <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    <span class='icon'>&#xe056;</span><?php echo $_SESSION['login_lname'].", ".$_SESSION['login_fname']; ?>
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

<!--		  User info -->
		  <li><p class="text-muted h6" style="margin-left:20px">Org Unit : <?php echo $rowsmenu['OU_ABBREV']; ?></p></li>
		  <li><p class="text-muted h6" style="margin-left:20px">Role : <?php echo $rowsmenu['SYS_USER_ROLE']; ?></p> </li>
		  <li><p class="text-muted h6" style="margin-left:20px">Right desc : <?php echo $rowsmenu['USER_RIGHT']; ?></p> </li>

		   <li role="separator" class="divider"></li>


		  <li><a href="../Pages/profile.php"><span class="icon">&#xe058;</span>Profile</a></li>
		  <li><a href="../Pages/resetpassword.php"><span class="icon">&#xe014;</span>Reset Password</a></li>
		  <li role="separator" class="divider"></li>

<!--		  User Specific Menu under Drop Down-->
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
-->
	<button id="generate-pdf" type="button" class="btn-link" onclick="gotopdf()">
	    <span class='icon'>:</span>Generate PDF
	</button>	


</div>

	<!-- 
	Menu
	-->

<nav class="col-xs-2" id="menu">
	<ul class="col-xs-12 col-lg-10 col-lg-offset- col-md-offset-">
		<li class="" id="header"><a class="main" href="#" onclick="return false">Main <span id="main"
																						   class="caret"></span></a>
		</li>
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
		<li class="" id="header"><a class="goal" href="#" onclick="return false">Goal Management <span id="goal" class="caret"></span></a>
		</li>
		<?php
		for ($i = 0; $i < count($menu); $i++) {
			if ($menu[$i][3] == "goal") {
				echo "<li><a id ='" . $menu[$i][3] . "' class = '" . ($menu[$i][4] ? "selected" : "") . " hidden'href='../../Pages/" . $menu[$i][1] . "'><span class='icon'>" . $menu[$i][2] . "</span>" . $menu[$i][0] . "</a></li>";
			}
		}
		?>
	</ul>
</nav>

