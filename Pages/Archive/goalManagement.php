<?php

	require_once ("../Resources/Includes/initalize.php");
	$initalize = new Initialize();
	$initalize->checkSessionStatus();
	$connection = $initalize->connection;

	$message = array();
	$errorflag = 0;
	$goalstatement="";
	$goaltitle="";

	require_once ("../Resources/Includes/connect.php");
	$sqlay = "Select * from AcademicYears where GOAL_STATUS_ID is null;";
	$resultay = $mysqli->query($sqlay);

	if(isset($_POST['submit'])) {
	//    if (empty($_POST['AY'])) {
	//        $error[0] = " Please select a Academic Year";
	//        $errorflag = 1;
	//    }
	    if (empty($_POST['goaltitle'])) {
	        $message[0] = " Please enter goal title.";
	        $errorflag = 1;
	    }
	    if (empty($_POST['goalstatement'])) {
	        $message[2] = " Please enter goal statement.";
	        $errorflag = 1;
	    }

	    if ($errorflag != 1) {

	        $ay = $initalize->test_input($_POST['AY']);
	        $goaltitle = $initalize->test_input($_POST['goaltitle']);
	        $goalstatement = $initalize->mynl2br($_POST['goalstatement']);
	        $sql = "SELECT max(ID_UNIV_GOAL) AS lastid FROM UniversityGoals;";
	        $result = $mysqli->query($sql);
	        $row = $result->fetch_assoc();
	        if ($row['lastid'] != 0) {
	            $nextid = $row['lastid'] + 1;
	        } else {
	            $nextid = 1;
	        }
	        $sql = "INSERT INTO UniversityGoals(ID_UNIV_GOAL,GOAL_ACAD_YEARS,GOAL_TITLE,GOAL_STATEMENT) VALUES ('$nextid','$ay','$goaltitle','$goalstatement');";

	        if ($mysqli->query($sql)) {
	            $message[0] = "Goal has been successfully added.";


	        $id= $initalize->stringtoid($_POST['AY']);
				//
	//        $sql = "INSERT INTO AcademicYears (ID_ACAD_YEAR,ACAD_YEAR_DESC,ACAD_YEAR_DATE_BEGIN,ACAD_YEAR_DATE_END) VALUES ('$id','$ay','$academicstartdate','$academicenddate');";
	          $sql = "Update AcademicYears SET GOAL_STATUS_ID = 06 where ID_ACAD_YEAR = '$id'";
	            $mysqli->query($sql);

	        } else {
	            $message[0] = "Goal could not be added.";
	        }

	    }

}

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

<div class="overlay hidden"></div>
<?php if (isset($_POST['submit'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($message as $value) echo $value; ?></p>
        <button type="button" redirect="bphome.php" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>
<div id="main-content" class="col-lg-10 col-xs-8">
	<div class="col-xs-12">
		<h1 id="title" class="col-lg-5 col-xs-8">Goal Management</h1>

		<button id="add-goal" class="btn-primary col-lg-2 col-xs-4 pull-left" data-toggle="modal"
				data-target="#addGoalModal"><span class="icon">&#xe035;</span> Add Goal
		</button>
	</div>
	<div class="col-xs-3" id="table-container">
		<table class="table table-striped table-hover">
			<tr>
				<th>Academic Year</th>
			</tr>
			<?php
			$sqlviewgoal = "SELECT * FROM AcademicYears WHERE GOAL_STATUS_ID = 6";
			$resultviewgoal = $mysqli->query($sqlviewgoal);
			while ($rowsviewgoal = $resultviewgoal->fetch_assoc()): { ?>
				<tr id="year" class="<?php echo $rowsviewgoal['ACAD_YEAR_DESC']; ?>">
					<td><?php echo $rowsviewgoal['ACAD_YEAR_DESC']; ?></td>
				</tr>
				<?php
			} endwhile;
			mysqli_data_seek($resultviewgoal, 0);
			?>
		</table>
	</div>
	<?php
	$sqlug = "SELECT * FROM UniversityGoals";
	$resultug = $mysqli->query($sqlug);
	while ($rowsug = $resultug->fetch_assoc()): { ?>
		<aside class="col-xs-9 hidden <?php echo $rowsug['GOAL_ACAD_YEARS']; ?>" id="goal-summary">
			<h3 class="title">Goal Title</h3>
			<h2><?php echo $rowsug['GOAL_TITLE']; ?></h2>
			<h3 class="title">Goal Statement</h3>
			<p><?php echo $rowsug['GOAL_STATEMENT']; ?></p>
		</aside>
		<div id="aside-line"></div>
		<?php

	} endwhile;
	mysqli_data_seek($resultug, 0);
	?>

</div>

<!-- Modal -->
<div class="modal fade" id="addGoalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Goal</h4>
			</div>
			<div class="modal-body">
				<div class="col-xs-12">
					<form action="" method="POST">
						<div class="form-group">
							<label for="AYgoal">Please select Academic Year:</label>
							<select name="AY" class="form-control" id="AYgoal">
								<option value=""></option selected>
								<?php while ($rowsay = $resultay->fetch_array(MYSQLI_NUM)): { ?>
									<option value="<?php echo $rowsay[1]; ?>"> <?php echo $rowsay[1]; ?> </option>
								<?php } endwhile; ?>
							</select>
						</div>
						<div class="form-group">
							<label for="goaltitle">Please Enter Goal Title:</label>
							<input type="text" class="form-control" name="goaltitle" id="goaltitle" required>
						</div>
						<div class="form-group">
							<label for="goalstatement">Please Enter Goal Statement:</label>
							<textarea class="form-control" name="goalstatement" id="goalstatement" required></textarea>
						</div>
						<input type="submit" name="submit" value="Submit" class="btn-primary btn-sm">
					</form>
				</div>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<?php
	//Include Footer
	require_once("../Resources/Includes/footer.php");
?>
<script src="../Resources/Library/js/goalManagement.js"></script>
