<?php
session_start();
require_once ("../Resources/Includes/connect.php");
$ay = array();
$ayname ="";
$error = array();

$unigoallink = array();
$unigoallinkname = "";

$sqlbroad = "select * from broadcast;";
$resultbroad=$mysqli1->query($sqlbroad);
$rowsbroad = $resultbroad->fetch_assoc();

$sqlay = "Select * from AcademicYears;";
$resultay = $mysqli->query($sqlay);

if(isset($_POST['submit'])) {
    $ouabbrev = $_SESSION['login_ouabbrev'];
    $author = $_SESSION['login_email'];
    $time = date('Y-m-d H:i:s');
    $ay = $_POST['AY'];
    foreach ($ay as $value) {
        $ayname .= $value . ",";
    }
    $goaltitle = $_POST['goaltitle'];

    $unigoallink = $_POST['goallink'];
    foreach ($unigoallink as $value) {
        $unigoallinkname .= $value . ",";
    }

    $goalstatement = mynl2br($_POST['goalstatement']);
    $goalalignment = mynl2br($_POST['goalalignment']);

    $sqlunitgoal = "INSERT INTO BP_UnitGoals (OU_ABBREV, GOAL_AUTHOR, MOD_TIMESTAMP, UNIT_GOAL_AY, UNIT_GOAL_TITLE, LINK_UNIV_GOAL, GOAL_STATEMENT, GOAL_ALIGNMENT) VALUES ('$ouabbrev','$author','$time','$ayname','$goaltitle','$unigoallinkname','$goalstatement','$goalalignment');";
    if($mysqli->query($sqlunitgoal)) {
        $error[0] = "Unit Goal has been successfully added.";
    } else {
        $error[0] = "Unit Goal could not be added.";
    }

}


//Include Header
require_once("../Resources/Includes/header.php");
?>

<body>
<?php
// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="Css/templateTabs.css" rel="stylesheet" type="text/css"/>
<link href="Css/goalManagement.css" rel="stylesheet" type="text/css"/>

<div class="hr"></div>
<div id="main-content" class="col-xs-10">
    <h1 id="title">BluePrint Completion</h1>

    <ul id="tabs" class="nav nav-pills" id="menu-secondary" role="tablist">
        <li class="active"><a href="#input1">Confirm Goals</a></li>
        <li><a href="#input2">Input Section 1</a></li>
    </ul>
</div>

<div class="tab-content">
    <div role="tabpanel" class="tab-pane active fade in" id="input1">
        <div class="form-group col-xs-6" id="actionlist">
            <label for="goaltitle">Please confirm Previous year Goals :</label><br>
            <select multiple="multiple" class="form-control" name="goaltitles[]" id="goaltitle">
                <?php
                $ay = $rowsbroad['BROADCAST_AY'];
                $ay -= 101;
                $ouabbrev = $_SESSION['login_ouabbrev'];
                $aydesc = idtostring($ay);
                $sqlunit = "select * from BP_UnitGoals where find_in_set ('$aydesc',UNIT_GOAL_AY)>0 and OU_ABBREV ='$ouabbrev';";
                $resultunit = $mysqli->query($sqlunit);
                while ($rowsunit = $resultunit->fetch_assoc()) { ?>
                    <option value="<?php echo $rowsunit['ID_UNIT_GOAL']; ?>"><?php echo $rowsunit['UNIT_GOAL_TITLE'] . "\n"; ?></option>
                <?php } ?>
            </select>
            <button id="add-goal" class="btn-primary col-lg-4 col-xs-4 pull-left" data-toggle="modal"
                    data-target="#addunitGoalModal"><span class="icon">&#xe035;</span> Add Goal
            </button>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane fade " id="input2">
        Tab 2
    </div>
</div>


<!--Modal-->
<div class="modal fade" id="addunitGoalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Unit Goal</h4>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="AYgoal">Please select Academic Year:</label>
                            <select multiple="multiple" name="AY[]" class="form-control" id="AYgoal" required>
                                <option value=""></option>
                                <?php while ($rowsay = $resultay->fetch_array(MYSQLI_NUM)) { ?>
                                    <option value="<?php echo $rowsay[1]; ?>"> <?php echo $rowsay[1]; ?> </option>
                                <?php }  ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="goaltitle">Please Enter Goal Title:</label>
                            <input type="text" class="form-control" name="goaltitle" id="goaltitle" required>
                        </div>
                        <div class="form-group">
                            <label for="goaltitle">Link to University Goals:</label>
                            <select multiple="multiple" class="form-control" name="goallink[]" id="goallink">
                                <?php
                                $sqlug = "SELECT * FROM UniversityGoals;";
                                $resultug = $mysqli->query($sqlug);
                                echo "<option value=''> </option>";
                                while ($rowsug = $resultug->fetch_assoc()): { ?>
                                    <option
                                        value="<?php echo $rowsug['ID_UNIV_GOAL'] . "\n"; ?>"><?php echo $rowsug['GOAL_TITLE'] . "\n"; ?></option>
                                <?php } endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="goalstatement">Please Enter Goal Statement:</label>
                            <textarea rows="5" class="form-control" name="goalstatement" id="goalstatement"
                                      required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="goalalignment">Please explain Goal Alignment:</label>
                            <textarea rows="5" class="form-control" name="goalalignment" id="goalalignment"
                                      required></textarea>
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
