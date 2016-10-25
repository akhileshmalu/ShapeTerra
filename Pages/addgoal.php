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

<?php
require_once("../Resources/Includes/menu.php");
?>

<div class="col-lg-offset-3 col-lg-3 col-md-6 col-xs-9" id="ContentRight">
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

        <?php if(isset($_POST['submit'])) { ?>
            <div class="alert alert-warning">
                <?php foreach ($error as $value)echo $value; ?>
            </div>
        <?php } ?>
        <input type="submit" name="submit" value="Submit" class="btn-primary btn-sm">
    </form>
</div>


<?php
require_once("../Resources/Includes/footer.php");
?>
