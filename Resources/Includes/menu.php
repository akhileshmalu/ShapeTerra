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


$fileupload = new Initialize();
$menucon = $fileupload->connection;


// Local variables for Dev Environment only

$navdir = $_SESSION['site'];

$email = $_SESSION['login_email'];
try {
	$sqlmenu = "select USER_ROLE,OU_NAME,USER_RIGHT,SYS_USER_ROLE,SYS_USER_RIGHT, OU_ABBREV,FNAME,LNAME ,USER_OU_MEMBERSHIP,OU_TYPE from PermittedUsers inner join UserRights on PermittedUsers.SYS_USER_RIGHT = UserRights.ID_USER_RIGHT
inner join UserRoles on PermittedUsers.SYS_USER_ROLE = UserRoles.ID_USER_ROLE
inner join Hierarchy on PermittedUsers.USER_OU_MEMBERSHIP = Hierarchy.ID_HIERARCHY WHERE  NETWORK_USERNAME =:email ;";

	$resultmenu = $menucon->prepare($sqlmenu);
	$resultmenu->bindParam(':email', $email, 2);
	$resultmenu->execute();

} catch (PDOException $e) {
	//        SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
	echo $e->getMessage();
}

$rowsmenu = $resultmenu->fetch(2);
$_SESSION['login_ouabbrev'] = $rowsmenu['OU_ABBREV'];
$_SESSION['login_ouname'] = $rowsmenu['OU_NAME'];
$_SESSION['login_right'] = $rowsmenu['SYS_USER_RIGHT'];
$_SESSION['login_role'] = $rowsmenu['SYS_USER_ROLE'];
$ouid = $rowsmenu['USER_OU_MEMBERSHIP'];


/*
 * Menu Hub ; All menu items are loaded here and controlled by pages.
 */

$menu = array(
//	array("Upload OIRAA Data", "../$navdir"."/Pages/fileuploadhome.php", "" ,"main","service", true),
array("Data Visuals", "../$navdir"."/Pages/visualizations.php", "" ,"main","basic", true),
	array("Data Dictionary", "../$navdir"."/Pages/datadicthome.php", "" ,"main","basic", true),
    array("Footnotes", "../$navdir"."/Pages/footnotehome.php", "" ,"main","basic", true),
	//array("Approve BluePrint", "../$navdir"."/Pages/approvebp.php", "" ,"main","approver", true),
	array("Add Academic Year", "../$navdir"."/Pages/adday.php", "" ,"main","provost", true),
	array("Edit Academic Year", "../$navdir"."/Pages/editay.php", "" ,"main","provost", true),
	array("Initiate Academic BluePrint", "../$navdir"."/Pages/initiatebp.php", "" ,"main","provost", false),
	array("Approve Request", "../$navdir"."/Pages/updateaccess.php", "" ,"admin","basic", false),
	array("Invite User", "../$navdir"."/Pages/addUser.php", "" ,"admin","basic", false),
	array("Deactivate Users", "../$navdir"."/Pages/delete.php", "" ,"admin","basic", false)
//	array("Request privilege", "../$navdir"."/Pages/requestupgrade.php", "&#xe02f;" ,"user","basic", false),
	);

// Function to download templates for csv formats
function download($filename){
	if(!empty($filename)){
		// Specify file path.
		$path = '../uploads/csvtemplates/';
		$download_file =  $path.$filename;
		// Check file is exists on given path.
		if(file_exists($download_file))
		{
			// Getting file extension.
			$extension = explode('.',$filename);
			$extension = $extension[count($extension)-1];
			// For Gecko browsers
			header('Content-Transfer-Encoding: binary');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
			// Supports for download resume
			header('Accept-Ranges: bytes');
			// Calculate File size
			header('Content-Length: ' . filesize($download_file));
			header('Content-Encoding: none');
			// Change the mime type if the file is not PDF
			header('Content-Type: application/'.$extension);
			// Make the browser display the Save As dialog
			header('Content-Disposition: attachment; filename=' . $filename);
			readfile($download_file);
			exit;
		}
		else
		{
			echo 'File does not exists on given path';
		}

	}
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
	    <span class='icon'>&#xe056;</span><?php echo substr($_SESSION['login_fname'],0,1)." ".$_SESSION['login_lname']; ?>
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
<!--		  Add User & Modify User Page fetch Org Unit Name from #ouid-->
		  <li><p id="ouid" class="text-muted h6" style="margin-left:20px">Org Unit : <?php echo $rowsmenu['OU_ABBREV'];
				  ?></p></li>
		  <li><p class="text-muted h6" style="margin-left:20px">Role : <?php echo $rowsmenu['SYS_USER_ROLE']; ?></p> </li>
		  <li><p class="text-muted h6" style="margin-left:20px">Right desc : <?php echo $rowsmenu['USER_RIGHT']; ?></p> </li>

		   <li role="separator" class="divider"></li>

		  <li><a href="../Pages/profile.php"><span class="icon">&#xe058;</span>Profile</a></li>


			<!-- User Specific Menu under Drop Down - Right most-->
		  <?php
		  switch ($rowsmenu['SYS_USER_ROLE'])
		  {
			  case "sysadmin" :
				  for($i = 0; $i < count($menu); $i++){
					  if($menu[$i][3] == "admin"){
						  echo "<li><a class = '". ($menu[$i][4] ? "selected" : "") ."'href='../../Pages/". $menu[$i][1] ."'><span class='icon'>". $menu[$i][2] . "</span>" . $menu[$i][0] ."</a></li>";
					  }
				  }
				  echo "<li role='separator' class='divider'></li>";
				  break;
			  case "contributor" :
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
	Left Navigation Menu
	-->

<nav class="col-xs-2" id="menu">
	<!-- Main menu -->

	<ul class="col-xs-12">
		<?php if(@$BackToDashboard){ ?>
			<li class="" id="header"><a class="" href="<?php echo 'bphome.php?ayname='.@$bpayname.'&ou_abbrev='
					.$_SESSION['bpouabbrev']; ?>" >
			<span id="" class="icon">l</span>Back To Contents</a></li>
		<?php } ?>
		<?php if(@$BackToFileUploadHome){ ?>
			<li class="" id="header"><a class="" href="<?php echo 'fileuploadhome.php?ayname='.$_SESSION['FUayname']; ?>" >
					<span id="" class="icon">l</span>Back To Upload</a></li>
		<?php } ?>
		<?php if(@$BackToDataDictHome){ ?>
			<li class="" id="header"><a class="" href="<?php echo 'datadicthome.php' ?>" >
					<span id="" class="icon">l</span>Back To Dictionary</a></li>
		<?php } ?>
        <?php if(@$BackTofootnoteHome){ ?>
            <li class="" id="header"><a class="" href="<?php echo 'footnotehome.php' ?>" >
                    <span id="" class="icon">l</span>Back To Footnotes</a></li>
        <?php } ?>
		<?php if(@$BackToGoalOutHome){ ?>
			<li class="" id="header"><a class="" href="<?php echo 'goaloutcomeshome.php?linkid='.$contentlink_id; ?>" >
					<span id="" class="icon">l</span>Back To Outcomes</a></li>
		<?php } ?>
		<?php if(@$BackToGoal){ ?>
			<li class="" id="header"><a class="" onclick="window.history.back();" >
					<span id="" class="icon">l</span>Back To Previous</a></li>
		<?php } ?>

        <li class="" id="header">Main</li>

		<li><a id="main" class="main  selected" href="account.php">Home</a></li>


		<?php
		for ($i = 0; $i < count($menu); $i++) {
			if (strcmp($rowsmenu['SYS_USER_ROLE'], "provost") == 0) {
				if ($menu[$i][3] == "main" && ($menu[$i][4] == "provost" or $menu[$i][4] == "basic")) {
					echo "<li><a id ='" . $menu[$i][3] . "' class = '" . ($menu[$i][4] ? "selected" : "") . " ' href='../../Pages/" . $menu[$i][1] . "'><span class='icon'>" . $menu[$i][2] . "</span>" . $menu[$i][0] . "</a></li>";

				}
				continue;
			}
			if ($rowsmenu['SYS_USER_RIGHT'] == 3) {
				if ($menu[$i][3] == "main" && ($menu[$i][4] == "approver" or $menu[$i][4] == "basic")) {
					echo "<li><a id ='" . $menu[$i][3] . "' class = '" . ($menu[$i][4] ? "selected" : "") . " ' href='../../Pages/" . $menu[$i][1] . "'><span class='icon'>" . $menu[$i][2] . "</span>" . $menu[$i][0] . "</a></li>";
				}
				continue;
			}

            if ($rowsmenu['OU_TYPE'] == 'Service Unit') {
                if ($menu[$i][3] == "main" && ($menu[$i][4] == 'service'or $menu[$i][4] == "basic")) {
                    echo "<li><a id ='" . $menu[$i][3] . "' class = '" . ($menu[$i][4] ? "selected" : "") . " ' href='../../Pages/" . $menu[$i][1] . "'><span class='icon'>" . $menu[$i][2] . "</span>" . $menu[$i][0] . "</a></li>";

                }
                continue;
            }
            if ($menu[$i][3] == "main" && ($menu[$i][4] <> "provost" and $menu[$i][4] <> "approver" and $menu[$i][4] <> "service")) {
                echo "<li><a id ='" . $menu[$i][3] . "' class = '" . ($menu[$i][4] ? "selected" : "") . " ' href='../../Pages/" . $menu[$i][1] . "'><span class='icon'>" . $menu[$i][2] . "</span>" . $menu[$i][0] . "</a></li>";
            }
        }
		?>

<!--        <li><a id="main" class="main  selected" href="account.php">View OIRAA Data</a></li>-->
	</ul>

	<!-- Blueprint Home Menu -->

</nav>
