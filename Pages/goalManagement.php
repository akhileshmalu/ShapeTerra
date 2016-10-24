<?php
	session_start();
	$error = array();
	$errorflag = 0;
	require_once ("../Resources/Includes/connect.php");

	//Include Header
	require_once("../Resources/Includes/header.php");
?>

<link href="Css/goalManagement.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
	// Include Menu and Top Bar
	require_once("../Resources/Includes/menu.php");
?>
<div class="hr"></div>
	<div id="main-content" class="col-xs-10">
		<h1 id="title" class="col-xs-9">Goal Management</h1>
		<button id="add-goal" class="btn-primary col-xs-2"><span class="icon">&#xe035;</span> Add Goal</button>
		<div class="col-xs-6" id="table-container">
			<table class="table table-striped table-hover col-xs-6">
				<tr>
	  				<th>Academic Year</th>
	  				<th>Goal Title</th>
	  			</tr>
				<?php
					$sql = "select * from UniversityGoals ORDER BY GOAL_ACAD_YEARS ASC";
            		$result = $mysqli->query($sql);
            		while($rows = $result ->fetch_assoc()):{ ?>
            			<tr class="<?php echo $rows['ID_UNIV_GOAL']; ?>">
                		<td><?php echo $rows['GOAL_ACAD_YEARS']; ?></td>
                		<td><?php echo $rows['GOAL_TITLE']; ?></td>
                		</tr>	
            	<?php 
            		} endwhile; 
            		mysqli_data_seek($result, 0);
            	?>
			</table>
		</div>
			<?php
					$sql = "select * from UniversityGoals";
            		$result = $mysqli->query($sql);
            		while($rows = $result ->fetch_assoc()):{ ?>
            			<aside class="col-xs-6 hidden <?php echo $rows['ID_UNIV_GOAL']; ?>" id="goal-summary">
            				<h5><?php echo $rows['GOAL_ACAD_YEARS']; ?></h5>
            				<h1><?php echo $rows['GOAL_TITLE']; ?></h1>	
            				<p><?php echo $rows['GOAL_STATEMENT']; ?></p>
						</aside>
                		
                		
            	<?php 
            		} endwhile; 
            		mysqli_data_seek($result, 0);
            	?>
		


  	</div>



<?php
	//Include Footer
	require_once("../Resources/Includes/footer.php");

?>
<script src="../Resources/Library/js/goalManagement.js"></script>
