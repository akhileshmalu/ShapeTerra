<?php
	session_start();
	$error = array();
	$errorflag = 0;
	$goalstatement="";
	$goaltitle="";

	require_once ("../Resources/Includes/connect.php");
	$sql = "Select * from AcademicYears where ID_ACAD_YEAR > '1600' AND GOAL_STATUS_ID is null;";
	$result = $mysqli->query($sql);

	if(isset($_POST['submit'])) {
	//    if (empty($_POST['AY'])) {
	//        $error[0] = " Please select a Academic Year";
	//        $errorflag = 1;
	//    }
	    if (empty($_POST['goaltitle'])) {
	        $error[0] = " Please enter goal title.";
	        $errorflag = 1;
	    }
	    if (empty($_POST['goalstatement'])) {
	        $error[2] = " Please enter goal statement.";
	        $errorflag = 1;
	    }

	    if ($errorflag != 1) {

	        $ay = test_input($_POST['AY']);
	        $goaltitle = test_input($_POST['goaltitle']);
	        $goalstatement = mynl2br($_POST['goalstatement']);
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
	            $error[0] = "Goal has been successfully added.";


	        $id= stringtoid($_POST['AY']);
	//        $academicstartdate="20".($id /100)."-08-16";
	//        $academicenddate="20".($id%100)."-08-15";
	//
	//        $sql = "INSERT INTO AcademicYears (ID_ACAD_YEAR,ACAD_YEAR_DESC,ACAD_YEAR_DATE_BEGIN,ACAD_YEAR_DATE_END) VALUES ('$id','$ay','$academicstartdate','$academicenddate');";
	          $sql = "Update AcademicYears SET GOAL_STATUS_ID = 06 where ID_ACAD_YEAR = '$id'";
	            $mysqli->query($sql);

	        } else {
	            $error[0] = "Goal could not be added.";
	        }

	    }

}

	//Include Header
	require_once("../Resources/Includes/header.php");

	/*
 * Function to obtain String from ID and ID from String.
 */

function idtostring ($id){
    $id= $id %100;
    $string = "AY20".$id."-20".($id+1);
    return $string;
}
function stringtoid ($string){

    $id = intval(substr($string,4,2));
    $id = ($id*100)+$id+1;
    return $id;
}
/*
 * Function for taking paragraph with lines input in goal statement
 */
function mynl2br($text) {
    return strtr($text, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />'));
}
?>

<link href="Css/goalManagement.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
	// Include Menu and Top Bar
	require_once("../Resources/Includes/menu.php");
?>
<div class="hr"></div>
	<div id="main-content" class="col-lg-10 col-xs-8">
		<div class="col-xs-12">
		<h1 id="title" class="col-lg-5 col-xs-8">Goal Management</h1>
		
		<button id="add-goal" class="btn-primary col-lg-2 col-xs-4 pull-left" data-toggle="modal" data-target="#addGoalModal"><span class="icon">&#xe035;</span> Add Goal</button>

		<?php if(isset($_POST['submit'])) { ?>
            <div class="col-xs-offset-3 col-xs-2 alert alert-success">
                <?php foreach ($error as $value)echo $value; ?>
            </div>
        <?php } ?>
		
		</div>
		<div class="col-xs-3" id="table-container">
			<table class="table table-striped table-hover">
				<tr>
	  				<th>Academic Year</th>
	  			</tr>
				<?php
					$sql = "Select * from AcademicYears where GOAL_STATUS_ID = 6";
            		$result = $mysqli->query($sql);
            		while($rows = $result ->fetch_assoc()):{ ?>
            			<tr id="year" class="<?php echo $rows['ACAD_YEAR_DESC']; ?>">
                			<td><?php echo $rows['ACAD_YEAR_DESC']; ?></td>
                		</tr>	
            	<?php 
            		} endwhile; 
            		mysqli_data_seek($result, 0);
            	?>
			</table>
		</div>
		<div class="col-xs-3" id="table-container">
			<table class="table table-striped table-hover">
				<tr>
	  				<th>Goal title</th>
	  			</tr>
				<?php
					$sql = "select * from UniversityGoals ORDER BY GOAL_ACAD_YEARS ASC";
            		$result = $mysqli->query($sql);
            		while($rows = $result ->fetch_assoc()):{ ?>
            			<tr id="goal" class="<?php echo $rows['ID_UNIV_GOAL'] . " " . $rows['GOAL_ACAD_YEARS']; ?> hidden">
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
            				<h3 class="title">Goal Title</h3>
            				<h2><?php echo $rows['GOAL_TITLE']; ?></h2>
            				<h3 class="title">Academic Year</h3>
            				<p><?php echo $rows['GOAL_ACAD_YEARS']; ?></p>	
            				<h3 class="title">Goal Statement</h3>
            				<p><?php echo $rows['GOAL_STATEMENT']; ?></p>
						</aside>
            	<?php 
            		} endwhile; 
            		mysqli_data_seek($result, 0);
            	?>

  	</div>

  	<!-- Modal -->
	<div class="modal fade" id="addGoalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Add Goal</h4>
	      </div>
	      <div class="modal-body">
	        <div class="col-xs-12">
    <form action ="" method="POST">
        <div class="form-group">
            <label for="AYgoal">Please select Academic Year:</label>
            <select name="AY" class="form-control" id="AYgoal">
                <option value =""></option selected>
                <?php while($row = $result ->fetch_array(MYSQLI_NUM)): { ?>
                    <option value="<?php echo $row[1]; ?>"> <?php echo $row[1]; ?> </option>
                <?php }  endwhile; ?>
                </select>
        </div>
        <div class="form-group">
            <label for="goaltitle">Please Enter Goal Title:</label>
            <input type = "text" class="form-control" name="goaltitle" id ="goaltitle" required>
        </div>
        <div class="form-group">
            <label for="goalstatement">Please Enter Goal Statement:</label>
            <textarea  class="form-control" name="goalstatement" id ="goalstatement" required></textarea>
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
