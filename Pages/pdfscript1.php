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

<link rel="stylesheet" href="../Pages/blueprint/Blueprinthtml/blueprint.css"/>
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
            <div id="pf1" class="pf w0 h0 form-control" data-page-no="1" style="padding-left: 30px">
                <div class=WordSection1>

                    <p class=MsoNormal align=center style='margin-top:80px;text-align:center'><b><span
                                style='font-size:38.0pt;color:#8C2633'>Blueprint for </span></b></p>

                    <p class=MsoNormal align=center style='text-align:center'><b><span
                                style='font-size:38.0pt;color:#8C2633'>Academic Excellence</span></b></p>

                    <p class=MsoNormal align=center style='margin-top:48.0pt;text-align:center'><b><span
                                style='font-size:38.0pt;font-family:Open Sans, sans-serif;'>&lt;OU_NAME&gt;</span></b></p>

                    <p class=MsoNormal align=center style='margin-top:48.0pt;text-align:center'><span
                            style='font-size:35.0pt;color:#8C2633'>&lt;ACADEMIC_YEAR_DESC&gt;</span></p>

                    <p class=MsoNormal align=center style='margin-top: 100px;text-align:center'><img width=476
                                                                                   height=130 src="../Resources/Images/uscbplogo.png" align=middle hspace=9><br
                            clear=all style=' page-break-before:always'>
                    </p>

                </div>
            </div>
            <div id="pf2" class="pf w0 h0" data-page-no="2" style="padding-left: 30px;padding-right: 30px">
                <div style='border:none;border-bottom:solid #8C2633 1.0pt;padding:0in 0in 1.0pt 0in'>

                    <p class=BlueprintTitle><a name="_Toc469409042">Blueprint for Academic
                            Excellence</a></p>

                </div>

                <p class=MsoNormal>&lt;OU_NAME&gt;</p>

                <p class=MsoNormal>Hossein Haj-Hariri, Dean</p>

                <p class=MsoNormal>&nbsp;</p>

                <p class=MsoNormal><b><u><span style='font-variant:small-caps;letter-spacing:
2.4pt'>Table of Contents</span></u></b></p>

                <p class=MsoToc1>Blueprint for Academic Excellence................................................................................ 2</p>

                <p class=MsoToc2>Mission.......................................................................................................................................... 3</p>

                <p class=MsoToc2>Vision............................................................................................................................................ 3</p>

                <p class=MsoToc2>Values............................................................................................................................................ 3</p>

                <p class=MsoToc2>Goals............................................................................................................................................. 3</p>

                <p class=MsoToc1>&nbsp;</p>

                <p class=MsoNormal><img width=422 height=180
                                        src="" align=left hspace=9><br clear=all
                                                                                                            style='page-break-before:always'>
                </p>



            </div>
            <div id="pf3" class="pf w0 h0" data-page-no="3">

            </div>
            <div id="pf4" class="pf w0 h0" data-page-no="4">

<!--


                <div class="pc pc4 w0 h0"><img class="bi x5 y38 w1 h7" alt="" src="../Pages/blueprint/Blueprinthtml/bg4.png"/>
                    <div class="t m0 x3 h8 y39 ff5 fs3 fc1 sc1 ls1 ws0">Mission</div>
                    <div class="t m0 x3 h5 y3a ff2 fs2 fc1 sc0 ls0 ws0"><p id='missiontext' style="word-break:normal;
    white-space: normal;width: 340%;"><?php echo $rowsmvv['MISSION_STATEMENT']; ?></p></div>
                    <div class="t m0 x3 h8 y3b ff5 fs3 fc1 sc1 ls1 ws0">Vision</div>
                    <div class="t m0 x3 h5 y3c ff2 fs2 fc1 sc0 ls0 ws0"><p id='visiontext' style="word-break: normal;
    white-space: normal;width: 340%;"><?php echo $rowsmvv['VISION_STATEMENT']; ?></p></div>
                    <div class="t m0 x3 h8 y3d ff5 fs3 fc1 sc1 ls1 ws0">Values</div>
                    <div class="t m0 x3 h5 y3e ff2 fs2 fc1 sc0 ls0 ws0"><p id='valuetext' style="word-break: normal;
    white-space: normal;width: 340%;"><?php echo $rowsmvv['VALUES_STATEMENT']; ?></p></div>
                    <div class="t m0 x3 h8 y3f ff5 fs3 fc1 sc1 ls1 ws0">Goals</div>

                    <?php while ($rowsunit = $resultunit->fetch_assoc()) {
                    $x = 1; ?>

                    <div class="t m0 x3 h6 y40 ff1 fs2 fc1 sc0 ls0 ws0"><p>
                            Goal <?php echo $x . '-<' . $rowsunit['UNIT_GOAL_TITLE'] . '>'; ?> </p></div>
                    <div class="c x9 y41 w2 h9">
                        <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0"></div>
                        <div class="t m0 xa h6 y43 ff1 fs2 fc1 sc0 ls0 ws0">GOAL_ACHIEVEMENTS</div>
                    </div>
                    <div class="c xb y41 w3 h9">
                        <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0"><p id="goalout" style='margin-top: 50px;
    margin-left: 50px;'></p>
                        </div>
                    </div>
                    <div class="c x9 y44 w2 ha">
                        <div class="t m0 xa h6 y45 ff1 fs2 fc1 sc0 ls0 ws0">Goal</div>
                    </div>
                    <div class="c xb y44 w3 ha">
                        <div
                            class="t m0 xa h5 y45 ff2 fs2 fc1 sc0 ls0 ws0"><?php echo $rowsunit['GOAL_STATEMENT'] ?></div>
                    </div>
                    <div class="c x9 y46 w2 hb">
                        <div class="t m0 xa h6 y47 ff1 fs2 fc1 sc0 ls0 ws0">Alignment with</div>
                        <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Mission, Vision,</div>
                        <div class="t m0 xa h6 y43 ff1 fs2 fc1 sc0 ls0 ws0">and Values</div>
                    </div>
                    <div class="c xb y46 w3 hb">
                        <div class="t m0 xa h5 y47 ff2 fs2 fc1 sc0 ls0 ws0">
                            &lt;<?php echo $rowsunit['GOAL_ALIGNMENT'] ?>&gt;</div>
                    </div>
                    <div class="c x9 y48 w2 h9">
                        <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Status</div>
                    </div>
                    <div class="c xb y48 w3 h9">
                        <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;<?php echo $rowsunit['GOAL_STATUS'] ?>
                            &gt;</div>
                    </div>
                    <div class="c x9 y49 w2 h9">
                        <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Achievements</div>
                    </div>
                    <div class="c xb y49 w3 h9">
                        <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">
                            &lt;<?php echo $rowsunit['GOAL_ACHIEVEMENTS'] ?>&gt;</div>
                    </div>
                    <div class="c x9 y4a w2 h9">
                        <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Resources Utilized</div>
                    </div>
                    <div class="c xb y4a w3 h9">
                        <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">
                            &lt;<?php echo $rowsunit['GOAL_RSRCS_UTLZD'] ?>&gt;</div>
                    </div>
                    <div class="c x9 y4b w2 h9">
                        <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Continuation</div>
                    </div>
                    <div class="c xb y4b w3 h9">
                        <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">
                            &lt;<?php echo $rowsunit['GOAL_CONTINUATION'] ?>&gt;</div>
                    </div>
                    <div class="c x9 y4c w2 h9">
                        <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Resources Needed</div>
                    </div>
                    <div class="c xb y4c w3 h9">
                        <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">
                            &lt;<?php echo $rowsunit['GOAL_RSRCS_NEEDED'] ?>&gt;</div>
                    </div>
                    <div class="c x9 y4d w2 h9">
                        <div class="t m0 xa h6 y42 ff1 fs2 fc1 sc0 ls0 ws0">Notes</div>
                    </div>
                    <div class="c xb y4d w3 h9">
                        <div class="t m0 xa h5 y42 ff2 fs2 fc1 sc0 ls0 ws0">&lt;<?php echo $rowsunit['GOAL_NOTES'] ?>
                            &gt;</div>
                    </div>
                    <?php $x++; } ?>
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






