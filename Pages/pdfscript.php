<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

/*
 * This Page controls Academic BluePrint Preview.
 */

session_start();
$error = array();
$errorflag =0;
$pageno =1;
$goalcount=1;
$unigoal = array();
$BackToDashboard = true;

require_once ("../Resources/Includes/connect.php");

$bpayname = $_GET['ayname'];
$ouid = $_SESSION['login_ouid'];


if ($ouid == 4) {
    $ouabbrev = $_SESSION['bpouabbrev'];
} else {
    $ouabbrev = $_SESSION['login_ouabbrev'];
}

$prevbpayname = idtostring(stringtoid($bpayname)-101);


if ($ouid == 4) {
    $sqlbroad = "select BROADCAST_AY,OU_NAME,BROADCAST_STATUS,LastModified from broadcast inner join Hierarchy on broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY where BROADCAST_AY='$bpayname' and Hierarchy.OU_ABBREV ='$ouabbrev';";
} else{
    $sqlbroad = "select BROADCAST_AY,OU_NAME, BROADCAST_STATUS_OTHERS,LastModified from broadcast inner join Hierarchy on broadcast.BROADCAST_OU = Hierarchy.ID_HIERARCHY where BROADCAST_AY='$bpayname' and BROADCAST_OU ='$ouid'; ";
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

$sqlunit = "select * from BP_UnitGoals RIGHT join BP_UnitGoalOutcomes on BP_UnitGoals.ID_UNIT_GOAL = BP_UnitGoalOutcomes.ID_UNIT_GOAL 
INNER JOIN GoalStatus on BP_UnitGoalOutcomes.GOAL_STATUS=GoalStatus.ID_STATUS where find_in_set ('$bpayname',UNIT_GOAL_AY)>0 and OU_ABBREV ='$ouabbrev';";
$resultunit = $mysqli->query($sqlunit);
$countunit = $resultunit->num_rows;

$sqlfacultyinfo = "select * from AC_FacultyInfo where  OUTCOMES_AY='$bpayname' and OU_ABBREV ='$ouabbrev';";
$resultfacInfo = $mysqli->query($sqlfacultyinfo);
$rowsfacinfo = $resultfacInfo->fetch_assoc();

$sqlfacultyaward = "select * from AC_FacultyAwards where  OUTCOMES_AY='$bpayname' and OU_ABBREV ='$ouabbrev';";
$resultfacaward = $mysqli->query($sqlfacultyaward);


$sqldean = "select * from PermittedUsers inner join Hierarchy on PermittedUsers.USER_OU_MEMBERSHIP = Hierarchy.ID_HIERARCHY where  OU_ABBREV= '$ouabbrev' and SYS_USER_ROLE ='dean'; ";
$resultdean = $mysqli->query($sqldean);
$rowsdean = $resultdean -> fetch_assoc();


//Menu control for back to dashboard button
//true: Dont show button
//false: show button





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
<link rel="stylesheet" href="Css/blueprintPreview.css"/>

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
            <p class="status"><span>Org Unit Name:</span> <?php echo $rowbroad[1]; ?></p>
            <p class="status"><span>Status:</span> <?php echo $rowbroad[2]; ?></p>
        </div>

    </div>

    <div id="main-box" class="col-xs-10 col-xs-offset-1">
        <h1 class="box-title">BluePrint Preview</h1>


        <div id="sidebar">
            <div id="outline">
            </div>
        </div>

        <div id="page-container" class="col-lg-12" style="min-height: 810px">

            <div id="pf<?php echo $pageno; ?>" class="pf w0 h0 form-control" data-page-no="<?php echo $pageno; ?>" style="padding-left: 30px;">
                <div class=WordSection1>

                    <p class=MsoNormal align=center style='margin-top:80px;text-align:center'><b><span
                                style='font-size:38.0pt;color:#8C2633'>Blueprint for </span></b></p>

                    <p class=MsoNormal align=center style='text-align:center'><b><span
                                style='font-size:38.0pt;color:#8C2633'>Academic Excellence</span></b></p>

                    <p class=MsoNormal align=center style='margin-top:48.0pt;text-align:center'><b><span
                                style='font-size:30.0pt;font-family:Open Sans, sans-serif;'><?php echo $rowbroad[1]; ?></span></b>
                    </p>

                    <p class=MsoNormal align=center style='margin-top:48.0pt;text-align:center'><span
                            style='font-size:35.0pt;color:#8C2633'><?php echo date('M-Y',strtotime($rowsbroad['ACAD_YEAR_DATE_END'])); ?></span></p>

                    <p class=MsoNormal align=center style='margin-top: 100px;text-align:center'><img width=476
                                                                                                     height=130
                                                                                                     src="../Resources/Images/uscbplogo.png"
                                                                                                     align=middle
                                                                                                     hspace=9><br
                            clear=all style=' page-break-before:always'>
                    </p>

                </div>
            </div>
            <?php $pageno++; ?>

            <div id="pf<?php echo $pageno; ?>" class="pf w0 h0" data-page-no="<?php echo $pageno; ?>">
                <h2>Executive Summary</h2>
                <p>&lt;Executive summary&gt;</p>
            </div>
            <?php $pageno++; ?>

            <div id="pf<?php echo $pageno; ?>" class="pf w0 h0" data-page-no="<?php echo $pageno; ?>">
                <h2>Blueprint for Academic Excellence</h2>
                <p><?php echo $rowbroad[1]; ?></p>
                <p><?php echo $rowsdean['FNAME']." ".$rowsdean['LNAME'].", DEAN"; ?></p>

                <br />
                <p style="text-align:left;">Executive Summary <span style="float:right;">2</span></p>
                <p style="text-align:left;">Blueprint for Academic Excellence <span style="float:right;">3</span></p>
                <p class="indent" style="text-align:left;">Mission <span style="float:right;">4</span></p>
                <p class="indent" style="text-align:left;">Vision <span style="float:right;">4</span></p>
                <p class="indent" style="text-align:left;">Values <span style="float:right;">4</span></p>
                <p class="indent" style="text-align:left;">Goals <span style="float:right;">4</span></p>
                <p style="text-align:left;">Outcomes – <?php echo substr($prevbpayname,2); ?> Academic Year <span style="float:right;">6</span></p>
                <p class="indent" style="text-align:left;">Faculty Development &amp; Activities <span style="float:right;">6</span></p>
                <p class="indent" style="text-align:left;">Faculty Awards <span style="float:right;">6</span></p>
                <p class="indent" style="text-align:left;">Personnel – Faculty <span style="float:right;">7</span></p>
            </div>
            <?php $pageno++; ?>

            <div id="pf<?php echo $pageno; ?>" class="pf w0 h0" data-page-no="<?php echo $pageno; ?>">
                <h2 style="margin-bottom: 20px;border-bottom: transparent;">Blueprint for Academic Excellence</h2>
                <h3>Mission</h3>
                <p><?php echo $rowsmvv['MISSION_STATEMENT']; ?></p>

                <h3>Vision</h3>
                <p><?php echo $rowsmvv['VISION_STATEMENT']; ?></p>

                <h3>Values</h3>
                <p><?php echo $rowsmvv['VALUES_STATEMENT']; ?></p>

                <h3>Goals</h3>
                <?php  while ($rowsunit = $resultunit->fetch_assoc()): ?>

                <p><b>Goal <?php echo $goalcount;?> - <?php echo $rowsunit['UNIT_GOAL_TITLE']; ?></b></p>

                <table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
                       style='margin-left:40.25pt;border-collapse:collapse;border:none'>
                    <tr>
                        <td width=95 valign=top style='width:94.75pt;border-top:solid #A6A6A6 1.0pt;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:none;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b>Linkage
                                    to University Goal </b></p>
                        </td>
                        <td width=333 valign=top style='width:332.5pt;border-top:solid #A6A6A6 1.0pt;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:none;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'>
                                <?php

                                $unigoallinks=$rowsunit['LINK_UNIV_GOAL'];
                                $unigoal = explode(',',$unigoallinks);

                                foreach ($unigoal as $value) {
                                    if($value <> '') {
                                        $sqlunigoallink = "select GOAL_TITLE from UniversityGoals where ID_UNIV_GOAL='$value' ";
                                        $resultunigoallink = $mysqli1->query($sqlunigoallink);
                                        $rowsunigoallink = $resultunigoallink->fetch_assoc();
                                        echo '<span class="icon">S</span>'.$rowsunigoallink['GOAL_TITLE'].'<br>';
                                    }
                                }
                                ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td width=95 valign=top style='width:94.75pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b>Goal </b></p>
                        </td>
                        <td width=333 valign=top style='width:332.5pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'>
                                <?php echo $rowsunit['GOAL_STATEMENT']; ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td width=95 valign=top style='width:94.75pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b>Alignment
                                    with Mission, Vision, and Values </b></p>
                        </td>
                        <td width=333 valign=top style='width:332.5pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'>
                                <?php echo $rowsunit['GOAL_ALIGNMENT']; ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td width=95 valign=top style='width:94.75pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b>Status</b></p>
                        </td>
                        <td width=333 valign=top style='width:332.5pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'>
                                <?php echo $rowsunit['STATUS']; ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td width=95 valign=top style='width:94.75pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b>Achievements</b></p>
                        </td>
                        <td width=333 valign=top style='width:332.5pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'>
                                <?php echo $rowsunit['GOAL_ACHIEVEMENTS']; ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td width=95 valign=top style='width:94.75pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b>Resources
                                    Utilized</b></p>
                        </td>
                        <td width=333 valign=top style='width:332.5pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'>
                                <?php echo $rowsunit['GOAL_RSRCS_UTLZD']; ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td width=95 valign=top style='width:94.75pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b>Continuation
                                </b></p>
                        </td>
                        <td width=333 valign=top style='width:332.5pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'>
                                <?php echo $rowsunit['GOAL_CONTINUATION']; ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td width=95 valign=top style='width:94.75pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b>Resources
                                    Needed</b></p>
                        </td>
                        <td width=333 valign=top style='width:332.5pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'>
                                <?php echo $rowsunit['GOAL_RSRCS_NEEDED']; ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td width=95 valign=top style='width:94.75pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b>Notes</b></p>
                        </td>
                        <td width=333 valign=top style='width:332.5pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'>
                                <?php echo $rowsunit['GOAL_NOTES']; ?>
                            </p>
                        </td>
                    </tr>
                </table>

                <?php $goalcount++;
                    if($goalcount <= $countunit) { $pageno++;?>

            </div>
            <div id="pf<?php echo $pageno; ?>" class="pf w0 h0" data-page-no="<?php echo $pageno; ?>" style="padding-top: 50px;">

                <?php } endwhile; ?>

            </div>
            <?php $pageno++; ?>


            <div id="pf<?php echo $pageno; ?>" class="pf w0 h0" data-page-no="<?php echo $pageno; ?>">
                <h2 style="margin-bottom: 20px;">Outcomes–2015-2016 Academic Year</h2>
                <h3>Faculty Development &amp; Activities</h3>
                <p><b>Development.</b> College efforts and initiatives for faculty development, including investments, activities, incentives, objectives, and outcomes.</p>

                <p class="indent"><?php echo $rowsfacinfo['FACULTY_DEVELOPMENT']; ?></p>

                <p><b>Creative Activity.</b> Significant artistic, creative, and performance activities of faculty.</p>

                <p class="indent"><?php echo $rowsfacinfo['CREATIVE_ACTIVITY']; ?></p>

                <p><b>Supplemental Info.</b> Additional information provided by the College for Faculty Development & Activities is provided in Appendix 1.</p>

<!--                <p class="indent">&lt;link to Appendix 1, insert Appendix 1..99 at end of report, in numeric sequence&gt;</p>-->

                <h3>Faculty Awards</h3>
                <p><b>National Awards &amp; Recognition.</b> Among others, the Dean has selected to highlight the following awards and recognition received by the faculty during the <?php echo $bpayname; ?>.

<!--                <p class="indent">&lt;merge AC_FacultyAwards as follows… note that DATE_AWARDED has been intentionally omitted; for now, insert as many as entered for the Unit&gt;</p>-->

                    <table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0 width=437
                           style='width: 300pt;margin-left:0in;border-collapse:collapse;border:none'>
                        <tr>
                            <th width=112 valign=top style='width:112.25pt;border-top:solid #A6A6A6 1.0pt;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:none;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                            style='color:#8C2633'>Recipient(s)</span></b></p>
                </th>
                <th width=71 valign=top style='width:70.8pt;border-top:solid #A6A6A6 1.0pt;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:none;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                    <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                                style='color:#8C2633'>Award Type</span></b></p>
                </th>
                <th width=123 valign=top style='width:122.95pt;border-top:solid #A6A6A6 1.0pt;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:none;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                    <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                                style='color:#8C2633'>Award </span></b></p>
                </th>
                <th width=131 valign=top style='width:130.5pt;border-top:solid #A6A6A6 1.0pt;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:none;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                    <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                                style='color:#8C2633'>Organization</span></b></p>
                </th>
                </tr>
                <?php while ($rowsfacaward = $resultfacaward->fetch_assoc()): ?>
                    <tr>
                        <td width=112 valign=top style='width:112.25pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                                        style='font-size:9.5pt'><?php echo $rowsfacaward['RECIPIENT_NAME_LAST'] . ', ' . $rowsfacaward['RECIPIENT_NAME_FIRST']; ?></span></b>
                            </p>
                        </td>
                        <td width=71 valign=top style='width:70.8pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                                        style='font-size:9.5pt'><?php echo $rowsfacaward['AWARD_TYPE']; ?></span></b>
                            </p>
                        </td>
                        <td width=123 valign=top style='width:122.95pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                                        style='font-size:9.5pt'><?php echo $rowsfacaward['AWARD_TITLE']; ?></span></b>
                            </p>
                        </td>
                        <td width=131 valign=top style='width:130.5pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                                        style='font-size:9.5pt'><?php echo $rowsfacaward['AWARDING_ORG']; ?></span></b>
                            </p>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </table>
            </div>
            <?php $pageno++; ?>

            <div id="pf<?php echo $pageno; ?>" class="pf w0 h0" data-page-no="<?php echo $pageno; ?>" style="padding-top: 40px;">
                <h3>Personnel – Faculty</h3>
                <p>Composition of the faculty during the <2015-2016> Academic Year, as compared to the previous 2 years.</p>
                <p><b>Population by Type</b></p>
                <p class="indent">&lt;insert values from IR_AC_FacultyPop&gt;</p>

                <table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0 width=437
                       style='margin-left:.5in;border-collapse:collapse;border:none'>
                    <tr>
                        <td width=198 rowspan=2 valign=top style='width:2.75in;border:solid #A6A6A6 1.0pt;
  border-left:none;padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><span
                                    style='font-family:"Calibri Light"'>&nbsp;</span></p>
                        </td>
                        <td width=239 colspan=3 valign=top style='width:238.5pt;border-top:solid #A6A6A6 1.0pt;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:none;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><b><span style='font-family:"Calibri Light"'>Academic Year</span></b></p>
                        </td>
                    </tr>
                    <tr>
                        <td width=80 valign=top style='width:79.5pt;border-top:none;border-left:none;
  border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><b><u><span style='font-family:"Calibri Light"'>2015-2016</span></u></b></p>
                        </td>
                        <td width=80 valign=top style='width:79.5pt;border-top:none;border-left:none;
  border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><b><u><span style='font-family:"Calibri Light"'>2014-2015</span></u></b></p>
                        </td>
                        <td width=80 valign=top style='width:79.5pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><b><u><span style='font-family:"Calibri Light"'>2013-2014</span></u></b></p>
                        </td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=198 valign=top style='width:2.75in;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                                        style='font-family:"Calibri Light"'>Faculty Count Tenure</span></b></p>
                        </td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=198 valign=top style='width:2.75in;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                                        style='font-family:"Calibri Light"'>Faculty Count Tenure Track</span></b></p>
                        </td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=198 valign=top style='width:2.75in;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                                        style='font-family:"Calibri Light"'>Faculty Count Non-Tenture-Track</span></b></p>
                        </td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=198 valign=top style='width:2.75in;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                                        style='font-family:"Calibri Light"'>Faculty Count Adjunct</span></b></p>
                        </td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=198 valign=top style='width:2.75in;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                                        style='font-family:"Calibri Light"'>Faculty Count Affiliates</span></b></p>
                        </td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=198 valign=top style='width:2.75in;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                                        style='font-family:"Calibri Light"'>Faculty Hired</span></b></p>
                        </td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=198 valign=top style='width:2.75in;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                                        style='font-family:"Calibri Light"'>Faculty Unfilled Vacancies</span></b></p>
                        </td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=198 valign=top style='width:2.75in;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                                        style='font-family:"Calibri Light"'>Faculty Retention Packages</span></b></p>
                        </td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=80 nowrap valign=top style='width:79.5pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                    </tr>
                </table>

            </div>
            <?php $pageno++; ?>

            <div id="pf<?php echo $pageno; ?>" class="pf w0 h0" data-page-no="<?php echo $pageno; ?>" style="padding-top:50px; ">
                <p><b>Population Diversity – Gender, Race/Ethnicity, and Citizenship</b></p>
                <p>USC follows the federal self-identification and reporting categories for Race/Ethnicity. The values presented below reflect our estimation of the categories our faculty would be identified as, under the federal methodology. As such, each individual is reported in one category only; non-citizens are not included in Race/Ethnicity, and individuals who select Hispanic/Latino are reported as such, regardless of their selections on race.</p>

                <table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0 width=437
                       style='margin-left:.5in;border-collapse:collapse;border:none'>
                    <tr>
                        <td width=194 rowspan=2 valign=top style='width:193.5pt;border:solid #A6A6A6 1.0pt;
  border-left:none;padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><span
                                    style='font-family:"Calibri Light"'>&nbsp;</span></p>
                        </td>
                        <td width=243 colspan=3 valign=top style='width:243.0pt;border-top:solid #A6A6A6 1.0pt;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:none;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><b><span style='font-family:"Calibri Light"'>Academic Year</span></b></p>
                        </td>
                    </tr>
                    <tr>
                        <td width=81 valign=top style='width:81.0pt;border-top:none;border-left:none;
  border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><b><u><span style='font-family:"Calibri Light"'>2015-2016</span></u></b></p>
                        </td>
                        <td width=81 valign=top style='width:81.0pt;border-top:none;border-left:none;
  border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><b><u><span style='font-family:"Calibri Light"'>2014-2015</span></u></b></p>
                        </td>
                        <td width=81 valign=top style='width:81.0pt;border:none;border-bottom:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><b><u><span style='font-family:"Calibri Light"'>2013-2014</span></u></b></p>
                        </td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=194 valign=top style='width:193.5pt;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                                        style='font-family:"Calibri Light";color:#8C2633'>Gender</span></b></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><span style='font-family:"Calibri Light"'>&nbsp;</span></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><span style='font-family:"Calibri Light"'>&nbsp;</span></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><span style='font-family:"Calibri Light"'>&nbsp;</span></p>
                        </td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=194 valign=top style='width:193.5pt;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-top:0in;margin-right:0in;margin-bottom:0in;
  margin-left:15.1pt;margin-bottom:.0001pt'><b><span style='font-family:"Calibri Light"'>Female</span></b></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=194 valign=top style='width:193.5pt;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-top:0in;margin-right:0in;margin-bottom:0in;
  margin-left:15.1pt;margin-bottom:.0001pt'><b><span style='font-family:"Calibri Light"'>Male</span></b></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=194 valign=top style='width:193.5pt;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt'><b><span
                                        style='font-family:"Calibri Light";color:#8C2633'>Race/Ethnicity</span></b></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=194 valign=top style='width:193.5pt;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-top:0in;margin-right:0in;margin-bottom:0in;
  margin-left:15.1pt;margin-bottom:.0001pt'><b><span style='font-family:"Calibri Light"'>African
  American or Black</span></b></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=194 valign=top style='width:193.5pt;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-top:0in;margin-right:0in;margin-bottom:0in;
  margin-left:15.1pt;margin-bottom:.0001pt'><b><span style='font-family:"Calibri Light"'>American
  Indian or Alaska Native</span></b></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=194 valign=top style='width:193.5pt;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-top:0in;margin-right:0in;margin-bottom:0in;
  margin-left:15.1pt;margin-bottom:.0001pt'><b><span style='font-family:"Calibri Light"'>Asian</span></b></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=194 valign=top style='width:193.5pt;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-top:0in;margin-right:0in;margin-bottom:0in;
  margin-left:15.1pt;margin-bottom:.0001pt'><b><span style='font-family:"Calibri Light"'>Hispanic</span></b></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=194 valign=top style='width:193.5pt;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-top:0in;margin-right:0in;margin-bottom:0in;
  margin-left:15.1pt;margin-bottom:.0001pt'><b><span style='font-family:"Calibri Light"'>Native
  Hawaiian/Other Pacific Islander</span></b></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'></td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=194 valign=top style='width:193.5pt;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-top:0in;margin-right:0in;margin-bottom:0in;
  margin-left:15.1pt;margin-bottom:.0001pt'><b><span style='font-family:"Calibri Light"'>White</span></b></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><span style='font-family:"Calibri Light"'>&nbsp;</span></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><span style='font-family:"Calibri Light"'>&nbsp;</span></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><span style='font-family:"Calibri Light"'>&nbsp;</span></p>
                        </td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=194 valign=top style='width:193.5pt;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-top:0in;margin-right:0in;margin-bottom:0in;
  margin-left:15.1pt;margin-bottom:.0001pt'><b><span style='font-family:"Calibri Light"'>Two
  or More Races</span></b></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><span style='font-family:"Calibri Light"'>&nbsp;</span></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><span style='font-family:"Calibri Light"'>&nbsp;</span></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><span style='font-family:"Calibri Light"'>&nbsp;</span></p>
                        </td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=194 valign=top style='width:193.5pt;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-top:0in;margin-right:0in;margin-bottom:0in;
  margin-left:15.1pt;margin-bottom:.0001pt'><b><span style='font-family:"Calibri Light"'>Race/Ethnicity
  Unknown</span></b></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><span style='font-family:"Calibri Light"'>&nbsp;</span></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><span style='font-family:"Calibri Light"'>&nbsp;</span></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><span style='font-family:"Calibri Light"'>&nbsp;</span></p>
                        </td>
                    </tr>
                    <tr style='height:15.0pt'>
                        <td width=194 valign=top style='width:193.5pt;border-top:none;border-left:
  none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal style='margin-top:0in;margin-right:0in;margin-bottom:0in;
  margin-left:15.1pt;margin-bottom:.0001pt'><b><span style='font-family:"Calibri Light"'>Nonresident
  Alien (Non-Citizen)</span></b></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><span style='font-family:"Calibri Light"'>&nbsp;</span></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border-top:none;
  border-left:none;border-bottom:solid #A6A6A6 1.0pt;border-right:solid #A6A6A6 1.0pt;
  padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><span style='font-family:"Calibri Light"'>&nbsp;</span></p>
                        </td>
                        <td width=81 nowrap valign=top style='width:81.0pt;border:none;border-bottom:
  solid #A6A6A6 1.0pt;padding:2.9pt 2.9pt 2.9pt 2.9pt;height:15.0pt'>
                            <p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
  text-align:center'><span style='font-family:"Calibri Light"'>&nbsp;</span></p>
                        </td>
                    </tr>
                </table>
            </div>
            <?php $pageno++; ?>


            <div id="pf<?php echo $pageno; ?>" class="pf w0 h0" data-page-no="<?php echo $pageno; ?>" style="padding-top: 50px;">
                <p>Student Enrollment</p>

                <p>Population by Level</p>

                <p>Portion of USC Columbia Enrollment</p>

                <p>Race/Ethnicity &amp; Citizenship</p>
            </div>
            <?php $pageno++; ?>
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






