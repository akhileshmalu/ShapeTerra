<?php
session_start();
require_once("../Resources/Includes/connect.php");
$aysubmit = array();
$ayname = "";
$error = array();

//SQL query variables
global $sqlcreatebp;
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
$goalmodalcount=0;
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


if(isset($_POST['goal_submit'])) {
    $goaltitle = $_POST['goaltitle'];

    $unigoallink = $_POST['goallink'];
    foreach ($unigoallink as $value) {
        $unigoallinkname .= $value . ",";
    }
    $goalstatement = mynl2br($_POST['goalstatement']);
    $goalalignment = mynl2br($_POST['goalalignment']);


    $sqlcreatebp .= "INSERT INTO BP_UnitGoals (OU_ABBREV, GOAL_AUTHOR, MOD_TIMESTAMP, UNIT_GOAL_AY, UNIT_GOAL_TITLE, LINK_UNIV_GOAL, GOAL_STATEMENT, GOAL_ALIGNMENT) VALUES ('$ouabbrev','$author','$time','$ay','$goaltitle','$unigoallinkname','$goalstatement','$goalalignment');";

    $mysqli->query($sqlcreatebp);

    $goalmodalcount++;

}


if (isset($_POST['approve'])) {

    if(empty($_POST['goaltitlelist']) && $goalmodalcount!= 0) {

        $error[1]="Please select a Goal to submit";
        $errorflag = 1;
    }


    if ($errorflag != 1) {
        $goalid = $_POST['goaltitlelist'];
        $missionstatement = mynl2br($_POST['missionstatement']);
        $visionstatement = mynl2br($_POST['visionstatement']);
        $valuestatement = mynl2br($_POST['valuestatement']);


        //Mission , Vision , value Statement recorded for BP Academic Year
        $sqlcreatebp .= "INSERT INTO BP_MissionVisionValues (OU_ABBREV, MVV_AUTHOR, MOD_TIMESTAMP, UNIT_MVV_AY, MISSION_STATEMENT, VISION_STATEMENT, VALUES_STATEMENT) VALUES ('$ouabbrev','$author','$time','$ay','$missionstatement','$visionstatement','$valuestatement');";

        //Selected Previous Year Goals for New BP Academic Year
        foreach ($goalid as $idk) {
            $sqlcreatebp .= "INSERT INTO BP_UnitGoals (OU_ABBREV, GOAL_AUTHOR, MOD_TIMESTAMP, UNIT_GOAL_AY, UNIT_GOAL_TITLE, LINK_UNIV_GOAL, GOAL_STATEMENT, GOAL_ALIGNMENT) SELECT OU_ABBREV, '$author', '$time', '$ay', UNIT_GOAL_TITLE, LINK_UNIV_GOAL, GOAL_STATEMENT, GOAL_ALIGNMENT FROM BP_UnitGoals WHERE ID_UNIT_GOAL ='$idk';";
        }

        //Broadcast update as per status.
        $sqlcreatebp .= "UPDATE broadcast SET BROADCAST_STATUS_OTHERS = 'Approved by Admin',BROADCAST_STATUS ='In Progress' where BROADCAST_OU = $ouid and BROADCAST_AY ='$ay';";

        if ($mysqli->multi_query($sqlcreatebp)) {

            $error[0] = "BluePrint has been successfully approved.";
        } else {
            $error[0] = "BluePrint could not be  approved.";

        }

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

<div class="overlay hidden"></div>
<?php if (isset($_POST['approve'])) { ?>
    <div class="alert">
        <a href="#" class="close end"><span class="icon">9</span></a>
        <h1 class="title"></h1>
        <p class="description"><?php foreach ($error as $value) echo $value; ?></p>
        <button type="button" class="end btn-primary">Close</button>
    </div>
<?php } ?>

<div class="hr"></div>
<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">BluePrint Approval</h1> 
    </div>

    <div id="list" class="col-lg-2 col-md-4 col-xs-4">
        <ul class="tabs-nav">
            <li class="mission active">1. Mission Statement</li>
            <li class="vision disabled">2. Vision Statement</li>
            <li class="value disabled">3. Values Statement</li>
            <li class="goal disabled">4. Goals</li>
        </ul>
    </div>



    <div id="form" class="col-lg-10 col-md-8 col-xs-8">
        <form action="" method="POST">   
            <div class="form-group mission active" id="actionlist">

               <label class="col-xs-12" for="missiontitle">Mission Statement</label>
               
               <div class="col-xs-12">
                    <button id="add-mission" type="button" class="btn-secondary  col-lg-3 col-md-7 col-sm-8 pull-left" data-toggle="modal"
                        data-target="#addmissionModal"><span class="icon">&#xe035;</span> Add Mission
                    </button>
                    <textarea rows="5" cols="25" wrap="hard" class="form-control" name="missionstatement" id="missiontitle"
                        readonly><?php echo $rowsmission['MISSION_STATEMENT']; ?></textarea>

                   <button id="next-tab" type="button" class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right changeTab"> Next Tab
                   </button>
                </div>
           </div>

           <div class="form-group hidden vision" id="actionlist">
               <label class="col-xs-12" for="visiontitle">Vision Statement</label>
               
               <div class="col-xs-12">
                    <button id="add-vission" type="button" class="btn-secondary  col-lg-3 col-md-7 col-sm-8 pull-left" data-toggle="modal"
                        data-target="#addvisionModal"><span class="icon">&#xe035;</span> Add vission
                    </button>
                    <textarea rows="5" cols="25" wrap="hard" class="form-control" name="visionstatement" id="visiontitle"
                        readonly><?php echo $rowsmission['VISION_STATEMENT']; ?></textarea>

                   <button id="next-tab" type="button" class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right changeTab"> Next Tab
                   </button>
                </div>
           </div>

           <div class="form-group hidden value" id="actionlist">
                <label class="col-xs-12" for="visiontitle">Value Statement</label>
               
               <div class="col-xs-12">
                    <button id="add-value" type="button" class="btn-secondary  col-lg-3 col-md-7 col-sm-8 pull-left" data-toggle="modal"
                        data-target="#addvalueModal"><span class="icon">&#xe035;</span> Add value
                    </button>
                    <textarea rows="5" cols="25" wrap="hard" class="form-control" name="valuestatement" id="valuetitle"
                        readonly><?php echo $rowsmission['VALUES_STATEMENT']; ?></textarea>

                   
                   <button id="next-tab" type="button" class="btn-primary col-lg-3 col-md-7 col-sm-8 pull-right changeTab"> Next Tab
                   </button>
                </div>
           </div>

            <div class="form-group hidden goal" id="actionlist">
                <label for="goaltitle">Previous Year Goals</label><br>
                <div class="col-xs-12">
<!--                    <select multiple="multiple" class="form-control" name="goaltitlelist[]" id="goaltitlelist" required>-->
<!--                        <option value="0"></option>-->
                        <?php while ($rowsunit = $resultunit->fetch_assoc()) { ?>

                            <div class="checkbox" id="unitgoal">
                                <label><input type="checkbox" name="goaltitlelist[]"
                                              class="checkBoxClass" value="<?php echo $rowsunit['ID_UNIT_GOAL']; ?>"><?php echo $rowsunit['UNIT_GOAL_TITLE']; ?></label>
                            </div>

<!--                            <option-->
<!--                                value="--><?php //echo $rowsunit['ID_UNIT_GOAL']; ?><!--">--><?php //echo $rowsunit['UNIT_GOAL_TITLE']; ?><!--</option>-->
                        <?php } ?>
<!--                    </select>-->
                    <div id="curgoal" class="hidden form-group">
                        <label for="curgoaltext">Added Goals</label>
                        <textarea id="curgoaltext" class="form-control" rows="5" cols="25" readonly></textarea>
                    </div>
                    <button id="add-goal" type="button" name="addgoal" class="btn-secondary col-xs-3 pull-left" data-toggle="modal"
                            data-target="#addunitGoalModal"><span class="icon">&#xe035;</span>Add Goal
                    </button>
                    <button  type="submit" name="approve" class="btn-primary col-xs-3 pull-right">
                        Approve
                    </button>
                </div>
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
                        <div class="form-group">
                            <label for="visionstate">Please Enter New Vision Statement:</label>
                            <input type="text" class="form-control" name="visionnew" id="visionstate" required>
                        </div>
                        <input type="button" id="visionbtn" value="Add Vision" class="btn-primary btn-sm">
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
                        <div class="form-group">
                            <label for="valuestate">Please Enter New Value Statement:</label>
                            <input type="text" class="form-control" name="valuenew" id="valuestate" required>
                        </div>
                        <input type="button" id="valuebtn" value="Add Value" class="btn-primary btn-sm">
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
                    <form id="goalform"  class="ajaxform" action="approvebp.php" method="POST">
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
                                        value="<?php echo $rowsug['ID_UNIV_GOAL']; ?>"><?php echo $rowsug['GOAL_TITLE']; ?></option>
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
                        <input type="submit" id="unitgoalbtn" name="goal_submit" value="Add Goal" class="btn-primary btn-sm pull-left" >
                        <input type="button" id="goalmodalclose" class="btn-primary btn-sm pull-right"  value="Close" data-dismiss="modal" aria-label="Close">
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
    <script src="../Resources/Library/js/formajax.js"></script>
    <script src="../Resources/Library/js/content.js"></script>
