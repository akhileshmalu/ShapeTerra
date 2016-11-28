<?php
session_start();
require_once ("../Resources/Includes/connect.php");


$count=0;
$outcome = "";
$aydesc ="";

$error = array();

$ouabbrev = $_SESSION['login_ouabbrev'];
$author = $_SESSION['login_email'];
$time = date('Y-m-d H:i:s');
$ou = $_SESSION['login_ouid'];


$sqlbroad = "SELECT * FROM broadcast inner join Hierarchy on BROADCAST_OU = Hierarchy.ID_HIERARCHY INNER join AcademicYears on BROADCAST_AY=AcademicYears.ACAD_YEAR_DESC where Hierarchy.OU_ABBREV = '$ouabbrev';";
$resultbroad=$mysqli1->query($sqlbroad);
$rowsbroad = $resultbroad->fetch_assoc();
$aydesc = $rowsbroad['BROADCAST_AY'];

$sqlmvv = "Select * from BP_MissionVisionValues where UNIT_MVV_AY ='$aydesc' and OU_ABBREV ='$ouabbrev';";
$resultmvv = $mysqli->query($sqlmvv);
$rowsmvv = $resultmvv->fetch_assoc();

$sqlunit = "select * from BP_UnitGoals where find_in_set ('$aydesc',UNIT_GOAL_AY)>0 and OU_ABBREV ='$ouabbrev';";
$resultunit = $mysqli->query($sqlunit);

//$rowsunit = $resultunit->fetch_assoc();
$count = $resultunit->num_rows;
$goalname =array();
$goaloutcome = array();
$goalidlist = array();

if(isset($_POST['save'])) {

    $execsummary = mynl2br($_POST['execsummary']);
    $goaloutcome = $_POST['goaloutcome'];
    $goalidlist = $_POST['goalno'];


    $sqlgoalout = "UPDATE broadcast SET BROADCAST_EXECSUM ='$execsummary', BROADCAST_STATUS = 'Completed by User', BROADCAST_STATUS_OTHERS = 'Completed by User' where BROADCAST_AY='$aydesc' and BROADCAST_OU ='$ou';";

//    foreach ($goaloutcome as $item) {

    for( $i=0; $i<$count;$i++) {

        $outcome = mynl2br($goaloutcome[$i]);
        $idoutcome = $goalidlist[$i];

        $sqlgoalout .= "INSERT INTO BP_UnitGoalOutcomes(ID_UNIT_GOAL,OUTCOMES_AUTHOR,MOD_TIMESTAMP,GOAL_ACHIEVEMENTS) VALUES ('$idoutcome','$author','$time','$outcome')";
    }
    $mysqli->multi_query($sqlgoalout);
//    if($mysqli->multi_query($sqlgoalout)) {
//        $error[0] = "Academic BluePrint created Successfully";
//    } else {
//        $error[0] = "Academic BluePrint could not be craeted";
//    }

}


//Include Header
require_once("../Resources/Includes/header.php");
?>

<link href="Css/templateTabs.css" rel="stylesheet" type="text/css"/>
<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" href="../Pages/blueprint/Blueprinthtml/base.min.css"/>
<link rel="stylesheet" href="../Pages/blueprint/Blueprinthtml/fancy.min.css"/>
<link rel="stylesheet" href="../Pages/blueprint/Blueprinthtml/main.css"/>
<script src="../Pages/blueprint/Blueprinthtml/compatibility.min.js"></script>
<script src="../Pages/blueprint/Blueprinthtml/theViewer.min.js"></script>
<script>
    try {
        theViewer.defaultViewer = new theViewer.Viewer({});
    } catch (e) {
    }
</script>


</head>
<body>

<?php
// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>
<div class="hr"></div>
<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">BluePrint Completion</h1>
    </div>
    <div id="list" class="col-xs-2">
        <ul class="tabs-nav">
            <li class="executive active">1. Executive Summary</li>
            <li class="mvv disabled">2. MVV</li>
            <li class="goals disabled">3. Goals</li>
            <li class="preview disabled">4. Preview</li>
        </ul>
    </div>

    <div id="form" class="col-xs-9">
        <form action="createbp.php" class="ajaxform" method="POST">

            <div  class="executive active" id="actionlist">
                <div class="form-group" id="actionlist1">
                    <label for="execsummary">Please fill Executive Summary for <?php echo $rowsbroad['BROADCAST_AY']; ?>
                        :</label>
                    <textarea id="execsummary" name="execsummary" rows="5" cols="25" wrap="hard" class="form-control"></textarea>
                    <button type="button" name="next"
                            class="btn-primary col-lg-4 col-xs-4 pull-right changeTab">Next
                    </button>
                </div>
            </div>

            <div  class="mvv hidden" id="actionlist">
                <div class="form-group" id="actionlist2">
                    <label for="missionstatement">Please Verify Mission
                        Statement for <?php echo $rowsbroad['BROADCAST_AY'];?> :</label>
                    <textarea id="missionstatement" name="missionstatement" rows="5" cols="25"
                              wrap="hard" class="form-control"
                              readonly><?php echo $rowsmvv['MISSION_STATEMENT']; ?></textarea>
                    <label for="visionstatement">Please Verify Vision Statement
                        for <?php echo $rowsbroad['BROADCAST_AY']; ?> :</label>
                    <textarea id="visionstatement" name="visionstatement" rows="5" cols="25"
                              wrap="hard" class="form-control"
                              readonly><?php echo $rowsmvv['VISION_STATEMENT']; ?></textarea>
                    <label for="valuestatement">Please Verify Value Statement
                        for <?php echo $rowsbroad['BROADCAST_AY']; ?> :</label>
                    <textarea id="valuestatement" name="valuestatement" rows="5" cols="25" wrap="hard" class="form-control"
                              readonly><?php echo $rowsmvv['VALUES_STATEMENT']; ?>
                    </textarea>
                    <button type="button"
                            class="btn-primary col-lg-4 col-xs-4 pull-right changeTab">Next
                    </button>
                </div>
            </div>

            <div  class="goals hidden" id="actionlist">
                <div class="form-group " id="actionlist3">
                    <?php while ($rowsunit = $resultunit->fetch_array(MYSQLI_ASSOC)){ ?>
                        <label for="goaloutcome">Enter Goal Achievements for
                            : <?php  echo $rowsunit['UNIT_GOAL_TITLE']; ?> </label>
                        <textarea id="goaloutcome" name="goaloutcome[]" wrap="hard" rows="5" cols="25" class="form-control"
                                  required></textarea>
                        <input  type="hidden" name="goalno[]" value="<?php echo $rowsunit['ID_UNIT_GOAL'];?>">
                    <?php } ?>
                    <button type="button" id="preview"
                            class="btn-primary col-lg-4 col-xs-4 pull-right changeTab">Preview
                    </button>
                </div>
            </div>

            <div class="preview hidden" id="actionlist">
                <div class="form-group col-xs-12" style="min-height: 600px;" id="actionlist4">
                    <p><br></p>
                    <?php require_once("../Pages/pdfscript.php");?>
                </div>

                <button type="submit" name="save"
                        class="btn-secondary col-xs-3 pull-left changeTab">Save
                </button>
                <button type="button" name="print" onclick="window.open('../Pages/generatepdf.php','_blank');"
                        class="btn-primary col-xs-3 pull-right">Print
                </button>
            </div>

        </form>
    </div>
</div>
<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>

<script src="../Resources/Library/js/tabchange.js"></script>
<script src="../Resources/Library/js/content.js"></script>
<script src="../Resources/Library/js/formajax.js"></script>
<script src="../Resources/Library/js/pdfdirect.js"></script>