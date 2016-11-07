<?php
session_start();
require_once("../Resources/Includes/connect.php");
$ay = array();
$ayname = "";
$error = array();

$visionstatement = "";
$missionstatement = "";
$valuestatement = "";

$goalid = array();


$unigoallink = array();
$unigoallinkname = "";

$ouabbrev = $_SESSION['login_ouabbrev'];

$sqlbroad = "SELECT * FROM broadcast inner join Hierarchy on BROADCAST_OU = Hierarchy.ID_HIERARCHY where Hierarchy.OU_ABBREV = '$ouabbrev';";
$resultbroad = $mysqli1->query($sqlbroad);
$rowsbroad = $resultbroad->fetch_assoc();
$ay = $rowsbroad['BROADCAST_AY'];
$ayid = stringtoid($ay);
$prevay = $ayid - 101;
$aydesc = idtostring($prevay);

$author = $_SESSION['login_email'];
$time = date('Y-m-d H:i:s');

$sqlay = "SELECT * FROM AcademicYears;";
$resultay = $mysqli->query($sqlay);

if (isset($_POST['mission_submit'])) {
    $missionstatement = mynl2br($_POST['missionnew']);
}

if (isset($_POST['vision_submit'])) {
    $visionstatement = mynl2br($_POST['visionnew']);
}
if (isset($_POST['value_submit'])) {
    $valuestatement = mynl2br($_POST['valuenew']);
}
if (isset($_POST['goal_submit'])) {
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

    $sqlunitgoal ="INSERT INTO BP_UnitGoals (OU_ABBREV, GOAL_AUTHOR, MOD_TIMESTAMP, UNIT_GOAL_AY, UNIT_GOAL_TITLE, LINK_UNIV_GOAL, GOAL_STATEMENT, GOAL_ALIGNMENT) VALUES ('$ouabbrev','$author','$time','$ayname','$goaltitle','$unigoallinkname','$goalstatement','$goalalignment');";

    $mysqli->query($sqlunitgoal);
}

if (isset($_POST['approve'])) {

    if (!isset($_POST['vision_submit'])) {

        $visionstatement = mynl2br($_POST['visionstatement']);
    }
    if (!isset($_POST['mission_submit'])) {

        $missionstatement = mynl2br($_POST['missionstatement']);
    }
    if (!isset($_POST['value_submit'])) {

        $valuestatement = mynl2br($_POST['valuestatement']);
    }

    $goalid = remove_empty($_POST['goaltitle']);

    $sqlcreatebp = "INSERT INTO BP_MissionVisionValues (OU_ABBREV, MVV_AUTHOR, MOD_TIMESTAMP, UNIT_MVV_AY, MISSION_STATEMENT, VISION_STATEMENT, VALUES_STATEMENT) VALUES ('$ouabbrev','$author','$time','$ay','$missionstatement','$visionstatement','$valuestatement');";
    foreach($goalid as $idk) {
        $sqlcreatebp .= "INSERT INTO BP_UnitGoals (OU_ABBREV, GOAL_AUTHOR, MOD_TIMESTAMP, UNIT_GOAL_AY, UNIT_GOAL_TITLE, LINK_UNIV_GOAL, GOAL_STATEMENT, GOAL_ALIGNMENT) SELECT OU_ABBREV, GOAL_AUTHOR, MOD_TIMESTAMP, '$ay', UNIT_GOAL_TITLE, LINK_UNIV_GOAL, GOAL_STATEMENT, GOAL_ALIGNMENT FROM BP_UnitGoals WHERE ID_UNIT_GOAL ='$idk';";
    }
    if ($mysqli->multi_query($sqlcreatebp)) {
        $error[0] = "BluePrint has been successfully approved.";
    } else {
        $error[0] = "BluePrint could not be  approved.";
    }

}

function remove_empty($array) {
    return array_filter($array, '_remove_empty_internal');
}

function _remove_empty_internal($value) {
    return !empty($value) || $value === 0;
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
    <h1 id="title">BluePrint Approval</h1>

    <ul id="tabs" class="nav nav-pills"  role="tablist">
        <li class="active"><a href="#input1" data-toggle="tab">Confirm Mission</a></li>
        <li><a href="#input2" data-toggle="tab">Confirm Vision</a></li>
        <li><a href="#input3" data-toggle="tab">Confirm Values</a></li>
        <li><a href="#input4" data-toggle="tab">Confirm Goals</a></li>
    </ul>
</div>

<div class="tab-content">
    <div role="tabpanel" class="tab-pane active fade in" id="input1">
<!--        <form action="" method="POST">-->
            <div class="form-group col-xs-6" id="actionlist">
                <label for="missiontitle">Please Verify If <strong>Mission Statement</strong> is changed from Previous Year:</label>
                <?php

                $sqlmission = "select * from BP_MissionVisionValues where UNIT_MVV_AY ='$aydesc' and OU_ABBREV ='$ouabbrev';";
                $resultmission = $mysqli->query($sqlmission);
                $rowsmission = $resultmission->fetch_assoc()
                ?>
                <textarea rows="5" cols="25" wrap="hard" class="form-control" name="missionstatement" id="missiontitle"
                          required><?php echo $rowsmission['MISSION_STATEMENT']; ?></textarea>
                <button id="changetabbutton" type="button" name="nochangemissoin"
                        class="btn-primary col-lg-4 col-xs-4 pull-left">Same as Before
                </button>
                <button id="add-mission" class="btn-primary col-lg-4 col-xs-4 pull-left" data-toggle="modal"
                        data-target="#addmissionModal"><span class="icon">&#xe035;</span> Add Mission
                </button>
            </div>
<!--        </form>-->
    </div>
    <div role="tabpanel" class="tab-pane fade" id="input2">
        <form action="" method="POST">
            <div class="form-group col-xs-6" id="actionlist">
                <label for="visiontitle">Please Verify If <strong>Vision Statement</strong> is changed from Previous Year:</label><br>
                <textarea rows="5" cols="25" wrap="hard" class="form-control" name="visionstatement" required><?php echo $rowsmission['VISION_STATEMENT']; ?></textarea>
                <button id="changetabbutton" type="button" name="nochangemissoin"
                        class="btn-primary col-lg-4 col-xs-4 pull-left">Same as Before
                </button>
                <button id="add-vision" class="btn-primary col-lg-4 col-xs-4 pull-left" data-toggle="modal"
                        data-target="#addvisionModal"><span class="icon">&#xe035;</span> Add Vision</button>
            </div>
        </form>
    </div>
    <div role="tabpanel" class="tab-pane fade" id="input3">
        <form action="" method="POST">
            <div class="form-group col-xs-6" id="actionlist">
                <label for="valuestatement">Please Verify If <strong>Value Statement</strong> is changed from Previous Year:</label>
                <textarea id="valuestatement" rows="5" cols="25" wrap="hard" class="form-control" name="valuestatement"
                          required><?php echo $rowsmission['VALUES_STATEMENT']; ?></textarea>
                <button id="changetabbutton" type="button" name="nochangemissoin"
                        class="btn-primary col-lg-4 col-xs-4 pull-left">Same as Before
                </button>
                <button id="add-value" class="btn-primary col-lg-4 col-xs-4 pull-left" data-toggle="modal"
                        data-target="#addvalueModal"><span class="icon">&#xe035;</span> Add Values</button>
            </div>
        </form>
    </div>
    <div role="tabpanel" class="tab-pane fade" id="input4">
        <form action="" method="POST">
        <div class="form-group col-xs-6" id="actionlist">
            <label for="goaltitle">Please Select Previous year Goals to verify:</label><br>
            <?php
            $aydesc = idtostring($prevay);
            $sqlunit = "select * from BP_UnitGoals where find_in_set ('$aydesc',UNIT_GOAL_AY)>0 and OU_ABBREV ='$ouabbrev';";
            $resultunit = $mysqli->query($sqlunit);
            $rowsunit = $resultunit->fetch_assoc();
            $row_cnt = $resultunit->num_rows;
            for ($a = 0; $a < $row_cnt; $a++) { ?>
                <select class="form-control" name="<? echo 'goaltitle[' . $a . ']'; ?>" id="goaltitle">
                    <option value="0"></option>
                    <option
                        value="<?php echo $rowsunit['ID_UNIT_GOAL']; ?>"><?php echo $rowsunit['UNIT_GOAL_TITLE']; ?></option>
                </select>
                <p></p>
            <?php } ?>
            <button id="add-goal" class="btn-primary col-lg-4 col-xs-4 pull-left" data-toggle="modal"
                    data-target="#addunitGoalModal"><span class="icon">&#xe035;</span>Add Goal</button>
            <button id="add-goal" type="submit" name="approve" class="btn-primary col-lg-4 col-xs-4 pull-left">Approve</button>
        </div>
        </form>
    </div>

</div>


<!--Add Goal Modal-->
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
                                <?php } ?>
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
                        <input type="submit" name="goal_submit" value="Add Unit Goal" class="btn-primary btn-sm">
                    </form>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


<!--Mission Modal-->

<div class="modal fade" id="addmissionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Mission Statement</h4>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="missionstate">Please Enter New Mission Statement:</label>
                            <input type="text" class="form-control" name="missionnew" id="missionstate" required>
                        </div>
                        <input type="submit" name="mission_submit" value="Add Mission" class="btn-primary btn-sm">
                    </form>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<!--Vision Modal-->

<div class="modal fade" id="addvisionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Vision Statement</h4>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="missionstate">Please Enter New Vision Statement:</label>
                            <input type="text" class="form-control" name="visionnew" id="visionstate" required>
                        </div>
                        <input type="submit" name="vision_submit" value="Add Vision" class="btn-primary btn-sm">
                    </form>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<!--Value Modal-->

<div class="modal fade" id="addvalueModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Value Statement</h4>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="valuestate">Please Enter New Value Statement:</label>
                            <input type="text" class="form-control" name="valuenew" id="valuestate" required>
                        </div>
                        <input type="submit" name="value_submit" value="Add Value" class="btn-primary btn-sm">
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