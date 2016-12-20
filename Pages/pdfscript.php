<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

/*
 * This Page controls Academic BluePrint Preview.
 */

session_start();
$error = array();
$errorflag =0;

require_once ("../Resources/Includes/connect.php");

$bpayname = $_GET['ayname'];
$ouid = $_SESSION['login_ouid'];
$_SESSION['bpouabbrev'] = $_GET['ou_abbrev'];
$_SESSION['bpayname'] = $bpayname;
$ouabbrev = $_SESSION['login_ouabbrev'];


if ($ouid == 4) {
    $sqlbroad = "select BROADCAST_AY,BROADCAST_STATUS,LastModified from broadcast where BROADCAST_AY='$bpayname';";
} else{
    $sqlbroad = "select BROADCAST_AY,BROADCAST_STATUS_OTHERS,LastModified from broadcast where BROADCAST_AY='$bpayname' and BROADCAST_OU ='$ouid'; ";
}
$resultbroad = $mysqli->query($sqlbroad);
$rowbroad = $resultbroad->fetch_array(MYSQLI_NUM);

$sqlbroad = "SELECT * FROM broadcast inner join Hierarchy on BROADCAST_OU = Hierarchy.ID_HIERARCHY INNER join AcademicYears on BROADCAST_AY=AcademicYears.ACAD_YEAR_DESC where broadcast.BROADCAST_AY = '$bpayname' and Hierarchy.OU_ABBREV = '$ouabbrev'  ;";
$resultbroad=$mysqli1->query($sqlbroad);
$rowsbroad = $resultbroad->fetch_assoc();
$aydesc = $rowsbroad['BROADCAST_AY'];

$sqlmvv = "Select * from BP_MissionVisionValues where UNIT_MVV_AY ='$bpayname' and OU_ABBREV ='$ouabbrev';";
$resultmvv = $mysqli->query($sqlmvv);
$rowsmvv = $resultmvv->fetch_assoc();

$sqlunit = "select * from BP_UnitGoals inner join BP_UnitGoalOutcomes on BP_UnitGoals.ID_UNIT_GOAL = BP_UnitGoalOutcomes.ID_UNIT_GOAL where find_in_set ('$bpayname',UNIT_GOAL_AY)>0 and OU_ABBREV ='$ouabbrev';";
$resultunit = $mysqli->query($sqlunit);


//Menu control for back to dashboard button
//true: Dont show button
//false: show button
$notBackToDashboard = false;




require_once("../Resources/Includes/header.php");

// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
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


<div class="hr"></div>

<div id="main-content" class="col-lg-10 col-md-8 col-xs-8">
    <div id="title-header">
        <h1 id="title">Blueprint Management</h1>
    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <div class="col-xs-8">
            <h1 class="box-title"><?php echo $rowbroad[0]; ?></h1>
            <p class="status"><span>Org Unit Name:</span> <?php echo $_SESSION['login_ouname']; ?></p>
            <p class="status"><span>Status:</span> <?php echo $rowbroad[1]; ?></p>
        </div>

    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">BluePrint Preview</h1>


        <div id="sidebar">
            <div id="outline">
            </div>
        </div>

        <div id="page-container" class="col-lg-12" style="min-height: 810px">
            <div id="pf1" class="pf w0 h0 form-control" data-page-no="1">
                <div class="pc pc1 w0 h0">
                    <div class="t m0 x0 h1 y0 ff1 fs0 fc0 sc0 ls0 ws0">Blueprint <span class="_ _0"></span>for
                    </div>
                    <div class="t m0 x1 h1 y1 ff1 fs0 fc0 sc0 ls0 ws0">Academic</div>
                    <div class="t m0 x2 h1 y2 ff1 fs0 fc0 sc0 ls0 ws0">Excellence</div>
                    <div
                        class="t m0 x3 h2 y3 ff1 fs0 fc1 sc0 ls0 ws0"><?php echo "<p style='color:rgb(140,38,51);font-size:110px;'>" . $rowsmenu['OU_NAME'] . "</p>"; ?></div>
                    <div class="t m0 x4 h2 y4 ff2 fs0 fc0 sc0 ls0 ws0"><?php echo "<p style='color:rgb(140,38,51);font-size:110px; margin-left: 300px;'>" . date('M-Y',strtotime($rowsbroad['ACAD_YEAR_DATE_END'])). "</p>"; ?></div>
                </div>
                <div class="pi"
                     data-data='{"ctm":[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}'></div>
            </div>
            <div id="pf2" class="pf w0 h0" data-page-no="2">
                <div class="pc pc2 w0 h0"><img class="bi x5 y5 w1 h3" alt=""
                                               src="../Pages/blueprint/Blueprinthtml/bg2.png"/>
                    <div class="t m0 x3 h4 y6 ff3 fs1 fc1 sc0 ls1 ws0">Executive Summary</div>
                    <div class="t m0 x3 h5 y7 ff2 fs2 fc1 sc0 ls0 ws0"><span class="_ _0"></span>
                        <p id="exesumtitle"></p>
                    </div>
                </div>
                <div class="pi"
                     data-data='{"ctm":[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}'></div>
            </div>
            <div id="pf3" class="pf w0 h0" data-page-no="3">
                <div class="pc pc3 w0 h0"><img class="bi x5 y5 w1 h3" alt="" src="../Pages/blueprint/Blueprinthtml/bg3.png"/>
                    <div class="t m0 x3 h4 y6 ff3 fs1 fc1 sc0 ls1 ws0">Blueprint for Academic Excellence
                    </div>
                    <div
                        class="t m0 x3 h5 y7 ff2 fs2 fc1 sc0 ls0 ws0"><?php echo "<p>" . $rowsmenu['OU_NAME'] . "</p>"; ?></div>
                    <div
                        class="t m0 x3 h5 y2d ff2 fs2 fc1 sc0 ls0 ws0"><?php echo "<p>" . $rowsmenu['FNAME'] . "," . $rowsmenu['LNAME'] . "," . $rowsmenu['USER_RIGHT'] . "</p>"; ?></div>
                    <div class="t m0 x3 h5 y2e ff2 fs2 fc1 sc0 ls0 ws0">Executive Summary<span class="_ _2"> </span>2</div>
                    <div class="t m0 x3 h5 y2f ff2 fs2 fc1 sc0 ls0 ws0">Blueprint for Academic Excellence<span
                            class="_ _3"> </span>3
                    </div>
                    <div class="t m0 x8 h5 y30 ff2 fs2 fc1 sc0 ls0 ws0">Mission<span class="_ _4"> </span>4</div>
                    <div class="t m0 x8 h5 y31 ff2 fs2 fc1 sc0 ls0 ws0">Vision<span class="_ _5"> </span>4</div>
                    <div class="t m0 x8 h5 y32 ff2 fs2 fc1 sc0 ls0 ws0">Values<span class="_ _6"> </span>4</div>
                    <div class="t m0 x8 h5 y33 ff2 fs2 fc1 sc0 ls0 ws0">Goals<span class="_ _7"> </span>4</div>
                    <div class="t m0 x3 h5 y34 ff2 fs2 fc1 sc0 ls0 ws0">Outcomes – 2015-2016 Academic Year<span
                            class="_ _8"> </span>6
                    </div>
                    <div class="t m0 x8 h5 y35 ff2 fs2 fc1 sc0 ls0 ws0">Faculty Development &amp; Activities<span
                            class="_ _9"> </span>6
                    </div>
                    <div class="t m0 x8 h5 y36 ff2 fs2 fc1 sc0 ls0 ws0">Faculty Awards<span class="_ _a"> </span>6</div>
                    <div class="t m0 x8 h5 y37 ff2 fs2 fc1 sc0 ls0 ws0">Personnel – Faculty<span class="_ _b"> </span>7</div>
                    <a class="l" href="#pf2" data-dest-detail='[2,"XYZ",72,720,null]'>
                        <div class="d m1"
                             style="border-style:none;position:absolute;left:533.924988px;bottom:595.314026px;width:5.575012px;height:13.427979px;background-color:rgba(255,255,255,0.000001);"></div>
                    </a><a class="l" href="#pf3" data-dest-detail='[3,"XYZ",72,720,null]'>
                        <div class="d m1"
                             style="border-style:none;position:absolute;left:533.924988px;bottom:576.885986px;width:5.575012px;height:13.427979px;background-color:rgba(255,255,255,0.000001);"></div>
                    </a><a class="l" href="#pf4" data-dest-detail='[4,"XYZ",72,698,null]'>
                        <div class="d m1"
                             style="border-style:none;position:absolute;left:533.924988px;bottom:558.458008px;width:5.575012px;height:13.427979px;background-color:rgba(255,255,255,0.000001);"></div>
                    </a><a class="l" href="#pf4" data-dest-detail='[4,"XYZ",72,620,null]'>
                        <div class="d m1"
                             style="border-style:none;position:absolute;left:533.924988px;bottom:540.030029px;width:5.575012px;height:13.427979px;background-color:rgba(255,255,255,0.000001);"></div>
                    </a><a class="l" href="#pf4" data-dest-detail='[4,"XYZ",72,541,null]'>
                        <div class="d m1"
                             style="border-style:none;position:absolute;left:533.924988px;bottom:521.602051px;width:5.575012px;height:13.427979px;background-color:rgba(255,255,255,0.000001);"></div>
                    </a><a class="l" href="#pf4" data-dest-detail='[4,"XYZ",72,463,null]'>
                        <div class="d m1"
                             style="border-style:none;position:absolute;left:533.924988px;bottom:503.173981px;width:5.575012px;height:13.428009px;background-color:rgba(255,255,255,0.000001);"></div>
                    </a><a class="l" href="#pf6" data-dest-detail='[6,"XYZ",72,720,null]'>
                        <div class="d m1"
                             style="border-style:none;position:absolute;left:533.924988px;bottom:484.746002px;width:5.575012px;height:13.428009px;background-color:rgba(255,255,255,0.000001);"></div>
                    </a><a class="l" href="#pf6" data-dest-detail='[6,"XYZ",72,673,null]'>
                        <div class="d m1"
                             style="border-style:none;position:absolute;left:533.924988px;bottom:466.317993px;width:5.575012px;height:13.428009px;background-color:rgba(255,255,255,0.000001);"></div>
                    </a><a class="l" href="#pf6" data-dest-detail='[6,"XYZ",72,417,null]'>
                        <div class="d m1"
                             style="border-style:none;position:absolute;left:533.924988px;bottom:447.889984px;width:5.575012px;height:13.428009px;background-color:rgba(255,255,255,0.000001);"></div>
                    </a><a class="l" href="#pf7" data-dest-detail='[7,"XYZ",72,720,null]'>
                        <div class="d m1"
                             style="border-style:none;position:absolute;left:533.924988px;bottom:429.462006px;width:5.575012px;height:13.428009px;background-color:rgba(255,255,255,0.000001);"></div>
                    </a></div>
                <div class="pi" data-data='{"ctm":[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}'></div>
            </div>
            <div id="pf4" class="pf w0 h0" data-page-no="4">


                <hr />
                <h1><p class="mvv texthead"">Mission</p></h1>
                <hr />
                <p class="mvv "><?php echo $rowsmvv['MISSION_STATEMENT']; ?></p>
                <hr />
                <h1 class="mvv texthead">Vision</h1>
                <hr/>
                <p class="mvv"><?php echo $rowsmvv['VISION_STATEMENT']; ?></p>
                <hr />
                <h1 class="mvv texthead">Value</h1>
                <hr/>
                <p class="mvv"><?php echo $rowsmvv['VALUES_STATEMENT']; ?></p>

                <hr/>
                <h1 class="mvv texthead">Goals</h1>
                <hr />
                <?php while ($rowsunit = $resultunit->fetch_assoc()) {
                    echo '<p class="mvv texthead">Goal - </p><hr/>';
                    echo '<p class="mvv"> ' . $rowsunit['UNIT_GOAL_TITLE'] . '</p>';

                    echo '<p class="mvv texthead">Goal Statement </p><hr/>';
                    echo '<p class="mvv">' . $rowsunit['GOAL_STATEMENT'] . '</p>';

                    echo '<p class="mvv texthead">Alignment with Mission, Vision and Values,</p><hr/>';
                    echo '<p class="mvv">' . $rowsunit['GOAL_ALIGNMENT'] . '</p>';

                    echo '<p class="mvv texthead">Status</p><hr/>';
                    echo '<p class="mvv">' . $rowsunit['GOAL_STATUS'] . '</p>';


                    echo '<p class="mvv texthead">Achievement:</p>';
                    echo '<p class="mvv">' . $rowsunit['GOAL_ACHIEVEMENTS'] . '</p>';


                    echo '<p class="mvv">Resources Utilized</p>';
                    echo '<p class="mvv">' . $rowsunit['GOAL_RSRCS_UTLZD'] . '</p>';

                    echo '<p class="mvv">Resources Utilized</p>';
                    echo '<p class="mvv">' . $rowsunit['GOAL_RSRCS_UTLZD'] . '</p>';

                    echo '<p class="mvv texthead">Goal Continuation:</p>';
                    echo '<p class="mvv">' . $rowsunit['GOAL_CONTINUATION'] . '</p>';


                    echo '<p class="mvv">Resources Needed</p>';
                    echo '<p class="mvv">' . $rowsunit['GOAL_RSRCS_NEEDED'] . '</p>';

                    echo '<p class="mvv">Notes</p>';
                    echo '<p class="mvv">' . $rowsunit['GOAL_NOTES'] . '</p>';
                }
                    ?>


<!--                <div class="pc pc4 w0 h0"><img class="bi x5 y38 w1 h7" alt="" src="../Pages/blueprint/Blueprinthtml/bg4.png"/>-->
<!--                    <div class="t m0 x3 h8 y39 ff5 fs3 fc1 sc1 ls1 ws0">Mission</div>-->
<!--                    <div class="t m0 x3 h5 y3a ff2 fs2 fc1 sc0 ls0 ws0"><p id='missiontext' style="word-break:normal;-->
<!--    white-space: normal;width: 340%;">--><?php //echo $rowsmvv['MISSION_STATEMENT']; ?><!--</p></div>-->
<!--                    <div class="t m0 x3 h8 y3b ff5 fs3 fc1 sc1 ls1 ws0">Vision</div>-->
<!--                    <div class="t m0 x3 h5 y3c ff2 fs2 fc1 sc0 ls0 ws0"><p id='visiontext' style="word-break: normal;-->
<!--    white-space: normal;width: 340%;">--><?php //echo $rowsmvv['VISION_STATEMENT']; ?><!--</p></div>-->
<!--                    <div class="t m0 x3 h8 y3d ff5 fs3 fc1 sc1 ls1 ws0">Values</div>-->
<!--                    <div class="t m0 x3 h5 y3e ff2 fs2 fc1 sc0 ls0 ws0"><p id='valuetext' style="word-break: normal;-->
<!--    white-space: normal;width: 340%;">--><?php //echo $rowsmvv['VALUES_STATEMENT']; ?><!--</p></div>-->
<!--                    <div class="t m0 x3 h8 y3f ff5 fs3 fc1 sc1 ls1 ws0">Goals</div>-->
<!---->
<!--                    --><?php //while ($rowsunit = $resultunit->fetch_assoc()) {
//                    $x = 1; ?>
<!---->
<!--                    <div class="t m0 x3 h6 y40 ff1 fs2 fc1 sc0 ls0 ws0"><p>-->
<!--                            Goal --><?php //echo $x . '-<' . $rowsunit['UNIT_GOAL_TITLE'] . '>'; ?><!-- </p></div>-->
<!--                    <div class="c x9 y41 w2 h9">-->
<!--                        <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0"></div>-->
<!--                        <div class="t m0 xa h6 y43 ff1 fs2 fc1 sc0 ls0 ws0">GOAL_ACHIEVEMENTS</div>-->
<!--                    </div>-->
<!--                    <div class="c xb y41 w3 h9">-->
<!--                        <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0"><p id="goalout" style='margin-top: 50px;-->
<!--    margin-left: 50px;'></p>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="c x9 y44 w2 ha">-->
<!--                        <div class="t m0 xa h6 y45 ff1 fs2 fc1 sc0 ls0 ws0">Goal</div>-->
<!--                    </div>-->
<!--                    <div class="c xb y44 w3 ha">-->
<!--                        <div-->
<!--                            class="t m0 xa h5 y45 ff2 fs2 fc1 sc0 ls0 ws0">--><?php //echo $rowsunit['GOAL_STATEMENT'] ?><!--</div>-->
<!--                    </div>-->
<!--                    <div class="c x9 y46 w2 hb">-->
<!--                        <div class="t m0 xa h6 y47 ff1 fs2 fc1 sc0 ls0 ws0">Alignment with</div>-->
<!--                        <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Mission, Vision,</div>-->
<!--                        <div class="t m0 xa h6 y43 ff1 fs2 fc1 sc0 ls0 ws0">and Values</div>-->
<!--                    </div>-->
<!--                    <div class="c xb y46 w3 hb">-->
<!--                        <div class="t m0 xa h5 y47 ff2 fs2 fc1 sc0 ls0 ws0">-->
<!--                            &lt;--><?php //echo $rowsunit['GOAL_ALIGNMENT'] ?><!--&gt;</div>-->
<!--                    </div>-->
<!--                    <div class="c x9 y48 w2 h9">-->
<!--                        <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Status</div>-->
<!--                    </div>-->
<!--                    <div class="c xb y48 w3 h9">-->
<!--                        <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;--><?php //echo $rowsunit['GOAL_STATUS'] ?>
<!--                            &gt;</div>-->
<!--                    </div>-->
<!--                    <div class="c x9 y49 w2 h9">-->
<!--                        <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Achievements</div>-->
<!--                    </div>-->
<!--                    <div class="c xb y49 w3 h9">-->
<!--                        <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">-->
<!--                            &lt;--><?php //echo $rowsunit['GOAL_ACHIEVEMENTS'] ?><!--&gt;</div>-->
<!--                    </div>-->
<!--                    <div class="c x9 y4a w2 h9">-->
<!--                        <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Resources Utilized</div>-->
<!--                    </div>-->
<!--                    <div class="c xb y4a w3 h9">-->
<!--                        <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">-->
<!--                            &lt;--><?php //echo $rowsunit['GOAL_RSRCS_UTLZD'] ?><!--&gt;</div>-->
<!--                    </div>-->
<!--                    <div class="c x9 y4b w2 h9">-->
<!--                        <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Continuation</div>-->
<!--                    </div>-->
<!--                    <div class="c xb y4b w3 h9">-->
<!--                        <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">-->
<!--                            &lt;--><?php //echo $rowsunit['GOAL_CONTINUATION'] ?><!--&gt;</div>-->
<!--                    </div>-->
<!--                    <div class="c x9 y4c w2 h9">-->
<!--                        <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Resources Needed</div>-->
<!--                    </div>-->
<!--                    <div class="c xb y4c w3 h9">-->
<!--                        <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">-->
<!--                            &lt;--><?php //echo $rowsunit['GOAL_RSRCS_NEEDED'] ?><!--&gt;</div>-->
<!--                    </div>-->
<!--                    <div class="c x9 y4d w2 h9">-->
<!--                        <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Notes</div>-->
<!--                    </div>-->
<!--                    <div class="c xb y4d w3 h9">-->
<!--                        <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;--><?php //echo $rowsunit['GOAL_NOTES'] ?>
<!--                            &gt;</div>-->
<!--                    </div>-->
<!--                    --><?php //$x++; } ?>
                </div>

                <div class="pi" data-data='{"ctm":[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}'></div>
            </div>
        </div>
    </div>

</div>


<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>


<script src="../Resources/Library/js/tabAlert.js"></script>
<script type="text/javascript" src="../Resources/Library/js/moment.js"></script>
<script type="text/javascript" src="../Resources/Library/js/bootstrap-datetimepicker.min.js"></script>
<script src="../Resources/Library/js/calender.js"></script>
<script src="../Resources/Library/js/chkbox.js"></script>
<script src="../Resources/Library/js/taskboard.js"></script>






