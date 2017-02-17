<?php
$error = array();
$errorflag = 0;

require_once ("../Resources/Includes/connect.php");
$sql = "Select * from AcademicYears where  GOAL_STATUS_ID = 6;";
$result = $mysqli->query($sql);

if(isset($_POST['submit'])) {
//    if (empty($_POST['AY'])) {
//        $error[0] = " Please select a Academic Year";
//        $errorflag = 1;
//    }
//    if($errorflag != 1){

//    $ay = $_POST['AY'];
//    $sql = "select * from UniversityGoals where find_in_set ('$ay',GOAL_ACAD_YEARS)>0;";
//    $result = $mysqli->query($sql);
//
//    while($rows = $result->fetch_assoc()){
//        echo $rows['GOAL_TITLE'];
//    }
//

  //  }


}


require_once("../Resources/Includes/header.php");
?>


<?php
// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>


<div class="col-lg-offset-3 col-lg-3 col-md-6 col-xs-9" id="ContentRight">
    <form action ="" method="POST">
        <div class="form-group">
            <label for="AYgoal">Please select Academic Year:</label>
            <select name="AY" class="form-control" id="AYgoal" required>
                <option value =""></option selected>
                <?php while($row = $result ->fetch_array(MYSQLI_NUM)): { ?>
                    <option value="<?php echo $row[1]; ?>"> <?php echo $row[1]; ?> </option>
                <?php }  endwhile; ?>
            </select>
        </div>
        <?php if(isset($_POST['submit'])) { ?>
        <div class="form-group">
            <label for="goaltitle">Goal Title:</label>
            <select multiple="multiple" name="goaltitles[]" id= "goaltitle">
                <?php
            $ay = $_POST['AY'];
            $sql = "select * from UniversityGoals where find_in_set ('$ay',GOAL_ACAD_YEARS)>0;";
            $result = $mysqli->query($sql);
            while($rows = $result ->fetch_assoc()):{ ?>
                <option value="<?php echo $rows['ID_UNIV_GOAL']."\n"; ?>"><?php echo $rows['GOAL_TITLE']."\n"; ?></option>
            <?php } endwhile; ?>
            </select>
        </div>
        <?php } ?>
        <input type="submit" name="submit" value="submit" class="btn-primary btn-sm">
    </form>
</div>



<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>
