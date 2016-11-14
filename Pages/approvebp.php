<?php
session_start();
require_once("../Resources/Includes/connect.php");
$aysubmit = array();
$ayname = "";
$error = array();

//SQL query variables
$sqlcreatebp = "";
$visionstatement = "";
$missionstatement = "";
$valuestatement = "";

// Variable for selecting Org Unit in Broadcast table.
$goalid = array();
$ouid = $_SESSION['login_ouid'];

/*
 * Multiple Sel value variable for Link to University goals in Unit goal Modal.
 */
$unigoallink = array();
$unigoallinkname = "";

$ouabbrev = $_SESSION['login_ouabbrev'];

/*
 * Query handler for Input from Broadcast record <blueprint Intiation>
 */

$sqlbroad = "SELECT * FROM broadcast inner join Hierarchy on BROADCAST_OU = Hierarchy.ID_HIERARCHY where Hierarchy.OU_ABBREV = '$ouabbrev';";
$resultbroad = $mysqli1->query($sqlbroad);
$rowsbroad = $resultbroad->fetch_assoc();
$ay = $rowsbroad['BROADCAST_AY'];
$ayid = stringtoid($ay);

/*
 * Calculate Previous Year String
 */
$prevay = $ayid - 101;
$aydesc = idtostring($prevay);

$author = $_SESSION['login_email'];
$time = date('Y-m-d H:i:s');

/*
 * Query to select Academic Years in New Unit Goal Modal
 */
$sqlay = "SELECT * FROM AcademicYears;";
$resultay = $mysqli->query($sqlay);

/*
 * Query to Select Previous Year Mission , Visoin, Value Statement for Specific Org Unit.
 */
$sqlmission = "select * from BP_MissionVisionValues where UNIT_MVV_AY ='$aydesc' and OU_ABBREV ='$ouabbrev';";
$resultmission = $mysqli->query($sqlmission);
$rowsmission = $resultmission->fetch_assoc();


/*
 * Query to Select Unit Goals from Previous year for Specific Org Unit.
 */
$sqlunit = "select * from BP_UnitGoals where find_in_set ('$aydesc',UNIT_GOAL_AY)>0 and OU_ABBREV ='$ouabbrev';";
$resultunit = $mysqli->query($sqlunit);



if (isset($_POST['goal_submit'])) {
//    $aysubmit = $_POST['AY'];
//
//    foreach ($aysubmit as $value) {
//        $ayname .= $value . ",";
//    }
    $goaltitle = $_POST['goaltitle'];
    echo $goaltitle;
//
//    $unigoallink = $_POST['goallink'];
//    foreach ($unigoallink as $value) {
//        $unigoallinkname .= $value . ",";
//    }
//    $goalstatement = mynl2br($_POST['goalstatement']);
//    $goalalignment = mynl2br($_POST['goalalignment']);
//
//    $sqlcreatebp .="INSERT INTO BP_UnitGoals (OU_ABBREV, GOAL_AUTHOR, MOD_TIMESTAMP, UNIT_GOAL_AY, UNIT_GOAL_TITLE, LINK_UNIV_GOAL, GOAL_STATEMENT, GOAL_ALIGNMENT) VALUES ('$ouabbrev','$author','$time','$ayname','$goaltitle','$unigoallinkname','$goalstatement','$goalalignment');";
//
}

if (isset($_POST['approve'])) {

/*
 * Form submission without Refresh Page
 */
    $aysubmit = $_POST['AY'];

    foreach ($aysubmit as $value) {
        $ayname .= $value . ",";
    }
    $goaltitle = $_POST['goaltitle'];
//    echo $goaltitle;

    $unigoallink = $_POST['goallink'];
    foreach ($unigoallink as $value) {
        $unigoallinkname .= $value . ",";
    }
    $goalstatement = mynl2br($_POST['goalstatement']);
    $goalalignment = mynl2br($_POST['goalalignment']);




    $missionstatement = mynl2br($_POST['missionstatement']);
    $visionstatement = mynl2br($_POST['visionstatement']);
    $valuestatement = mynl2br($_POST['valuestatement']);

    $goalid = $_POST['goaltitle'];

    //Mission , Vision , value Statement recorded for BP Academic Year
    $sqlcreatebp = "INSERT INTO BP_MissionVisionValues (OU_ABBREV, MVV_AUTHOR, MOD_TIMESTAMP, UNIT_MVV_AY, MISSION_STATEMENT, VISION_STATEMENT, VALUES_STATEMENT) VALUES ('$ouabbrev','$author','$time','$ay','$missionstatement','$visionstatement','$valuestatement');";

    //Selected Previous Year Goals for New BP Academic Year
    foreach($goalid as $idk) {
        $sqlcreatebp .= "INSERT INTO BP_UnitGoals (OU_ABBREV, GOAL_AUTHOR, MOD_TIMESTAMP, UNIT_GOAL_AY, UNIT_GOAL_TITLE, LINK_UNIV_GOAL, GOAL_STATEMENT, GOAL_ALIGNMENT) SELECT OU_ABBREV, '$author', '$time', '$ay', UNIT_GOAL_TITLE, LINK_UNIV_GOAL, GOAL_STATEMENT, GOAL_ALIGNMENT FROM BP_UnitGoals WHERE ID_UNIT_GOAL ='$idk';";
    }

    if(isset($_POST['addgoal'])) {
        $sqlcreatebp .= "INSERT INTO BP_UnitGoals (OU_ABBREV, GOAL_AUTHOR, MOD_TIMESTAMP, UNIT_GOAL_AY, UNIT_GOAL_TITLE, LINK_UNIV_GOAL, GOAL_STATEMENT, GOAL_ALIGNMENT) VALUES ('$ouabbrev','$author','$time','$ayname','$goaltitle','$unigoallinkname','$goalstatement','$goalalignment');";

    }
    //Broadcast update as per status.
    $sqlcreatebp .= "UPDATE broadcast SET BROADCAST_STATUS = 'Approved by Admin' where BROADCAST_OU = $ouid and BROADCAST_AY ='$ay';";

    if ($mysqli->multi_query($sqlcreatebp)) {
        $error[0] = "BluePrint has been successfully approved.";
    } else {
        $error[0] = "BluePrint could not be  approved.";
    }

}

/*Function to remove empty values from array

function remove_empty($array) {
    return array_filter($array, '_remove_empty_internal');
}

function _remove_empty_internal($value) {
    return !empty($value) || $value === 0;
}

*/

//Include Header
require_once("../Resources/Includes/header.php");
?>

<body>
<?php
// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>

<link href="Css/templateTabs.css" rel="stylesheet" type="text/css"/>
<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>

<div class="hr"></div>
<div id="main-content" class="col-xs-10">
    <h1 id="title">BluePrint Approval</h1> 

    <div id="list" class="col-xs-2">
        <ul class="tabs-nav">
            <li class="mission active">1. Mission Statement</li>
            <li class="vision disabled">2. Vision Statement</li>
            <li class="value disabled">3. Values Statement</li>
            <li class="goal disabled">4. Goals</li>
        </ul>
    </div>


    <div id="form" class="col-xs-9">
        <form action="" method="POST">   
            <div class="form-group mission active" id="actionlist">
               <?php if (isset($_POST['approve'])) { ?>
                   <div class="col-xs-8 alert alert-success">
                       <?php foreach ($error as $value) echo $value; ?>
                   </div>
               <?php } ?>

               <label class="col-xs-12" for="missiontitle">Mission Statement</label>
               
               <div class="col-xs-12">
                    <button id="add-mission" type="button" class="btn-secondary  col-xs-3 pull-left" data-toggle="modal"
                        data-target="#addmissionModal"><span class="icon">&#xe035;</span> Add Mission
                    </button>
                    <textarea rows="5" cols="25" wrap="hard" class="form-control" name="missionstatement" id="missiontitle"
                        readonly><?php echo $rowsmission['MISSION_STATEMENT']; ?></textarea>
                    <button id="changetabbutton" type="button" name="nochangemission"
                       class="btn-secondary col-xs-3 pull-left changeTab">Same as Before
                   </button>
                   
                   <button id="next-tab" type="button" class="btn-primary col-xs-3 pull-right changeTab"> Next Tab
                   </button>
                </div>
           </div>

           <div class="form-group hidden vision" id="actionlist">
               <label class="col-xs-12" for="visiontitle">Vission Statement</label>
               
               <div class="col-xs-12">
                    <button id="add-vission" type="button" class="btn-secondary  col-xs-3 pull-left" data-toggle="modal"
                        data-target="#addvisionModal"><span class="icon">&#xe035;</span> Add vission
                    </button>
                    <textarea rows="5" cols="25" wrap="hard" class="form-control" name="vissionstatement" id="vissiontitle"
                        readonly><?php echo $rowsmission['VISION_STATEMENT']; ?></textarea>
                    <button id="changetabbutton" type="button" name="nochangevission"
                       class="btn-secondary col-xs-3 pull-left changeTab">Same as Before
                   </button>
                   
                   <button id="next-tab" type="button" class="btn-primary col-xs-3 pull-right changeTab"> Next Tab
                   </button>
                </div>
           </div>

           <div class="form-group hidden value" id="actionlist">
                <label class="col-xs-12" for="visiontitle">Value Statement</label>
               
               <div class="col-xs-12">
                    <button id="add-value" type="button" class="btn-secondary  col-xs-3 pull-left" data-toggle="modal"
                        data-target="#addvalueModal"><span class="icon">&#xe035;</span> Add value
                    </button>
                    <textarea rows="5" cols="25" wrap="hard" class="form-control" name="valuestatement" id="valuetitle"
                        readonly><?php echo $rowsmission['VALUES_STATEMENT']; ?></textarea>
                    <button id="changetabbutton" type="button" name="nochangevalue"
                       class="btn-secondary col-xs-3 pull-left changeTab">Same as Before
                   </button>
                   
                   <button id="next-tab" type="button" class="btn-primary col-xs-3 pull-right changeTab"> Next Tab
                   </button>
                </div>
           </div>

           <div class="form-group hidden goal" id="actionlist">
               <label for="goaltitle">Goals</label><br>
               <button id="add-goal" type="button" class="btn-secondary col-xs-3 pull-left" data-toggle="modal"
                       data-target="#addunitGoalModal"><span class="icon">&#xe035;</span>Add Goal
               </button>
               <!--                --><?php //for ($a = 0; $a < $row_cnt; $a++) { ?>
                   <select multiple="multiple" class="form-control" name="goaltitle[]" id="goaltitle">
                       <option value="0"></option>
                       <?php while($rowsunit = $resultunit->fetch_assoc()){ ?>
                       <option
                           value="<?php echo $rowsunit['ID_UNIT_GOAL']; ?>"><?php echo $rowsunit['UNIT_GOAL_TITLE']; ?></option>
                       <?php } ?>
                   </select>
               
               <button id="add-goal" type="submit" name="approve" class="btn-primary col-xs-3 pull-right">
                   Approve
               </button>
           </div>
        </form>
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
                        <div class="form-group">
                            <label for="missionstate">Please Enter New Mission Statement:</label>
                            <input type="text" class="form-control" name="missionnew" id="missionstate" required>
                        </div>
                        <input type="button" id="missionbtn" name="mission_submit" value="Add Mission" class="btn-primary btn-sm">
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
                    <form action="#input1" method="POST">
                        <div class="form-group">
                            <label for="visionstate">Please Enter New Vision Statement:</label>
                            <input type="text" class="form-control" name="visionnew" id="visionstate" required>
                        </div>
                        <input type="button" id="visionbtn" value="Add Vision" class="btn-primary btn-sm">
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
                    <form action="#input2" method="POST">
                        <div class="form-group">
                            <label for="valuestate">Please Enter New Value Statement:</label>
                            <input type="text" class="form-control" name="valuenew" id="valuestate" required>
                        </div>
                        <input type="button" id="valuebtn" value="Add Value" class="btn-primary btn-sm">
                    </form>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
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

                <div id="modalcontent1" class="col-xs-12">
                    <form id="goalform" action="" method="POST">

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
                            <label for="goallink">Link to University Goals:</label>
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
                        <input type="submit" id="unitgoalbtn" name="goal_submit" value="Add Unit Goal" class="btn-primary btn-sm">
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

<script src="../Resources/Library/js/tabchange.js"></script>
